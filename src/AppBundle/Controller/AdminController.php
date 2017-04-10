<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Articulo;
use AppBundle\Entity\Comentario;
use AppBundle\Form\Type\ComentarioType;
use AppBundle\Form\Type\LocalType;
use AppBundle\Form\Type\MenuType;
use AppBundle\Form\Type\ArticuloType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Local;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Menu;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @Route("/platos", name="platos")
     */

    public function verPlatos (Request $request)
    {
        $tipo = new Tipo();

        $form = $this->createForm(TipoType::class, $tipo);

        $form->handleRequest($request);


        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($tipo);
            $em->flush();

            return $this->redirect($this->generateUrl('platos'));
        }


        /**
         * @var EntityRepository
         */
        $tiposRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Tipo');


        $tipos = $tiposRepository->findAll();

        return $this->render('admin/tipos.html.twig', [
            'tipos' => $tipos,
            'form' => $form->createView()


        ]);
    }



    /**
     * @Route("/modificartipo/{tipo}", name="modificartipo")
     */
    public function formAction(Tipo $tipo, Request $request)
    {

        $form = $this->createForm('AppBundle\Form\Type\TipoType', $tipo);

        $form->handleRequest($request);
       

        if ($form->isSubmitted() && $form->isValid()) {

            
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'cambios_ok'
            );


            return $this->redirect($this->generateUrl('platos'));
        }

        return $this->render('admin/tiposform.html.twig',
            [
                'form' => $form->createView(),
                
            ]);
    }


    /**
     * @Route("/eliminartodo/{local}", name="eliminartodo")
     */
    public function deletetodo(Request $peticion, Local $local)
    {

        $em = $this-> getDoctrine()->getManager();

        $usuario=$local->getPropietario();
        $comentarios=$local->getComentarios();
        $menus=$local->getMenus();


        for ( $i = 0 ; $i < sizeof($comentarios) ; $i ++) {
            $em->remove($comentarios[$i]);
        }

        $articulosRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Articulo');


        for ( $i = 0 ; $i < sizeof($menus) ; $i ++) {

            $articulos = $articulosRepository
                ->createQueryBuilder('a')
                ->where('a.menu = :men')
                ->setParameter('men', $menus[$i])
                ->getQuery()
                ->getResult();

            for ( $z = 0 ; $z < sizeof($articulos) ; $z ++) {
                $em->remove($articulos[$z]);
            }

            $em->remove($menus[$i]);
        }
        $em->remove($usuario);
        $em->remove($local);






        $em->flush();


        $this->get('session')->getFlashBag()->add(
            'notice',
            'eliminado_ok'
        );



        return $this->redirect($this->generateUrl('borrar'));
    }


    /**
     * @Route("/borrar", name="borrar")
     */
    public function borrarAction(Request $peticion)
    {

        $localesRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Local');
        $locales=$localesRepository->findAll();
        return $this->render('admin/borrar.html.twig',
            [
                'locales' => $locales,

            ]);

    }


    /**
     * @Route("/admin", name="inicio_admin")
     */
    public function inicioadminAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $comentariosRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Comentario');


        $avisos = $comentariosRepository
            ->createQueryBuilder('l')
            ->select('COUNT(l)')
            ->where('l.aviso = 1')
            ->getQuery()
            ->getSingleScalarResult();


        return $this->render(':admin:inicio_admin.html.twig',
            [
                'avisos'=>$avisos
            ]


        );

    }


    /**
     * @Route("/admin/locales", name="locales")
     */
    public function localesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * @var EntityRepository $localesRepository
         */

        $localesRepository = $em->getRepository('AppBundle:Local');

        $locales = $localesRepository->findAll();


        return $this->render(':admin:listadolocales.html.twig', [
            'locales'=>$locales,

        ]);

    }


    /**
     * @Route("/tooglelocal/{local}", name="tooglelocal")
     */

    public function toogleLocal(Request $request, Local $local)
    {

        $cambio=$local->getActivo();
        $local->setActivo(!$cambio);
        $em = $this->getDoctrine()->getManager();
        $em->persist($local);
        $em->flush();

        $session = $request->getSession();

        if (false === $session->has('_security.main.target_path')) {

            $authChecker = $this->container->get('security.authorization_checker');
            $router = $this->container->get('router');
            // replace this example code with whatever you need
            if ($authChecker->isGranted('ROLE_ADMIN')) {
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'cambios_ok'
                );
                return $this->redirectToRoute('locales');

            }
            if ($authChecker->isGranted('ROLE_CLIENTE')) {

                $user=$local->getPropietario();
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'cambios_ok'
                );
                return $this->redirect($this->generateUrl('local',array('propietario' => $user->getId())));



            }
        } else {
            return new RedirectResponse($session->get('_security.main.target_path'));
        }


        return $this->render(':publico:publico.html.twig');





    }


    /**
     * @Route("/admin/usuarios", name="usuarios")
     */
    public function usuariosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * @var EntityRepository $usuariosRepository
         */

        $usuariosRepository = $em->getRepository('AppBundle:Usuario');

        $usuarios = $usuariosRepository->createQueryBuilder('u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_CLIENTE%')
            ->getQuery()
            ->getResult();


        return $this->render(':admin:listadousuarios.html.twig', [
            'usuarios'=>$usuarios,

        ]);

    }


    /**
     * @Route("/banear/{usuario}", name="banear")
     */
    public function banearAction(Request $request,Usuario $usuario)
    {
        $cambio=$usuario->isLocked();

        $usuario->setLocked(!$cambio);
        $em = $this->getDoctrine()->getManager();
        $em->persist($usuario);
        $em->flush();
        $this->get('session')->getFlashBag()->add(
            'notice',
            'cambios_ok'
        );

        return $this->redirect($this->generateUrl('usuarios'));

    }

    /**
     * @Route("/admin/comentarios", name="comentarios_admin")
     */
    public function comentariosadminAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * @var EntityRepository $comentariosRepository
         */

        $comentariossRepository = $em->getRepository('AppBundle:Comentario');

        $comentarios = $comentariossRepository
            ->createQueryBuilder('c')
            ->orderBy('c.aviso','desc')
            ->getQuery()
            ->getResult();


        return $this->render(':admin:comentarios_admin.html.twig', [
            'comentarios'=>$comentarios,

        ]);

    }
    /**
     * @Route("/admin/removecomentario/{comentario}", name="remove_comentarios")
     */
    public function comentariosRemove(Request $request,Comentario $comentario)
    {
        $this->getDoctrine()->getManager()->remove($comentario);
        $this->getDoctrine()->getManager()->flush();
        $this->get('session')->getFlashBag()->add(
            'notice',
            'eliminado_ok'
        );
        return $this->redirect($this->generateUrl('comentarios_admin'));
    }


}

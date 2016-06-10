<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Articulo;
use AppBundle\Entity\Comentario;
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

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();

        if (false === $session->has('_security.main.target_path')) {

            $authChecker = $this->container->get('security.authorization_checker');
            $router = $this->container->get('router');
            // replace this example code with whatever you need
            if ($authChecker->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('inicio_admin');
            }
            if ($authChecker->isGranted('ROLE_CLIENTE')) {



               return $this->redirectToRoute('usuario_menu');

            }
        } else {
            return new RedirectResponse($session->get('_security.main.target_path'));
        }


        return $this->render(':publico:publico.html.twig');


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
     * @Route("/usuario", name="usuario_menu")
     */
    public function menuAction(Request $request)
    {
        $user=$this->getUser();

        $localesRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Local');


        $local = $localesRepository
            ->createQueryBuilder('l')
            ->where('l.propietario = :prop')
            ->setParameter('prop', $user)
            ->getQuery()
            ->getResult();

        //$_SESSION['localid']=$local[0]->getId();
         //return $this->redirect($this->generateUrl('usuario_menu',array('local' => $local[0]->getId())));
        return $this->render(':usuario:usuario_menu.html.twig'

        );
        //return $this->render(':usuario:usuario_menu.html.twig');

    }


   /**
  * @Route("/local/{propietario}", name="local")
    */
    public function plantillaAction(Usuario $propietario)
    {
        /**
        * @var EntityRepository
         */
        $localesRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Local');


        $local = $localesRepository
            ->createQueryBuilder('l')
            ->where('l.propietario = :prop')
            ->setParameter('prop', $propietario)
            ->getQuery()
            ->getResult();
        $imagen=null;
        if(!empty($local)) {
            $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
            $imagen = $helper->asset($local[0], 'fotoImage');
        }

         /*if(empty($local)){
             return $this->render(':publico:publico.html.twig');
         }*/
        return $this->render('usuario/local.html.twig', [
            'local' => $local,
            'ImagenLocal' => $imagen

        ]);
    }

    /**
     * @Route("/registrarlocal/{usuario}", name="registrolocal")
     */

    public function addLocal(Request $request, Usuario $usuario)
    {


        $local = new local();

        $form = $this->createForm(LocalType::class, $local);

        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $local->setPuntuacion(0);
            $local->setActivo(false);
            $local->setPropietario($usuario);
            $em = $this->getDoctrine()->getManager();
            $em->persist($local);
            $em->flush();

            return $this->redirect($this->generateUrl('usuario_menu'));
        }

        return $this->render('usuario/localform.html.twig', array(
            'form' => $form->createView()

        ));
    }

    /**
     * @Route("/modificarlocal/{local}", name="modificarlocal")
     */
    public function formAction(Local $local, Request $request)
   {

        $form = $this->createForm('AppBundle\Form\Type\LocalType', $local);

        $form->handleRequest($request);
       $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
       $imagen = $helper->asset($local, 'fotoImage');

        if ($form->isSubmitted() && $form->isValid()) {

            $user=$local->getPropietario();
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );
            

            return $this->redirect($this->generateUrl('local',array('propietario' => $user->getId())));
        }

        return $this->render(':usuario:localform.html.twig',
                [
                    'form' => $form->createView(),
                    'imagenLocal' => $imagen,
                ]);
    }


    /**
     * @Route("/menus/{propietario}", name="menus")
     */

    public function menusAction(Usuario $propietario)
    {
        /**
         * @var EntityRepository
         */
        $localesRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Local');


        $local = $localesRepository
            ->createQueryBuilder('l')
            ->where('l.propietario = :prop')
            ->setParameter('prop', $propietario)
            ->getQuery()
            ->getResult();

        $menusRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Menu');


        $menus = $menusRepository
            ->createQueryBuilder('l')
            ->where('l.establecimiento = :prop')
            ->setParameter('prop', $local)
            ->getQuery()
            ->getResult();



        /*if(empty($local)){
            return $this->render(':publico:publico.html.twig');
        }*/
        return $this->render('usuario/menu.html.twig', [
            'menus' => $menus,
            'local' => $local
        ]);
    }

    /**
     * @Route("/registrarmenu/{usuario}", name="registromenu")
     */

    public function addMenu(Request $request, Usuario $usuario)
    {
        $localesRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Local');


        $local = $localesRepository
            ->createQueryBuilder('l')
            ->where('l.propietario = :prop')
            ->setParameter('prop', $usuario)
            ->getQuery()
            ->getResult();

        if(empty($local)){
            return $this->redirect($this->generateUrl('local',array('propietario' => $usuario->getId())));
       }
        
        $user =$this->getUser();
        $menu = new menu();

        $form = $this->createForm(new MenuType(), $menu);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $menu->setEstablecimiento($local[0]);
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();

            return $this->redirect($this->generateUrl('menus',array('propietario'=>$user->getId())));
        }

        return $this->render('usuario/menuform.html.twig', array(
            'form' => $form->createView()

        ));
    }


    /**
     * @Route("/modificarmenu/{menu}", name="modificarmenu")
     */
    public function formmenu(Menu $menu, Request $request)
    {
        $user =$this->getUser();
        $form = $this->createForm('AppBundle\Form\Type\MenuType', $menu);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('menus',array('propietario'=>$user->getId())));
        }

        return $this->render(':usuario:menuform.html.twig',
            [
                'form' => $form->createView()
            ]);
    }


    /**
     * @Route("/articulos/{menu}", name="mostrararticulo")
     */

    public function mostrarArticulos (Request $request, Menu $menu)
    {
        /**
         * @var EntityRepository
         */
        $articulosRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Articulo');


        $articulos = $articulosRepository
            ->createQueryBuilder('a')
            ->where('a.menu = :men')
            ->setParameter('men', $menu)
            ->orderBy('a.tipo')
            ->getQuery()
            ->getResult();


        /*if(empty($local)){
            return $this->render(':publico:publico.html.twig');
        }*/
        return $this->render('usuario/articulos.html.twig', [
            'articulos' => $articulos,
            'menu'=> $menu
        ]);
    }

    /**
     * @Route("/registrararticulos/{menu}", name="registrararticulo")
     */
    public function addArticulo(Request $request, Menu $menu)
    {
        $user =$this->getUser();


        $articulo = new Articulo();

        $form = $this->createForm(ArticuloType::class, $articulo);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $articulo->setMenu($menu);
            $em = $this->getDoctrine()->getManager();
            $em->persist($articulo);
            $em->flush();

            return $this->redirect($this->generateUrl('mostrararticulo',array('menu'=>$menu->getId())));
        }

        return $this->render('usuario/articuloform.html.twig', array(
            'form' => $form->createView()

        ));
    }


    /**
     * @Route("/modificararticulo/{articulo}", name="modificararticulo")
     */
    public function formarticulo(Articulo $articulo, Request $request)
    {

        $menu=$articulo->getMenu();
        $form = $this->createForm('AppBundle\Form\Type\ArticuloType', $articulo,
            [
                'nuevo' => false
            ]

            );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('borrar')->isClicked()) {
                                             $this->getDoctrine()->getManager()->remove($articulo);
                                                    }
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('mostrararticulo',array('menu'=>$menu->getId())));
        }

        return $this->render(':usuario:articuloform.html.twig',
            [
                'form' => $form->createView()
            ]);
    }
    /**
     * @Route("/eliminararticulo/{articulo}", name="eliminararticulo")
     */
    public function deleteArticulo(Request $peticion, Articulo $articulo)
    {
        $menu=$articulo->getMenu();
        $em = $this-> getDoctrine()->getManager();
        $em->remove($articulo);
        $em->flush();

        return $this->redirect($this->generateUrl('mostrararticulo',array('menu'=>$menu->getId())));
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
                return $this->redirectToRoute('locales');
            }
            if ($authChecker->isGranted('ROLE_CLIENTE')) {

                $user=$local->getPropietario();
                return $this->redirect($this->generateUrl('local',array('propietario' => $user->getId())));



            }
        } else {
            return new RedirectResponse($session->get('_security.main.target_path'));
        }


        return $this->render(':publico:publico.html.twig');





    }


    /**
     * @Route("/eliminarmenu/{menu}", name="eliminarmenu")
     */
    public function deletemenu(Request $peticion, Menu $menu)
    {
        $user=$this->getUser();
        $articulosRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Articulo');


        $articulos = $articulosRepository
            ->createQueryBuilder('a')
            ->where('a.menu = :men')
            ->setParameter('men', $menu)
            ->getQuery()
            ->getResult();


        $em = $this-> getDoctrine()->getManager();
        for ( $i = 0 ; $i < sizeof($articulos) ; $i ++) {
            $em->remove($articulos[$i]);
        }


        $em->remove($menu);
        $em->flush();

        return $this->redirect($this->generateUrl('menus',array('propietario'=>$user->getId())));
    }



    /**
     * @Route("/comentarios/{usuario}", name="vercomentarios")
     */

    public function vercomentarios(Request $request, Usuario $usuario)
    {
        $localesRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Local');


        $local = $localesRepository
            ->createQueryBuilder('l')
            ->where('l.propietario = :prop')
            ->setParameter('prop', $usuario)
            ->getQuery()
            ->getResult();

        if(empty($local)){
            return $this->redirect($this->generateUrl('local',array('propietario' => $usuario->getId())));
        }

        $comentariosRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Comentario');
        $comentarios = $comentariosRepository
            ->createQueryBuilder('c')
            ->where('c.local = :local')
            ->setParameter('local', $local[0])
            ->getQuery()
            ->getResult();

        return $this->render('usuario/comentario.html.twig', array(
            'comentarios' => $comentarios

        ));
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

        $usuarios = $usuariosRepository->findAll();
        

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
        dump($comentarios);

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
        return $this->redirect($this->generateUrl('comentarios_admin'));
    }

    /**
     * @Route("/reclamar/{comentario}", name="reclamar")
     */
    public function reclamarAction(Request $request,Comentario $comentario)
    {
        $user=$this->getUser();
        if(1==$comentario->getAviso()){
            $cambio=0;
        }
        if(0==$comentario->getAviso()){
            $cambio=1;
        }
        if(null==$comentario->getAviso()){
            $cambio=1;
        }


        $comentario->setAviso($cambio);

        $em = $this->getDoctrine()->getManager();
        $em->persist($comentario);
        $em->flush();
        $session = $request->getSession();

        if (false === $session->has('_security.main.target_path')) {

            $authChecker = $this->container->get('security.authorization_checker');
            $router = $this->container->get('router');
            // replace this example code with whatever you need
            if ($authChecker->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('comentarios_admin');
            }

        }


        return $this->redirect($this->generateUrl('vercomentarios',array('usuario' => $user->getId())));

    }
    
    
    //--------------AJAXXXX

    /**

     * @Route("/todos_locales", name="todos_locales", condition="request.isXmlHttpRequest()")
     */
    public function todos_locales(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var EntityRepository $localesRepository
         */
        $localesRepository = $em->getRepository('AppBundle:Local');
        $locales = $localesRepository->createQueryBuilder('u')
            ->select('u.id')
            ->addSelect('u.nombre')
            ->addSelect('u.latitud')
            ->addSelect('u.longitud')
            ->where('u.activo = 1')
            ->getQuery()
            ->getResult();

        $resultado = json_encode($locales);
        dump($resultado);
        return new Response($resultado);
    }

    /**
     * @Route("/busqueda_avanzada", name="busqueda_avanzada", condition="request.isXmlHttpRequest()")
     */
    public function busqueda_avanzada(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var EntityRepository $localesRepository
         */
        $localesRepository = $em->getRepository('AppBundle:Local');
        $locales = $localesRepository->createQueryBuilder('u')
            ->select('u.id')
            ->addSelect('u.nombre')
            ->addSelect('u.latitud')
            ->addSelect('u.longitud')
            ->where('u.activo = 1')
            ->getQuery()
            ->getResult();

        $resultado = json_encode($locales);
        dump($resultado);
        return new Response($resultado);
    }

    /**
     * @Route("/verficha/{local}", name="verficha")
     */
    public function ficha(Local $local)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var EntityRepository $localesRepository
         */

        $localesRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Local');


        $local = $localesRepository
            ->createQueryBuilder('l')
            ->where('l.id = :id')
            ->setParameter('id', $local)
            ->getQuery()
            ->getResult();
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $imagen = $helper->asset($local[0], 'fotoImage');


        $comentariosRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Comentario');
        $comentarios = $comentariosRepository
            ->createQueryBuilder('c')
            ->where('c.local = :local')
            ->setParameter('local', $local[0])
            ->getQuery()
            ->getResult();


        $menusRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Menu');


        $menus = $menusRepository
            ->createQueryBuilder('l')
            ->where('l.establecimiento = :prop')
            ->setParameter('prop', $local)
            ->getQuery()
            ->getResult();


        /**
         * @var EntityRepository
         */
        $articulosRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Articulo');
        $todos[]=null;
        for($i=0;$i<sizeof($menus);$i++) {
            $articulos = $articulosRepository
                ->createQueryBuilder('a')
                ->where('a.menu = :men')
                ->setParameter('men', $menus[$i])
                ->orderBy('a.tipo')
                ->getQuery()
                ->getResult();
            $todos[$i]=$articulos;
        }
        dump($todos);
        
        return $this->render(':publico:fichalocal.html.twig',
            [
            'local'=>$local,
                'ImagenLocal' => $imagen,
                'comentarios' => $comentarios,
                'menus' => $menus,
                'articulos'=>$todos
            ]

        );

    }


    /**
     * @Route("/vercarta/{menu}", name="vercarta")
     */

    public function verCarta (Request $request, Menu $menu)
    {
        /**
         * @var EntityRepository
         */
        $articulosRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Articulo');


        $articulos = $articulosRepository
            ->createQueryBuilder('a')
            ->where('a.menu = :men')
            ->setParameter('men', $menu)
            ->orderBy('a.tipo')
            ->getQuery()
            ->getResult();
        
        
        return $this->render('publico/carta.html.twig', [
            'articulos' => $articulos,
            'menu' => $menu
            
        ]);
    }

}

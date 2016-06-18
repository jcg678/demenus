<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Tipo;
use AppBundle\Form\Type\TipoType;
use AppBundle\Entity\local;
use AppBundle\Entity\Comentario;

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

}

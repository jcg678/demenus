<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Tipo;
use AppBundle\Form\Type\TipoType;

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
}

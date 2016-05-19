<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\LocalType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Local;
use AppBundle\Entity\Usuario;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
                return $this->redirectToRoute('locales');
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
     * @Route("/locales", name="locales")
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
        return $this->render(':usuario:usuario_menu.html.twig');

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

         /*if(empty($local)){
             return $this->render(':publico:publico.html.twig');
         }*/
        return $this->render('usuario/local.html.twig', [
            'local' => $local

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

        if ($form->isSubmitted() && $form->isValid()) {

            $user=$local->getPropietario();
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('local',array('propietario' => $user->getId())));
        }

        return $this->render(':usuario:localform.html.twig',
                [
                        'form' => $form->createView()
                       ]);
    }
}

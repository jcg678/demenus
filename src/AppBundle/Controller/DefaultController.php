<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Local;
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

        /*return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));*/
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
       


        return $this->render(':usuario:usuario_menu.html.twig'
            

        );

    }
    
    
}

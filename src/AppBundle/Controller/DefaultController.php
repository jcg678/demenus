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
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');
        // replace this example code with whatever you need
        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($router->generate('locales'), 307);
        }
        if ($authChecker->isGranted('ROLE_CLIENTE')) {
            return new RedirectResponse($router->generate('usuario_menu'), 307);
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

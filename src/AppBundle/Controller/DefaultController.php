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
        
        return new Response($resultado);
    }

    /**
     * @Route("/busqueda_avanzada", name="busqueda_avanzada", condition="request.isXmlHttpRequest()")
     */
    public function busqueda_avanzada(Request $request)
    {
        $nombre = $request->request->get('nombre');
        $localidad = $request->request->get('localidad');
        $provincia = $request->request->get('provincia');
        $cp = $request->request->get('cp');
        //dump($nombre);
        $em = $this->getDoctrine()->getManager();
        /**
         * @var EntityRepository $localesRepository
         */
        $localesRepository = $em->getRepository('AppBundle:Local');
        if(empty($cp)){
            $locales = $localesRepository->createQueryBuilder('u')
                ->select('u.id')
                ->addSelect('u.nombre')
                ->addSelect('u.latitud')
                ->addSelect('u.longitud')
                ->where('u.activo = 1')
                ->andWhere('u.nombre LIKE :nombre')
                ->andWhere('u.localidad LIKE :localidad')
                ->andWhere('u.provincia LIKE :provincia')
                ->setParameter('nombre', '%' . $nombre . '%')
                ->setParameter('localidad', '%' . $localidad . '%')
                ->setParameter('provincia', '%' . $provincia . '%')
                ->getQuery()
                ->getResult();

        }else {
            $locales = $localesRepository->createQueryBuilder('u')
                ->select('u.id')
                ->addSelect('u.nombre')
                ->addSelect('u.latitud')
                ->addSelect('u.longitud')
                ->where('u.activo = 1')
                ->andWhere('u.nombre LIKE :nombre')
                ->andWhere('u.localidad LIKE :localidad')
                ->andWhere('u.provincia LIKE :provincia')
                ->andWhere('u.cp = :cp')
                ->setParameter('nombre', '%' . $nombre . '%')
                ->setParameter('localidad', '%' . $localidad . '%')
                ->setParameter('provincia', '%' . $provincia . '%')
                ->setParameter('cp', $cp)
                ->getQuery()
                ->getResult();
        }
        $resultado = json_encode($locales);
        
        return new Response($resultado);
    }

    /**
     * @Route("/verficha/{local}", name="verficha")
     */
    public function ficha(Request $request,Local $local)
    {


        $comentario = new Comentario();

        $form = $this->createForm(ComentarioType::class, $comentario);

        $form->handleRequest($request);
        
        $em = $this->getDoctrine()->getManager();
        /**
         * @var EntityRepository $localesRepository
         */

        $localesRepository = $this->getDoctrine()->getEntityManager()
            ->getRepository('AppBundle:Local');
        $pass=$local->getId();

        $local = $localesRepository
            ->createQueryBuilder('l')
            ->where('l.id = :id')
            ->setParameter('id', $local)
            ->getQuery()
            ->getResult();
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $imagen = $helper->asset($local[0], 'fotoImage');

        if ($form->isValid()) {

            $comentario->setAviso('0');
            $comentario->setLocal($local[0]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comentario);
            $em->flush();

            return $this->redirect($this->generateUrl('verficha',array('local' => $pass)));
        }


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
        
        
        return $this->render(':publico:fichalocal.html.twig',
            [
            'local'=>$local,
                'ImagenLocal' => $imagen,
                'comentarios' => $comentarios,
                'menus' => $menus,
                'articulos'=>$todos,
                'form' => $form->createView()
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
            'menu' => $menu,
            
            
        ]);
    }

    /**
     * @Route("/publico", name="publico")
     */
    public function indexPublico(Request $request)
    {
        
        return $this->render(':publico:publico.html.twig');


    }


    /**
     * @Route("/prueba", name="prueba")
     */
    public function pruebaAction($name = 'paco')
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Correo de prueba')
            ->setFrom('info@demenus.es')
            ->setTo('jcg678@hotmail.es')
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'publico/prueba.html.twig',
                    array('name' => $name)
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
        $this->get('mailer')->send($message);

        return $this->render(':publico:publico.html.twig');
    }
    
    
    


    public function pageNotFoundAction()
    {
        return $this->render(':publico:publico.html.twig');

    }




    /**
     * @Route("/2prueba", name="2prueba")
     */
    public function prueba2Action($name = 'paco')        
    {

        $transport = \Swift_SmtpTransport::newInstance()
            ->setUsername('info@demenus.es')->setPassword('demenus2015')
            ->setHost('smtp.1and1.com')
            ->setPort(587)->setEncryption('tls');

        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance()
            ->setSubject('Hola')
            ->setFrom(array('info@demenus.es' => 'I am someone'))
            ->setTo(array('jcg678@hotmail.es' => "jcg678@hotmail.es"))
            ->addPart("<h1>Welcome</h1>",'text/html')
        ;

        $result = $mailer->send($message);
        /*
        $message = \Swift_Message::newInstance()
            ->setSubject('Correo de prueba')
            ->setFrom('info@demenus.es')
            ->setTo('jcg678@hotmail.es')
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'publico/prueba.html.twig',
                    array('name' => $name)
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
        //$this->get('mailer')->send($message);

        return $this->render(':publico:publico.html.twig');
    }



}

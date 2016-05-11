<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Articulo;
use AppBundle\Entity\Comentario;
use AppBundle\Entity\Local;
use AppBundle\Entity\Menu;
use AppBundle\Entity\Tipo;
use AppBundle\Entity\Usuario;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DatosIniciales implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {
        /*
        // Equipos
        $denominacionEquipos = [
            "Tintas Cómicas Industriales",
            "Los Bestiajos",
            "Frikies TV",
            "Japan Otaku"
        ];

        $equipos = [];

        foreach($denominacionEquipos as $denominacion) {
            $equipo = new Equipo();
            $equipo->setDenominacion($denominacion);
            $manager->persist($equipo);

            $equipos[] = $equipo;
        }

        // Jugadores
        $jugadores = [
            [0, "Juan", "Nadie Nadie", 25],
            [0, "Francisco", "Ibáñez Talavera", 75],
            [0, "Saturnino", "Bacterio", 55],
            [0, "Vicente", "Ruínez", 57],
            [0, "Filemón", "Pi", 42],
            [0, "Pantuflo", "Zapatilla Llobregat", 40],
            [0, "Telesforo", "Bestiájez", 35],
            [1, "Chuck", "Norris", 9999],
            [1, "Jean-Claude", "Van Damme", 60],
            [1, "Arnold", "Schwarzenegger", 65],
            [1, "Sylvester", "Stallone", 66],
            [1, "Bruce", "Willis", 58],
            [1, "Jackie", "Chan", 45],
            [1, "Wesley", "Snipes", 52],
            [2, "Carmen", "de Mairena", 90],
            [2, "Kiko", "Rivera Pantoja", 3],
            [2, "Boris Rodolfo", "Izaguirre Lobo", 42],
            [2, "Dinio", "García Leyva", 45],
            [2, "Kiko", "Matamoros Hernández", 58],
            [2, "Jorge Javier", "Vázquez", 46],
            [2, "Jimmy", "Giménez-Arnau Puente", 52],
            [3, "Goku", "Kakarotto", 33],
            [3, "Vegetta", "Vegetta", 35],
            [3, "Edward", "Elric", 20],
            [3, "Alfonse", "Elric", 18],
            [3, "Naruto", "Uzumaki", 21],
            [3, "Sasuke", "Uchiha", 22],
            [3, "Ash", "Ketchum", 15]
        ];

        foreach($jugadores as $persona) {
            $jugador = new Jugador();
            $jugador->setNombre($persona[1])
                ->setApellidos($persona[2])
                ->setEquipo($equipos[$persona[0]])
                ->setEdad($persona[3]);

            $manager->persist($jugador);
        }

        // Entrenadores
        $entrenadores = [
            ["Rompetechos", "No Veo", 45],
            ["Clint", "Eastwood", 30],
            ["Belén", "Esteban Menéndez", 1],
            ["Kame", "Sennin", 10]
        ];

        foreach($entrenadores as $i => $persona) {
            $entrenador = new Entrenador();
            $entrenador->setNombre($persona[0])
                ->setApellidos($persona[1])
                ->setEquipo($equipos[$i])
                ->setTemporadas($persona[2]);
            $entrenador->getEquipo()->setEntrenador($entrenador);

            $manager->persist($entrenador);
        }

        // Partidos
        $partidos = [
            ['2016-01-01', 0, 1, 2, 6],
            ['2016-01-02', 2, 3, 0, 4],
            ['2016-01-08', 3, 0, 1, 1],
            ['2016-01-09', 1, 2, 9, 0],
            ['2016-01-08', 0, 2, 3, 0],
            ['2016-01-09', 1, 3, 2, 1]
        ];

        foreach($partidos as $combinacion) {
            $partido = new Partido();
            $partido->setEquipoLocal($equipos[$combinacion[1]])
                ->setEquipoVisitante($equipos[$combinacion[2]])
                ->setMarcadorLocal($combinacion[3])
                ->setMarcadorVisitante($combinacion[4])
                ->setFechaCelebracion(new \DateTime($combinacion[0]));

            $manager->persist($partido);
        }

        // Usuario administrador
        $usuario = new Usuario();

        if ($usuario instanceof UserInterface) {
            $usuario
                ->setCorreoElectronico('admin@example.com')
                ->setClave($this->container->get('security.password_encoder')->encodePassword($usuario, 'admin'))
                ->setAdministrador(true)
                ->setArbitro(false)
                ->setComentarista(false);

            $manager->persist($usuario);
        }
        */


        $usuario1 = new Usuario();

        if ($usuario1 instanceof UserInterface) {
            $usuario1
                ->setNickname('admin')
                ->setNombre('Administrador')
                ->setApellido1('apellido1')
                //->setPassword($this->container->get('security.password_encoder')->encodePassword($usuario, 'admin'))
                ->setPassword('admin')
                ->setAdministrador(true)
                ->setCliente(false);


            $manager->persist($usuario1);
        }

        $usuario2 = new Usuario();

        if ($usuario2 instanceof UserInterface) {
            $usuario2
                ->setNickname('pepe123')
                ->setNombre('Pepe')
                ->setApellido1('Martinez')
                //->setPassword($this->container->get('security.password_encoder')->encodePassword($usuario, 'admin'))
                ->setPassword('pepe123')
                ->setAdministrador(false)
                ->setCliente(true);


            $manager->persist($usuario2);
        }
        $usuario3 = new Usuario();
        if ($usuario3 instanceof UserInterface) {
            $usuario3
                ->setNickname('manolo123')
                ->setNombre('Manuel')
                ->setApellido1('Martinez')
                //->setPassword($this->container->get('security.password_encoder')->encodePassword($usuario, 'admin'))
                ->setPassword('manolo123')
                ->setAdministrador(false)
                ->setCliente(true);


            $manager->persist($usuario3);
        }

        $local1 = new Local();
         $local1->setPropietario($usuario2)->setNombre("Bar Calero")->setLongitud(38.040664)->setLatitud(-4.051243)
         ->setPuntuacion(0)->setTelefono(953505050)->setLocalidad("Andújar")->setProvincia("Jaén")->setCp(23740)->setActivo(1);
        $manager->persist($local1);

        $local2 = new Local();
        $local2->setPropietario($usuario3)->setNombre("Pacos")->setLongitud(38.039615)->setLatitud(-4.047035)
            ->setPuntuacion(0)->setTelefono(953505034)->setLocalidad("Andújar")->setProvincia("Jaén")->setCp(23740)->setActivo(1);
        $manager->persist($local2);

        $comentario1 = new Comentario();
        $comentario1->setLocal($local1)->setTexto("Muy buen trato");
        $manager->persist($comentario1);
        $comentario2 = new Comentario();
        $comentario2->setLocal($local1)->setTexto("Muy buenos precios");
        $manager->persist($comentario2);
        $comentario3 = new Comentario();
        $comentario3->setLocal($local1)->setTexto("Muy buenas tapas");
        $manager->persist($comentario3);
        
        $menu1= new Menu();
        $menu1->setEstablecimiento($local1)->setNombre("Menu Calero")->setPrecio(4,5)->setDescripcion("Menu diario");
        $manager->persist($menu1);

        $menu2= new Menu();
        $menu2->setEstablecimiento($local2)->setNombre("Menu Pacos")->setPrecio(7)->setDescripcion("Menu diario");
        $manager->persist($menu2);

        $tipo1=new Tipo();
        $tipo1->setPlato("Primer Plato");
        $manager->persist($tipo1);

        $tipo2=new Tipo();
        $tipo2->setPlato("Segundo Plato");
        $manager->persist($tipo2);

        $tipo3=new Tipo();
        $tipo3->setPlato("Postre");
        $manager->persist($tipo3);

        $tipo4=new Tipo();
        $tipo4->setPlato("Bebida");
        $manager->persist($tipo4);
        
        //Menu del local1 
        $articulo1_1 =new Articulo();
        $articulo1_1->setDescripcion("Refresco")->setTipo($tipo4)->setMenu($menu1)->setNombre('Refresco');
        $manager->persist($articulo1_1);

        $articulo1_2 =new Articulo();
        $articulo1_2->setDescripcion("Cerveza")->setTipo($tipo4)->setMenu($menu1)->setNombre("Cerveza");
        $manager->persist($articulo1_2);

        $articulo1_3 =new Articulo();
        $articulo1_3->setDescripcion("Agua")->setTipo($tipo4)->setMenu($menu1)->setNombre("Agua");
        $manager->persist($articulo1_3);

        $articulo1_4 =new Articulo();
        $articulo1_4->setDescripcion("Pescado")->setTipo($tipo1)->setMenu($menu1)->setNombre("Pescado");
        $manager->persist($articulo1_4);

        $articulo1_5 =new Articulo();
        $articulo1_5->setDescripcion("Carne")->setTipo($tipo1)->setMenu($menu1)->setNombre("Carne");
        $manager->persist($articulo1_5);

        $articulo1_6 =new Articulo();
        $articulo1_6->setDescripcion("Arroz")->setTipo($tipo2)->setMenu($menu1)->setNombre("Arroz");
        $manager->persist($articulo1_6);

        $articulo1_7 =new Articulo();
        $articulo1_7->setDescripcion("Pasta")->setTipo($tipo2)->setMenu($menu1)->setNombre("Pasta");
        $manager->persist($articulo1_7);

        $articulo1_8 =new Articulo();
        $articulo1_8->setDescripcion("Flan")->setTipo($tipo3)->setMenu($menu1)->setNombre("Flan");
        $manager->persist($articulo1_8);

        $articulo1_9 =new Articulo();
        $articulo1_9->setDescripcion("Cafe")->setTipo($tipo3)->setMenu($menu1)->setNombre("Cafe");
        $manager->persist($articulo1_9);

        //Menu del local2 
        $articulo2_1 =new Articulo();
        $articulo2_1->setDescripcion("Refresco")->setTipo($tipo4)->setMenu($menu2)->setNombre("Refersco");
        $manager->persist($articulo2_1);

        $articulo2_2 =new Articulo();
        $articulo2_2->setDescripcion("Vino")->setTipo($tipo4)->setMenu($menu2)->setNombre("Vino");
        $manager->persist($articulo2_2);

        $articulo2_3 =new Articulo();
        $articulo2_3->setDescripcion("Agua")->setTipo($tipo4)->setMenu($menu2)->setNombre("Agua");
        $manager->persist($articulo2_3);

        $articulo2_4 =new Articulo();
        $articulo2_4->setDescripcion("Pescado")->setTipo($tipo1)->setMenu($menu2)->setNombre("Pescado");
        $manager->persist($articulo2_4);

        $articulo2_5 =new Articulo();
        $articulo2_5->setDescripcion("Carne")->setTipo($tipo1)->setMenu($menu2)->setNombre("Carne");
        $manager->persist($articulo2_5);

        $articulo2_6 =new Articulo();
        $articulo2_6->setDescripcion("Tortilla")->setTipo($tipo2)->setMenu($menu2)->setNombre("Tortilla");
        $manager->persist($articulo2_6);

        $articulo2_7 =new Articulo();
        $articulo2_7->setDescripcion("Pasta")->setTipo($tipo2)->setMenu($menu2)->setNombre("Pasta");
        $manager->persist($articulo2_7);

        $articulo2_8 =new Articulo();
        $articulo2_8->setDescripcion("Tarta")->setTipo($tipo3)->setMenu($menu2)->setNombre("Tarta");
        $manager->persist($articulo2_8);

        $articulo2_9 =new Articulo();
        $articulo2_9->setDescripcion("Cafe")->setTipo($tipo3)->setMenu($menu2)->setNombre("Cafe");
        $manager->persist($articulo2_9);
        
        $manager->flush();
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}

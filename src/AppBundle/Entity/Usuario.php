<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Validation;

/**
 * @ORM\Entity
 */
class Usuario implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Validation\NotBlank()
     * @var string
     */
    private $nickname;



    /**
     * @ORM\Column(type="string")
     * @Validation\NotBlank()
     * @var string
     */
    private $nombre;

    /**
     * @ORM\Column(type="string")
     * @Validation\NotBlank()
     * @var string
     */
    private $apellido1;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @var string
     */
    private $apellido2;


    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $administrador;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $cliente;


    /**
     * @ORM\OneToOne(targetEntity="Local", inversedBy="propietario")
     */
    protected $local;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tareas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombreUsuario
     *
     * @param string $nombreUsuario
     * @return Usuario
     */
    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }

    /**
     * Get nombreUsuario
     *
     * @return string
     */
    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    /**
     * Set claveEntrada
     *
     * @param string $claveEntrada
     * @return Usuario
     */
    public function setPassword($claveEntrada)
    {
        $this->password= $claveEntrada;

        return $this;
    }

    /**
     * Get claveEntrada
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set administrador
     *
     * @param boolean $administrador
     * @return Usuario
     */
    public function setAdministrador($administrador)
    {
        $this->administrador = $administrador;

        return $this;
    }

    /**
     * Get administrador
     *
     * @return boolean
     */
    public function isAdministrador()
    {
        return $this->administrador;
    }

    /**
     * Set gestor
     *
     * @param boolean $gestor
     * @return Usuario
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }




    public function isCliente()
    {
        return $this->cliente;
    }







    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roles = ['ROLE_USER'];
        if($this->isAdministrador()){
            $roles[] = 'ROLE_ADMIN';
        }
        if($this->isCliente()){
            $roles[]='ROLE_CLIENTE';
        }

        return $roles;
    }



    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        $this->getNombreUsuario();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {

    }

    /**
     * Set apellido1
     *
     * @param string $apellido1
     * @return Usuario
     */
    public function setApellido1($apellido1)
    {
        $this->apellido1 = $apellido1;

        return $this;
    }

    /**
     * Get apellido1
     *
     * @return string 
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * Set apellido2
     *
     * @param string $apellido2
     * @return Usuario
     */
    public function setApellido2($apellido2)
    {
        $this->apellido2 = $apellido2;

        return $this;
    }

    /**
     * Get apellido2
     *
     * @return string 
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * Get administrador
     *
     * @return boolean 
     */
    public function getAdministrador()
    {
        return $this->administrador;
    }

    /**
     * Get cliente
     *
     * @return boolean 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set local
     *
     * @param Local $local
     * @return Usuario
     */
    public function setLocal(Local $local = null)
    {
        $this->local = $local;

        return $this;
    }

    /**
     * Get local
     *
     * @return Local
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     * @return Usuario
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Usuario
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
}

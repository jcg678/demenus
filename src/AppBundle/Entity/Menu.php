<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Validation;

/**
 * @ORM\Entity
 */
class Menu
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
    private $nombre;

    /**
     * @ORM\Column(type="float")
     * @var number
     */
    private $precio;

    /**
     * @ORM\Column(type="string")
     * @Validation\NotBlank()
     * @var string
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity="Local", inversedBy="menus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $establecimiento;


    /**
     * @ORM\OneToMany(targetEntity="Articulo", mappedBy="menu")
     */
    private $productos;




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Menu
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

    /**
     * Set precio
     *
     * @param float $precio
     * @return Menu
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Menu
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set establecimiento
     *
     * @param \AppBundle\Entity\Local $establecimiento
     * @return Menu
     */
    public function setEstablecimiento(\AppBundle\Entity\Local $establecimiento)
    {
        $this->establecimiento = $establecimiento;

        return $this;
    }

    /**
     * Get establecimiento
     *
     * @return \AppBundle\Entity\Local 
     */
    public function getEstablecimiento()
    {
        return $this->establecimiento;
    }

    /**
     * Add productos
     *
     * @param \AppBundle\Entity\Articulo $productos
     * @return Menu
     */
    public function addProducto(\AppBundle\Entity\Articulo $productos)
    {
        $this->productos[] = $productos;

        return $this;
    }

    /**
     * Remove productos
     *
     * @param \AppBundle\Entity\Articulo $productos
     */
    public function removeProducto(\AppBundle\Entity\Articulo $productos)
    {
        $this->productos->removeElement($productos);
    }

    /**
     * Get productos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductos()
    {
        return $this->productos;
    }
}

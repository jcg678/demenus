<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Validation;

/**
 * @ORM\Entity
 */
class Articulo
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
     * @ORM\Column(type="text")
     * @Validation\NotBlank()
     * @var string
     */
    private $descripcion;


    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="productos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $menu;



    /**
     * @ORM\ManyToOne(targetEntity="Tipo", inversedBy="articulos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipo;

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
     * @return Articulo
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Articulo
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
     * Set menu
     *
     * @param Menu $menu
     * @return Articulo
     */
    public function setMenu(Menu $menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set tipo
     *
     * @param Tipo $tipo
     * @return Articulo
     */
    public function setTipo(Tipo $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return Tipo
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}

<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Validation;

/**
 * @ORM\Entity
 */
class Tipo
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
    private $plato;



    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Articulo", mappedBy="tipo")

     */
    private $articulos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articulos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set plato
     *
     * @param string $plato
     * @return Tipo
     */
    public function setPlato($plato)
    {
        $this->plato = $plato;

        return $this;
    }

    /**
     * Get plato
     *
     * @return string 
     */
    public function getPlato()
    {
        return $this->plato;
    }

    /**
     * Add articulos
     *
     * @param \AppBundle\Entity\Articulo $articulos
     * @return Tipo
     */
    public function addArticulo(\AppBundle\Entity\Articulo $articulos)
    {
        $this->articulos[] = $articulos;

        return $this;
    }

    /**
     * Remove articulos
     *
     * @param \AppBundle\Entity\Articulo $articulos
     */
    public function removeArticulo(\AppBundle\Entity\Articulo $articulos)
    {
        $this->articulos->removeElement($articulos);
    }

    /**
     * Get articulos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticulos()
    {
        return $this->articulos;
    }
}

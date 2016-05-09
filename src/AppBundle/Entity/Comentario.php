<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Validation;

/**
 * @ORM\Entity
 */
class Comentario
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
    private $texto;


    /**
     * @ORM\ManyToOne(targetEntity="Local", inversedBy="comentarios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $local;



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
     * Set texto
     *
     * @param string $texto
     * @return Comentario
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string 
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set local
     *
     * @param Local $local
     * @return Comentario
     */
    public function setLocal(Local $local)
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
}

<?php

namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Validation;


/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Local
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
    private $latitud;

    /**
     * @ORM\Column(type="float")
     * @var number
     */
    private $longitud;


    /**
     * @ORM\Column(type="integer")
     * @var number
     */
    private $puntuacion;

    /**
     * @ORM\Column(type="integer")
     * @var number
     */
    private $telefono;

    /**
     * @ORM\Column(type="string")
     * @Validation\NotBlank()
     * @var string
     */
    private $localidad;

    /**
     * @ORM\Column(type="string")
     * @Validation\NotBlank()
     * @var string
     */
    private $provincia;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @var string
     */
    private $direccion;


    /**
     * @ORM\Column(type="string",nullable=true)

     * @var string
     */
    private $numero;


    /**
     * @ORM\Column(type="integer")
     * @var number
     */
    private $cp;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $activo;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Validation\Image(
     *      maxWidth=1100,
     *      maxHeight=800,
     *      maxSize="2M",
     *      mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="imagenLocal", fileNameProperty="fotoName")
     *
     * @var File
     */
    private $fotoImage;

    /**
     * @ORM\Column(type="string", length=255, nullable = true)
     *
     * @var string
     */
    private $fotoName;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="establecimiento")
     */
    private $menus;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Usuario", mappedBy="local")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $propietario;



    /**
     * @ORM\OneToMany(targetEntity="Comentario", mappedBy="local")
     */
    private $comentarios;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->menus = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comentarios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Local
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
     * Set latitud
     *
     * @param float $latitud
     * @return Local
     */
    public function setLatitud($latitud)
    {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get latitud
     *
     * @return float 
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud
     *
     * @param float $longitud
     * @return Local
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * Get longitud
     *
     * @return float 
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set puntuacion
     *
     * @param integer $puntuacion
     * @return Local
     */
    public function setPuntuacion($puntuacion)
    {
        $this->puntuacion = $puntuacion;

        return $this;
    }

    /**
     * Get puntuacion
     *
     * @return integer 
     */
    public function getPuntuacion()
    {
        return $this->puntuacion;
    }

    /**
     * Set telefono
     *
     * @param integer $telefono
     * @return Local
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return integer 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set localidad
     *
     * @param string $localidad
     * @return Local
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return string 
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     * @return Local
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return string 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set cp
     *
     * @param integer $cp
     * @return Local
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return integer 
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Local
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Add menus
     *
     * @param \AppBundle\Entity\Menu $menus
     * @return Local
     */
    public function addMenu(\AppBundle\Entity\Menu $menus)
    {
        $this->menus[] = $menus;

        return $this;
    }

    /**
     * Remove menus
     *
     * @param \AppBundle\Entity\Menu $menus
     */
    public function removeMenu(\AppBundle\Entity\Menu $menus)
    {
        $this->menus->removeElement($menus);
    }

    /**
     * Get menus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * Set propietario
     *
     * @param \AppBundle\Entity\Usuario $propietario
     * @return Local
     */
    public function setPropietario(\AppBundle\Entity\Usuario $propietario)
    {
        $this->propietario = $propietario;

        return $this;
    }

    /**
     * Get propietario
     *
     * @return \AppBundle\Entity\Usuario 
     */
    public function getPropietario()
    {
        return $this->propietario;
    }

    /**
     * Add comentarios
     *
     * @param \AppBundle\Entity\Comentario $comentarios
     * @return Local
     */
    public function addComentario(\AppBundle\Entity\Comentario $comentarios)
    {
        $this->comentarios[] = $comentarios;

        return $this;
    }

    /**
     * Remove comentarios
     *
     * @param \AppBundle\Entity\Comentario $comentarios
     */
    public function removeComentario(\AppBundle\Entity\Comentario $comentarios)
    {
        $this->comentarios->removeElement($comentarios);
    }

    /**
     * Get comentarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Local
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return Local
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set fotoName
     *
     * @param string $fotoName
     * @return Local
     */
    public function setFotoName($fotoName)
    {
        $this->fotoName = $fotoName;

        return $this;
    }

    /**
     * Get fotoName
     *
     * @return string 
     */
    public function getFotoName()
    {
        return $this->fotoName;
    }



    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Local
     */
    public function setfotoImage(File $image = null)
    {
        $this->fotoImage = $image;
        
        return $this;
    }

    /**
     * @return File
     */
    public function getfotoImage()
    {
        return $this->fotoImage;
    }

}

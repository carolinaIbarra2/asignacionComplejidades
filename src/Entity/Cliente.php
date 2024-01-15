<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClienteRepository::class)
 */
class Cliente
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="El nombre no puede guardarse vacio")
     * @Assert\Length(
     *          min=3, 
     *          minMessage="El nombre debe contener minimo 3 caracteres")
     * @Assert\Regex(
     *      pattern="/^[a-zA-Z]+$/",
     *      message="El nombre solo puede contener letras")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="El apellido no puede guardarse vacio")
     * @Assert\Length(
     *          min=3, 
     *          minMessage="El apellido debe contener minimo 3 caracteres")
     * @Assert\Regex(
     *      pattern="/^[a-zA-Z]+$/",
     *      message="El nombre solo puede contener letras")
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Email(
     *     message = "El correo '{{ value }}' no es un correo valido.")
     */
    private $correo;

    /**
     * @ORM\OneToMany(targetEntity=Soporte::class, mappedBy="clientes")
     */
    private $soportes;


    /**
     * Constructor de la clase Cliente.
     */
    public function __construct()
    {
        $this->soportes = new ArrayCollection();
    }

    /**
     * Obtener el identificador del cliente.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

     /**
     * Obtener el nombre del cliente.
     * @return string|null
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * Establecer el nombre del cliente.
     * @param string $nombre
     * @return self
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Obtener el apellido del cliente.
     * @return string|null
     */
    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    /**
     * Establecer el apellido del cliente.
     * @param string $apellido
     * @return self
     */
    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Obtener el correo del cliente.
     * @return string|null
     */
    public function getCorreo(): ?string
    {
        return $this->correo;
    }

     /**
     * Establecer el correo del cliente.
     * @param string $correo
     * @return self
     */
    public function setCorreo(string $correo): self
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Obtener los soportes asociados a este cliente.
     * @return Collection<int, Soporte>
     */
    public function getSoportes(): Collection
    {
        return $this->soportes;
    }

    /**
     * Agregar un soporte al cliente.
     * @param Soporte $soporte
     * @return self
     */
    public function addSoporte(Soporte $soporte): self
    {
        if (!$this->soportes->contains($soporte)) {
            $this->soportes[] = $soporte;
            $soporte->setClientes($this);
        }

        return $this;
    }

    /**
     * Eliminar un soporte del cliente.
     * @param Soporte $soporte
     * @return self
     */
    public function removeSoporte(Soporte $soporte): self
    {
        if ($this->soportes->removeElement($soporte)) {
            // set the owning side to null (unless already changed)
            if ($soporte->getClientes() === $this) {
                $soporte->setClientes(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\SoporteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SoporteRepository::class)
 */
class Soporte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La descripcion no puede guardarse vacia")
     * @Assert\Length(
     *          min=10, 
     *          minMessage="El nombre debe contener minimo 10 caracteres")
     */
    private $descripcion;

    /**
     * @ORM\Column(type="smallint")
     */
    private $complejidad;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="La fecha no puede guardarse vacia")      
     */
    private $fecha;

    /**
     * Cliente asociado al soporte.
     * @ORM\ManyToOne(targetEntity=Cliente::class, inversedBy="soportes")
     */
    private $clientes;

    /**
     * Obtiene el identificador único del soporte.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtiene la descripción del soporte.
     * @return string|null
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * Establece la descripción del soporte.
     * @param string $descripcion
     * @return self
     */
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

     /**
     * Obtiene el nivel de complejidad del soporte.
     * @return int|null
     */
    public function getComplejidad(): ?int
    {
        return $this->complejidad;
    }

     /**
     * Establece el nivel de complejidad del soporte.
     * @param int $complejidad
     * @return self
     */
    public function setComplejidad(int $complejidad): self
    {
        $this->complejidad = $complejidad;

        return $this;
    }

    /**
     * Obtiene la fecha en que se registró el soporte.
     * @return \DateTimeInterface|null
     */
    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

     /**
     * Obtiene la fecha formateada como cadena (d-m-Y).
     * @return string
     */
    public function getFechaFormateada(): string
    {
        return $this->fecha->format('d-m-Y');
    }

    /**
     * Establece la fecha en que se registró el soporte.
     * @param \DateTimeInterface $fecha
     * @return self
     */
    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

     /**
     * Obtiene el cliente asociado al soporte.
     * @return Cliente|null
     */
    public function getClientes(): ?Cliente
    {
        return $this->clientes;
    }

     /**
     * Establece el cliente asociado al soporte.
     * @param Cliente|null $clientes
     * @return self
     */
    public function setClientes(?Cliente $clientes): self
    {
        $this->clientes = $clientes;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\AsignacionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AsignacionRepository::class)
 */
class Asignacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="La fecha no puede guardarse vacia")  
     */
    private $fechaAsignacion;

    /**
     * @ORM\OneToOne(targetEntity=Soporte::class, cascade={"persist", "remove"})
     */
    private $soportes;

    /**
     * @ORM\ManyToOne(targetEntity=Trabajador::class, inversedBy="asignaciones")
     */
    private $trabajadores;

    /**
     * Obtener el identificador de la asignación.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

     /**
     * Obtener la fecha de asignación.
     * @return \DateTimeInterface|null
     */
    public function getFechaAsignacion(): ?\DateTimeInterface
    {
        return $this->fechaAsignacion;
    }

    /**
     * Establecer la fecha de asignación.
     * @param \DateTimeInterface $fechaAsignacion
     * @return self
     */
    public function setFechaAsignacion(\DateTimeInterface $fechaAsignacion): self
    {
        $this->fechaAsignacion = $fechaAsignacion;

        return $this;
    }

     /**
     * Obtener el soporte asociado a esta asignación.
     * @return Soporte|null
     */
    public function getSoportes(): ?Soporte
    {
        return $this->soportes;
    }

    /**
     * Establecer el soporte asociado a esta asignación.
     * @param Soporte|null $soportes
     * @return self
     */
    public function setSoportes(?Soporte $soportes): self
    {
        $this->soportes = $soportes;

        return $this;
    }

    /**
     * Obtener el trabajador asociado a esta asignación.
     * @return Trabajador|null
     */
    public function getTrabajadores(): ?Trabajador
    {
        return $this->trabajadores;
    }

    /**
     * Establecer el trabajador asociado a esta asignación.
     * @param Trabajador|null $trabajadores
     * @return self
     */
    public function setTrabajadores(?Trabajador $trabajadores): self
    {
        $this->trabajadores = $trabajadores;

        return $this;
    }
}

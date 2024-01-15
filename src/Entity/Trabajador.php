<?php

namespace App\Entity;

use App\Repository\TrabajadorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=TrabajadorRepository::class)
 */
class Trabajador
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
     *  @Assert\Regex(
     *      pattern="/^[a-zA-Z]+$/",
     *      message="El nombre solo puede contener letras")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Asignacion::class, mappedBy="trabajadores")
     */
    private $asignaciones;

    /**
     * Constructor de la clase Trabajador.
     */
    public function __construct()
    {
        $this->asignaciones = new ArrayCollection();
    }

     /**
     * Obtiene el identificador del trabajador.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtiene el nombre del trabajador.
     * @return string|null
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * Establece el nombre del trabajador.
     * @param string $nombre
     * @return self
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Obtiene las asignaciones asociadas a este trabajador.
     * @return Collection<int, Asignacion>
     */
    public function getAsignaciones(): Collection
    {
        return $this->asignaciones;
    }

    /**
     * Agrega una asignación al trabajador.
     * @param Asignacion $asignacione
     * @return self
     */
    public function addAsignacione(Asignacion $asignacione): self
    {
        if (!$this->asignaciones->contains($asignacione)) {
            $this->asignaciones[] = $asignacione;
            $asignacione->setTrabajadores($this);
        }

        return $this;
    }

    /**
     * Elimina una asignación del trabajador.
     * @param Asignacion $asignacione
     * @return self
     */
    public function removeAsignacione(Asignacion $asignacione): self
    {
        if ($this->asignaciones->removeElement($asignacione)) {
            // set the owning side to null (unless already changed)
            if ($asignacione->getTrabajadores() === $this) {
                $asignacione->setTrabajadores(null);
            }
        }

        return $this;
    }
}

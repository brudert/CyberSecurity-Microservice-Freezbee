<?php

namespace App\Model\Entity;

use App\Model\Repository\ModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ModelRepository::class)]
class Model
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $pUHT = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Series $ModelId = null;

    /**
     * @var Collection<int, Process>
     */
    #[ORM\OneToMany(targetEntity: Process::class, mappedBy: 'model', orphanRemoval: true)]
    private Collection $processes;

    /**
     * @var Collection<int, Dosage>
     */
    #[ORM\OneToMany(targetEntity: Dosage::class, mappedBy: 'model', orphanRemoval: true)]
    private Collection $ingredientDosages;

    public function __construct()
    {
        $this->processes = new ArrayCollection();
        $this->ingredientDosages = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPUHT(): ?int
    {
        return $this->pUHT;
    }

    public function setPUHT(int $pUHT): static
    {
        $this->pUHT = $pUHT;

        return $this;
    }

    public function getModelId(): ?Series
    {
        return $this->ModelId;
    }

    public function setModelId(?Series $ModelId): static
    {
        $this->ModelId = $ModelId;

        return $this;
    }

    /**
     * @return Collection<int, Process>
     */
    public function getProcesses(): Collection
    {
        return $this->processes;
    }

    public function addProcess(Process $process): static
    {
        if (!$this->processes->contains($process)) {
            $this->processes->add($process);
            $process->setModel($this);
        }

        return $this;
    }

    public function removeProcess(Process $process): static
    {
        if ($this->processes->removeElement($process)) {
            // set the owning side to null (unless already changed)
            if ($process->getModel() === $this) {
                $process->setModel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Dosage>
     */
    public function getIngredientDosages(): Collection
    {
        return $this->ingredientDosages;
    }

    public function addIngredientDosage(Dosage $ingredientDosage): static
    {
        if (!$this->ingredientDosages->contains($ingredientDosage)) {
            $this->ingredientDosages->add($ingredientDosage);
            $ingredientDosage->setModel($this);
        }

        return $this;
    }

    public function removeIngredientDosage(Dosage $ingredientDosage): static
    {
        if ($this->ingredientDosages->removeElement($ingredientDosage)) {
            // set the owning side to null (unless already changed)
            if ($ingredientDosage->getModel() === $this) {
                $ingredientDosage->setModel(null);
            }
        }

        return $this;
    }
}

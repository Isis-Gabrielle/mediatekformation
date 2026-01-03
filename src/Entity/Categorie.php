<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité représentant une catégorie
 */
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie {

    /**
     * Identifiant unique de la catégorie
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nom de la catégorie
     */
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $name = null;

    /**
     * @var Collection<int, Formation>
     */
    #[ORM\ManyToMany(targetEntity: Formation::class, mappedBy: 'categories')]
    private Collection $formations;

    /**
     * Initialise la collection de formations
     */
    public function __construct() {
        $this->formations = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * @param string|null $name Nom de la catégorie
     *
     * @return static
     */
    public function setName(?string $name): static {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection {
        return $this->formations;
    }

    /**
     * Associe une formation à la catégorie
     *
     * @param Formation $formation
     *
     * @return static
     */
    public function addFormation(Formation $formation): static {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->addCategory($this);
        }

        return $this;
    }

    /**
     * Supprime l’association entre la formation et la catégorie
     *
     * @param Formation $formation
     *
     * @return static
     */
    public function removeFormation(Formation $formation): static {
        if ($this->formations->removeElement($formation)) {
            $formation->removeCategory($this);
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entité représentant une formation
 */
#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation {

    /**
     * Début de chemin vers les images
     */
    private const CHEMINIMAGE = "https://i.ytimg.com/vi/";

    /**
     * Identifiant unique de la formation
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Date de publication
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\LessThanOrEqual("now", message: "La date ne peut pas être au-delà d'aujourd'hui.")]
    private ?DateTimeInterface $publishedAt = null;

    /**
     * Titre de la formation
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $title = null;

    /**
     * Description de la formation
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * Identifiant de la vidéo YouTube associée
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $videoId = null;

    /**
     * Playlist associée à la formation
     */
    #[ORM\ManyToOne(inversedBy: 'formations')]
    private ?Playlist $playlist = null;

    /**
     * Catégories associées à la formation
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'formations')]
    private Collection $categories;

    /**
     * Initialise la collection de catégories
     */
    public function __construct() {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getPublishedAt(): ?DateTimeInterface {
        return $this->publishedAt;
    }

    /**
     * @param DateTimeInterface|null $publishedAt Date de publication
     *
     * @return static
     */
    public function setPublishedAt(?DateTimeInterface $publishedAt): static {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return string Date au format d/m/Y ou chaîne vide
     */
    public function getPublishedAtString(): string {
        if ($this->publishedAt == null) {
            return "";
        }
        return $this->publishedAt->format('d/m/Y');
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string {
        return $this->title;
    }

    /**
     * @param string|null $title Titre de la formation
     *
     * @return static
     */
    public function setTitle(?string $title): static {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    /**
     * @param string|null $description Description de la formation
     *
     * @return static
     */
    public function setDescription(?string $description): static {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVideoId(): ?string {
        return $this->videoId;
    }

    /**
     * @param string|null $videoId Identifiant YouTube
     *
     * @return static
     */
    public function setVideoId(?string $videoId): static {
        $this->videoId = $videoId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMiniature(): ?string {
        return self::CHEMINIMAGE . $this->videoId . "/default.jpg";
    }

    /**
     * @return string|null
     */
    public function getPicture(): ?string {
        return self::CHEMINIMAGE . $this->videoId . "/hqdefault.jpg";
    }

    /**
     * @return Playlist|null
     */
    public function getPlaylist(): ?playlist {
        return $this->playlist;
    }

    /**
     * @param Playlist|null $playlist Playlist associée
     *
     * @return static
     */
    public function setPlaylist(?Playlist $playlist): static {
        $this->playlist = $playlist;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection {
        return $this->categories;
    }
    
    /**
     * Associe une catégorie à la formation
     *
     * @param Categorie $category
     *
     * @return static
     */
    public function addCategory(Categorie $category): static {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }
    
    /**
     * Supprime l’association avec une catégorie
     *
     * @param Categorie $category
     *
     * @return static
     */
    public function removeCategory(Categorie $category): static {
        $this->categories->removeElement($category);

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 * @UniqueEntity(fields={"name"}, message="Ce nom de figure est déjà pris.")
 * @UniqueEntity(fields={"slug"}, message="Ce slug existe déjà.")
 */
class Trick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Length(
     *      min = 3, 
     *      max = 50,
     *      minMessage = "Le nom de votre figure doit être supérieur à {{ limit }} caractères.",
     *      maxMessage = "Le nom de votre figure ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 10, 
     *      max = 255,
     *      minMessage = "La description de votre figure doit être supérieure à {{ limit }} caractères.",
     *      maxMessage = "La description de votre figure ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 3, 
     *      max = 50,
     *      minMessage = "La catégorie de votre figure doit être supérieure à {{ limit }} caractères.",
     *      maxMessage = "La catégorie de votre figure ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $category;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *      message = "Cette valeur ne doit pas être laissée blanche."
     * )
     * @Assert\NotNull(
     *      message = "Cette valeur ne doit pas être laissée nulle."
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="date")
     * @Assert\DateTime(
     *      message = "Ce n'est pas une date valide."
     * )
     */
    private $creationDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\DateTime(
     *      message = "Ce n'est pas une date valide."
     * )
     */
    private $modifDate;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Length(
     *      min = 3, 
     *      max = 50,
     *      minMessage = "Le slug de l'URL de votre figure doit être supérieur à {{ limit }} caractères.",
     *      maxMessage = "Le slug de l'URL de votre figure ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tricks")
     * @ORM\JoinColumn(name="user_id", nullable=false)
     * @Assert\Length(
     *      min = 3, 
     *      max = 50,
     *      minMessage = "Votre nom d'auteur doit être supérieur à {{ limit }} caractères.",
     *      maxMessage = "Votre nom d'auteur ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="trick", orphanRemoval=true)
     */
    private $media;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="trick", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getModifDate(): ?\DateTimeInterface
    {
        return $this->modifDate;
    }

    public function setModifDate(?\DateTimeInterface $modifDate): self
    {
        $this->modifDate = $modifDate;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setTrick($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getTrick() === $this) {
                $medium->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }
}

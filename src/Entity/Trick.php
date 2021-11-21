<?php

namespace App\Entity;

use App\Repository\TrickRepository;
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
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 3, 
     *      max = 50,
     *      minMessage = "Votre nom d'auteur doit être supérieur à {{ limit }} caractères.",
     *      maxMessage = "Votre nom d'auteur ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $author;

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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

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
}

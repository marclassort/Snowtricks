<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 3, 
     *      max = 50,
     *      minMessage = "Le nom de votre média doit être supérieur à {{ limit }} caractères.",
     *      maxMessage = "Le nom de votre média ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 3, 
     *      max = 50,
     *      minMessage = "Le type de votre média doit être supérieur à {{ limit }} caractères.",
     *      maxMessage = "Le type de votre média ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 3, 
     *      max = 255,
     *      minMessage = "Le texte alternatif de votre média doit être supérieur à {{ limit }} caractères.",
     *      maxMessage = "Le texte alternatif de votre média ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $altText;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message = "Cette valeur ne doit pas être laissée blanche."
     * )
     * @Assert\NotNull(
     *      message = "Cette valeur ne doit pas être laissée nulle."
     * )
     */
    private $url;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setAltText(string $altText): self
    {
        $this->altText = $altText;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}

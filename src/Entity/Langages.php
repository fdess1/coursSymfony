<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use http\Env\Response;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LangagesRepository")
 */
class Langages
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $lg_nom;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFront;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->lg_nom;
    }

    public function setLgNom(string $lg_nom): self
    {
        $this->lg_nom = $lg_nom;
        $this->slug = $this->FaitUnSlug($this->lg_nom);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIsFront(): ?bool
    {
        return $this->isFront;
    }

    public function setIsFront(bool $isFront): self
    {
        $this->isFront = $isFront;

        return $this;
    }
    public function __toString()
    {
        return $this->lg_nom;

    }

    private function FaitUnSlug (string $texte) : string {
        // replace non letter or digits by -
        $texte = preg_replace('~[^\pL\d]+~u', '_', $texte);

        // transliterate
        $texte = iconv('utf-8', 'us-ascii//IGNORE', $texte);

        // remove unwanted characters
        $texte = preg_replace('~[^-\w]+~', '', $texte);

        // trim
        $texte = trim($texte, '-');

        // remove duplicate -
        $texte = preg_replace('~-+~', '_', $texte);

        // lowercase
        $texte = strtolower ($texte);
        return $texte;
    }
}

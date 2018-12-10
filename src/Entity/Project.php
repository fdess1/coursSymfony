<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use http\Env\Response;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Le nom doit avoir au moins {{ limit }} caracteres ",
     *      maxMessage = "Le nom doit avoir au plus {{ limit }} caracteres"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     *   /// Assert\LessThanOrEqual("today")
     */
    private $programmed_At;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_Published;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $published_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 0,
     *      max = 50,
     *      minMessage = "Le nom doit avoir au moins {{ limit }} caracteres ",
     *      maxMessage = "L'URL doit avoir au plus {{ limit }} caracteres"
     * )
     */
    private $url;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Langages")
     */
    private $langages;
    public function __construct()
    {
        $this->published_at = new \DateTime('now');
        $this->is_Published = false;
        $this->langages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
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
    public function setName(string $name): self
    {
        $this->name = $name;
        $this->slug = $this->FaitUnSlug($this->name);

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

    public function getProgrammedAt(): ?\DateTimeInterface
    {
        return $this->programmed_At;
    }

    public function setProgrammedAt(?\DateTimeInterface $programmed_At): self
    {
        $this->programmed_At = $programmed_At;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->is_Published;
    }

    public function setIsPublished(bool $is_Published): self
    {
        $this->is_Published = $is_Published;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->published_at;
    }

    public function setPublishedAt(?\DateTimeInterface $published_at): self
    {
        $this->published_at = $published_at;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection|Langages[]
     */
    public function getLangages(): Collection
    {
        return $this->langages;
    }

    public function addLangage(Langages $langage): self
    {
        if (!$this->langages->contains($langage)) {
            $this->langages[] = $langage;
        }

        return $this;
    }

    public function removeLangage(Langages $langage): self
    {
        if ($this->langages->contains($langage)) {
            $this->langages->removeElement($langage);
        }

        return $this;
    }


}

<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Forum::class, inversedBy: 'categories')]
    private Collection $forum;


    public function __toString()
    {
        return $this->getName();
    }
    

    public function __construct()
    {
        $this->forum = new ArrayCollection();
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

    /**
     * @return Collection<int, Forum>
     */
    public function getForum(): Collection
    {
        return $this->forum;
    }

    public function addForum(Forum $forum): self
    {
        if (!$this->forum->contains($forum)) {
            $this->forum->add($forum);
        }

        return $this;
    }

    public function removeForum(Forum $forum): self
    {
        $this->forum->removeElement($forum);

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ForumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: ForumRepository::class)]
class Forum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 300)]
    #[Assert\NotBlank()]
    //permet de metrre min 2 max 50
    #[Assert\Length(min: 2, max: 300)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $isFavorite = null;

    #[ORM\Column]
    private ?bool $isPublic = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'forum')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'forum', targetEntity: Mark::class, orphanRemoval: true)]
    private Collection $marks;

    private ?int $average = null;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'forum')]
    #[ORM\JoinColumn(nullable: false)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'forum', targetEntity: ForumImage::class,  cascade: ['persist'], orphanRemoval: true)]
    private Collection $images;



    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->marks = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function isIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(bool $isFavorite): self
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setcreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Mark>
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

    public function addMark(Mark $mark): self
    {
        if (!$this->marks->contains($mark)) {
            $this->marks->add($mark);
            $mark->setForum($this);
        }

        return $this;
    }

    public function removeMark(Mark $mark): self
    {
        if ($this->marks->removeElement($mark)) {
            // set the owning side to null (unless already changed)
            if ($mark->getForum() === $this) {
                $mark->setForum(null);
            }
        }

        return $this;
    }

        /**
     * Get the value of average
     */
    public function getAverage()
    {
        $marks = $this->marks;

        if ($marks->toArray() === []) {
            $this->average = null;
            return $this->average;
        }

        $total = 0;
        foreach ($marks as $mark) {
            $total += $mark->getMark();
        }

        $this->average = $total / count($marks);

        return $this->average;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addForum($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeForum($this);
        }

        return $this;
    }

     /**
     * @return Collection<int, ForumImage>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ForumImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setForum($this);
        }

        return $this;
    }

    public function removeImage(ForumImage $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getForum() === $this) {
                $image->setForum(null);
            }
        }

        return $this;
    }

}

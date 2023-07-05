<?php

/**
 * entity recipe
 */
namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

/**
 * Recipe class.
 */
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\Table(name: 'recipes')]
class Recipe
{
    /**
     * @var int|null id
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null title
     */
    #[ORM\Column(length: 250)]
    private ?string $title = null;

    /**
     * @var string|null content
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    /**
     * Category.
     *
     * @var Category
     */
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
     * @var \DateTimeImmutable|null createdAt
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * /**
     * Tags.
     *
     * @var ArrayCollection<int, Tag>
     */
    #[Assert\Valid]
    #[ORM\ManyToMany(targetEntity: Tag::class, fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private $tags;

    /**
     * @var int|null score
     */
    #[ORM\Column(nullable: true)]
    private ?int $score = null;

    /**
     * @var int|null votes
     */
    #[ORM\Column(nullable: true)]
    private ?int $votes = null;

    /**
     * @var float|null rating
     */
    #[ORM\Column(nullable: true)]
    private ?float $rating = null;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Get Id.
     *
     * @return int|null id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get title.
     *
     * @return string|null title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * set title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * get content.
     *
     * @return string|null content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * set content.
     *
     * @param string $content
     *
     * @return $this
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * get category.
     *
     * @return $this
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * set category.
     *
     * @param Category $category
     *
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * get created at.
     *
     * @return \DateTimeImmutable|null createdAt
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * set created at.
     *
     * @param \DateTimeImmutable $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * get tags.
     *
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * add tag.
     *
     * @param Tag $tag
     *
     * @return $this
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * remove tag.
     *
     * @param Tag $tag
     *
     * @return $this
     */
    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * get score.
     *
     * @return int|null score
     */
    public function getScore(): ?int
    {
        return $this->score;
    }

    /**
     * set score.
     *
     * @param int $score
     *
     * @return $this
     */
    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * get votes.
     *
     * @return int|null votes
     */
    public function getVotes(): ?int
    {
        return $this->votes;
    }

    /**
     * set votes.
     *
     * @param int $votes
     *
     * @return $this
     */
    public function setVotes(?int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * get rating.
     *
     * @return float|null rating
     */
    public function getRating(): ?float
    {
        return $this->rating;
    }

    /**
     * set rating.
     *
     * @param float $rating
     *
     * @return $this
     */
    public function setRating(?float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}

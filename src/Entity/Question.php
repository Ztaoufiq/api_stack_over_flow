<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Question
 * @package App\Entity
 * @ORM\Entity
 */
class Question
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Groups({"get"})
     */
    private ?int $id = null;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Groups({"get"})
     * @Assert\NotBlank
     * @Assert\Length(min=5)
     */
    private ?string $title = null;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Groups({"get"})
     * @Assert\NotBlank
     * @Assert\Length(min=5)
     */
    private ?string $content = null;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"get"})
     */
    private \DateTimeInterface $posedAt;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    private User $author;

    /**
     * @param string $title
     * @param string $content
     * @param User $author
     * @return static
     */
    public static function create(string $title,string $content, User $author): self
    {
        $question = new self();
        $question->title = $title;
        $question->content = $content;
        $question->author = $author;

        return $question;
    }

    /**
     * Question constructor.
     */
    public function __construct()
    {
        $this->posedAt = new \DateTimeImmutable();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getPosedAt(): \DateTimeInterface
    {
        return $this->posedAt;
    }

    /**
     * @param \DateTimeInterface $posedAt
     */
    public function setPosedAt(\DateTimeInterface $posedAt): void
    {
        $this->posedAt = $posedAt;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }
}
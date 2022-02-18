<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Answer
 * @package App\Entity
 * @ORM\Entity
 */
class Answer
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private ?int $id;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private string $content;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeInterface $answeredAt;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    private User $author;

    /**
     * @var Question
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Question $question;

    /**
     * @var Collection|User[]
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="answer_valid")
     */
    private Collection|array $validatedBy;

    /**
     * @param string $content
     * @param User $author
     * @param Question $question
     * @return static
     */
    public static function create(string $content, User $author, Question $question): self
    {
        $answer = new self();
        $answer->content = $content;
        $answer->author = $author;
        $answer->question = $question;

        return $answer;
    }

    /**
     * Answer constructor.
     */
    public function __construct()
    {
        $this->answeredAt = new \DateTimeImmutable();
        $this->validatedBy = new ArrayCollection();
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
    public function getAnsweredAt(): \DateTimeInterface
    {
        return $this->answeredAt;
    }

    /**
     * @param \DateTimeInterface $answeredAt
     */
    public function setAnsweredAt(\DateTimeInterface $answeredAt): void
    {
        $this->answeredAt = $answeredAt;
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

    /**
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    /**
     * @param Question $question
     */
    public function setQuestion(Question $question): void
    {
        $this->question = $question;
    }

    /**
     * @return Collection
     */
    public function getValidatedBy(): Collection
    {
        return $this->validatedBy;
    }

    /**
     * @param User $user
     */
    public function setValidatedBy(User $user): void
    {
        if ($this->validatedBy->contains($user)) {
            return;
        }

        $this->validatedBy->add($user);
    }

    /**
     * @param User $user
     */
    public function invalidatedBy(User $user): void
    {
        if (!$this->validatedBy->contains($user)) {
            return;
        }

        $this->validatedBy->removeElement($user);
    }
}
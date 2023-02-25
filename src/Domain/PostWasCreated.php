<?php

namespace App\Domain;

use Buttercup\Protects\DomainEvent;
use Buttercup\Protects\IdentifiesAggregate;

class PostWasCreated implements DomainEvent
{
    private $postId;
    private $title;
    private $content;

    public function __construct($aggregateId, $title, $content)
    {
        $this->postId = $aggregateId;
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * The Aggregate this event belongs to.
     * @return IdentifiesAggregate
     */
    public function getAggregateId()
    {
        return $this->postId;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
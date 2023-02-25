<?php

namespace App\Infrastructure\Persistence\EventStore;

use Buttercup\Protects\IdentifiesAggregate;
use Buttercup\Protects\IsEventSourced;
use Buttercup\Protects\RecordsEvents;
use App\Domain\Post;
use App\Domain\PostProjection;
use App\Domain\PostRepository as BasePostRepository;
use App\Infrastructure\Persistence\EventStore\PDOEventStore;
class PostRepository implements BasePostRepository
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var PostProjection
     */
    private $postProjection;

    public function __construct($eventStore, $postProjection)
    {
        $this->eventStore = $eventStore;
        $this->postProjection = $postProjection;
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return IsEventSourced
     */
    public function get(IdentifiesAggregate $aggregateId)
    {
        $eventStream = $this->eventStore->getAggregateHistoryFor($aggregateId);

        return Post::reconstituteFrom($eventStream);
    }

    /**
     * @param RecordsEvents $aggregate
     * @return void
     */
    public function add(RecordsEvents $aggregate)
    {
        $events = $aggregate->getRecordedEvents();
        //$eventStore = new PDOEventStore();
        //echo '<pre>';var_dump($this->eventStore);echo '</pre>';
        //die();
        $this->eventStore->commit($events);
        die();     
        $this->postProjection->project($events);

        $aggregate->clearRecordedEvents();
    }
}
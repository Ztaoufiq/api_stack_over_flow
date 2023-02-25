<?php

namespace App\Domain;

class Comment
{
    /**
     * @var CommentId
     */
    private $id;

    /**
     * @var string
     */
    private $comment;

    public function __construct($id, $comment)
    {
        $this->id = $id;
        $this->comment = $comment;
    }

    public static function create($aCommentId, $aComment)
    {
        return new Comment($aCommentId, $aComment);
    }
}
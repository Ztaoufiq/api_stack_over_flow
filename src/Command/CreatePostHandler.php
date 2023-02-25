<?php

namespace App\Command;

use App\Command\CreatePostCommand;
use App\Domain\Post;
use App\Domain\PostId;
use App\Domain\PostRepository;

class CreatePostHandler
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    public function __construct($postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function handle(CreatePostCommand $aCreatePostCommand)
    {
        $aNewPost = Post::create(
            PostId::generate(),
            $aCreatePostCommand->getTitle(),
            $aCreatePostCommand->getContent()
        );
        //echo '<pre>';var_dump($aNewPost);echo '</pre>';
            
            
        //die('eeeee');
        $this->postRepository->add($aNewPost);
    }
}
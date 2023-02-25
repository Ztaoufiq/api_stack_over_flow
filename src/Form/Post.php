<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;

class Post
{
    /**
    * @Assert\NotBlank
    */
    public ?string $title = '';
    
    /**
    * @Assert\NotBlank
    */
    public ?string $content = '';


    public function __construct()
    {
    }
}
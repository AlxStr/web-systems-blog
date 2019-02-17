<?php

namespace app\models\services;

use app\models\forms\PostForm;
use app\models\Post;
use app\models\repositories\PostRepository;

class PostManageService
{
    private $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    public function create(PostForm $form, $active = null): Post
    {
        $post = Post::create(
            $form->title,
            $form->category_id,
            $form->description,
            $form->body
        );
        $post->loadPhoto($form);
        if($active){
            $post->status = Post::ACTIVE;
        } else {
            $post->status = Post::INACTIVE;
        }
        $this->posts->save($post);

        return $post;
    }

    public function edit($id, PostForm $form): void
    {
        $post = $this->posts->get($id);

        $post->edit(
            $form->title,
            $form->category_id,
            $form->description,
            $form->body
        );

        $post->loadPhoto($form);
        $this->posts->save($post);
    }

    public function remove($id): void
    {
        $post = $this->posts->get($id);
        $this->posts->remove($post);
    }

    public function activate($id): void
    {
        $post = $this->posts->get($id);
        $post->activate();
        $this->posts->save($post);
    }

}
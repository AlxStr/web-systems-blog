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

    public function create(PostForm $form): Post
    {
        $post = Post::create(
            $form->title,
            $form->category_id,
            $form->description,
            $form->body,
            $form->logo,
            $form->status
        );

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
            $form->body,
            $form->logo
        );

        $this->posts->save($post);
    }

    public function activate($id): void
    {
        $post = $this->posts->get($id);
        $post->activate();
        $this->posts->save($post);
    }

    public function remove($id): void
    {
        $post = $this->posts->get($id);
        $this->posts->remove($post);
    }
}
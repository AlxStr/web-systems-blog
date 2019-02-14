<?php

namespace app\models\services;


use app\models\forms\PhotoForm;
use app\models\forms\PostCreateForm;
use app\models\forms\PostEditForm;
use app\models\Post;
use app\models\repositories\PostRepository;

class PostManageService
{
    private $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    public function create(PostCreateForm $form, $active = null): Post
    {
        $post = Post::create(
            $form->title,
            $form->category_id,
            $form->description,
            $form->body
        );

        if($active){
            $post->status = Post::ACTIVE;
        } else {
            $post->status = Post::INACTIVE;
        }

        foreach ($form->photo->file as $image){
            $post->updatePhoto($image);
        }

        $this->posts->save($post);

        return $post;
    }

    public function edit($id, PostEditForm $form): void
    {
        $post = $this->posts->get($id);

        $post->edit(
            $form->title,
            $form->category_id,
            $form->description,
            $form->body
        );

        $this->posts->save($post);
    }

    public function addPhoto($id, PhotoForm $form): void
    {
        $post = $this->posts->get($id);

        $post->updatePhoto($form->file[0]);
        $this->posts->save($post);
    }

    public function removePhoto($id, $photoId): void
    {
        $post = $this->posts->get($id);
        $post->removePhoto($photoId);
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
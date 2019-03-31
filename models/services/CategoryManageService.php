<?php

namespace app\models\services;

use app\models\Category;
use app\models\forms\CategoryForm;
use app\models\repositories\CategoryRepository;

class CategoryManageService
{
    private $categories;

    public function __construct(CategoryRepository $categories)
    {
        $this->categories = $categories;
    }

    public function create(CategoryForm $form): Category
    {
        $cat = Category::create(
            $form->title
        );
        $this->categories->save($cat);
        return $cat;
    }

    public function edit($id, CategoryForm $form): void
    {
        $cat = $this->categories->get($id);

        $cat->edit(
            $form->title
        );
        $this->categories->save($cat);
    }

    public function remove($id): void
    {
        $post = $this->categories->get($id);
        $this->categories->remove($post);
    }
}
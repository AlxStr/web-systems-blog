<?php

namespace app\commands;

use app\models\User;
use app\rbac\AuthorRule;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $authManager = Yii::$app->authManager;
        $authManager->removeAll();
        // create roles
        $admin = $authManager->createRole('admin');
        $author = $authManager->createRole('author');
        $guest = $authManager->createRole('guest');

        //create permissions
        $postIndex = $authManager->createPermission('post index');
        $postCreate = $authManager->createPermission('post create');
        $postView = $authManager->createPermission('post view');
        $postUpdate = $authManager->createPermission('post update');
        $postDelete = $authManager->createPermission('post delete');

        $categoryIndex = $authManager->createPermission('category index');
        $categoryCreate = $authManager->createPermission('category create');
        $categoryView = $authManager->createPermission('category view');
        $categoryUpdate = $authManager->createPermission('category update');
        $categoryDelete = $authManager->createPermission('category delete');

        // add permissions to auth manager
        $authManager->add($postIndex);
        $authManager->add($postCreate);
        $authManager->add($postView);
        $authManager->add($postUpdate);
        $authManager->add($postDelete);

        $authManager->add($categoryIndex);
        $authManager->add($categoryCreate);
        $authManager->add($categoryView);
        $authManager->add($categoryUpdate);
        $authManager->add($categoryDelete);

        // add roles to auth manager
        $authManager->add($admin);
        $authManager->add($author);
        $authManager->add($guest);

        //add permissions-per-role in auth manager
        // Guest
        $authManager->addChild($guest, $postIndex);
        $authManager->addChild($guest, $postView);


        $rule = new AuthorRule;
        $authManager->add($rule);

        $updateOwnPost = $authManager->createPermission('updateOwnPost');
        $updateOwnPost->ruleName = $rule->name;
        $authManager->add($updateOwnPost);
        $authManager->addChild($updateOwnPost, $postUpdate);
        $authManager->addChild($author, $updateOwnPost);

        // Author
        $authManager->addChild($author, $guest);
        $authManager->addChild($author, $postCreate);
        $authManager->addChild($author, $postUpdate);
        $authManager->addChild($author, $postDelete);

        // Admin
        $authManager->addChild($admin, $author);
        $authManager->addChild($admin, $categoryIndex);
        $authManager->addChild($admin, $categoryCreate);
        $authManager->addChild($admin, $categoryView);
        $authManager->addChild($admin, $categoryUpdate);
        $authManager->addChild($admin, $categoryDelete);
    }
}
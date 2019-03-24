<?php

use app\models\fixtures\PostFixture;
use app\models\fixtures\TokenFixture;
use app\models\fixtures\UserFixture;

class PostsCest
{
    public function _before(ApiTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
            'token' => [
                'class' => TokenFixture::className(),
                'dataFile' => codecept_data_dir() . 'token.php'
            ],
            'post' => [
                'class' => PostFixture::className(),
                'dataFile' => codecept_data_dir() . 'post.php'
            ],
        ]);
    }

    public function indexUnauthorized(ApiTester $I){
        $I->sendGET('/posts');
        $I->seeResponseCodeIs(401);
    }

    public function index(ApiTester $I)
    {
        $I->amBearerAuthenticated('admin-token-correct');
        $I->sendGET('/posts');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            ['status' => ['code' => 1, 'active' => true]],
        ]);
    }

    public function createUnauthorized(ApiTester $I)
    {
        $I->sendPOST('/posts', [
            'title' => 'New Post',
        ]);
        $I->seeResponseCodeIs(401);
    }

    public function create(ApiTester $I)
    {
        $I->amBearerAuthenticated('admin-token-correct');
        $I->sendPOST('/posts', [
            'title' => 'New Post',
            'category_id' => '3',
            'description' => 'desc',
            'body' => 'new post content'
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'author' => ['id' => 1, 'username' => 'erau'],
            'title' => 'New Post',
        ]);
    }

    public function createValidationFailed(ApiTester $I)
    {
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendPOST('/posts', []);
        $I->seeResponseCodeIs(422);
    }


    public function updateUnauthorized(ApiTester $I)
    {
        $I->sendPUT('/posts/1', ['title' => 'New Title']);
        $I->seeResponseCodeIs(401);
    }

    public function updateAsAdmin(ApiTester $I)
    {
        $I->amBearerAuthenticated('admin-token-correct');
        $I->sendPUT('/posts/1', ['title' => 'New Title']);
        $I->seeResponseCodeIs(200);
    }

    public function updateForbbidenForAuthor(ApiTester $I)
    {
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendPUT('/posts/1', ['title' => 'New Title']);
        $I->seeResponseCodeIs(403);
    }

    public function updateAsOwner(ApiTester $I)
    {
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendPUT('/posts/3', ['title' => 'New Title']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'author' => ['id' => 2, 'username' => 'john'],
            'title' => 'New Title',
        ]);
    }

    public function deleteUnauthorized(ApiTester $I)
    {
        $I->sendDELETE('/posts/1');
        $I->seeResponseCodeIs(401);
    }

    public function deleteAsAdmin(ApiTester $I)
    {
        $I->amBearerAuthenticated('admin-token-correct');
        $I->sendDELETE('/posts/1');
        $I->seeResponseCodeIs(204);
    }

    public function deleteForbbidenForAuthor(ApiTester $I)
    {
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendDELETE('/posts/1');
        $I->seeResponseCodeIs(403);
    }

    public function deleteAsOwner(ApiTester $I)
    {
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendDELETE('/posts/3');
        $I->seeResponseCodeIs(204);
    }


    public function activateUnauthorized(ApiTester $I){
        $I->sendGET('/posts/1/activate');
        $I->seeResponseCodeIs(401);
    }

    public function activateForbbiden(ApiTester $I){
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendGET('/posts/1/activate');
        $I->seeResponseCodeIs(403);
    }

    public function activateAsAdmin(ApiTester $I){
        $I->amBearerAuthenticated('admin-token-correct');
        $I->sendGET('/posts/1/activate');
        $I->seeResponseCodeIs(204);
    }
}

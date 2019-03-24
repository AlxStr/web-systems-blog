<?php

use app\models\fixtures\CategoryFixture;
use app\models\fixtures\TokenFixture;

class CategoriesCest
{
    public function _before(ApiTester $I)
    {
        $I->haveFixtures([
            'token' => [
                'class' => TokenFixture::className(),
                'dataFile' => codecept_data_dir() . 'token.php'
            ],
            'category' => [
                'class' => CategoryFixture::className(),
                'dataFile' => codecept_data_dir() . 'category.php'
            ],
        ]);
    }

    public function indexUnauthorized(ApiTester $I){
        $I->sendGET('/categories');
        $I->seeResponseCodeIs(401);
    }

    public function index(ApiTester $I)
    {
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendGET('/categories');
        $I->seeResponseCodeIs(200);
    }

    public function createForbidden(ApiTester $I){
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendPOST('/categories', ['title' => 'New Title']);
        $I->seeResponseCodeIs(403);
    }

    public function updateForbidden(ApiTester $I){
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendPUT('/categories/1', ['title' => 'New Category Title']);
        $I->seeResponseCodeIs(403);
    }

    public function deleteForbbiden(ApiTester $I){
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendDELETE('/categories/1');
        $I->seeResponseCodeIs(403);
    }
}

<?php
use app\models\fixtures\TokenFixture;
use app\models\fixtures\UserFixture;

class UsersCest
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
        ]);
    }

    public function indexUnauthorized(ApiTester $I){
        $I->sendGET('/users');
        $I->seeResponseCodeIs(401);
    }

    public function index(ApiTester $I)
    {
        $I->amBearerAuthenticated('admin-token-correct');
        $I->sendGET('/users');
        $I->seeResponseCodeIs(200);
    }

    public function indexForbbiden(ApiTester $I)
    {
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendGET('/users');
        $I->seeResponseCodeIs(403);
    }

    public function create(ApiTester $I){
        $I->amBearerAuthenticated('admin-token-correct');
        $I->sendPOST('/users', [
            'username' => 'newUser',
            'email' => 'newUser@email.com',
            'password' => 'qwerty'
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'username' => 'newUser',
            'email' => 'newUser@email.com',
        ]);
    }

    public function createForbbiden(ApiTester $I){
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendPOST('/users', [
            'username' => 'newUser',
            'email' => 'newUser@email.com',
            'password' => 'qwerty'
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function update(ApiTester $I){
        $I->amBearerAuthenticated('admin-token-correct');
        $I->sendPUT('/users/2', [
            'username' => 'newUsername',
            'email' => 'newUser@email.com',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'username' => 'newUsername',
            'email' => 'newUser@email.com',
        ]);
    }

    public function updateForbbiden(ApiTester $I){
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendPUT('/users/2', [
            'username' => 'newUsername',
            'email' => 'newUser@email.com',
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function ban(ApiTester $I){
        $I->amBearerAuthenticated('admin-token-correct');
        $I->sendGET('/users/2/ban');
        $I->seeResponseCodeIs(204);
    }

    public function banForbbiden(ApiTester $I){
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendGET('/users/2/ban');
        $I->seeResponseCodeIs(403);
    }

    public function unban(ApiTester $I){
        $I->amBearerAuthenticated('admin-token-correct');
        $I->sendGET('/users/2/unban');
        $I->seeResponseCodeIs(204);
    }

    public function unbanForbbiden(ApiTester $I){
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendGET('/users/2/ban');
        $I->seeResponseCodeIs(403);
    }

    public function delete(ApiTester $I){
        $I->amBearerAuthenticated('admin-token-correct');
        $I->sendDELETE('/users/2');
        $I->seeResponseCodeIs(204);
    }

    public function deleteForbbiden(ApiTester $I){
        $I->amBearerAuthenticated('author-token-correct');
        $I->sendDELETE('/users/2');
        $I->seeResponseCodeIs(403);
    }
}

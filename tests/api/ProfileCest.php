<?php 

class ProfileCest
{
    public function _before(ApiTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => \app\models\fixtures\UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
            'token' => [
                'class' => \app\models\fixtures\TokenFixture::className(),
                'dataFile' => codecept_data_dir() . 'token.php'
            ],
        ]);
    }

    public function accessUnauthorized(ApiTester $I){
        $I->sendGET('/profile');
        $I->seeResponseCodeIs(401);
    }

    public function authenticatedUnauthorized(ApiTester $I){
        $I->amBearerAuthenticated('token-wrong');
        $I->sendGET('/profile');
        $I->seeResponseCodeIs(401);
    }

    public function authenticated(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-correct');
        $I->sendGET('/profile');
        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('$.name');
    }

    public function expired(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-expired');
        $I->sendGET('/profile');
        $I->seeResponseCodeIs(401);
    }
}

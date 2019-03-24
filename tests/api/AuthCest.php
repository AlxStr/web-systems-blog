<?php
namespace app\tests\api;
class AuthCest
{
    public function success(\ApiTester $I): void
    {
        $I->sendPOST('/auth', [
            'username' => 'erau',
            'password' => 'password_0',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.expired');
    }

    public function error(\ApiTester $I): void
    {
        $I->sendPOST('/auth', [
            'username' => 'erau',
            'password' => 'wrong-password',
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            'field' => 'password',
            'message' => 'Incorrect username or password.'
        ]);
    }
}
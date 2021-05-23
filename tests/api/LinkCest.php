<?php

class LinkCest
{
    private $source;

    public function _before(ApiTester $I)
    {
    }

    // tests
    public function CreateToTest(ApiTester $I)
    {
        $I->wantTo("Create Link");
        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->comment("If the link was not send");
        $I->sendPost('/link/create');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => false
        ]);

        $I->comment("If the link was send, but the url is more than 255 symbols");
        $I->sendPost('/link/create', [
            'link' => 'http://somedomain.ru/some-path-' . sha1(microtime()) . sha1(microtime()) . sha1(microtime()) . sha1(microtime()) . sha1(microtime()) . sha1(microtime())
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => false,
        ]);
        $I->canSeeResponseJsonMatchesJsonPath('$.data');


        $this->source = 'http://somedomain.ru/some-path-' . md5(microtime());
        $I->comment("If the correct link was send");
        $I->sendPost('/link/create', [
           'link' => $this->source
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => true,
        ]);
        $I->canSeeResponseJsonMatchesJsonPath('$.data.hash');
    }

    public function GetToTest(ApiTester $I)
    {
        $I->wantTo("Get Link");

        $I->haveHttpHeader('Content-Type', 'application/json');

        $I->comment("If the link was send, but the url is more than 6 symbols");
        $I->sendGet('/link/12345678');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => false
        ]);
        $I->canSeeResponseJsonMatchesJsonPath('$.data');

        $I->comment("If the link was send, but the url is less than 4 symbols");
        $I->sendGet('/link/123');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => false
        ]);
        $I->canSeeResponseJsonMatchesJsonPath('$.data');

        $I->comment("If the correct link was send");
        $link = \app\models\Link::find()->andWhere(['source' => $this->source])->one();

        $I->sendGet('/link/' . $link->hash);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'success' => true,
        ]);
        $I->canSeeResponseJsonMatchesJsonPath('$.data.link');
    }

}

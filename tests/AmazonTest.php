<?php namespace LemonStand\OAuth2\Client\Test\Provider;

use LemonStand\OAuth2\Client\Provider\Amazon as OauthProvider;

class AmazonTest extends \PHPUnit_Framework_TestCase
{
    protected $config = [
        'clientId'     => 'mock_client_id',
        'clientSecret' => 'mock_secret',
        'redirectUri'  => 'none',
    ];

    protected $sandboxConfig = [
        'clientId'     => 'mock_client_id',
        'clientSecret' => 'mock_secret',
        'redirectUri'  => 'none',
        'testMode'     => true,
    ];

    public function testGetAuthorizationUrl()
    {
        $provider = new OauthProvider($this->config);
        $url = $provider->urlAuthorize();
        $this->assertEquals('https://www.amazon.com/ap/oa', $url);
    }

    public function testGetUrlAccessToken()
    {
        $provider = new OauthProvider($this->config);
        $url = $provider->urlAccessToken();
        $this->assertEquals('https://api.amazon.com/auth/o2/token', $url);
    }

    public function testGetUrlAccessTokenSandbox()
    {
        $provider = new OauthProvider($this->sandboxConfig);
        $url = $provider->urlAccessToken();
        $this->assertEquals('https://api.sandbox.amazon.com/auth/o2/token', $url);
    }

    public function testGetUrlUserDetails()
    {
        $provider = new OauthProvider($this->config);
        $accessTokenDummy = $this->getAccessToken();
        $url = $provider->urlUserDetails($accessTokenDummy);
        $this->assertEquals('https://api.amazon.com/user/profile', $url);
    }

    public function testGetUrlUserDetailsSandbox()
    {
        $provider = new OauthProvider($this->sandboxConfig);
        $accessTokenDummy = $this->getAccessToken();
        $url = $provider->urlUserDetails($accessTokenDummy);
        $this->assertEquals('https://api.sandbox.amazon.com/user/profile', $url);
    }

    private function getAccessToken()
    {
        return $this->getMockBuilder('League\OAuth2\Client\Token\AccessToken')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @dataProvider getUserDetailsDataProvider
     */
    public function testGetUserDetails($responseData, $expectedUserData)
    {
        $response = (object) $responseData;
        $provider = new OauthProvider($this->config);
        $accessTokenDummy = $this->getAccessToken();
        $userDetails = $provider->userDetails($response, $accessTokenDummy);
        $this->assertInstanceOf('League\OAuth2\Client\Entity\User', $userDetails);
        $this->assertObjectHasAttribute('uid', $userDetails);
        $this->assertObjectHasAttribute('name', $userDetails);
        $this->assertObjectHasAttribute('email', $userDetails);
        $this->assertSame($expectedUserData['uid'], $userDetails->uid);
        $this->assertSame($expectedUserData['name'], $userDetails->name);
        $this->assertSame($expectedUserData['email'], $userDetails->email);
    }

    /**
     * @dataProvider getUserDetailsDataProvider
     */
    public function testGetUserEmail($responseData, $expectedUserData)
    {
        $response = (object) $responseData;
        $provider = new OauthProvider($this->config);
        $accessTokenDummy = $this->getAccessToken();
        $email = $provider->userEmail($response, $token);
        $this->assertSame($expectedUserData['email'], $email);
    }

    /**
     * @dataProvider getUserDetailsDataProvider
     */
    public function testGetUserUid($responseData, $expectedUserData)
    {
        $response = (object) $responseData;
        $provider = new OauthProvider($this->config);
        $accessTokenDummy = $this->getAccessToken();
        $uid = $provider->userUid($response, $token);
        $this->assertSame($expectedUserData['uid'], $uid);
    }

    /**
     * @dataProvider getUserDetailsDataProvider
     */
    public function testGetUserScreenName($responseData, $expectedUserData)
    {
        $response = (object) $responseData;
        $provider = new OauthProvider($this->config);
        $accessTokenDummy = $this->getAccessToken();
        $name = $provider->userScreenName($response, $token);
        $this->assertSame($expectedUserData['name'], $name);
    }

    public function getUserDetailsDataProvider()
    {
        return [
            [
                [
                    'user_id'  => 123,
                    'name'     => 'test_man',
                    'email'    => 'test_man@hotmail.com',
                ],
                [
                    'uid'      => 123,
                    'name'     => 'test_man',
                    'email'    => 'test_man@hotmail.com',
                ],
            ],
        ];
    }
}

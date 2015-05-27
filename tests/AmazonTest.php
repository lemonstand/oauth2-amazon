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
}

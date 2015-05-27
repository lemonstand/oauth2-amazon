<?php namespace LemonStand\OAuth2\Client\Provider;

use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;

class Amazon extends AbstractProvider
{
    public $scopeSeparator = ' ';
    public $testMode = false;

    public $scopes = [
        'profile',
        'payments:widget',
        'payments:shipping_address',
    ];

    public $authorizationHeader = 'OAuth';

    public function __construct($options = [])
    {
        parent::__construct($options);

        if (isset($options['testMode'])) {
            $this->testMode = $options['testMode'];
        }
    }

    public function getTestMode()
    {
        return $this->testMode;
    }

    public function setTestMode($value)
    {
        $this->testMode = $value;
    }

    public function urlAuthorize()
    {       
        return 'https://www.amazon.com/ap/oa';
    }

    public function urlAccessToken()
    {
        return ($this->testMode) ? 'https://api.sandbox.amazon.com/auth/o2/token' : 'https://api.amazon.com/auth/o2/token';
    }

    public function urlUserDetails(\League\OAuth2\Client\Token\AccessToken $token)
    {
        return ($this->testMode) ? 'https://api.sandbox.amazon.com/user/profile' : 'https://api.amazon.com/user/profile';
    }

    public function userDetails($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        return parent::userDetails($response, $token);
    }

    public function userUid($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        return parent::userUid($response, $token);
    }

    public function userEmail($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        return parent::userEmail($response, $token);
    }

    public function userScreenName($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        return parent::userScreenName($response, $token);
    }

    public function getAuthorizationUrl($options = [])
    {
        $this->state = isset($options['state']) ? $options['state'] : md5(uniqid(rand(), true));

        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'state' => $this->state,
            'scope' => is_array($this->scopes) ? implode($this->scopeSeparator, $this->scopes) : $this->scopes,
            'response_type' => isset($options['response_type']) ? $options['response_type'] : 'code',
        ];

        return $this->urlAuthorize().'?'.$this->httpBuildQuery($params, '', '&');
    }
}

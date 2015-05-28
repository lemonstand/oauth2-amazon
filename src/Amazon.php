<?php namespace LemonStand\OAuth2\Client\Provider;

use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;

class Amazon extends AbstractProvider
{
    public $scopeSeparator = ' ';
    public $testMode = false;
    public $scopes = ['profile','payments:widget','payments:shipping_address'];

    public function __construct($options = [])
    {
        parent::__construct($options);

        if (isset($options['testMode'])) {
            $this->testMode = $options['testMode'];
        }
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
        $url = ($this->testMode) ? 'https://api.sandbox.amazon.com/user/profile' : 'https://api.amazon.com/user/profile';
        return $url . '?access_token=' . $token;
    }

    public function userDetails($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        $user = new User();

        $user->exchangeArray([
            'uid'   => isset($response->user_id) ? $response->user_id : null,
            'name'  => isset($response->name) ? $response->name : null,
            'email' => isset($response->email) ? $response->email : null
        ]);

        return $user;
    }

    public function userUid($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        return isset($response->user_id) ? $response->user_id : null;
    }

    public function getAuthorizationUrl($options = [])
    {
        $this->state = isset($options['state']) ? $options['state'] : md5(uniqid(rand(), true));

        $params = [
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'state'         => $this->state,
            'scope'         => is_array($this->scopes) ? implode($this->scopeSeparator, $this->scopes) : $this->scopes,
            'response_type' => isset($options['response_type']) ? $options['response_type'] : 'code',
        ];

        if ($this->testMode) {
            $params['sandbox'] = 'true';
        }

        return $this->urlAuthorize().'?'.$this->httpBuildQuery($params, '', '&');
    }
}

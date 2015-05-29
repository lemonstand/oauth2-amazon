# Amazon Provider for OAuth 2.0 Client

[![Build Status](https://img.shields.io/travis/lemonstand/oauth2-amazon.svg)](https://travis-ci.org/lemonstand/oauth2-amazon)
[![License](https://img.shields.io/packagist/l/lemonstand/oauth2-amazon.svg)](https://github.com/lemonstand/oauth2-amazon/blob/master/LICENSE)
[![Total Downloads](https://poser.pugx.org/lemonstand/oauth2-amazon/downloads)](https://packagist.org/packages/lemonstand/oauth2-amazon)

This package provides Amazon OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require lemonstand/oauth2-amazon
```

## Usage

Usage is the same as The League's OAuth client, using `LemonStand\OAuth2\Client\Provider\Amazon` as the provider.

### Authorization Code Flow

```php
$provider = new LemonStand\OAuth2\Client\Provider\Amazon([
    'clientId' => 'YOUR_CLIENT_ID',
    'clientSecret' => 'YOUR_CLIENT_SECRET',
    'redirectUri' => 'http://your-redirect-uri',
]);

$provider->testMode = true; // Allows you to work in Amazon's Sandbox environment.

if (isset($_GET['code']) && $_GET['code']) {
    $token = $this->provider->getAccessToken('authorizaton_code', [
        'code' => $_GET['code']
    ]);

    // Returns an instance of League\OAuth2\Client\User
    $user = $this->provider->getUserDetails($token);
    $uid = $provider->getUserUid($token);
    $email = $provider->getUserEmail($token);
    $screenName = $provider->getUserScreenName($token);
}
```

### Refreshing A Token

```php
$provider = new LemonStand\OAuth2\Client\Provider\Amazon([
    'clientId' => 'YOUR_CLIENT_ID',
    'clientSecret' => 'YOUR_CLIENT_SECRET',
    'redirectUri' => 'http://your-redirect-uri',
]);

$grant = new \League\OAuth2\Client\Grant\RefreshToken();
$token = $provider->getAccessToken($grant, ['refresh_token' => $refreshToken]);
```

<?php

require_once __DIR__.'/inc.php';
require_once __DIR__.'/cfg.php';

$fs = new \FriendlyScore\OAuth2\Client\Provider\WhiteLabel([
		'clientId'                => $cfg['wl']['clientId'],
		'clientSecret'            => $cfg['wl']['clientSecret'],
		'redirectUri'             => $cfg['wl']['redirectUri'],
]);

try {
    $fs->setBaseUrl($cfg['wl']['baseUrl']);
	// Try to get an access token using the client credentials grant.
	$accessToken = $fs->getAccessToken('client_credentials');
	$res = $fs->with($accessToken)->getUsers();

	$user = $fs->byId($res->results[0]->id)->getUser();
	echo "<pre>\n";
    echo "USER:\n";
	print_r($user);
    echo "USER IP DATA:\n";
    print_r($fs->getUserIpData());
	$resp = $fs->setPositive(true);
	$user = $fs->byId($res->results[0]->id)->getUser();
	print_r($user);
	$resp = $fs->setStatus('test', 'testing api calls');
	$user = $fs->byId($res->results[0]->id)->getUser();
	print_r($user);
} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
	// Failed to get the access token
	exit($e->getMessage());
}

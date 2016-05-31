<?php

require_once __DIR__.'/inc.php';
require_once __DIR__.'/cfg.php';

$fs = new \FriendlyScore\OAuth2\Client\Provider\WhiteLabel([
		'clientId'								=> $cfg['wl']['clientId'],
		'clientSecret'						=> $cfg['wl']['clientSecret'],
		'redirectUri'						 => $cfg['wl']['redirectUri'],
]);

try {
	$fs->setBaseUrl($cfg['wl']['baseUrl']);
	// Try to get an access token using the client credentials grant.
	$accessToken = $fs->getAccessToken('client_credentials');
	echo "\ncalculateScore:\n";
	$res = $fs
		->with($accessToken)
		->byPartnerId('test-partner-id')
		->calculateScore($cfg['wl']['scoring']);

	print_r($res);

	echo "\ngetScore:\n";
	$res = $fs
		// we don't need to call with and byPartnerId as those are stored in API client
		->getScore();

	print_r($res);

	echo "Score is not ready? So \"app/console fs:process-api-requests\" command should be executed. It'll call webhook when data is ready, and then rest of code should be executed.\n";
	echo "Hit enter to continue...\n";
	$fp = fopen("php://stdin", "r");
	$in = trim(fgets($fp));
	fclose($fp);

	echo "\ngetScore:\n";
	$res = $fs
		// we don't need to call with and byPartnerId as those are stored in API client
		->getScore();

	print_r($res);

	echo "\ngetUser:\n";
	$user = $fs
		// we don't need to call with and byPartnerId as those are stored in API client
		->getUser();

	echo "USER:\n";
	print_r($user);
	echo "USER IP DATA:\n";
	print_r($fs->getUserIpData());
	$resp = $fs->setPositive(true);
	$user = $fs->byId($user->id)->getUser();
	print_r($user);
	$resp = $fs->setStatus('test', 'testing api calls');
	$user = $fs->getUser();
	print_r($user);
} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
	// Failed to get the access token
	exit($e->getMessage());
} catch (\GuzzleHttp\Exception\ClientException $e) {
	$code = $e->getResponse()->getStatusCode();
	$resp = $e->getResponse()->getBody();
	echo "User not found?\nResponse code from server was: {$code}.\nResponse was: {$resp}.\nClient thrown an exception:\n\n";
	echo $e->getMessage();
}

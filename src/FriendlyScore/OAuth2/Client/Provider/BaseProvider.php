<?php

namespace FriendlyScore\OAuth2\Client\Provider;

use FriendlyScore\OAuth2\Client\User\User;
use FriendlyScore\OAuth2\Client\Exception;
use League\OAuth2\Client\Provider\AbstractProvider;

class BaseProvider extends AbstractProvider {
	use \League\OAuth2\Client\Tool\BearerAuthorizationTrait;

	protected $__access_token
	        , $__id
	        , $__by
	        , $__base_url = 'https://friendlyscore.com/';

	public function setBaseUrl($v) {
		$this->__base_url = $v;
		return $this;
	}

	public function getBaseUrl() {

		if (!$this->__base_url) {
			throw new NoBaseUrlException();
		}

		return $this->__base_url;
	}

	public function getBaseAuthorizationUrl() {
		return $this->getBaseUrl().'oauth/v2/auth';
	}
	public function getBaseAccessTokenUrl(array $params) {
		return $this->getBaseUrl().'oauth/v2/token';
	}
	public function getResourceOwnerDetailsUrl(\League\OAuth2\Client\Token\AccessToken $token) {
		return $this->getBaseUrl().'api/v2/current-user';
	}
	protected function getDefaultScopes() {
		return [];
	}
	protected function checkResponse(\Psr\Http\Message\ResponseInterface $response, $data) {
		// If you'd like to throw exceptions when there is { "error": "..." } response
	}
	protected function createResourceOwner(array $response, \League\OAuth2\Client\Token\AccessToken $token) {
		return new User($response);
	}

	public function getHttpClient() {
		static $client = null;

		if ($client === null) {
			$client = new \GuzzleHttp\Client();
		}

		return $client;
	}

	public function with($token) {
		$this->__access_token = $token;
		return $this;
	}

	public function byId($id) {
		$this->__by = 'id';
		$this->__id = $id;
		return $this;
	}

	public function byPartnerId($id) {
		$this->__by = 'partner-id';
		$this->__id = $id;
		return $this;
	}

	public function send($method, $endpoint, $params) {
		$request = $this->getAuthenticatedRequest(
			$method,
			$this->getBaseUrl().$endpoint,
			$this->__access_token,
			[
				'body' => json_encode($params)
			]
		);

		return json_decode($this->getHttpClient()->send($request)->getBody());
	}

	public function calculateScore($params) {
		$request = $this->getAuthenticatedRequest(
			'POST',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/calculate_score.json',
			$this->__access_token,
			[
				'body' => json_encode($params)
			]
		);

		return json_decode($this->getHttpClient()->send($request)->getBody());
	}

	public function getUser() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/show.json',
			$this->__access_token
		);

		return json_decode($this->getHttpClient()->send($request)->getBody());
	}

	public function getUserIpData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/show/ip-address-data.json',
			$this->__access_token
		);

		return json_decode($this->getHttpClient()->send($request)->getBody());
	}

	public function setPositive($positive) {
		$request = $this->getAuthenticatedRequest(
			'PUT',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/positive/'.($positive ? 1 : 0).'.json',
			$this->__access_token
		);

		return json_decode($this->getHttpClient()->send($request)->getBody());
	}

	public function setStatus($status, $status_description) {
		$request = $this->getAuthenticatedRequest(
			'POST',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/status.json',
			$this->__access_token,
			[
				'body' => json_encode([
					'status'             => $status,
					'status_description' => $status_description
				])
			]
		);

		return json_decode($this->getHttpClient()->send($request)->getBody());
	}

	public function getUsers($page = null, $max_per_page = null) {

		$params = [];

		if ($page > 0) {
			$params['page'] = $page;
		}

		if ($max_per_page > 0) {
			$params['max_results'] = $max_per_page;
		}

		$url = $this->getBaseUrl().'api/v2/users.json';

		if (!empty($params)) {
			$url .= '?'.http_build_query($params);
		}

		$request = $this->getAuthenticatedRequest('GET', $url, $this->__access_token);

		return json_decode($this->getHttpClient()->send($request)->getBody());
	}
}

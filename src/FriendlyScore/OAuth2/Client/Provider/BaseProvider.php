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
	        , $__base_url = 'https://friendlyscore.com/'
            , $_json_decode_assoc = false;

	public function setBaseUrl($v) {
		$this->__base_url = preg_match('#/$#', $v) ? $v : $v.'/';
		return $this;
	}

    public function setJsonDecodeAssoc($bool) {
        $this->_json_decode_assoc = $bool;
        return $this;
    }

    public function getJsonDecodeAssoc($bool) {
        return $this->_json_decode_assoc;
    }

    public function jsonDecode($json) {
        return json_decode($json, $this->_json_decode_assoc);
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
			$client = new \GuzzleHttp\Client(['headers' => [ 
				'Content-Type' => 'application/json',
				'Accept'       => 'application/json',
			]]);
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

	public function send($method, $endpoint, $params = []) {

		if (strtoupper($method) === 'GET') {
			$endpoint .= '?'.http_build_query($params);
			$params    = [];
		} else {
			$params = [ 'body' => json_encode($params) ];
		}

		$request = $this->getAuthenticatedRequest(
			$method,
			$this->getBaseUrl().$endpoint,
			$this->__access_token,
			$params
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}

	public function calculateScore($params) {
		return $this->send(
			'POST', 
			'api/v2/users/'.$this->__by.'/'.$this->__id.'/calculate_score.json',
			$params
		);
	}

	public function getUser() {
		return $this->send(
			'GET',
			'api/v2/users/'.$this->__by.'/'.$this->__id.'/show.json'
		);
	}

	public function getUserIpData() {
		return $this->send(
			'GET',
			'api/v2/users/'.$this->__by.'/'.$this->__id.'/show/ip-address-data.json'
		);
	}

	public function setPositive($positive) {
		return $this->send(
			'PUT',
			'api/v2/users/'.$this->__by.'/'.$this->__id.'/positive/'.($positive ? 1 : 0).'.json'
		);
	}

	public function setStatus($status, $status_description) {
		return $this->send(
			'POST',
			'api/v2/users/'.$this->__by.'/'.$this->__id.'/status.json',
			[
				'status'             => $status,
				'status_description' => $status_description
			]
		);
	}

	public function getUsers($page = null, $max_per_page = null) {
		$params = [];

		if ($page > 0) {
			$params['page'] = $page;
		}

		if ($max_per_page > 0) {
			$params['max_results'] = $max_per_page;
		}

		return $this->send(
			'GET',
			'api/v2/users.json',
			$params
		);
	}
}

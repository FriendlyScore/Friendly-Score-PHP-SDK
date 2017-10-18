<?php

namespace FriendlyScore\OAuth2\Client\Provider;

class WhiteLabel extends GeneralProvider {
	protected $__base_url = null;

	public function getScore($partner_user_id = null) {

		if ($partner_user_id === null && $this->__by === 'partner-id') {
			$partner_user_id = $this->__id;
		}

		return $this->send('GET', 'api/v2/get_score.json', [
			'partner_user_id' => $partner_user_id
		]);
	}

		
	public function getFacebookData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/facebook-data.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}
			
	public function getLinkedinData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/linkedin-data.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}
			
	public function getTwitterData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/twitter-data.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}
			
	public function getGoogleData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/google-data.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}
			
	public function getPaypalData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/paypal-data.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}
			
	public function getInstagramData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/instagram-data.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}
	
}

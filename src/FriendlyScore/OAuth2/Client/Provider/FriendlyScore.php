<?php

namespace FriendlyScore\OAuth2\Client\Provider;

class FriendlyScore extends GeneralProvider {

	public function getUserSocialData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/show/social-network-data.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}

	public function getUserDataPoints() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/show/data-points.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}

	public function getUserHeatMap() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/show/heat-map-coordinates.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}

	public function getMinFraudData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/show/min-fraud-data.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}
}

<?php

namespace FriendlyScore\OAuth2\Client\Provider;

class GeneralProvider extends BaseProvider {

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

	public function getMinFraudData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/show/min-fraud-data.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}

	public function getUserGooglePlacesData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/google/raw-file-data/places.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}

	public function getUserGoogleDobData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/google/raw-file-data/days-of-birth.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}

	public function getUserGoogleSkillsData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/google/raw-file-data/skills.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}

	public function getUserGooglePhonesData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/google/raw-file-data/phones.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}

	public function getUserGoogleWorkPeriodsData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/google/raw-file-data/work-periods.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}

	public function getUserGoogleUniversitiesData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/google/raw-file-data/universities.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}

	public function getUserGoogleEmailsData() {
		$request = $this->getAuthenticatedRequest(
			'GET',
			$this->getBaseUrl().'api/v2/users/'.$this->__by.'/'.$this->__id.'/google/raw-file-data/emails.json',
			$this->__access_token
		);

		return $this->jsonDecode($this->getHttpClient()->send($request)->getBody());
	}

	public function addUserPerformanceData($params) {
		return $this->send(
			'POST',
			'api/v2/users/'.$this->__by.'/'.$this->__id.'/performance-data.json',
			$params
		);
	}
}

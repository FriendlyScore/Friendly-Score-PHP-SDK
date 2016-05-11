<?php

namespace FriendlyScore\OAuth2\Client\User;

class User {
	private $response;
	public function __construct($response) {
		$this->response = $response;
	}

	public function getUsername() {
		return $this->response['username'];
	}
}

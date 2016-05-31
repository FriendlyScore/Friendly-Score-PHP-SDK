<?php

namespace FriendlyScore\OAuth2\Client\Provider;

class WhiteLabel extends BaseProvider {
	protected $__base_url = null;

	public function getScore($partner_user_id = null) {

		if ($partner_user_id === null && $this->__by === 'partner-id') {
			$partner_user_id = $this->__id;
		}

		return $this->send('GET', 'api/v2/get_score.json', [
			'partner_user_id' => $partner_user_id
		]);
	}

}

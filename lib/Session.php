<?php

class Session {

	public function __construct() {		
		try {
			session_start([
        'cookie_lifetime' => 86400
      ]);
			session_id();

		} catch (Throwable $t) {
			$error = new LightphantError($t, $this);
		}
	}

}
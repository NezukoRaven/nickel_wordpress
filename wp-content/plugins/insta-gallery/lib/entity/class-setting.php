<?php
namespace QuadLayers\IGG\Entity;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Setting extends SingleEntity {
	public $insta_flush       = false;
	public $insta_reset       = 8;
	public $spinner_image_url = '';
	public $mail_to_alert     = '';

	public function __construct() {
		$this->mail_to_alert = get_option( 'admin_email' );
	}
}

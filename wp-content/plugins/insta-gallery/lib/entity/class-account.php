<?php
namespace QuadLayers\IGG\Entity;

use QuadLayers\WP_Orm\Entity\CollectionEntity;

class Account extends CollectionEntity {
	public static $primaryKey      		 = 'id'; //phpcs:ignore
	public $id                           = '';
	public $username                     = '';
	public $profile_picture_url          = '';
	public $access_token                 = '';
	public $access_token_type            = '';
	public $access_token_expiration_date = 0;
	public $access_token_renew_attempts  = 0;
}

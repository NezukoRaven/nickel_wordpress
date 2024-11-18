<?php
namespace QuadLayers\IGG\Entity;

use QuadLayers\WP_Orm\Entity\CollectionEntity;

class Feed extends CollectionEntity {
	public static $primaryKey  = 'id'; //phpcs:ignore
	public $id                = 0;
	public $account_id        = '';
	public $source            = 'username';
	public $tag               = 'WordPress';
	public $order_by          = 'top_media';
	public $layout            = 'gallery';
	public $limit             = 12;
	public $columns           = 3;
	public $lazy              = false;
	public $spacing           = 10;
	public $highlight         = array(
		'tag'      => '',
		'id'       => '',
		'position' => '1,3,5',
	);
	public $reel              = array(
		'hide' => false,
	);
	public $copyright         = array(
		'hide'        => false,
		'placeholder' => '',
	);
	public $profile           = array(
		'display'      => false,
		'auto'         => false,
		'username'     => '',
		'nickname'     => '',
		'website'      => '',
		'biography'    => '',
		'link_text'    => 'Follow',
		'website_text' => 'Website',
		'avatar'       => '',
	);
	public $box               = array(
		'display'    => false,
		'padding'    => 1,
		'radius'     => 0,
		'background' => '#fefefe',
		'profile'    => false,
		'desc'       => '',
		'text_color' => '#000000',
	);
	public $mask              = array(
		'display'        => true,
		'background'     => '#000000',
		'likes_count'    => true,
		'comments_count' => true,
	);
	public $card              = array(
		'display'          => false,
		'radius'           => 1,
		'font_size'        => 12,
		'background'       => '#ffffff',
		'background_hover' => '#ffffff',
		'text_color'       => '#000000',
		'padding'          => 5,
		'likes_count'      => true,
		'text_length'      => 10,
		'comments_count'   => true,
		'text_align'       => 'left',
	);
	public $carousel          = array(
		'slidespv'          => 5,
		'centered_slides'   => false,
		'autoplay'          => false,
		'autoplay_interval' => 3000,
		'navarrows'         => true,
		'navarrows_color'   => '',
		'pagination'        => true,
		'pagination_color'  => '',
	);
	public $modal             = array(
		'display'           => true,
		'profile'           => true,
		'media_description' => true,
		'likes_count'       => true,
		'comments_count'    => true,
		'text_align'        => 'left',
		'modal_align'       => 'right',
		'text_length'       => 10000,
		'font_size'         => 12,
	);
	public $button            = array(
		'display'          => true,
		'text'             => 'View on Instagram',
		'text_color'       => '#ffff',
		'background'       => '',
		'background_hover' => '',
	);
	public $button_load       = array(
		'display'          => false,
		'text'             => 'Load more...',
		'text_color'       => '#ffff',
		'background'       => '',
		'background_hover' => '',
	);
}

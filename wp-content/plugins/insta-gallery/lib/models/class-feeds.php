<?php

namespace QuadLayers\IGG\Models;

use QuadLayers\IGG\Entity\Feed;
use QuadLayers\WP_Orm\Builder\CollectionRepositoryBuilder;

/**
 * Models_Feeds Class
 */
class Feeds {

	protected static $instance;
	protected $repository;
	/**
	 * Instagram URL
	 *
	 * @var string
	 */
	public $instagram_url = 'https://www.instagram.com';

	public function __construct() {

		$builder = ( new CollectionRepositoryBuilder() )
		->setTable( 'insta_gallery_feeds' )
		->setEntity( Feed::class )
		->setAutoIncrement( true );

		$this->repository = $builder->getRepository();
	}

	/**
	 * Function to sanitize instagram feed
	 *
	 * @param string $feed Feed string to be sanitized.
	 * @return string
	 */
	protected function sanitize_instagram_feed( $feed ) {

		// Removing @, # and trimming input
		// ---------------------------------------------------------------------

		$feed = sanitize_text_field( $feed );

		$feed = trim( $feed );
		$feed = str_replace( '@', '', $feed );
		$feed = str_replace( '#', '', $feed );
		$feed = str_replace( $this->instagram_url, '', $feed );
		$feed = str_replace( '/explore/tags /', '', $feed );
		$feed = str_replace( '/', '', $feed );

		return $feed;
	}

	/* CRUD */

	/**
	 * Function to get default args
	 *
	 * @return array
	 */
	public function get_args() {
		return ( new Feed() )->getDefaults();
	}

	/**
	 * Function to get feed by id
	 *
	 * @return array
	 */
	public function get( $id ) {
		$entity = $this->repository->find( $id );
		if ( $entity ) {
			return $entity->getProperties();
		}
	}

	/**
	 * Function to create new feed
	 *
	 * @param array $feed_data New feed data.
	 * @return array|false
	 */
	public function create( $feed_data ) {

		if ( isset( $feed_data['id'] ) ) {
			unset( $feed_data['id'] );
		}

		$feed_data['tag'] = $this->sanitize_instagram_feed( $feed_data['tag'] );

		$entity = $this->repository->create( $feed_data );

		if ( ! $entity ) {
			throw new \Exception( esc_html__( 'Error creating feed.', 'insta-gallery' ), 400 );
		}

		return $entity->getProperties();
	}

	/**
	 * Function to edit feed
	 *
	 * @param array $feed New feed data to replace old one.
	 * @return boolean
	 */
	public function update( $id, $data ) {
		$entity = $this->repository->update( $id, $data );
		if ( $entity ) {
			return $entity->getProperties();
		}
	}

	/**
	 * Function to delete a feed
	 *
	 * @param int $id Feed id to be deleted.
	 * @return boolean
	 */
	public function delete( $id = null ) {
		return $this->repository->delete( $id );
	}

	/**
	 * Function to get all feeds
	 *
	 * @return array
	 */
	public function get_all() {
		$entities = $this->repository->findAll();
		if ( ! $entities ) {
			return;
		}
		$accounts = array();
		foreach ( $entities as $entity ) {
			$accounts[] = $entity->getProperties();
		}
		return $accounts;
	}

	/**
	 * Function to delete all feeds
	 *
	 * @return boolean
	 */
	public function delete_all() {
		return $this->repository->deleteAll();
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

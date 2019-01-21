<?php

/**
 * Class Tribe__Tickets__Attendee_Info__View
 */
class Tribe__Promoter__View extends Tribe__Template {

	/**
	 * Tribe__Tickets__Attendee_Info__View constructor.
	 *
	 * @since TBD
	 */
	public function __construct() {
		$this->set_template_origin( Tribe__Main::instance() );
		$this->set_template_folder( 'src/views/promoter' );
		$this->set_template_context_extract( true );
		$this->set_template_folder_lookup( true );
	}

	/**
	 * Add the rewrite rules and tags.
	 *
	 * @since TBD
	 */
	public function add_rewrites() {
		add_rewrite_rule( 'promoter-auth/?$', 'index.php?promoter-auth-check=1', 'top' );
		add_rewrite_tag( '%promoter-auth-check%', '([^&]+)' );
	}

	/**
	 * Display the auth check page when the correct permalink is loaded.
	 *
	 * @since TBD
	 */
	public function display_auth_check_view() {
		global $wp_query;

		$promoter_key = tribe_get_request_var( 'promoter_key' );
		$license_key  = tribe_get_request_var( 'license_key' );
		$user         = $this->maybe_auth_user();
		$authorized   = false;

		if ( $user && ! empty( $_POST['promoter_authenticate'] ) ) {
			/** @var Tribe__Promoter__Auth $promoter_auth */
			$promoter_auth = tribe( 'promoter.auth' );
			$authorized    = $promoter_auth->authorize_with_connector();
		}

		if ( empty( $promoter_key ) || empty( $wp_query->query_vars['promoter-auth-check'] ) ) {
			return;
		}

		$this->template( 'auth', array(
			'authorized'   => $authorized,
			'logged_in'    => is_user_logged_in(),
			'promoter_key' => $promoter_key,
			'license_key'  => $license_key,
		), true );

		tribe_exit();
	}

	/**
	 * If a user with admin capabilities is not logged in, log them in using the provided creds.
	 *
	 * @return bool Whether the user should be authorized and has access.
	 *
	 * @since TBD
	 */
	public function maybe_auth_user() {
		if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
			return true;
		}

		$username = tribe_get_request_var( 'username' );
		$password = tribe_get_request_var( 'password' );
		$user     = wp_authenticate( $username, $password );

		return ! is_wp_error( $user ) && user_can( $user, 'manage_options' );
	}

}

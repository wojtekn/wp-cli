<?php

namespace WP_CLI\Fetchers;

use WP_User;

/**
 * Fetch a WordPress user based on one of its attributes.
 */
class User extends Base {

	/**
	 * The message to display when an item is not found.
	 *
	 * @var string
	 */
	protected $msg = "Invalid user ID, email or login: '%s'";

	/**
	 * Get a user object by one of its identifying attributes.
	 *
	 * @param string $arg The raw CLI argument.
	 * @return WP_User|false The item if found; false otherwise.
	 */
	public function get( $arg ) {

		if ( is_numeric( $arg ) ) {
			$users = get_users( [ 'include' => [ (int) $arg ] ] );
		} elseif ( is_email( $arg ) ) {
			$users = get_users(
				[
					'search'         => $arg,
					'search_columns' => [ 'user_email' ],
				]
			);
			// Logins can be emails.
			if ( ! $users ) {
				$users = get_users( [ 'login' => $arg ] );
			}
		} else {
			$users = get_users( [ 'login' => $arg ] );
		}

		return is_array( $users ) ? $users[0] : false;
	}
}


<?php
/**
 * Plugin Name: Change Recovery Email
 * Plugin URI:
 * Description: A plugin to change Recovery Email
 * Author:      Chris Kelley
 * Author URI:  https://iwritecode.blog
 * Version:     1.0.0
 * Text Domain: re
 * Domain Path: languages
 *
 * Change Recovery Email is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Change Recovery Email is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Change Recovery Email. If not, see <http://www.gnu.org/licenses/>.
*/


final class iwcRecoveryEmail {

	/**
	 * Holds singleton instnace.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance = null;

	/**
	 * Holds Plugin File String.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Admin Page Hook.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $hook;

	/**
	 * Init Method
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_filter( 'recovery_mode_email', [ $this, 'recovery_mode_email' ], 10, 2 );

	}

	/**
	 * Helper Method to filter Recovery Mode Email
	 *
	 * @since 1.0.0
	 *
	 * @param string $email Default Admin email.
	 * @param string $url URL.
	 * @return void
	 */
	public function recovery_mode_email( $email, $url ) {

		$re_optiom = get_option('re_email');

		if ( ! $re_optiom) {
			return $email;
		}

		$email['to'] = $re_optiom;
		return $email;

	}

	/**
	 * Helper Method to add Options Menu and Page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_menu() {

		$this->hook = add_options_page(
			esc_attr__( 'Recovery Email', 're' ),
			esc_attr__( 'Recovery Email', 're' ),
			'manage_options',
			're.php',
			[ $this, 'admin_page' ]
		);

		if ( ! $this->hook ) {
			return;
		}

		add_action( 'load-' . $this->hook, [ $this, 'save_settings' ] );

	}

	/**
	 * Admin Page Markup
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_page() {
		include_once trailingslashit( plugin_dir_path( $this->file) ) . 'settings-view.php';
	}

	/**
	 * Helper Method to save settings.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function save_settings(){

		if ( ! isset( $_POST['iwc-re-submit'] ) ) {
			return;
		}
		if ( isset( $_POST['iwc-re-nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['iwc-re-nonce'] ) ), 'iwc-re-nonce' ) ) {
			return;
		}

		$email = isset( $_POST['recovery_email'] ) ? sanitize_email( $_POST['recovery_email'] ) : get_option('admin_email');

		update_option('re_email', $email );

	}

	/**
	 * Undocumented function
	 *
	 * @since 1.0.0
	 *
	 * @return object|iwcRecoveryEmail
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof iwcRecoveryEmail ) ) {

			self::$instance = new self();
			self::$instance->init();

		}

		return self::$instance;
	}

}

add_action( 'plugins_loaded', function() {
	return iwcRecoveryEmail::get_instance();
} );
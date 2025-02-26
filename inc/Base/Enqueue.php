<?php
declare(strict_types=1);

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Base;

/**
 * loads Enqueue from BaseController to register public-functions
 */
class Enqueue extends BaseController {

	/**
	 * @return void
	 */
	public function register(): void {
		// Admin CSS & JS
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );

		// Frontend CSS & JS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ) );

		// Dashicons in frontend (if needed)
		add_action( 'wp_enqueue_scripts', array( $this, 'load_dashicons_front_end' ) );
	}

	/**
	 * Loads CSS and JS for WordPress admin
	 */
	public function enqueue_admin(): void {
		wp_enqueue_style( 'cbn_admin_style', plugins_url( 'assets/css/style.css', dirname( __DIR__, 1 ) ) );
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script(
			'cbn_script',
			plugins_url( 'src/js/backend-settings.js', dirname( __DIR__, 1 ) ),
			array( 'wp-i18n', 'jquery', 'wp-color-picker' ),
			$this->plugin_version,
			true
		);

		wp_localize_script(
			'cbn_script',
			'cbn_ajax',
			array(
				'cbn_location_nonce' => wp_create_nonce( 'cbn_location' ),
			)
		);

		wp_set_script_translations(
			'cbn_script',
			'compass',
			plugin_dir_path( __FILE__ ) . 'languages'
		);
	}

	/**
	 * Loads CSS and JS for the frontend
	 */
	public function enqueue_frontend(): void {
		wp_enqueue_style( 'cbn_frontend_style', plugins_url( 'assets/css/frontend.css', dirname( __DIR__, 1 ) ) );
		wp_enqueue_script( 'compass-script', plugins_url( 'src/js/script.js', dirname( __DIR__, 1 ) ), array( 'jquery' ), '1.0.0', true );
	}

	/**
	 * Loads Dashicons for the frontend (if necessary)
	 */
	public function load_dashicons_front_end(): void {
		wp_enqueue_style( 'dashicons' );
	}
}

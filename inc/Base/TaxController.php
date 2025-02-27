<?php
declare(strict_types=1);

/**
 * @package CompassPlugin
 */

namespace CompassPlugin\Base;

/**
 *
 */
class TaxController extends BaseController {

	public $settings;

	/**
	 * @return void
	 */
	public function register(): void {

		if ( true ) :

			if ( true ) :

				//Feature: use types
				if ( get_option( 'cbn_enable_marker_types' ) ) {

					// Taxonomy: type
					add_action( 'init', array( $this, 'type_tax' ) );
					add_action( 'cbn-type_add_form_fields', array( $this, 'type_tax_add_custom_fields' ) );
					add_action( 'cbn-type_edit_form_fields', array( $this, 'type_tax_edit_custom_fields' ), 10, 2 );
					add_action( 'edited_cbn-type', array( $this, 'type_tax_save' ) );
					add_action( 'create_cbn-type', array( $this, 'type_tax_save' ) );
				}

			endif;
		endif;

		if ( get_option( 'cbn_enable_regions' ) ) {

			// Taxonomy: region
			add_action( 'init', array( $this, 'region_tax' ) );
			add_action( 'cbn-region_add_form_fields', array( $this, 'region_tax_add_custom_fields' ) );
			add_action( 'cbn-region_edit_form_fields', array( $this, 'region_tax_edit_custom_fields' ), 10, 2 );
			add_action( 'edited_cbn-region', array( $this, 'region_tax_save' ) );
			add_action( 'create_cbn-region', array( $this, 'region_tax_save' ) );
			add_action( 'manage_edit-cbn-region_columns', array( $this, 'set_custom_region_columns' ) );
			add_action( 'manage_cbn-region_custom_column', array( $this, 'set_custom_region_columns_data' ), 10, 3 ); // this method has 3 attributes

		}
	}

	/**
	 * Taxonomy: cbn-type
	 */

	public static function type_tax(): void {
		$labels = array(
			'name'                       => __( 'Marker Categories', 'compass' ),
			'singular_name'              => __( 'Marker Category', 'compass' ),
			'menu_name'                  => __( 'Marker Categories', 'compass' ),
			'all_items'                  => __( 'All Marker Categories', 'compass' ),
			'edit_item'                  => __( 'Edit Marker Category', 'compass' ),
			'view_item'                  => __( 'Show Marker Category', 'compass' ),
			'update_item'                => __( 'Update Marker Category', 'compass' ),
			'add_new_item'               => __( 'Add new Marker Category', 'compass' ),
			'new_item_name'              => __( 'New Type name', 'compass' ),
			'search_items'               => __( 'Search Marker Categories', 'compass' ),
			'choose_from_most_used'      => __( 'Choose from the most used Marker Categories', 'compass' ),
			'popular_items'              => __( 'Popular Marker Categories', 'compass' ),
			'add_or_remove_items'        => __( 'Add or remove Marker Categories', 'compass' ),
			'separate_items_with_commas' => __( 'Separate Marker Categories with commas', 'compass' ),
			'back_to_items'              => __( 'Back to Marker Categories', 'compass' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => false,
			'show_ui'             => true,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => false,
			'show_admin_column'   => true,
			'show_in_quick_edit'  => true,
			'hierarchical'        => false,
			'show_in_rest'        => true,
		);

		register_taxonomy( 'cbn-type', 'cbn-location', $args );
	}

	/**
	 * @return void
	 */
	public function type_tax_add_custom_fields(): void {
		wp_nonce_field( 'cbn_location', 'cbn_location_nonce' );

		// render view
		require_once cbn_get_template( 'page-backend-add-type.php' );

		wp_enqueue_script( 'cbn_backend_type_js', $this->plugin_url . 'src/js/backend-type.js', array( 'wp-polyfill' ), $this->plugin_version , true);
	}

	/**
	 * @return void
	 */
	public function type_tax_edit_custom_fields(): void {
		wp_nonce_field( 'cbn_location', 'cbn_location_nonce' );

		// render view
		require_once cbn_get_template( 'page-backend-edit-type.php' );

		wp_enqueue_script( 'cbn_backend_type_js', $this->plugin_url . 'src/js/backend-type.js', array( 'wp-polyfill' ), $this->plugin_version , true);
	}

	/**
	 * @param $term_id
	 * @return mixed
	 */
	public function type_tax_save( $term_id ): mixed {
		// Dont save without nonce
		if ( ! isset( $_POST['cbn_location_nonce'] ) ) {
			return $term_id;
		}

		// Dont save if nonce is incorrect
		$nonce = $_POST['cbn_location_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'cbn_location' ) ) {
			return $term_id;
		}

		// Dont save if WordPress just auto-saves
		if ( defined( 'DOING AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $term_id;
		}

		// Save Taxonomy Icon
		if ( isset( $_POST['cbn_marker_icon'] ) ) {
			// Validation
			$cbn_marker_icon_validated = sanitize_text_field( $_POST['cbn_marker_icon'] );
			if ( ! $cbn_marker_icon_validated ) {
				$cbn_marker_icon_validated = '';
			}

			if ( $cbn_marker_icon_validated ) {
				update_term_meta( $term_id, 'cbn_marker_icon', $cbn_marker_icon_validated );
			}
		}

		// Save Custom Image Icon
		if ( isset( $_POST['cbn_marker_user_icon'] ) ) {
			// Validation
			$cbn_marker_user_icon_validated = sanitize_text_field( $_POST['cbn_marker_user_icon'] );
			if ( ! $cbn_marker_user_icon_validated ) {
				$cbn_marker_user_icon_validated = '';
			}

			if ( $cbn_marker_user_icon_validated ) {
				update_term_meta( $term_id, 'cbn_marker_user_icon', $cbn_marker_user_icon_validated );
			}
		}
		return $term_id;
	}


	/**
	 * Taxonomy: cbn-region
	 */

	public static function region_tax(): void {
		$labels = array(
			'name'                       => __( 'Regions', 'compass' ),
			'singular_name'              => __( 'Region', 'compass' ),
			'menu_name'                  => __( 'Regions', 'compass' ),
			'all_items'                  => __( 'All Regions', 'compass' ),
			'edit_item'                  => __( 'Edit Region', 'compass' ),
			'view_item'                  => __( 'Show Region', 'compass' ),
			'update_item'                => __( 'Update Region', 'compass' ),
			'add_new_item'               => __( 'Add new Region', 'compass' ),
			'new_item_name'              => __( 'New Type name', 'compass' ),
			'search_items'               => __( 'Search Regions', 'compass' ),
			'choose_from_most_used'      => __( 'Choose from the most used Regions', 'compass' ),
			'popular_items'              => __( 'Popular Regions', 'compass' ),
			'add_or_remove_items'        => __( 'Add or remove Regions', 'compass' ),
			'separate_items_with_commas' => __( 'Separate Regions with commas', 'compass' ),
			'back_to_items'              => __( 'Back to Regions', 'compass' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => false,
			'show_ui'             => true,
			'exclude_from_search' => true,
			'show_in_nav_menus'   => false,
			'show_admin_column'   => false,
			'show_in_quick_edit'  => false,
			'meta_box_cb'         => false,
			'hierarchical'        => false,
			'show_in_rest'        => false,
		);

		register_taxonomy( 'cbn-region', 'cbn-location', $args );
	}

	/**
	 * @return void
	 */
	public function region_tax_add_custom_fields(): void {
		wp_nonce_field( 'cbn_location', 'cbn_location_nonce' );

		// render view
		require_once cbn_get_template( 'page-backend-add-region.php' );
	}

	/**
	 * @return void
	 */
	public function region_tax_edit_custom_fields(): void {
		wp_nonce_field( 'cbn_location', 'cbn_location_nonce' );

		// render view
		require_once cbn_get_template( 'page-backend-edit-region.php' );
	}

	/**
	 * @param $term_id
	 * @return mixed
	 */
	public function region_tax_save( $term_id ): mixed {
		// Dont save without nonce
		if ( ! isset( $_POST['cbn_location_nonce'] ) ) {
			return $term_id;
		}

		// Dont save if nonce is incorrect
		$nonce = $_POST['cbn_location_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'cbn_location' ) ) {
			return $term_id;
		}

		// Dont save if WordPress just auto-saves
		if ( defined( 'DOING AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $term_id;
		}

		if ( isset( $_POST['cbn_lat'] ) ) {
			// Validation
			$cbn_lat_validated = floatval( str_replace( ',', '.', sanitize_text_field( $_POST['cbn_lat'] ) ) );
			if ( ! $cbn_lat_validated ) {
				$cbn_lat_validated = '';
			}

			if ( $cbn_lat_validated ) {
				update_term_meta( $term_id, 'cbn_lat', $cbn_lat_validated );
			}
		}

		if ( isset( $_POST['cbn_lng'] ) ) {
			// Validation
			$cbn_lng_validated = floatval( str_replace( ',', '.', sanitize_text_field( $_POST['cbn_lng'] ) ) );
			if ( ! $cbn_lng_validated ) {
				$cbn_lng_validated = '';
			}

			if ( $cbn_lng_validated ) {
				update_term_meta( $term_id, 'cbn_lng', $cbn_lng_validated );
			}
		}

		if ( isset( $_POST['cbn_zoom'] ) ) {
			// Validation
			$cbn_zoom_validated = floatval( str_replace( ',', '.', sanitize_text_field( $_POST['cbn_zoom'] ) ) );
			if ( ! $cbn_zoom_validated ) {
				$cbn_zoom_validated = '';
			}

			if ( $cbn_zoom_validated ) {
				update_term_meta( $term_id, 'cbn_zoom', $cbn_zoom_validated );
			}
		}
		return $term_id;
	}

	/**
	 * @param $columns
	 * @return mixed
	 */
	public static function set_custom_region_columns( $columns ): mixed {
		// preserve default columns
		$name = $columns['name'];
		unset( $columns['description'], $columns['slug'], $columns['posts'] );

		$columns['name']           = $name;
		$columns['geocoordinates'] = __( 'Coordinates', 'compass' );
		$columns['zoom']           = __( 'Zoom', 'compass' );

		return $columns;
	}

	/**
	 * @param $column
	 * @param $term_id
	 * @return void
	 */
	public static function set_custom_region_columns_data( $column, $term_id ): void {
		$data = get_term_meta( $term_id );

		$lat  = $data['cbn_lat'][0] ?? '';
		$lng  = $data['cbn_lng'][0] ?? '';
		$zoom = $data['cbn_zoom'][0] ?? '';

		switch ( $column ) {
			case 'geocoordinates':
				echo esc_attr( $lat ) . ', ' . esc_attr( $lng );
				break;
			case 'zoom':
				echo esc_attr( $zoom );
				break;
			default:
				break;
		}
	}
}

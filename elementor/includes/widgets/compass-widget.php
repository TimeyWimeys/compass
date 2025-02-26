<?php /** @noinspection ALL */

namespace Elementor_cbn_Addon;

class Elementor_Compass_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'Compass_widget';
	}

	public function get_title() {
		return esc_html__( 'compass', 'compass' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	public function get_categories() {
		return array( 'basic' );
	}

	public function get_keywords() {
		return array( 'map', 'location', 'leaflet', 'marker' );
	}

	public function get_style_depends() {

		wp_register_style( 'cbn_style', plugins_url( '../../../assets/style.css', __FILE__ ) );

		return array(
			'cbn_style',
		);
	}

	protected function register_controls() {

		// Content Tab Start

		$this->start_controls_section(
			'section_info',
			array(
				'label' => esc_html__( 'How to use', 'compass' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'important_note',
			array(
				'label'           => '',
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( 'This block will show all <a href="edit.php?post_type=cbn-location">Locations</a> on a map. By default users will be able to propose new locations by clicking a + Button on the map.', 'compass' ) . '<br><br>' . __( 'Please configure the map styles and features in <a class="link-cbn-settings" href="edit.php?post_type=cbn-location&page=Compass-settings">Compass > Settings</a>.', 'compass' ),
				'content_classes' => 'cbn-elementor-howto-description',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_custom_map_position',
			array(
				'label' => esc_html__( 'Custom Map Position', 'compass' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'custom_map_position_note',
			array(
				'label'           => '',
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( 'Feel free to customize initial map position (Latitude, Longitude, Zoom OR Region).<br><br>This will override the general configuration from the <a href="edit.php?post_type=cbn-location&page=Compass-settings">settings</a>.', 'compass' ),
				'content_classes' => 'cbn-elementor-howto-description',
			)
		);

		$this->add_control(
			'latitude',
			array(
				'label'       => esc_html__( 'Latitude', 'compass' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'e.g. 51.50665732176545',
			)
		);

		$this->add_control(
			'longitude',
			array(
				'label'       => esc_html__( 'Longitude', 'compass' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'e.g. -0.12752251529432854',
			)
		);

		$this->add_control(
			'zoom',
			array(
				'label'       => esc_html__( 'Zoom (3 - 15)', 'compass' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => 'e.g. 13',
				'min'         => 3,
				'max'         => 15,
			)
		);

		$this->add_control(
			'or',
			array(
				'label'           => '',
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<b>OR</b>', 'compass' ),
				'content_classes' => 'cbn-elementor-howto-description',
			)
		);

		$this->add_control(
			'region',
			array(
				'label'       => esc_html__( 'Pre-select Region', 'compass' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'e.g. Europe',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_custom_locations',
			array(
				'label' => esc_html__( 'Filter Locations', 'compass' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'custom_locations_note',
			array(
				'label'           => '',
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( 'Show only specific markers by filtering for categories or Post IDs. You can separate multiple Categories or IDs with a | symbol.', 'compass' ),
				'content_classes' => 'cbn-elementor-howto-description',
			)
		);

		$this->add_control(
			'types',
			array(
				'label'       => esc_html__( 'Marker categories ', 'compass' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'food|drinks', 'compass' ),
			)
		);

		$this->add_control(
			'ids',
			array(
				'label'       => esc_html__( 'Post IDs', 'compass' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( '1|2|3', 'compass' ),
			)
		);

		$this->end_controls_section();

		// Content Tab End

		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => esc_html__( 'Custom Size', 'compass' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'cbn_map_size',
			array(
				'label'   => esc_html__( 'Map Size', 'compass' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					''          => '',
					'default'   => esc_html__( 'Content width', 'compass' ),
					'fullwidth' => esc_html__( 'Full width', 'compass' ),
				),
			)
		);

		$this->add_control(
			'cbn_map_height',
			array(
				'label'       => esc_html__( 'Height', 'compass' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => 'px',
			)
		);

		$this->add_control(
			'cbn_map_height_mobile',
			array(
				'label'       => esc_html__( 'Height (Mobile)', 'compass' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => 'px',
			)
		);

		$this->end_controls_section();

		// Style Tab End
	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		// error_log(print_r($settings, true));

		?>
		
		<?php if ( Plugin::is_elementor_backend() ) : ?>

			<!-- Backend Block -->
			
			<div class="hint" style="height: <?php echo $settings['cbn_map_height']; ?>px">
				<h5><?php echo __( 'compass', 'compass' ); ?></h5>
				<p>
					<?php echo __( 'This block will show your Locations on a map in the front end.', 'compass' ); ?>
				</p>
				<?php if ( $settings['latitude'] != '' || $settings['longitude'] != '' || $settings['zoom'] != '' || $settings['region'] != '' || $settings['types'] != '' || $settings['ids'] != '' ) : ?>
					<div class="cbn-custom-settings">
						<?php if ( $settings['latitude'] != '' || $settings['longitude'] != '' || $settings['zoom'] != '' || $settings['region'] != '' ) : ?>
							<p class="custom-settings-label">
								<strong><?php echo __( 'Custom Map Position (optional):', 'compass' ); ?></strong>
							</p>
						<?php endif; ?>
						<?php if ( $settings['latitude'] != '' || $settings['longitude'] != '' || $settings['zoom'] != '' ) : ?>
							<div class="flex">
								<div>
									<div>
										<label><?php echo __( 'Latitude', 'compass' ); ?></label><br>
										<input type="text" value="<?php echo $settings['latitude']; ?>" disabled>
									</div>
								</div>
								<div>
									<div>
										<label><?php echo __( 'Longitude', 'compass' ); ?></label><br>
										<input type="text" value="<?php echo $settings['longitude']; ?>" disabled>
									</div>
								</div>
								<div>
									<div>
										<label><?php echo __( 'Zoom', 'compass' ); ?></label><br>
										<input type="text" value="<?php echo $settings['zoom']; ?>" disabled>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $settings['region'] != '' ) : ?>
							<div class="flex">
								<div>
									<div>
										<label><?php echo __( 'Pre-select Region', 'compass' ); ?></label><br>
										<input type="text" value="<?php echo $settings['region']; ?>" disabled>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $settings['types'] != '' || $settings['ids'] != '' ) : ?>
							<p class="custom-settings-label">
								<strong><?php echo __( 'Filter Locations (optional):', 'compass' ); ?></strong>
							</p>
							<div class="flex">
								<div>
									<div>
										<label><?php echo __( 'Filter by Marker Categories ', 'compass' ); ?></label><br>
										<input type="text" value="<?php echo $settings['types']; ?>" disabled>
									</div>
								</div>
								<div>
									<div>
										<label><?php echo __( 'Filter by POST IDs', 'compass' ); ?></label><br>
										<input type="text" value="<?php echo $settings['ids']; ?>" disabled>
									</div>
								</div>
							</div>
						<?php endif; ?>

					</div>
				<?php endif; ?>
			</div>

		<?php else : ?>

			<!-- Frontend Block -->

			<?php
			$lat  = $settings['latitude'] ? 'lat="' . $settings['latitude'] . '"' : '';
			$long = $settings['longitude'] ? 'long="' . $settings['longitude'] . '"' : '';
			$zoom = $settings['zoom'] ? 'zoom="' . $settings['zoom'] . '"' : '';

			$region = $settings['region'] ? 'region="' . $settings['region'] . '"' : '';

			$types = $settings['types'] ? 'types="' . $settings['types'] . '"' : '';
			$ids   = $settings['ids'] ? 'ids="' . $settings['ids'] . '"' : '';

			$size = $settings['cbn_map_size'] ? 'size="' . $settings['cbn_map_size'] . '"' : '';

			$height        = $settings['cbn_map_height'] ? 'height="' . $settings['cbn_map_height'] . 'px"' : '';
			$height_mobile = $settings['cbn_map_height_mobile'] ? 'height_mobile="' . $settings['cbn_map_height_mobile'] . 'px"' : '';

			echo do_shortcode( '[Compass ' . $lat . ' ' . $long . ' ' . $zoom . ' ' . $region . ' ' . $types . ' ' . $ids . ' ' . $size . ' ' . $height . ' ' . $height_mobile . ']' );
			?>

		<?php endif; ?>
		
		<?php
	}

	private function start_controls_section( string $string, array $array ) {
	}
}

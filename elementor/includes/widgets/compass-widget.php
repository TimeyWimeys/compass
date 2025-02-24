<?php /** @noinspection ALL */

namespace Elementor_cbn_Addon;

class Elementor_Compass_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'Compass_widget';
    }

    public function get_title()
    {
        return esc_html__('Compass', 'Compass');
    }

    public function get_icon()
    {
        return 'eicon-google-maps';
    }

    public function get_categories()
    {
        return [ 'basic' ];
    }

    public function get_keywords()
    {
        return [ 'map', 'location', 'leaflet', 'marker' ];
    }

    public function get_style_depends()
    {

        wp_register_style('cbn_style', plugins_url('../../../assets/style.css', __FILE__));

        return [
            'cbn_style'
        ];

    }

    protected function register_controls()
    {

        // Content Tab Start

        $this->start_controls_section(
            'section_info',
            [
                'label' => esc_html__('How to use', 'Compass'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'important_note',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('This block will show all <a href="edit.php?post_type=cbn-location">Locations</a> on a map. By default users will be able to propose new locations by clicking a + Button on the map.', 'Compass') . '<br><br>' . __('Please configure the map styles and features in <a class="link-cbn-settings" href="edit.php?post_type=cbn-location&page=Compass-settings">Compass > Settings</a>.', 'Compass'),
                'content_classes' => 'cbn-elementor-howto-description',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_custom_map_position',
            [
                'label' => esc_html__('Custom Map Position', 'Compass'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'custom_map_position_note',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('Feel free to customize initial map position (Latitude, Longitude, Zoom OR Region).<br><br>This will override the general configuration from the <a href="edit.php?post_type=cbn-location&page=Compass-settings">settings</a>.', 'Compass'),
                'content_classes' => 'cbn-elementor-howto-description',
            ]
        );

        $this->add_control(
            'latitude',
            [
                'label' => esc_html__('Latitude', 'Compass'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'e.g. 51.50665732176545',
            ]
        );

        $this->add_control(
            'longitude',
            [
                'label' => esc_html__('Longitude', 'Compass'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'e.g. -0.12752251529432854',
            ]
        );

        $this->add_control(
            'zoom',
            [
                'label' => esc_html__('Zoom (3 - 15)', 'Compass'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'placeholder' => 'e.g. 13',
                'min' => 3,
                'max' => 15
            ]
        );

        $this->add_control(
            'or',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('<b>OR</b>', 'Compass'),
                'content_classes' => 'cbn-elementor-howto-description',
            ]
        );

        $this->add_control(
            'region',
            [
                'label' => esc_html__('Pre-select Region', 'Compass'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'e.g. Europe',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_custom_locations',
            [
                'label' => esc_html__('Filter Locations', 'Compass'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'custom_locations_note',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('Show only specific markers by filtering for categories or Post IDs. You can separate multiple Categories or IDs with a | symbol.', 'Compass'),
                'content_classes' => 'cbn-elementor-howto-description',
            ]
        );

        $this->add_control(
            'types',
            [
                'label' => esc_html__('Marker categories ', 'Compass'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('food|drinks', 'Compass')
            ]
        );

        $this->add_control(
            'ids',
            [
                'label' => esc_html__('Post IDs', 'Compass'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('1|2|3', 'Compass')
            ]
        );

        $this->end_controls_section();

        // Content Tab End


        // Style Tab Start

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Custom Size', 'Compass'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'cbn_map_size',
            [
                'label' => esc_html__('Map Size', 'Compass'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    ''  => '',
                    'default'  => esc_html__('Content width', 'Compass'),
                    'fullwidth' => esc_html__('Full width', 'Compass'),
                ],
            ]
        );

        $this->add_control(
            'cbn_map_height',
            [
                'label' => esc_html__('Height', 'Compass'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'placeholder' => 'px',
            ]
        );

        $this->add_control(
            'cbn_map_height_mobile',
            [
                'label' => esc_html__('Height (Mobile)', 'Compass'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'placeholder' => 'px',
            ]
        );

        $this->end_controls_section();

        // Style Tab End

    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        // error_log(print_r($settings, true));

        ?>
		
		<?php if(Plugin::is_elementor_backend()): ?>

			<!-- Backend Block -->
			
			<div class="hint" style="height: <?php echo $settings['cbn_map_height']; ?>px">
				<h5><?php echo __('Compass', 'Compass'); ?></h5>
				<p>
					<?php echo __('This block will show your Locations on a map in the front end.', 'Compass'); ?>
				</p>
				<?php if($settings['latitude'] != '' || $settings['longitude'] != '' || $settings['zoom'] != '' || $settings['region'] != '' || $settings['types'] != '' || $settings['ids'] != ''): ?>
					<div class="cbn-custom-settings">
						<?php if($settings['latitude'] != '' || $settings['longitude'] != '' || $settings['zoom'] != '' || $settings['region'] != ''): ?>
							<p class="custom-settings-label">
								<strong><?php echo __('Custom Map Position (optional):', 'Compass'); ?></strong>
							</p>
						<?php endif; ?>
						<?php if($settings['latitude'] != '' || $settings['longitude'] != '' || $settings['zoom'] != ''): ?>
							<div class="flex">
								<div>
									<div>
										<label><?php echo __('Latitude', 'Compass'); ?></label><br>
										<input type="text" value="<?php echo $settings['latitude']; ?>" disabled>
									</div>
								</div>
								<div>
									<div>
										<label><?php echo __('Longitude', 'Compass'); ?></label><br>
										<input type="text" value="<?php echo $settings['longitude']; ?>" disabled>
									</div>
								</div>
								<div>
									<div>
										<label><?php echo __('Zoom', 'Compass'); ?></label><br>
										<input type="text" value="<?php echo $settings['zoom']; ?>" disabled>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<?php if($settings['region'] != ''): ?>
							<div class="flex">
								<div>
									<div>
										<label><?php echo __('Pre-select Region', 'Compass'); ?></label><br>
										<input type="text" value="<?php echo $settings['region']; ?>" disabled>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<?php if($settings['types'] != '' || $settings['ids'] != ''): ?>
							<p class="custom-settings-label">
								<strong><?php echo __('Filter Locations (optional):', 'Compass'); ?></strong>
							</p>
							<div class="flex">
								<div>
									<div>
										<label><?php echo __('Filter by Marker Categories ', 'Compass'); ?></label><br>
										<input type="text" value="<?php echo $settings['types']; ?>" disabled>
									</div>
								</div>
								<div>
									<div>
										<label><?php echo __('Filter by POST IDs', 'Compass'); ?></label><br>
										<input type="text" value="<?php echo $settings['ids']; ?>" disabled>
									</div>
								</div>
							</div>
						<?php endif; ?>

					</div>
				<?php endif; ?>
			</div>

		<?php else: ?>

			<!-- Frontend Block -->

			<?php
            $lat = $settings['latitude'] ? 'lat="'.$settings['latitude'].'"' : '';
		    $long = $settings['longitude'] ? 'long="'.$settings['longitude'].'"' : '';
		    $zoom = $settings['zoom'] ? 'zoom="'.$settings['zoom'].'"' : '';

		    $region = $settings['region'] ? 'region="'.$settings['region'].'"' : '';

		    $types = $settings['types'] ? 'types="'.$settings['types'].'"' : '';
		    $ids = $settings['ids'] ? 'ids="'.$settings['ids'].'"' : '';

		    $size = $settings['cbn_map_size'] ? 'size="'.$settings['cbn_map_size'].'"' : '';

		    $height = $settings['cbn_map_height'] ? 'height="'.$settings['cbn_map_height'].'px"' : '';
		    $height_mobile = $settings['cbn_map_height_mobile'] ? 'height_mobile="'.$settings['cbn_map_height_mobile'].'px"' : '';

		    echo do_shortcode('[Compass '. $lat . ' ' . $long . ' ' . $zoom . ' ' . $region . ' '. $types . ' '. $ids . ' '. $size .' '. $height .' '. $height_mobile .']');
		    ?>

		<?php endif; ?>
		
		<?php
    }

    private function start_controls_section(string $string, array $array)
    {
    }

}

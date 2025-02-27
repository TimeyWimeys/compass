<
<div class="wrap">
    <h1>Compass</h1>
    <?php settings_errors('cbn_messages'); ?>
    <form method="post" action="options.php">
        <?php if (get_option('cbn_enable_add_location') !== 'on' && get_option('cbn_enable_add_location') !== '') : ?>
            <?php settings_fields('cbn-settings-group'); ?>
            <?php do_settings_sections('cbn-settings-group'); ?>
            <div class="cbn-wizard">
                <div class="hero">
                    <div class="logo">Compass</div>
                    <div class="overline"><?php echo __('Quick Setup (2/3)', 'compass'); ?></div>
                    <h1><?php echo __('What type of map do you need?', 'compass'); ?></h1>
                    <ul class="steps">
                        <li class="done"></li>
                        <li class="done"></li>
                        <li></li>
                    </ul>
                </div>
                <div class="step-content">
                    <div class="intro">
                        <?php echo __('Use Compass to create either an interactive map that lets visitors add location markers or a custom map featuring your own locations.', 'compass'); ?>
                        <br><br>
                        <?php echo __('Don\'t worry, you can adjust this later in the settings.', 'compass'); ?>
                    </div>
                </div>
                <div class="map-types">
                    <div class="option">
                        <label>
                            <div class="map-type-preview" data-type="interactive"></div>
                            <div class="label-text">
                                <input type='radio' name='cbn_wizard_usecase' value='1' checked>
                                <h2><?php echo __('Interactive Map', 'compass'); ?></h2>
                                <p><?php echo __('Create a community map! Visitors to your page can add new location markers to the map. You will receive a notification to approve each location before it is displayed.', 'compass'); ?></p>
                            </div>
                        </label>
                    </div>
                    <div class="option">
                        <label>
                            <div class="map-type-preview" data-type="simple"></div>
                            <div class="label-text">
                                <input type='radio' name='cbn_wizard_usecase' value='2'>
                                <h2><?php echo __('Simple Map', 'compass'); ?></h2>
                                <p><?php echo __('A customized and clear map showcasing only your own location markers, without the option for other users to add new locations.', 'compass'); ?></p>
                            </div>
                        </label>
                    </div>
                </div>
                <input type="hidden" name="cbn_wizard_usecase_done" value="1">
                <?php submit_button(__('Next', 'compass'), 'primary', 'submit', false); ?>
            </div> <!-- Close cbn-wizard -->
        <?php elseif (get_option('cbn_wizard_usecase_done') && !get_option('cbn_wizard_finish_done')) : ?>
            <?php settings_fields('cbn-settings-group'); ?>
            <?php do_settings_sections('cbn-settings-group'); ?>
            <div class="cbn-wizard">
                <div class="hero">
                    <div class="logo">Compass</div>
                    <div class="overline"><?php echo __('Quick Setup (3/3)', 'compass'); ?></div>
                    <h1>🎉 <?php echo __('Yeah, complete!', 'compass'); ?></h1>
                    <ul class="steps">
                        <li class="done"></li>
                        <li class="done"></li>
                        <li class="done"></li>
                    </ul>
                </div>
                <div class="step-content">
                    <h3><?php echo __('Your next steps:', 'compass'); ?></h3>
                    <?php if (get_option('cbn_wizard_usecase') == '1') : ?>
                        <ol class="next-steps">
                            <li><?php echo __('Use the page editor or Elementor to insert the <b>"Compass"</b> block onto a page.<br>Alternatively, you can use the shortcode <code>[Compass]</code>.', 'compass'); ?></li>
                            <li><?php echo __('Your website visitors will see a <div class="cbn-inline-plus">+</div> button in the upper right corner of the map, which they can use to propose their own location markers.', 'compass'); ?></li>
                            <li><?php echo __('Customize styles, activate features and find help under <i>Compass > Settings</i>', 'compass'); ?></li>
                        </ol>
                    <?php elseif (get_option('cbn_wizard_usecase') == '2') : ?>
                        <ol class="next-steps">
                            <li><?php printf(__('Add your first Location under <a href="%s">Compass > Add Location</a>', 'compass'), 'post-new.php?post_type=cbn-location'); ?></li>
                            <li><?php echo __('Use the page editor or Elementor to insert the <b>"Compass"</b> block onto a page.<br>Alternatively, you can use the shortcode <code>[Compass]</code>.', 'compass'); ?></li>
                            <li><?php echo __('Customize styles, activate features and find help under <i>Compass > Settings</i>', 'compass'); ?></li>
                        </ol>
                        <input type="hidden" name="cbn_wizard_finish_done" value="1">
                        <?php submit_button('Okay, got it', 'primary', 'submit', false); ?>
                    <?php endif; ?>
                </div> <!-- Close step-content -->
            </div> <!-- Close cbn-wizard -->
        <?php else : ?>
            <?php settings_fields('cbn-settings-group'); ?>
            <?php do_settings_sections('cbn-settings-group'); ?>
            <nav class="nav-tab-wrapper">
                <a href="#tab-1" class="nav-tab nav-tab-active"><?php echo __('Map Settings', 'compass'); ?></a>
                <a href="#tab-2" class="nav-tab"><?php echo __('Form Settings', 'compass'); ?></a>
                <a href="#tab-3" class="nav-tab"><?php echo __('Filters & Categories', 'compass'); ?></a>
                <a href="#tab-4" class="nav-tab"><?php echo __('Regions', 'compass'); ?></a>
                <a href="#tab-5" class="nav-tab"><?php echo __('Advanced', 'compass'); ?></a>
                <a href="#tab-6" class="nav-tab"><?php echo __('Import & Export', 'compass'); ?></a>
                <a href="#tab-7" class="nav-tab"><?php echo __('Help & Getting Started', 'compass'); ?></a>
            </nav>
        <?php endif; ?>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <table class="form-table">
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_add_location = get_option('cbn_enable_add_location', 'on');
                        $cbn_plus_button_label = get_option('cbn_plus_button_label');
                        ?>
                        <th scope="row">
                            <?php echo __('Map Type', 'compass'); ?>
                            <br><br>
                            <span class="description"><?php echo __('Tip: Watch <a href="https://www.youtube.com/watch?v=7v605z1FT2c" target="_blank">this video</a> to see a demonstration of the interactive map.', 'compass'); ?></span>
                        </th>
                        <td>
                            <div class="map-types">
                                <div class="option">
                                    <label>
                                        <div class="map-type-preview" data-type="interactive"></div>
                                        <div class="label-text">
                                            <input type='radio' name='cbn_map_type'
                                                   value='1' <?php echo ($cbn_enable_add_location == 'on') ? 'checked' : ''; ?>>
                                            <h2><?php echo __('Interactive Map', 'compass'); ?></h2>
                                            <p><?php echo __('Create a community map! Visitors to your page can add new location markers to the map. You will receive a notification to approve each location before it is displayed.', 'compass'); ?></p>
                                            <div id="plus_button_label">
                                                <strong><?php echo __('Custom "+" Button Label:', 'compass'); ?></strong><br>
                                                <input class="regular-text" type="text" name="cbn_plus_button_label"
                                                       id="cbn_plus_button_label"
                                                       placeholder="<?php echo __('Add location', 'compass'); ?>"
                                                       value="<?php echo esc_textarea($cbn_plus_button_label); ?>">
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="option">
                                    <label>
                                        <div class="map-type-preview" data-type="simple"></div>
                                        <div class="label-text">
                                            <input type='radio' name='cbn_map_type'
                                                   value='2' <?php echo ($cbn_enable_add_location != 'on') ? 'checked' : ''; ?>>
                                            <h2><?php echo __('Simple Map', 'compass'); ?></h2>
                                            <p><?php echo __('A customized and clear map showcasing only your own location markers, without the option for other users to add new locations.', 'compass'); ?></p>
                                            <br>
                                            <p><?php echo __('<a href="edit.php?post_type=cbn-location">Manage all Locations here</a>', 'compass'); ?></p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <input type="checkbox" id="cbn_enable_add_location"
                                   name="cbn_enable_add_location" <?php echo ($cbn_enable_add_location == 'on') ? 'checked' : ''; ?>>
                            <label for="cbn_enable_add_location">
                                <?php echo __('Enable Add Location', 'compass'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('Map Style', 'compass'); ?>
                        </th>
                        <td>
                            <div class="map_styles">
                                <?php
                                $map_style = get_option('cbn_map_style') ? get_option('cbn_map_style') : 'Esri.WorldStreetMap';
                                $items = isset($this->map_styles) && is_array($this->map_styles) ? $this->map_styles : [];
                                foreach ($items as $val => $label) {
                                    $selected = ($map_style == $val) ? 'checked' : '';
                                    echo '<label class="' . $selected . '"><div class="map_style_preview" data-style="' . esc_attr($val) . '"><div>' . esc_textarea($label) . '</div></div><input type="radio" name="cbn_map_style" value="' . esc_attr($val) . '" ' . $selected . '></label>';
                                }
                                $custom_items = isset($this->custom_map_styles) && is_array($this->custom_map_styles) ? $this->custom_map_styles : [];
                                foreach ($custom_items as $val => $label) {
                                    $selected = ($map_style == $val) ? 'checked' : '';
                                    echo '<label class="' . $selected . '"><div class="map_style_preview custom" data-style="' . esc_attr($val) . '"><div>' . esc_textarea($label) . '</div></div><input type="radio" name="cbn_map_style" value="' . esc_attr($val) . '" ' . $selected . '></label>';
                                }
                                $commercial_items = isset($this->commercial_map_styles) && is_array($this->commercial_map_styles) ? $this->commercial_map_styles : [];
                                foreach ($commercial_items as $val => $label) {
                                    $selected = ($map_style == $val) ? 'checked' : '';
                                    echo '<label class="' . $selected . '"><div class="map_style_preview commercial" data-style="' . esc_attr($val) . '"><div>' . esc_textarea($label) . '</div></div><input type="radio" name="cbn_map_style" value="' . esc_attr($val) . '" ' . $selected . '></label>';
                                }
                                ?>
                            </div>
                            <div class="wrap-tile-provider-settings">
                                <?php
                                $cbn_tile_provider_mapbox_key = get_option('cbn_tile_provider_mapbox_key', '');
                                ?>
                                <div class="tile-provider-mapbox">
                                    <strong><?php echo __('MapBox API Key:', 'compass'); ?></strong><br>
                                    <input class="regular-text" type="text" name="cbn_tile_provider_mapbox_key"
                                           id="cbn_tile_provider_mapbox_key"
                                           value="<?php echo esc_attr($cbn_tile_provider_mapbox_key); ?>">
                                    <label for="cbn_tile_provider_mapbox_key">
                                        <?php echo __('Mapbox API Key', 'compass'); ?>
                                    </label>
                                    <br><br>
                                    <span class="description"><?php printf(__('You can get a MapBox API key <a href="%s">here</a>. It is free to use with up to 200,000 map tile requests per month. More details on the MapBox website.', 'compass'), 'https://www.mapbox.com/'); ?></span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('Default Marker Icon', 'compass'); ?>
                        </th>
                        <td>
                            <div class="marker_icons">
                                <?php
                                $marker_icon = get_option('cbn_marker_icon') ?: 'default';
                                $marker_user_icon = get_option('cbn_marker_user_icon');

                                $items = array_merge($this->marker_icons ?? [], $this->pro_marker_icons ?? []);

                                foreach ($items as $val) {
                                    $selected = ($marker_icon == $val) ? 'checked' : '';
                                    $user_icon_style = ($marker_user_icon) ? 'style="background-image: url(' . esc_attr($marker_user_icon) . ')"' : '';

                                    echo '<label class="' . $selected . ' label_marker_user_icon">
                <div class="marker_icon_preview" data-style="' . esc_attr($val) . '" ' . $user_icon_style . '></div>
                <input type="radio" name="cbn_marker_icon" ' . $selected . ' value="' . esc_attr($val) . '">
                <div class="icon_upload">
                    <a href="#" class="cbn_upload_icon_button button button-secondary">' . __('Upload Icon', 'compass') . '</a>
                    <p class="description">PNG, max. 100px</p>
                    <input type="hidden" id="cbn_marker_user_icon" name="cbn_marker_user_icon" value="' . esc_attr($marker_user_icon) . '">
                </div>
              </label>';
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_ui_color = get_option('cbn_ui_color') ? get_option('cbn_ui_color') : ($this->cbn_ui_color_default ?? '');
                        ?>
                        <th scope="row">
                            <?php echo __('UI Elements color', 'compass'); ?>
                        </th>
                        <td>
                            <div id="cbn_ui_color_wrap">
                                <label for="cbn_ui_color"><?php echo __('UI Color', 'compass'); ?></label>
                                <input type="text" id="cbn_ui_color" class="cbn_colorpicker" name="cbn_ui_color"
                                       value="<?php echo esc_attr($cbn_ui_color); ?>">
                            </div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <?php
                        $cbn_ui_color = $this->cbn_ui_color_default ?? '';
                        ?>
                        <th scope="row">
                            <?php echo __('UI Elements color', 'compass'); ?>
                        </th>
                        <td>
                            <div id="cbn_ui_color_wrap">
                                <label for="cbn_ui_color"><?php echo __('Choose UI Color:', 'compass'); ?></label>
                                <input type="text" id="cbn_ui_color" class="cbn_colorpicker" name="cbn_ui_color"
                                       value="<?php echo esc_attr($cbn_ui_color); ?>"
                                       placeholder="<?php echo esc_attr($cbn_ui_color); ?>">
                            </div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('Map size', 'compass'); ?>
                        </th>
                        <td>
                            <label for="cbn_map_size"><?php echo __('Select Map Size:', 'compass'); ?></label>
                            <select name="cbn_map_size" id="cbn_map_size">
                                <?php
                                $map_size = get_option('cbn_map_size') ? get_option('cbn_map_size') : 'default';
                                $cbn_map_height = get_option('cbn_map_height');
                                $items = isset($this->cbn_map_sizes) && is_array($this->cbn_map_sizes) ? $this->cbn_map_sizes : [];

                                foreach ($items as $val => $label) {
                                    $selected = ($map_size == $val) ? 'selected' : '';
                                    echo '<option value="' . esc_attr($val) . '" ' . $selected . '>' . esc_html($label) . '</option>';
                                }
                                ?>
                            </select>

                            <br><br>
                            <strong><?php echo __('Custom Height:', 'compass'); ?></strong><br>
                            <label for="cbn_map_height"><?php echo __('Custom height', 'compass'); ?></label>
                            <input class="regular-text" type="text" name="cbn_map_height" id="cbn_map_height"
                                   placeholder="e.g. 400px" value="<?php echo esc_attr($cbn_map_height); ?>"><br><br>
                            <div class="description"><?php echo __('Don\'t forget to add a unit like <b>px</b>.', 'compass'); ?></div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('Map size (mobile)', 'compass'); ?>
                        </th>
                        <td>
                            <?php
                            $cbn_map_height_mobile = get_option('cbn_map_height_mobile');
                            ?>
                            <strong><?php echo __('Custom Height:', 'compass'); ?></strong><br>
                            <label for="cbn_map_height_mobile"><?php echo __('Custom height for mobile', 'compass'); ?></label>
                            <input class="regular-text" type="text" name="cbn_map_height_mobile"
                                   id="cbn_map_height_mobile" placeholder="e.g. 400px"
                                   value="<?php echo esc_attr($cbn_map_height_mobile); ?>"><br><br>
                            <div class="description"><?php echo __('Don\'t forget to add a unit like <b>px</b>.', 'compass'); ?></div>
                        </td>
                    </tr>
                    <tr class="top">
                        <th scope="row">
                            <label><?php echo __('Initial map view', 'compass'); ?></label><br>
                            <span class="description"><?php echo __('This can be customized in the Block / Shortcode settings.', 'compass'); ?></span><br>
                        </th>
                        <td>
                            <?php
                            $start_lat = get_option('cbn_start_lat');
                            $start_lng = get_option('cbn_start_lng');
                            $start_zoom = get_option('cbn_start_zoom');
                            $cbn_enable_fixed_map_bounds = get_option('cbn_enable_fixed_map_bounds');
                            $cbn_searchaddress_label = get_option('cbn_searchaddress_label') ? get_option('cbn_searchaddress_label') : $this->cbn_searchaddress_label_default;
                            ?>
                            <div class="form-field geo-coordinates-wrap">
                                <div class="map-wrap">
                                    <div id="mapGetInitial"
                                         class="leaflet-map map-style_<?php echo esc_attr($map_style); ?>"></div>
                                </div>
                                <div class="input-wrap">
                                    <div class="latlng-wrap">
                                        <div class="form-field lat-wrap">
                                            <label class="meta-label" for="cbn_start_lat">
                                                <?php echo __('Lat', 'compass'); ?>
                                            </label>
                                            <input type="text" readonly class="widefat" id="cbn_start_lat"
                                                   name="cbn_start_lat"
                                                   value="<?php echo esc_attr($start_lat); ?>"></div>
                                        <div class="form-field lng-wrap">
                                            <label class="meta-label" for="cbn_start_lng">
                                                <?php echo __('Lng', 'compass'); ?>
                                            </label>
                                            <input type="text" readonly class="widefat" id="cbn_start_lng"
                                                   name="cbn_start_lng"
                                                   value="<?php echo esc_attr($start_lng); ?>"></div>
                                        <div class="form-field zoom-wrap">
                                            <label class="meta-label" for="cbn_start_zoom">
                                                <?php echo __('Zoom', 'compass'); ?>
                                            </label>
                                            <input type="text" readonly class="widefat" id="cbn_start_zoom"
                                                   name="cbn_start_zoom"
                                                   value="<?php echo esc_attr($start_zoom) ? esc_attr($start_zoom) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="geo-coordinates-hint">
                                        <strong><?php echo __('How to adjust the initial view:', 'compass'); ?></strong>
                                        <ol>
                                            <li><?php echo __('Use the map to the left to find your area of interest', 'compass'); ?></li>
                                            <li><?php echo __('Zoom and pan the map to set the perfect initial view', 'compass'); ?>
                                                <br><br><strong><?php echo __('Tip:', 'compass'); ?></strong> <?php echo __('Hold down the Shift key + mouse to zoom in on an area.', 'compass'); ?>
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="additional-map-settings">
                                        <input class="cbn-switch" type="checkbox" id="cbn_enable_fixed_map_bounds"
                                               name="cbn_enable_fixed_map_bounds" <?php echo ($cbn_enable_fixed_map_bounds == 'on') ? 'checked' : ''; ?>
                                        <label for="cbn_enable_fixed_map_bounds"><?php echo __('Keep map focus in fixed position', 'compass'); ?></label><br>
                                        <span class="description"><?php echo __('If enabled, the visible map will try to stay in the boundaries. (Initial Map View).', 'compass'); ?><?php echo __('This does not work when using Custom Map Positions (e.g. Regions).', 'compass'); ?></span>
                                    </div>
                                </div>
                                <?php
                                // load map base scripts
                                $this->include_map_scripts();
                                wp_enqueue_script('cbn_backend_settings_js', $this->plugin_url . 'src/js/backend-settings.js', ['cbn_leaflet_providers_js', 'cbn_leaflet_markercluster_js', 'cbn_leaflet_subgroups_js', 'cbn_leaflet_geosearch_js', 'cbn_leaflet_locate_js', 'cbn_leaflet_fullscreen_js', 'cbn_leaflet_search_js', 'cbn_leaflet_gesture_js', 'wp-i18n', 'cbn_global_leaflet_js'], $this->plugin_version);
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_cluster = get_option('cbn_enable_cluster', 'on');
                        ?>
                        <th scope="row"><?php echo __('Pins Clustering (group nearby markers)', 'compass'); ?></th>
                        <td>
                            <input class="cbn-switch" type="checkbox" name="cbn_enable_cluster"
                                   id="cbn_enable_cluster" <?php echo ($cbn_enable_cluster === 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_cluster"> <?php echo __('Enable cluster', 'compass'); ?></label><br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_scrollwheel_zoom_map = get_option('cbn_enable_scrollwheel_zoom_map');
                        ?>
                        <th scope="row"><?php echo __('Scroll Wheel Zoom', 'compass'); ?></th>
                        <td>
                            <input class="cbn-switch" type="checkbox" name="cbn_enable_scrollwheel_zoom_map"
                                   id="cbn_enable_scrollwheel_zoom_map" <?php echo ($cbn_enable_scrollwheel_zoom_map === 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_scrollwheel_zoom_map"></label><br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('Geosearch Provider', 'compass'); ?>
                        </th>
                        <td>
                            <label for="cbn_geosearch_provider"><?php echo __('cbn_geosearch_provider', 'compass'); ?></label><select
                                    name="cbn_geosearch_provider" id="cbn_geosearch_provider">
                                <?php
                                $cbn_geosearch_provider = get_option('cbn_geosearch_provider') ? get_option('cbn_geosearch_provider') : 'osm';
                                $cbn_geosearch_provider_geoapify_key = get_option('cbn_geosearch_provider_geoapify_key', '');
                                $cbn_geosearch_provider_here_key = get_option('cbn_geosearch_provider_here_key', '');
                                $cbn_geosearch_provider_mapbox_key = get_option('cbn_geosearch_provider_mapbox_key', '');

                                // Ensure $this->cbn_geosearch_provider is an array
                                if (!isset($this->cbn_geosearch_provider) || !is_array($this->cbn_geosearch_provider)) {
                                    error_log('Error: $this->cbn_geosearch_provider is not an array in page-backend-settings.php');
                                    $this->cbn_geosearch_provider = [];
                                }

                                $available_geosearch_providers = $this->cbn_geosearch_provider; // No need to merge with itself

                                foreach ($available_geosearch_providers as $val => $label) {
                                    $selected = ($cbn_geosearch_provider == $val) ? 'selected' : '';
                                    echo '<option value="' . esc_attr($val) . '" ' . $selected . '>' . esc_html($label) . '</option>';
                                }
                                ?>
                            </select><br><br>
                            <div class="wrap-geosearch-provider-settings">
                                <div class="geosearch-provider-geoapify">
                                    <strong><?php echo __('Geoapify API Key:', 'compass'); ?></strong><br>
                                    <label for="cbn_geosearch_provider_geoapify_key"></label><input class="regular-text"
                                                                                                    type="text"
                                                                                                    name="cbn_geosearch_provider_geoapify_key"
                                                                                                    id="cbn_geosearch_provider_geoapify_key"
                                                                                                    value="<?php echo esc_attr($cbn_geosearch_provider_geoapify_key); ?>">
                                    <br><br>
                                    <span class="description"><?php printf(__('You can get a Geoapify API key <a href="%s">here</a>. It is free to use with up to 3000 requests per day. Please attribute Geoapify service if you use their free plan.', 'compass'), 'https://www.geoapify.com/get-started-with-maps-api'); ?></span><br>
                                </div>
                                <div class="geosearch-provider-here">
                                    <strong><?php echo __('Here API Key:', 'compass'); ?></strong><br>
                                    <label for="cbn_geosearch_provider_here_key"></label><input class="regular-text"
                                                                                                type="text"
                                                                                                name="cbn_geosearch_provider_here_key"
                                                                                                id="cbn_geosearch_provider_here_key"
                                                                                                value="<?php echo esc_attr($cbn_geosearch_provider_here_key); ?>">
                                    <br><br>
                                    <span class="description"><?php printf(__('You can get a Here API key <a href="%s">here</a>. It is free to use with up to 30,000 requests per month. Please attribute Here service if you use their free plan.', 'compass'), 'https://developer.here.com/'); ?></span><br>
                                </div>
                                <div class="geosearch-provider-mapbox">
                                    <strong><?php echo __('MapBox API Key:', 'compass'); ?></strong><br>
                                    <label for="cbn_geosearch_provider_mapbox_key"></label><input class="regular-text"
                                                                                                  type="text"
                                                                                                  name="cbn_geosearch_provider_mapbox_key"
                                                                                                  id="cbn_geosearch_provider_mapbox_key"
                                                                                                  value="<?php echo esc_attr($cbn_geosearch_provider_mapbox_key); ?>">
                                    <br><br>
                                    <span class="description"><?php printf(__('You can get a MapBox API key <a href="%s">here</a>. It is free to use with up to 100,000 geocoding requests per month. Please attribute MapBox service if you use their free plan.', 'compass'), 'https://account.mapbox.com/signup/'); ?></span><br>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('Geosearch Provider', 'compass'); ?>
                        </th>
                        <td>
                            <select name="cbn_geosearch_provider" id="cbn_geosearch_provider">
                                <?php
                                $available_geosearch_providers = $this->cbn_geosearch_provider;
                                $not_available_geosearch_providers = $this->cbn_geosearch_provider;

                                foreach ($available_geosearch_providers as $val => $label) {
                                    echo '<option value="' . esc_textarea($val) . '" selected>' . esc_textarea($label) . '</option>';
                                }

                                foreach ($not_available_geosearch_providers as $val => $label) {
                                    echo '<option >' . esc_textarea($label) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_fullscreen = get_option('cbn_enable_fullscreen', 'on');
                        ?>
                        <th scope="row"><?php echo __('Full Screen Button', 'compass'); ?></th>
                        <td>
                            <input class="cbn-switch" type="checkbox" name="cbn_enable_fullscreen"
                                   id="cbn_enable_fullscreen" <?php echo ($cbn_enable_fullscreen === 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_fullscreen"></label><br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_currentlocation = get_option('cbn_enable_currentlocation');
                        ?>
                        <th scope="row">
                            <?php echo __('"Show me where I am" Button', 'compass'); ?>

                        </th>
                        <td>
                            <input class="cbn-switch" type="checkbox" name="cbn_enable_currentlocation"
                                   id="cbn_enable_currentlocation" <?php echo ($cbn_enable_currentlocation) ? 'checked' : ''; ?>
                            <label for="cbn_enable_currentlocation"></label><br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('"Show me where I am" Button', 'compass'); ?>
                        </th>
                        <td>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label></label>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="tab-2" class="tab-pane">
                <table class="form-table">
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_form_headline = get_option('cbn_form_headline');
                        ?>
                        <th scope="row"><?php echo __('Headline', 'compass'); ?></th>
                        <td>
                            <label for="cbn_form_headline"></label><input class="regular-text" type="text"
                                                                          name="cbn_form_headline"
                                                                          id="cbn_form_headline"
                                                                          placeholder="<?php echo __('Add a new location', 'compass'); ?>"
                                                                          value="<?php echo esc_textarea($cbn_form_headline); ?>"><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_title = get_option('cbn_enable_title', 'on');
                        $cbn_title_required = get_option('cbn_title_required', 'on');
                        $cbn_title_label = get_option('cbn_title_label');
                        $cbn_title_maxlength = get_option('cbn_title_maxlength');
                        ?>
                        <th scope="row"><?php echo __('"Title" field', 'compass'); ?></th>
                        <td>
                            <div class="cbn_2cols">
                                <div>
                                    <input class="cbn-switch" type="checkbox" name="cbn_enable_title"
                                           id="cbn_enable_title" <?php echo ($cbn_enable_title == 'on') ? 'checked' : ''; ?>
                                    <label for="cbn_enable_title"><?php echo __('Enable', 'compass'); ?></label>
                                </div>
                                <div>
                                    <input class="cbn-switch" type="checkbox" name="cbn_title_required"
                                           id="cbn_title_required" <?php echo ($cbn_title_required) ? 'checked' : ''; ?>
                                    <label for="cbn_title_required"><?php echo __('Required', 'compass'); ?></label>
                                </div>
                                <div>
                                    <input class="small-text cbn_title_maxlength" type="number" min="0"
                                           name="cbn_title_maxlength" id="cbn_title_maxlength"
                                           value="<?php echo isset($cbn_title_maxlength) ? esc_attr($cbn_title_maxlength) : ''; ?>"/>
                                    <label for="cbn_title_maxlength"><?php echo __('Max. length', 'compass'); ?></label>
                                </div>
                            </div>
                            <br>
                            <strong><?php echo __('Custom Label:', 'compass'); ?></strong><br>
                            <label for="cbn_title_label"></label><input class="regular-text" type="text"
                                                                        name="cbn_title_label" id="cbn_title_label"
                                                                        placeholder="<?php echo esc_attr($this->cbn_title_label_default); ?>"
                                                                        value="<?php echo esc_attr($cbn_title_label); ?>">
                            <br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_map_label = get_option('cbn_map_label');
                        ?>
                        <th scope="row"><?php echo __('"Map" field', 'compass'); ?></th>
                        <td>
                            <strong><?php echo __('Custom Label:', 'compass'); ?></strong><br>
                            <label for="cbn_map_label"></label><input class="regular-text" type="text"
                                                                      name="cbn_map_label" id="cbn_map_label"
                                                                      placeholder="<?php echo esc_attr($this->cbn_map_label_default); ?>"
                                                                      value="<?php echo esc_attr($cbn_map_label); ?>">
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('Custom fields', 'compass'); ?>
                        </th>
                        <td>
                            <div class="cbn_custom_fields_wrapper">
                                <?php
                                $cbn_custom_fields = get_option('cbn_custom_fields');
                                ?>
                                <table>
                                    <thead>
                                    <tr>
                                        <th><?php echo __('Label', 'compass'); ?></th>
                                        <th><?php echo __('Required', 'compass'); ?></th>
                                        <th><?php echo __('Private', 'compass'); ?></th>
                                        <th><?php echo __('Max. length', 'compass'); ?></th>
                                        <th><?php echo __('Field type', 'compass'); ?> </th>
                                        <th><?php echo __('Options', 'compass'); ?></th>
                                        <th><?php echo __('Description', 'compass'); ?></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (is_array($cbn_custom_fields)) : ?>
                                        <?php foreach ($cbn_custom_fields as $index => $custom_field) : ?>
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input type="text"
                                                               class="field-type-text field-type-link field-type-email field-type-checkbox field-type-radio field-type-select"
                                                               name="cbn_custom_fields[<?php echo $index; ?>][label]"
                                                               placeholder="<?php echo __('Enter label', 'compass'); ?>"
                                                               value="<?php echo esc_attr($custom_field['label']); ?>"/>
                                                    </label>
                                                </td>
                                                <td>
                                                    <input class="cbn-switch field-type-text field-type-link field-type-email field-type-checkbox field-type-radio field-type-select"
                                                           id="cbn_custom_fields_<?php echo $index; ?>_required"
                                                           type="checkbox"
                                                           name="cbn_custom_fields[<?php echo $index; ?>][required]" <?php echo (isset($custom_field['required'])) ? 'checked' : ''; ?> /><label
                                                            class="field-type-text field-type-link field-type-email field-type-checkbox field-type-radio field-type-select"
                                                            for="cbn_custom_fields_<?php echo $index; ?>_required"></label>
                                                </td>
                                                <td>
                                                    <input class="cbn-switch field-type-text field-type-link field-type-email field-type-checkbox field-type-radio field-type-select"
                                                           id="cbn_custom_fields_<?php echo $index; ?>_private"
                                                           type="checkbox"
                                                           name="cbn_custom_fields[<?php echo $index; ?>][private]" <?php echo (isset($custom_field['private'])) ? 'checked' : ''; ?> /><label
                                                            class="field-type-text field-type-link field-type-email field-type-checkbox field-type-radio field-type-select"
                                                            for="cbn_custom_fields_<?php echo $index; ?>_private"></label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input class="small-text field-type-text field-type-link field-type-email"
                                                               type="number" min="0"
                                                               name="cbn_custom_fields[<?php echo $index; ?>][maxlength]"
                                                               value="<?php echo isset($custom_field['maxlength']) ? esc_attr($custom_field['maxlength']) : ''; ?>"/>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <select class="cbn-custom-field-fieldtype"
                                                                name="cbn_custom_fields[<?php echo $index; ?>][fieldtype]">
                                                            <?php
                                                            $available_field_types = $this->cbn_custom_field_fieldtypes;
                                                            ?>
                                                            <?php
                                                            $available_field_types = array_merge($available_field_types, $this->cbn_custom_field_fieldtypes);
                                                            ?>
                                                            <?php foreach ($available_field_types as $value => $label) : ?>
                                                                <?php $selected = (isset($custom_field['fieldtype']) && $custom_field['fieldtype'] == $value) ? 'selected' : ''; ?>
                                                                <?php echo '<option value="' . esc_textarea($value) . '" ' . $selected . '>' . esc_textarea($label) . '</option>'; ?>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="text"
                                                               class="regular-text field-type-checkbox field-type-radio field-type-select"
                                                               name="cbn_custom_fields[<?php echo $index; ?>][options]"
                                                               placeholder="Red|Blue|Green"
                                                               value="<?php echo isset($custom_field['options']) ? esc_attr($custom_field['options']) : ''; ?>"/>
                                                    </label>
                                                    <label class="field-type-select cbn-custom-field-allow-empty"><input
                                                                class="field-type-select" type="checkbox"
                                                                name="cbn_custom_fields[<?php echo $index; ?>][emptyoption]" <?php echo isset($custom_field['emptyoption']) ? 'checked' : ''; ?> ><?php echo __('add empty option', 'compass'); ?>
                                                    </label>
                                                    <label class="field-type-link cbn-custom-field-use-label-as-text"><input
                                                                class="field-type-link" type="checkbox"
                                                                name="cbn_custom_fields[<?php echo $index; ?>][uselabelastextoption]" <?php echo isset($custom_field['uselabelastextoption']) ? 'checked' : ''; ?> ><?php echo __('use label as text', 'compass'); ?>
                                                    </label>
                                                    <label>
<textarea class="regular-text field-type-html"
          name="cbn_custom_fields[<?php echo $index; ?>][html]"
          placeholder="Enter HTML here"><?php echo isset($custom_field['html']) ? esc_attr($custom_field['html']) : ''; ?></textarea>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="text"
                                                               class="field-type-text field-type-link field-type-email field-type-checkbox field-type-radio field-type-select"
                                                               name="cbn_custom_fields[<?php echo $index; ?>][description]"
                                                               placeholder="<?php echo __('Enter description (optional)', 'compass'); ?>"
                                                               value="<?php echo isset($custom_field['description']) ? esc_textarea($custom_field['description']) : ''; ?>"/>
                                                    </label>
                                                </td>
                                                <td class="actions">
                                                    <a class="up" href="#"><span
                                                                class="dashicons dashicons-arrow-up"></span></a>
                                                    <a class="down" href="#"><span
                                                                class="dashicons dashicons-arrow-down"></span></a>
                                                    <a class="remove_button" href="#"><span
                                                                class="dashicons dashicons-trash"></span></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div>
                                <a href="#" class="cbn_add_button button" title="Add field">Add field</a>
                            </div>
                            <br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_address = get_option('cbn_enable_address', 'on');
                        $cbn_hide_address = get_option('cbn_hide_address');
                        $cbn_enable_gmaps_link = get_option('cbn_enable_gmaps_link', 'on');
                        $cbn_address_label = get_option('cbn_address_label');
                        ?>
                        <th scope="row"><?php echo __('"Subtitle" field', 'compass'); ?></th>
                        <td>
                            <input class="cbn-switch" type="checkbox" name="cbn_enable_address"
                                   id="cbn_enable_address" <?php echo ($cbn_enable_address === 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_address"><?php echo __('Enable', 'compass'); ?></label><br>

                            <input class="cbn-switch" type="checkbox" name="cbn_hide_address"
                                   id="cbn_hide_address" <?php echo ($cbn_hide_address) ? 'checked' : ''; ?>
                            <label for="cbn_hide_address"><?php echo __('Don\'t show inside Location Pop-Up', 'compass'); ?></label><br>

                            <input class="cbn-switch" type="checkbox" name="cbn_enable_gmaps_link"
                                   id="cbn_enable_gmaps_link" <?php echo ($cbn_enable_gmaps_link === 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_gmaps_link"><?php echo __('Link to Google Maps', 'compass'); ?></label><br>

                            <strong><?php echo __('Custom Label:', 'compass'); ?></strong><br>
                            <label for="cbn_address_label"></label><input class="regular-text" type="text"
                                                                          name="cbn_address_label"
                                                                          id="cbn_address_label"
                                                                          placeholder="<?php echo esc_attr($this->cbn_address_label_default); ?>"
                                                                          value="<?php echo esc_attr($cbn_address_label); ?>">
                            <br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_description = get_option('cbn_enable_description', 'on');
                        $cbn_description_required = get_option('cbn_description_required');
                        $cbn_description_label = get_option('cbn_description_label');
                        ?>
                        <th scope="row"><?php echo __('"Description" field', 'compass'); ?></th>
                        <td>
                            <div class="cbn_2cols">
                                <div>
                                    <input class="cbn-switch" type="checkbox" name="cbn_enable_description"
                                           id="cbn_enable_description" <?php echo ($cbn_enable_description === 'on') ? 'checked' : ''; ?>
                                    <label for="cbn_enable_description"><?php echo __('Enable', 'compass'); ?></label>
                                </div>
                                <div>
                                    <input class="cbn-switch" type="checkbox" name="cbn_description_required"
                                           id="cbn_description_required" <?php echo ($cbn_description_required) ? 'checked' : ''; ?>
                                    <label for="cbn_description_required"><?php echo __('Required', 'compass'); ?></label>
                                </div>
                            </div>
                            <br>
                            <strong><?php echo __('Custom Label:', 'compass'); ?></strong><br>
                            <label for="cbn_description_label"></label><input class="regular-text" type="text"
                                                                              name="cbn_description_label"
                                                                              id="cbn_description_label"
                                                                              placeholder="<?php echo esc_attr($this->cbn_description_label_default); ?>"
                                                                              value="<?php echo esc_attr($cbn_description_label); ?>">
                            <br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_image = get_option('cbn_enable_image', 'on');
                        $cbn_image_required = get_option('cbn_image_required');
                        $cbn_enable_audio = get_option('cbn_enable_audio', 'on');
                        $cbn_audio_required = get_option('cbn_audio_required');
                        $cbn_enable_video = get_option('cbn_enable_video', false);
                        $cbn_video_required = get_option('cbn_video_required');
                        $cbn_upload_media_label = get_option('cbn_upload_media_label');
                        ?>
                        <th scope="row"><?php echo __('"Media upload" fields', 'compass'); ?></th>
                        <td>
                            <div class="cbn_2cols">
                                <div>
                                    <input class="cbn-switch" type="checkbox" name="cbn_enable_image"
                                           id="cbn_enable_image" <?php echo ($cbn_enable_image === 'on') ? 'checked' : ''; ?>
                                    <label for="cbn_enable_image"><?php echo __('Image', 'compass'); ?></label>
                                </div>
                                <div>
                                    <input class="cbn-switch" type="checkbox" name="cbn_image_required"
                                           id="cbn_image_required" <?php echo ($cbn_image_required) ? 'checked' : ''; ?>
                                    <label for="cbn_image_required"><?php echo __('Required', 'compass'); ?></label>
                                </div>
                            </div>
                            <br><br>
                            <div class="cbn_2cols">
                                <div>
                                    <input class="cbn-switch" type="checkbox" name="cbn_enable_video"
                                           id="cbn_enable_video" <?php echo ($cbn_enable_video === 'on') ? 'checked' : ''; ?>
                                    <label for="cbn_enable_video"><?php echo __('Video (YouTube, Vimeo)', 'compass'); ?></label>
                                </div>
                                <div>
                                    <input class="cbn-switch" type="checkbox" name="cbn_video_required"
                                           id="cbn_video_required" <?php echo ($cbn_video_required) ? 'checked' : ''; ?>
                                    <label for="cbn_video_required"><?php echo __('Required', 'compass'); ?></label>
                                </div>
                            </div>
                            <br><br>
                            <br><br>
                            <div class="cbn_2cols">
                                <div>
                                    <input class="cbn-switch" type="checkbox" name="cbn_enable_audio"
                                           id="cbn_enable_audio" <?php echo ($cbn_enable_audio === 'on') ? 'checked' : ''; ?>
                                    <label for="cbn_enable_audio"><?php echo __('Audio', 'compass'); ?></label>
                                </div>
                                <div>
                                    <input class="cbn-switch" type="checkbox" name="cbn_audio_required"
                                           id="cbn_audio_required" <?php echo ($cbn_audio_required) ? 'checked' : ''; ?>
                                    <label for="cbn_audio_required"><?php echo __('Required', 'compass'); ?></label>
                                </div>
                            </div>
                            <br><br>
                            <strong><?php echo __('Custom Label:', 'compass'); ?></strong><br>
                            <label for="cbn_upload_media_label"></label><input class="regular-text" type="text"
                                                                               name="cbn_upload_media_label"
                                                                               id="cbn_upload_media_label"
                                                                               placeholder="<?php echo esc_attr($this->cbn_upload_media_label_default); ?>"
                                                                               value="<?php echo esc_attr($cbn_upload_media_label); ?>">
                            <br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_max_image_filesize = get_option('cbn_max_image_filesize') ? get_option('cbn_max_image_filesize') : 10;
                        $cbn_max_audio_filesize = get_option('cbn_max_audio_filesize') ? get_option('cbn_max_audio_filesize') : 10;
                        ?>
                        <th scope="row">
                            <?php echo __('Max upload size', 'compass'); ?>
                        </th>
                        <td>
                            <div class="cbn_2cols">
                                <div>
                                    <strong><?php echo __('Image'); ?>:</strong><br>
                                    <label for="cbn_max_image_filesize"></label><input class="small-text" type="number"
                                                                                       min="1"
                                                                                       name="cbn_max_image_filesize"
                                                                                       id="cbn_max_image_filesize"
                                                                                       value="<?php echo esc_attr($cbn_max_image_filesize); ?>">MB
                                </div>
                                <div>
                                    <strong><?php echo __('Audio'); ?>:</strong><br>
                                    <label for="cbn_max_audio_filesize"></label><input class="small-text" type="number"
                                                                                       min="1"
                                                                                       name="cbn_max_audio_filesize"
                                                                                       id="cbn_max_audio_filesize"
                                                                                       value="<?php echo esc_attr($cbn_max_audio_filesize); ?>">MB
                                </div>
                            </div>
                            <br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('Max upload size', 'compass'); ?>
                        </th>
                        <td>
                            <div class="cbn_2cols">
                                <div>
                                    <strong><?php echo __('Image'); ?>:</strong><br>
                                    <label>
                                        <input class="small-text" type="number" min="1" value="10">
                                    </label>MB
                                </div>
                                <div>
                                    <strong><?php echo __('Audio'); ?>:</strong><br>
                                    <label>
                                        <input class="small-text" type="number" min="1" value="10">
                                    </label>MB
                                </div>
                            </div>
                            <br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_user_notification = get_option('cbn_enable_user_notification');
                        $cbn_user_notification_label = get_option('cbn_user_notification_label');
                        $cbn_user_notification_subject = get_option('cbn_user_notification_subject') ? get_option('cbn_user_notification_subject') : __('Your location has been approved', 'compass');
                        $cbn_user_notification_message = get_option('cbn_user_notification_message') ? get_option('cbn_user_notification_message') : __('Hey %name%! Your location proposal on %website_url% has been published!', 'compass');
                        ?>
                        <th scope="row">
                            <?php echo __('User email notification', 'compass'); ?>
                        </th>
                        <td>
                            <input class="cbn-switch" type="checkbox" id="cbn_enable_user_notification"
                                   name="cbn_enable_user_notification" <?php echo ($cbn_enable_user_notification == 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_user_notification"><?php echo __('Enable'); ?></label><br><br>
                            <strong><?php echo __('Custom Label:', 'compass'); ?></strong><br>
                            <label for="cbn_user_notification_label"></label><input class="regular-text" type="text"
                                                                                    name="cbn_user_notification_label"
                                                                                    id="cbn_user_notification_label"
                                                                                    placeholder="<?php echo esc_attr($this->cbn_user_notification_label_default); ?>"
                                                                                    value="<?php echo esc_textarea($cbn_user_notification_label); ?>">
                            <br><br>
                            <strong><?php echo __('Subject'); ?>:</strong><br>
                            <label for="cbn_user_notification_subject"></label><input class="regular-text" type="text"
                                                                                      name="cbn_user_notification_subject"
                                                                                      id="cbn_user_notification_subject"
                                                                                      value="<?php echo esc_textarea($cbn_user_notification_subject); ?>"><br><br>
                            <strong><?php echo __('Message'); ?>:</strong><br>
                            <label for="cbn_user_notification_message"></label><textarea class="regular-text"
                                                                                         name="cbn_user_notification_message"
                                                                                         id="cbn_user_notification_message"
                                                                                         rows="8"
                                                                                         cols="50"><?php echo esc_textarea($cbn_user_notification_message); ?></textarea><br>
                            <span class="description"><?php echo __('Available tags'); ?>: %name%, %website_url%, %website_name%</span>
                            <br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('User email notification', 'compass'); ?>
                        </th>
                        <td>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label></label><br><br>
                            <strong><?php echo __('Subject'); ?>:</strong><br>
                            <label>
                                <input class="regular-text" type="text"
                                       placeholder="<?php echo __('Your location has been approved', 'compass'); ?>">
                            </label><br><br>
                            <strong><?php echo __('Message'); ?>:</strong><br>
                            <label>
<textarea class="regular-text" rows="8" cols="50"
          placeholder="<?php echo __('Hey %name%! Your location proposal on %website_url% has been published!', 'compass'); ?>"></textarea>
                            </label><br><br>
                            <span class="description"><?php echo __('Available tags'); ?>: %name%, %website_url%, %website_name%</span>
                            <br><br>
                        </td>
                    </tr>
                    <?php
                    $cbn_submit_button_label = get_option('cbn_submit_button_label');
                    ?>
                    <tr style="vertical-align: top;">
                        <th scope="row"><?php echo __('"Submit" Button text', 'compass'); ?></th>
                        <td>
                            <label for="cbn_submit_button_label"></label><input class="regular-text" type="text"
                                                                                name="cbn_submit_button_label"
                                                                                id="cbn_submit_button_label"
                                                                                placeholder="<?php echo __('Submit location for review', 'compass'); ?>"
                                                                                value="<?php echo esc_textarea($cbn_submit_button_label); ?>"><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row"><?php echo __('Action after submit', 'compass'); ?></th>
                        <td>
                            <label for="cbn_action_after_submit"></label><select name="cbn_action_after_submit"
                                                                                 id="cbn_action_after_submit">
                                <?php
                                $cbn_action_after_submit = get_option('cbn_action_after_submit') ? get_option('cbn_action_after_submit') : 'text';
                                $items = [
                                    'text' => __('Display message', 'compass'),
                                    'refresh' => __('Refresh', 'compass'),
                                    'redirect' => __('Redirect', 'compass'),
                                ];

                                foreach ($items as $val => $label) {
                                    $selected = ($cbn_action_after_submit == $val) ? 'selected' : '';
                                    echo '<option value="' . esc_textarea($val) . '" ' . $selected . '>' . esc_textarea($label) . '</option>';
                                }
                                ?>
                            </select>
                            <br><br>
                            <div id="cbn_action_after_submit_text">
                                <?php
                                $cbn_thankyou_headline = get_option('cbn_thankyou_headline');
                                $cbn_thankyou_text = get_option('cbn_thankyou_text');
                                ?>
                                <label for="cbn_thankyou_headline"></label><input class="regular-text" type="text"
                                                                                  name="cbn_thankyou_headline"
                                                                                  id="cbn_thankyou_headline"
                                                                                  placeholder="<?php echo __('Thank you!', 'compass'); ?>"
                                                                                  value="<?php echo esc_textarea($cbn_thankyou_headline); ?>"><br><br>
                                <label for="cbn_thankyou_text"></label><textarea class="regular-text"
                                                                                 name="cbn_thankyou_text"
                                                                                 id="cbn_thankyou_text" rows="4"
                                                                                 cols="50"
                                                                                 placeholder="<?php echo __('We will check your location suggestion and release it as soon as possible.', 'compass'); ?>"><?php echo esc_textarea($cbn_thankyou_text); ?></textarea><br><br>
                            </div>
                            <div id="cbn_action_after_submit_redirect">
                                <?php
                                $cbn_thankyou_redirect = get_option('cbn_thankyou_redirect');
                                ?>
                                <label for="cbn_thankyou_redirect"></label><input class="regular-text" type="text"
                                                                                  name="cbn_thankyou_redirect"
                                                                                  id="cbn_thankyou_redirect"
                                                                                  placeholder="<?php echo 'https://yoursite.here'; ?>"
                                                                                  value="<?php echo esc_textarea($cbn_thankyou_redirect); ?>">
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="tab-3" class="tab-pane">
                <table class="form-table">
                    <tr style="vertical-align: top;">
                        <th scope="row"><?php echo __('Searchbar', 'compass'); ?></th>
                        <td>
                            <?php
                            $cbn_enable_searchbar = get_option('cbn_enable_searchbar', 'on');
                            ?>
                            <input class="cbn-switch" type="checkbox" name="cbn_enable_searchbar"
                                   id="cbn_enable_searchbar" <?php echo ($cbn_enable_searchbar === 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_searchbar"></label><br><br>
                            <div class="wrap-searchbar-settings">
                                <div id="cbn_searchbar_type_options">
                                    <?php
                                    $cbn_searchbar_types = isset($this->cbn_searchbar_types) && is_array($this->cbn_searchbar_types)
                                        ? $this->cbn_searchbar_types
                                        : [
                                            'address' => __('Find a specific address', 'compass'),
                                            'markers' => __('Search for specific markers', 'compass'),
                                            'live_filter' => __('Filter markers live as you type', 'compass'),
                                        ];

                                    $cbn_searchbar_type = get_option('cbn_searchbar_type', 'address');

                                    foreach ($cbn_searchbar_types as $val => $label) :
                                        $checked = ($cbn_searchbar_type === $val) ? 'checked' : '';
                                        ?>
                                        <label>
                                            <input type="radio" name="cbn_searchbar_type"
                                                   value="<?php echo esc_attr($val); ?>" <?php echo $checked; ?>>
                                            <strong><?php echo esc_html($label); ?></strong>
                                            <?php echo ($val === 'live_filter') ? '&nbsp;&nbsp;' : ''; ?>
                                            <br>
                                            <?php if ($val === 'address') : ?>
                                                <small><?php echo __('Find a specific address – type to see matching suggestions below and locate them on the map.', 'compass'); ?></small>
                                            <?php elseif ($val === 'markers') : ?>
                                                <small><?php echo __('Search for specific markers and see suggestions below as you type.', 'compass'); ?></small>
                                            <?php elseif ($val === 'live_filter') : ?>
                                                <small><?php echo __('Filter markers live as you type to instantly refine the map view.', 'compass'); ?></small>
                                            <?php endif; ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('"Search for Address (Geosearch)" Button', 'compass'); ?>
                        </th>
                        <td>
                            <?php
                            $cbn_enable_searchaddress_button = get_option('cbn_enable_searchaddress_button', 'on');
                            $cbn_searchaddress_label = get_option('cbn_searchaddress_label');
                            ?>
                            <input class="cbn-switch" type="checkbox" name="cbn_enable_searchaddress_button"
                                   id="cbn_enable_searchaddress_button" <?php echo ($cbn_enable_searchaddress_button === 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_searchaddress_button"></label><br><br>
                            <strong><?php echo __('Custom Label:', 'compass'); ?></strong><br>
                            <label for="cbn_searchaddress_label"></label><input class="regular-text" type="text"
                                                                                name="cbn_searchaddress_label"
                                                                                id="cbn_searchaddress_label"
                                                                                placeholder="<?php echo esc_attr($this->cbn_searchaddress_label_default); ?>"
                                                                                value="<?php echo esc_attr($cbn_searchaddress_label); ?>">
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('"Search for location markers" Button', 'compass'); ?>
                        </th>
                        <td>
                            <?php
                            $cbn_enable_searchmarkers_button = get_option('cbn_enable_searchmarkers_button', 'on');
                            $cbn_searchmarkers_label = get_option('cbn_searchmarkers_label');
                            $cbn_searchmarkers_zoom = get_option('cbn_searchmarkers_zoom');
                            ?>
                            <input class="cbn-switch" type="checkbox" name="cbn_enable_searchmarkers_button"
                                   id="cbn_enable_searchmarkers_button" <?php echo ($cbn_enable_searchmarkers_button === 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_searchmarkers_button"></label><br><br>
                            <strong><?php echo __('Custom Label:', 'compass'); ?></strong><br>
                            <label for="cbn_searchmarkers_label"></label><input class="regular-text" type="text"
                                                                                name="cbn_searchmarkers_label"
                                                                                id="cbn_searchmarkers_label"
                                                                                placeholder="<?php echo esc_attr($this->cbn_searchmarkers_label_default); ?>"
                                                                                value="<?php echo esc_attr($cbn_searchmarkers_label); ?>"><br><br>
                            <strong><?php echo __('Zoom level:', 'compass'); ?></strong><br>
                            <label for="cbn_searchmarkers_zoom"></label><input class="small-text" type="number" min="1"
                                                                               max="19" name="cbn_searchmarkers_zoom"
                                                                               id="cbn_searchmarkers_zoom"
                                                                               placeholder="<?php echo esc_attr($this->cbn_searchmarkers_zoom_default); ?>"
                                                                               value="<?php echo esc_attr($cbn_searchmarkers_zoom); ?>"><br><br>
                            <span class="description"><?php echo __('Set a value between 1 (far away) and 19 (very close).', 'compass'); ?></span><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_marker_types = get_option('cbn_enable_marker_types');
                        $cbn_enable_empty_marker_type = get_option('cbn_enable_empty_marker_type', true);
                        $cbn_enable_multiple_marker_types = get_option('cbn_enable_multiple_marker_types', false);
                        $cbn_collapse_filter = get_option('cbn_collapse_filter');
                        $cbn_marker_types_label = get_option('cbn_marker_types_label') ? get_option('cbn_marker_types_label') : $this->cbn_marker_types_label_default;
                        ?>
                        <th scope="row">
                            <?php echo __('Marker Categories', 'compass'); ?>
                        </th>
                        <td>
                            <input class="cbn-switch" type="checkbox" name="cbn_enable_marker_types"
                                   id="cbn_enable_marker_types" <?php echo ($cbn_enable_marker_types) ? 'checked' : ''; ?>
                            <label for="cbn_enable_marker_types"><?php echo __('Enable', 'compass'); ?></label><br>
                            <?php if ($cbn_enable_marker_types) : ?>
                            <div class="description"><?php echo __('You can manage Marker Categories <a href="edit-tags.php?taxonomy=cbn-type&post_type=cbn-location">here</a>', 'compass'); ?></div>
                            <br><br>
                            <div class="wrap-marker-categories-settings">
                                <strong><?php echo __('Custom Label:', 'compass'); ?></strong><br>
                                <label for="cbn_marker_types_label"></label><input class="regular-text" type="text"
                                                                                   name="cbn_marker_types_label"
                                                                                   id="cbn_marker_types_label"
                                                                                   placeholder="<?php echo esc_attr($this->cbn_marker_types_label_default); ?>"
                                                                                   value="<?php echo esc_attr($cbn_marker_types_label); ?>">
                                <br><br><br>

                                <input class="cbn-switch" type="checkbox" name="cbn_enable_multiple_marker_types"
                                       id="cbn_enable_multiple_marker_types" <?php echo ($cbn_enable_multiple_marker_types) ? 'checked' : ''; ?>
                                <label for="cbn_enable_multiple_marker_types"><?php echo __('Allow multiple selections', 'compass'); ?></label><br>
                                <div class="description"><?php echo __('<strong>Important:</strong> If enabled all locations will fallback to the <a href="edit.php?post_type=cbn-location&page=Compass-settings">Default Marker Icon</a> instead of a specific category icon.', 'compass'); ?></div>
                                <br><br><br>

                                <input class="cbn-switch" type="checkbox" name="cbn_enable_empty_marker_type"
                                       id="cbn_enable_empty_marker_type" <?php echo ($cbn_enable_empty_marker_type) ? 'checked' : ''; ?>
                                <label for="cbn_enable_empty_marker_type"><?php echo __('Allow empty selection', 'compass'); ?></label>
                                <br><br><br>

                                <input class="cbn-switch" type="checkbox" name="cbn_collapse_filter"
                                       id="cbn_collapse_filter" <?php echo ($cbn_collapse_filter) ? 'checked' : ''; ?>
                                <label for="cbn_collapse_filter"><?php echo __('Collapsed Filterbox', 'compass'); ?></label><br>
                                <div class="description"><?php echo __('If enabled the filterbox will take less space and just open on mouseover.', 'compass'); ?></div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('"Marker Categories" field', 'compass'); ?>
                        </th>
                        <td>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label><?php echo __('Enable', 'compass'); ?></label>
                            <br>
                            <br>
                            <strong><?php echo __('Custom Label:', 'compass'); ?></strong><br>
                            <label>
                                <input class="regular-text" type="text" value=""
                                       placeholder="<?php echo esc_attr($this->cbn_marker_types_label_default); ?>">
                            </label>
                            <br><br>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label><?php echo __('Allow multiple selections', 'compass'); ?></label><br>
                            <div class="description"><?php echo __('<strong>Important:</strong> If enabled all locations will fallback to the <a href="edit.php?post_type=cbn-location&page=Compass-settings">Default Marker Icon</a> instead of a specific category icon.', 'compass'); ?></div>
                            <br>
                            <br>
                            <br>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label><?php echo __('Allow empty selection', 'compass'); ?></label>
                            <br>
                            <br>
                            <br>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label for="cbn_collapse_filter"><?php echo __('Collapsed Filterbox', 'compass'); ?></label><br>
                            <div class="description"><?php echo __('If enabled the filterbox will take less space and just open on mouseover.', 'compass'); ?></div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('Filterbox', 'compass'); ?>
                        </th>
                        <td>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label><?php echo __('Collapsed design', 'compass'); ?></label>
                            <br>
                            <br>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="tab-4" class="tab-pane">
                <table class="form-table">

                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_regions = get_option('cbn_enable_regions');
                        ?>
                        <th scope="row">
                            <?php echo __('Enable', 'compass'); ?>
                        </th>
                        <td>
                            <input class="cbn-switch" type="checkbox" name="cbn_enable_regions"
                                   id="cbn_enable_regions" <?php echo ($cbn_enable_regions) ? 'checked' : ''; ?>
                            <label for="cbn_enable_regions"></label><br><br>

                            <?php if ($cbn_enable_regions) : ?>
                                <div class="description"><?php echo __('You can manage Regions <a href="edit-tags.php?taxonomy=cbn-region&post_type=cbn-location">here</a>', 'compass'); ?></div>
                                <br>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_regions_layout_style = get_option('cbn_regions_layout_style', 'layout-1');
                        $items = $this->cbn_regions_layout_styles;
                        ?>
                        <th scope="row">
                            <?php echo __('Layout', 'compass'); ?>
                        </th>
                        <td>
                            <?php
                            echo "<select id='cbn_regions_layout_style' name='cbn_regions_layout_style'>";
                            foreach ($items as $value => $label) {
                                $selected = ($cbn_regions_layout_style == $value) ? 'selected="selected"' : '';
                                echo '<option value="' . esc_textarea($value) . '" ' . $selected . '>' . esc_textarea($label) . '</option>';
                            }
                            echo '</select>';
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="tab-5" class="tab-pane">
                <table class="form-table">
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_location_date = get_option('cbn_enable_location_date');
                        ?>
                        <th scope="row">
                            <?php echo __('Show location date', 'compass'); ?>
                        </th>
                        <td>
                            <input class="cbn-switch" type="checkbox" id="cbn_enable_location_date"
                                   name="cbn_enable_location_date" <?php echo ($cbn_enable_location_date == 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_location_date"></label><br><br>
                            <span class="description"><?php echo __('Displays the date when the location was modified or published inside the location bubble.', 'compass'); ?></span><br>
                            <br>
                            <?php
                            $cbn_location_date_type = get_option('cbn_location_date_type', 'modified');
                            $items = [
                                'modified' => __('Date of Last Modification', 'compass'),
                                'created' => __('Publishing Date', 'compass'),
                            ];
                            echo "<select id='cbn_location_date_type' name='cbn_location_date_type'>";
                            foreach ($items as $value => $label) {
                                $selected = ($cbn_location_date_type == $value) ? 'selected="selected"' : '';
                                echo '<option value="' . esc_textarea($value) . '" ' . $selected . '>' . esc_textarea($label) . '</option>';
                            }
                            echo '</select>';

                            ?>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_single_page = get_option('cbn_enable_single_page');
                        ?>
                        <th scope="row">
                            <?php echo __('Public pages for locations (Single pages)', 'compass'); ?>

                        </th>
                        <td>
                            <input class="cbn-switch" type="checkbox" id="cbn_enable_single_page"
                                   name="cbn_enable_single_page" <?php echo ($cbn_enable_single_page == 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_single_page"></label><br><br>
                            <span class="description"><?php echo __('This will add a "Read more"-Button to the location bubble. It will link to the location\'s single page.', 'compass'); ?></span><br>
                            <span class="description"><?php echo __('In the backend on the "Edit location" page an additional content editor will become available. You can use shortcodes to display individual values of a location. <strong>See the Help section for details.</strong>', 'compass'); ?></span><br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('Public pages for locations (Single pages)', 'compass'); ?>
                        </th>
                        <td>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label></label><br><br>
                            <span class="description"><?php echo __('This will add a "Read more"-Button to the location bubble. It will link to the location\'s single page.', 'compass'); ?></span><br>
                            <span class="description"><?php echo __('In the backend on the "Edit location" page an additional content editor will become available. You can use shortcodes to display individual values of a location. <strong>See the Help section for details.</strong>', 'compass'); ?></span><br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_user_restriction = get_option('cbn_enable_user_restriction');
                        $cbn_enable_redirect_to_registration = get_option('cbn_enable_redirect_to_registration');
                        ?>
                        <th scope="row">
                            <?php echo __('Restrict "Add location" to logged in users only', 'compass'); ?>

                        </th>
                        <td>
                            <input class="cbn-switch" type="checkbox" id="cbn_enable_user_restriction"
                                   name="cbn_enable_user_restriction" <?php echo ($cbn_enable_user_restriction == 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_user_restriction"></label><br><br>
                            <span class="description"><?php echo __('If enabled, only registered users can add new locations. The minimum required role is "Subscriber". <a target="_blank" href="https://www.Compass.com/knowledge-base/redirect-users-to-the-map-after-login/?ref=settings">Here</a> is an article on how to redirect users to the map page after login.', 'compass'); ?></span><br><br>
                            <div id="redirect_to_registration">
                                <input class="cbn-switch" type="checkbox" id="cbn_enable_redirect_to_registration"
                                       name="cbn_enable_redirect_to_registration" <?php echo ($cbn_enable_redirect_to_registration == 'on') ? 'checked' : ''; ?>
                                <label for="cbn_enable_redirect_to_registration"><?php echo __('Redirect "Add location"-Button to registration page'); ?></label><br><br>
                            </div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('Restrict "Add location" to logged in users only', 'compass'); ?>
                        </th>
                        <td>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label></label><br><br>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label><?php echo __('Redirect "Add location"-Button to registration page'); ?></label><br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_auto_publish = get_option('cbn_enable_auto_publish');
                        ?>
                        <th scope="row">
                            <?php echo __('Auto-Publish for registered users', 'compass'); ?>
                        </th>
                        <td>
                            <input class="cbn-switch" type="checkbox" id="cbn_enable_auto_publish"
                                   name="cbn_enable_auto_publish" <?php echo ($cbn_enable_auto_publish == 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_auto_publish"></label><br><br>
                            <span class="description"><?php echo __('This works only for users with "edit posts" capabilities.', 'compass'); ?></span><br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('Auto-Publish for registered users', 'compass'); ?>
                        </th>
                        <td>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label></label><br><br>
                            <span class="description"><?php echo __('This works only for users with "edit posts" capabilities.', 'compass'); ?></span><br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_auto_publish_for_everyone = get_option('cbn_enable_auto_publish_for_everyone');
                        ?>
                        <th scope="row">
                            <?php echo __('Auto-Publish for unregistered users', 'compass'); ?>

                        </th>
                        <td>
                            <input class="cbn-switch" type="checkbox" id="cbn_enable_auto_publish_for_everyone"
                                   name="cbn_enable_auto_publish_for_everyone" <?php echo ($cbn_enable_auto_publish_for_everyone == 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_auto_publish_for_everyone"></label><br><br>
                            <span class="description"><strong><?php echo __('USE WITH CAUTION!', 'compass'); ?></strong> <?php echo __('Every location proposal will be published directly without your verification. No user registration is necessary.', 'compass'); ?></span><br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('Auto-Publish for unregistered users', 'compass'); ?>
                        </th>
                        <td>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label></label><br><br>
                            <span class="description"><strong><?php echo __('USE WITH CAUTION!', 'compass'); ?></strong> <?php echo __('Every location proposal will be published directly without your verification. No user registration is necessary.', 'compass'); ?></span><br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_add_user_location = get_option('cbn_enable_add_user_location');
                        ?>
                        <th scope="row">
                            <?php echo __('Extend WordPress user registration form with "Add location" map', 'compass'); ?>
                        </th>
                        <td>
                            <input class="cbn-switch" type="checkbox" id="cbn_enable_add_user_location"
                                   name="cbn_enable_add_user_location" <?php echo ($cbn_enable_add_user_location == 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_add_user_location"></label><br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('Extend WordPress user registration form with "Add location" map', 'compass'); ?>
                        </th>
                        <td>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label></label>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_admin_notification = get_option('cbn_enable_admin_notification');
                        $cbn_admin_notification_email = get_option('cbn_admin_notification_email') ? get_option('cbn_admin_notification_email') : get_option('admin_email');
                        $cbn_admin_notification_subject = get_option('cbn_admin_notification_subject') ? get_option('cbn_admin_notification_subject') : __('New Compass location', 'compass');
                        $cbn_admin_notification_message = get_option('cbn_admin_notification_message') ? get_option('cbn_admin_notification_message') : __('A new location with the title "%title%" on %website_url% has been added! Please verify and publish or use the "auto-publish" feature.', 'compass');
                        ?>
                        <th scope="row">
                            <?php echo __('Admin email notification on new location proposals', 'compass'); ?>
                        </th>
                        <td>
                            <input class="cbn-switch" type="checkbox" id="cbn_enable_admin_notification"
                                   name="cbn_enable_admin_notification" <?php echo ($cbn_enable_admin_notification == 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_admin_notification"></label><br><br>
                            <strong><?php echo __('Email address'); ?>:</strong><br>
                            <label for="cbn_admin_notification_email"></label><input class="regular-text" type="text"
                                                                                     name="cbn_admin_notification_email"
                                                                                     id="cbn_admin_notification_email"
                                                                                     value="<?php echo esc_textarea($cbn_admin_notification_email); ?>"><br><br>
                            <strong><?php echo __('Subject'); ?>:</strong><br>
                            <label for="cbn_admin_notification_subject"></label><input class="regular-text" type="text"
                                                                                       name="cbn_admin_notification_subject"
                                                                                       id="cbn_admin_notification_subject"
                                                                                       value="<?php echo esc_textarea($cbn_admin_notification_subject); ?>"><br><br>
                            <strong><?php echo __('Message'); ?>:</strong><br>
                            <label for="cbn_admin_notification_message"></label><textarea class="regular-text"
                                                                                          name="cbn_admin_notification_message"
                                                                                          id="cbn_admin_notification_message"
                                                                                          rows="8"
                                                                                          cols="50"><?php echo esc_textarea($cbn_admin_notification_message); ?></textarea><br>
                            <span class="description"><?php echo __('Available tags'); ?>: %title%, %website_url%, %website_name%, %edit_location_url%, %user_name%, %user_email%</span>
                            <br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('Admin email notification on new location proposals', 'compass'); ?>
                        </th>
                        <td>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label></label><br><br>

                            <strong><?php echo __('Email address'); ?>:</strong><br>
                            <label>
                                <input class="regular-text" type="text"
                                       placeholder="<?php echo __('john@doe.com', 'compass'); ?>">
                            </label><br><br>

                            <strong><?php echo __('Subject'); ?>:</strong><br>
                            <label>
                                <input class="regular-text" type="text"
                                       placeholder="<?php echo __('New Compass location', 'compass'); ?>">
                            </label><br><br>

                            <strong><?php echo __('Message'); ?>:</strong><br>
                            <label>
<textarea class="regular-text" rows="8" cols="50"
          placeholder="<?php echo __('A new location with the title "%title%" on %website_url% has been added! Please verify and publish or use the "auto-publish" feature. \n\n %edit_location_url%', 'compass'); ?>"></textarea>
                            </label><br><br>
                            <span class="description"><?php echo __('Available tags'); ?>: %title%, %website_url%, %website_name%, %edit_location_url%, %user_name%, %user_email%</span>
                            <br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_enable_webhook_notification = get_option('cbn_enable_webhook_notification');
                        $cbn_webhook_notification_url = get_option('cbn_webhook_notification_url');
                        ?>
                        <th scope="row">
                            <?php echo __('Trigger Webhook on new or updated Locations', 'compass'); ?>
                        </th>
                        <td>
                            <input class="cbn-switch" type="checkbox" id="cbn_enable_webhook_notification"
                                   name="cbn_enable_webhook_notification" <?php echo ($cbn_enable_webhook_notification == 'on') ? 'checked' : ''; ?>
                            <label for="cbn_enable_webhook_notification"></label><br><br>

                            <strong><?php echo __('Webhook URL'); ?>:</strong><br>
                            <label for="cbn_webhook_notification_url"></label><input class="regular-text" type="text"
                                                                                     name="cbn_webhook_notification_url"
                                                                                     id="cbn_webhook_notification_url"
                                                                                     value="<?php echo esc_url($cbn_webhook_notification_url); ?>">
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('Trigger Webhook on new or updated Locations', 'compass'); ?>
                        </th>
                        <td>
                            <label>
                                <input class="cbn-switch" type="checkbox">
                            </label>
                            <label></label><br><br>

                            <strong><?php echo __('Webhook URL'); ?>:</strong><br>
                            <label>
                                <input class="regular-text" type="text">
                            </label></td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <?php
                        $cbn_custom_js = get_option('cbn_custom_js');
                        ?>
                        <th scope="row">
                            <?php echo __('Custom JS', 'compass'); ?>
                        </th>
                        <td>
                            <strong><?php echo __('This JS code will be executed after the map has been loaded:'); ?></strong><br>
                            <label for="cbn_custom_js"></label><textarea class="regular-text" name="cbn_custom_js"
                                                                         id="cbn_custom_js" rows="8" cols="50"
                                                                         placeholder="<?php echo __("e.g. console.log('The map is ready')", 'compass'); ?>"><?php echo $cbn_custom_js; ?></textarea><br><br>
                            <span class="description"></span>
                            <br><br>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="tab-6" class="tab-pane">
                <table class="form-table">
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('Export all Locations', 'compass'); ?>
                        </th>
                        <td>
                            <button class="cbn_export_csv_button button button-secondary"><?php echo __('Export to CSV', 'compass'); ?></button>
                            <br><br>
                            <div class="description">
                                <strong>This is how the export works:</strong><br>
                                <ul>
                                    <li>Only published locations will be exported</li>
                                    <li>The CSV uses Comma as delimiter</li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('Export all Locations', 'compass'); ?>
                        </th>
                        <td>
                            <button
                                    class="button button-secondary"><?php echo __('Export to CSV', 'compass'); ?></button>
                            <br><br>
                            <div class="description">
                                <strong>This is how the export works:</strong><br>
                                <ul>
                                    <li>Only published locations will be exported</li>
                                    <li>The CSV uses Comma as delimiter</li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('Import all Locations', 'compass'); ?>
                        </th>
                        <td>
                            <div class="csv_upload">
                                <button class="cbn_upload_csv_button button button-secondary"><?php echo __('Upload CSV & Import', 'compass'); ?></button>
                                <br><br>
                                <div class="description">
                                    <strong>This is important to make the import work:</strong><br>
                                    <ul>
                                        <li>Be patient, this can take a while.</li>
                                        <li>Be aware that every location with matching POST ID will be overwritten.
                                            <span
                                                    style="color: red">Consider creating a DB Backup before!</span></li>
                                        <li>To import new locations leave values in the post_id column empty</li>
                                        <li>Download an Export file first and use it as template for your import</li>
                                        <li>Comma or Semicolon work as delimiter</li>
                                        <li>Non-existing Marker Categories will be created automatically</li>
                                        <li>Multiselect values need to be written like so: Red|Green|Blue</li>
                                        <li>All imported locations will have status "Draft". You need to publish them
                                            yourself.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;" class="cbn-gopro-tr">
                        <th scope="row">
                            <?php echo __('Import all Locations', 'compass'); ?>

                            <a class="cbn-gopro-text"
                        </th>
                        <td>
                            <div class="csv_upload">
                                <button
                                        class="button button-secondary"><?php echo __('Upload CSV & Import', 'compass'); ?></button>
                                <br><br>
                                <div class="description">
                                    <strong>This is important to make the import work:</strong><br>
                                    <ul>
                                        <li>Be patient, this can take a while.</li>
                                        <li>Be aware that every location with matching POST ID will be overwritten.
                                            <span
                                                    style="color: red">Consider creating a DB Backup before!</span></li>
                                        <li>To import new locations leave values in the post_id column empty</li>
                                        <li>Download an Export file first and use it as template for your import</li>
                                        <li>Comma or Semicolon work as delimiter</li>
                                        <li>Non-existing Marker Categories will be created automatically</li>
                                        <li>Multiselect values need to be written like so: Red|Green|Blue</li>
                                        <li>All imported locations will have status "Draft". You need to publish them
                                            yourself.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="tab-7" class="tab-pane">
                <table class="form-table">
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('🚀 Getting started', 'compass'); ?>
                        </th>
                        <td class="top-padding-20">
                            <?php printf(__('<ol><li>Use the page editor or Elementor to insert the <b>"Compass"</b> block onto a page. Alternatively, you can use the shortcode <code>[Compass]</code></li><li>You can <a href="%1$s">manage Locations</a> under <i>Compass > All Locations</i></li><li><a href="%2$s">Customize</a> styles and features under <i>Compass > Settings</i></li></ol>', 'compass'), 'edit.php?post_type=cbn-location', 'edit.php?post_type=cbn-location&page=Compass-settings'); ?>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('Gutenberg Block', 'compass'); ?>
                        </th>
                        <td class="top-padding-20">
                            <?php echo __('Use the "Compass" block to integrate the map inside your page. <br>You can set custom map position and filter for categories and locations inside the block settings.', 'compass'); ?>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('Elementor Widget', 'compass'); ?>
                        </th>
                        <td class="top-padding-20">
                            <?php echo __('Use the Elementor Widget "Compass" to integrate the map inside your page. <br>You can set custom map position and filter for categories and locations inside the widget settings.', 'compass'); ?>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row"><?php echo __('Place the shortcode anywhere in your content or integrate it within your theme template with PHP', 'compass'); ?></th>
                        <td class="top-padding-20">
                            <strong>Shortcode:</strong><br><br>
                            <code>[Compass]</code> or with PHP <code>&lt;?php echo do_shortcode('[Compass]');
                                ?&gt;</code><br><br>
                            <p class="hint"><?php echo __('Displays the Map with all locations.', 'compass'); ?></p>
                            <br><br>

                            <strong><?php echo __('Shortcode attributes:', 'compass'); ?></strong><br>
                            <p class="hint"><?php echo __('You can use shortcode attributes to override the <a href="edit.php?post_type=cbn-location&page=Compass-settings">global settings</a>. This allows for custom individual maps.', 'compass'); ?></p>
                            <br><br>

                            <code>lat="51.50665732176545" long="-0.12752251529432854" zoom="13"</code><br>
                            <p class="hint"><?php echo __('Set an individual map position with lat, long and zoom.', 'compass'); ?></p>
                            <br><br>

                            <code>types="food|drinks" ids="123"</code><br>
                            <p class="hint"><?php echo __('Filter locations by types (Marker Categories) or <a href="https://gigapress.net/how-to-find-a-page-id-or-post-id-in-wordpress/" target="_blank">Post ID</a>. Separate multiple Types or Post IDs with a | symbol.', 'compass'); ?></p>
                            <br><br>

                            <code>size="default|fullwidth" size_mobile="square|landscape|portrait"</code><br>
                            <p class="hint"><?php echo __('Set a custom size.', 'compass'); ?></p><br><br>

                            <code>height="400px" height_mobile="300px"</code><br>
                            <p class="hint"><?php echo __('Set a custom height. Don\'t forget to add a unit like <b>px</b>.', 'compass'); ?></p>
                            <br><br>

                            <code>region="Europe"</code><br>
                            <p class="hint"><?php echo __('Pre-select a region.', 'compass'); ?><?php echo __('This works only if you enabled the regions feature in the settings.', 'compass'); ?></p>
                            <br><br>

                            <code>map_type="interactive|simple"</code><br>
                            <p class="hint"><?php echo __('Enable or disable the "Add location" button.', 'compass'); ?></p>
                            <br><br>

                            <code>disable_regions="true|false"</code><br>
                            <p class="hint"><?php echo __('Enable or disable Regions.', 'compass'); ?></p><br><br>

                            <code>enable_cluster="true|false"</code><br>
                            <p class="hint"><?php echo __('Enable or disable Marker Clustering.', 'compass'); ?></p><br><br>

                            <code>enable_searchbar="true|false"</code><br>
                            <p class="hint"><?php echo __('Enable or disable the searchbar.', 'compass'); ?></p><br><br>

                            <code>enable_searchaddress_button="true|false"</code><br>
                            <p class="hint"><?php echo __('Enable or disable the "Search for Address (Geosearch)" button.', 'compass'); ?></p>
                            <br><br>

                            <code>enable_searchmarkers_button="true|false"</code><br>
                            <p class="hint"><?php echo __('Enable or disable the "Search for Markers" button.', 'compass'); ?></p>
                            <br><br>

                            <code>enable_currentlocation="true|false"</code><br>
                            <p class="hint"><?php echo __('Enable or disable the "Show me where I am" button.', 'compass'); ?></p>
                            <br><br>

                            <code>enable_fullscreen="true|false"</code><br>
                            <p class="hint"><?php echo __('Enable or disable the fullscreen button', 'compass'); ?></p>
                            <br><br>

                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row"><?php echo __('Additional Shortcodes', 'compass'); ?></th>
                        <td class="top-padding-20">
                            <code>[Compass-location value="Favorite color"
                                post_id="12345"]</code>
                            <br><br>
                            <span class="hint"><?php echo __('Display specific values from a location. The POST_ID attribute is optional. Alternatively use the PHP function <code>cbn_get_location_value( $value, $post_id )</code> in case you just want to return the value.', 'compass'); ?></span>
                            <br><br>
                            <strong><?php echo __('These values are available:', 'compass'); ?></strong>
                            <ul>
                                <li>title</li>
                                <li>image</li>
                                <li>audio</li>
                                <li>video</li>
                                <li>type</li>
                                <li>map</li>
                                <li>address</li>
                                <li>lat</li>
                                <li>lng</li>
                                <li>route</li>
                                <li>text</li>
                                <li>notification</li>
                                <li>author_name</li>
                                <li>author_email</li>
                                <li>wp_author_id</li>
                                <li>CUSTOM FIELD LABEL</li>
                            </ul>
                            <br><br><br>
                            <code>[Compass-gallery url="https://mysite.com/"
                                number="10"]</code>
                            <br><br>
                            <span class="hint"><?php echo __('Get a nice gallery view of all the location images. Each image is linked to the location marker on the map. Use the URL attribute to link the images to another page. Use the NUMBER attribute to limit the number of images. Both attributes are optional.', 'compass'); ?></span>
                            <br><br><br>
                            <code>[Compass-list]</code>
                            <br><br>
                            <span class="hint"><?php echo __('Get a list view of all the locations. The list view is paginated. This number of items per page can be adjusted under <i>Settings > Reading</i>.', 'compass'); ?></span>
                            <br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row"><?php echo __('URL parameters', 'compass'); ?></th>
                        <td class="top-padding-20">
                            <code>?markerid=123</code> <span
                                    class="hint"><?php echo __('123 can be the post_id of any public location. Add the parameter to the URL to auto-open a specific location.', 'compass'); ?></span><br><br>
                            <code>?region=Europe</code> <span
                                    class="hint"><?php echo __('Pre-select a region.', 'compass'); ?><?php echo __('This works only if you enabled the regions feature in the settings.', 'compass'); ?></span><br><br>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row"><?php echo __('Conditional Fields (experimental)', 'compass'); ?></th>
                        <td class="top-padding-20">
                            <span class="hint"><?php echo __('Show or Hide a Custom Field based on the selected value of a field.', 'compass'); ?></span><br><br>
                            <strong><?php echo __('Use this Javascript function in your template:', 'compass'); ?></strong><br><br>
                            <code class="block">/**
                                * OUM: Conditional Field
                                *
                                * sourceField Element that defines the condition
                                * targetField Element to show or hide
                                * condShow Array of values that lead to show
                                * condHide Array of values that lead to hide
                                */
                                cbnConditionalField(sourceField, targetField, condShow, condHide);
                            </code><br><br>
                            <strong><?php echo __('Example:', 'compass'); ?></strong><br><br>
                            <code>
                                cbnConditionalField('[name="cbn_marker_icon[]"]',
                                '[name="cbn_location_custom_fields[1645650268221]"]', ['1', '2'], ['3', '']);
                            </code>
                        </td>
                    </tr>
                    <tr style="vertical-align: top;">
                        <th scope="row">
                            <?php echo __('Hooks', 'compass'); ?>
                        </th>
                        <td class="top-padding-20">
                            <span class="hint"><?php echo __('Make use of filter hooks to extend the functionality of the Compass plugin.', 'compass'); ?><?php echo __('Find more info on how to use hooks <a href="https://www.Compass.com/knowledge-base/change-or-extend-content-of-each-location-bubble/?ref=pluginsettings">here</a>.', 'compass'); ?></span><br><br>
                            <strong><?php echo __('Customize location bubble content:', 'compass'); ?></strong><br><br>
                            <code class="block"><pre>add_filter('cbn_location_bubble_content', function ( $content, $location ) {
	$content .= 'Post ID: ' . $location['post_id'];
	return $content;
}, 10, 2);</pre>
                            </code>
                            <br><br><br>
                            <strong><?php echo __('Customize location list item content:', 'compass'); ?></strong><br><br>
                            <code class="block"><pre>add_filter('cbn_location_list_item_content', function ( $content, $location ) {
	$content .= 'Post ID: ' . $location['post_id'];
	return $content;
}, 10, 2);</pre>
                            </code>
                            <br><br><br>
                            <strong><?php echo __('Customize location bubble image (eg. to add a lightbox):', 'compass'); ?></strong><br><br>
                            <code class="block"><pre>add_filter('cbn_location_bubble_image', function ( $image, $location ) {
	// extend or change image
	$image = '&lt;a class=&quot;lightbox&quot; href=&quot;' . $location['image'] . '&quot;&gt;' . $image . '&lt;/a&gt;';
	return $image;
}, 10, 2);</pre>
                            </code>
                            <br><br><br>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </div>
        </div>
    </form>
</div>

<div class="wrap">
    <h1>Open User Map</h1>

    <?php settings_errors('oum_messages'); ?>

    <form method="post" action="options.php">


        <?php if (get_option('oum_enable_add_location') !== 'on' && get_option('oum_enable_add_location') !== ''): ?>

            <?php settings_fields('open-user-map-settings-group-wizard-1'); ?>
            <?php do_settings_sections('open-user-map-settings-group-wizard-1'); ?>

            <div class="oum-wizard">
                <div class="hero">
                    <div class="logo">Open User Map</div>
                    <div class="overline"><?php echo __('Quick Setup (2/3)', 'open-user-map'); ?></div>
                    <h1><?php echo __('What type of map do you need?', 'open-user-map'); ?></h1>
                    <ul class="steps">
                        <li class="done"></li>
                        <li class="done"></li>
                        <li></li>
                    </ul>
                </div>
                <div class="step-content">
                    <div class="intro">
                        <?php echo __('Use Open User Map to create either an interactive map that lets visitors add location markers or a custom map featuring your own locations.', 'open-user-map'); ?>
                        <br><br>
                        <?php echo __('Don\'t worry, you can adjust this later in the settings.', 'open-user-map'); ?>
                    </div>
                    <div class="map-types">
                        <div class="option">
                            <label>
                                <div class="map-type-preview" data-type="interactive"></div>
                                <div class="label-text">
                                    <input type='radio' name='oum_wizard_usecase' value='1' checked>
                                    <h2><?php echo __('Interactive Map', 'open-user-map'); ?></h2>
                                    <p><?php echo __('Create a community map! Visitors to your page can add new location markers to the map. You will receive a notification to approve each location before it is published.', 'open-user-map'); ?></p>
                                </div>
                            </label>
                        </div>
                        <div class="option">
                            <label>
                                <div class="map-type-preview" data-type="simple"></div>
                                <div class="label-text">
                                    <input type='radio' name='oum_wizard_usecase' value='2'>
                                    <h2><?php echo __('Simple Map', 'open-user-map'); ?></h2>
                                    <p><?php echo __('A customized and clear map showcasing only your own location markers, without the option for other users to add new locations. Additional features will be deactivated by default.', 'open-user-map'); ?></p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <input type="hidden" name="oum_wizard_usecase_done" value="1">

                    <?php submit_button(__('Next', 'open-user-map'), 'primary', 'submit', false); ?>
                </div>
            </div>

        <?php elseif (get_option('oum_wizard_usecase_done') && !get_option('oum_wizard_finish_done')): ?>

            <?php settings_fields('open-user-map-settings-group-wizard-2'); ?>
            <?php do_settings_sections('open-user-map-settings-group-wizard-2'); ?>

            <div class="oum-wizard">
                <div class="hero">
                    <div class="logo">Open User Map</div>
                    <div class="overline"><?php echo __('Quick Setup (3/3)', 'open-user-map'); ?></div>
                    <h1>🎉 <?php echo __('Yeah, complete!', 'open-user-map'); ?></h1>
                    <ul class="steps">
                        <li class="done"></li>
                        <li class="done"></li>
                        <li class="done"></li>
                    </ul>
                </div>
                <div class="step-content">

                    <h3><?php echo __('Your next steps:', 'open-user-map'); ?></h3>

                    <?php if (get_option('oum_wizard_usecase') == '1'): ?>

                        <ol class="next-steps">
                            <li><?php echo __('Use the page editor or Elementor to insert the <b>"Open User Map"</b> block onto a page.<br>Alternatively, you can use the shortcode <code>[open-user-map]</code>.', 'open-user-map'); ?></li>
                            <li><?php echo __('Your website visitors will see a <div class="oum-inline-plus">+</div> button in the upper right corner of the map, which they can use to propose their own location markers. New location proposals will have status "pending" to wait for your approval in the <i>Open User Map > All Locations</i> menu.', 'open-user-map'); ?></li>
                            <li><?php echo __('Customize styles, activate features and find help under <i>Open User Map > Settings</i>', 'open-user-map'); ?></li>
                        </ol>

                    <?php elseif (get_option('oum_wizard_usecase') == '2'): ?>

                        <ol class="next-steps">
                            /* translators: %s is the URL to the "Add Location" page */
                            <li><?php echo sprintf(__('Add your first Location under <a href="%s">Open User Map > Add Location</a>', 'open-user-map'), 'post-new.php?post_type=oum-location'); ?></li>
                            <li><?php echo __('Use the page editor or Elementor to insert the <b>"Open User Map"</b> block onto a page.<br>Alternatively, you can use the shortcode <code>[open-user-map]</code>.', 'open-user-map'); ?></li>
                            <li><?php echo __('Customize styles, activate features and find help under <i>Open User Map > Settings</i>', 'open-user-map'); ?></li>
                        </ol>

                    <?php endif; ?>

                    <input type="hidden" name="oum_wizard_finish_done" value="1">

                    <?php submit_button('Okay, got it', 'primary', 'submit', false); ?>
                </div>
            </div>

        <?php else: ?>

            <?php settings_fields('open-user-map-settings-group'); ?>
            <?php do_settings_sections('open-user-map-settings-group'); ?>

            <!-- NAV -->
            <nav class="nav-tab-wrapper">
                <a href="#tab-1" class="nav-tab nav-tab-active"><?php echo __('Map Settings', 'open-user-map'); ?></a>
                <a href="#tab-2" class="nav-tab"><?php echo __('Form Settings', 'open-user-map'); ?></a>
                <a href="#tab-3" class="nav-tab"><?php echo __('Filters & Categories', 'open-user-map'); ?></a>
                <a href="#tab-4" class="nav-tab"><?php echo __('Regions', 'open-user-map'); ?></a>
                <a href="#tab-5" class="nav-tab"><?php echo __('Advanced', 'open-user-map'); ?></a>
                <a href="#tab-6" class="nav-tab"><?php echo __('Import & Export', 'open-user-map'); ?></a>
                <a href="#tab-7" class="nav-tab"><?php echo __('Help & Getting Started', 'open-user-map'); ?></a>
            </nav>


            <!-- TABS -->
            <div class="tab-content">

                <div id="tab-1" class="tab-pane active">
                    <table class="form-table">

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_add_location = get_option('oum_enable_add_location', 'on');
                            $oum_plus_button_label = get_option('oum_plus_button_label');
                            ?>
                            <th scope="row">
                                <?php echo __('Map Type', 'open-user-map'); ?>
                                <br><br>
                                <span class="description"><?php echo __('Tip: Watch <a href="https://www.youtube.com/watch?v=7v605z1FT2c" target="_blank">this video</a> to see a demonstration of the interactive map.', 'open-user-map'); ?></span>
                            </th>
                            <td>

                                <div class="map-types">
                                    <div class="option">
                                        <label>
                                            <div class="map-type-preview" data-type="interactive"></div>
                                            <div class="label-text">
                                                <input type='radio' name='oum_map_type'
                                                       value='1' <?php echo ($oum_enable_add_location == 'on') ? 'checked' : ''; ?>>
                                                <h2><?php echo __('Interactive Map', 'open-user-map'); ?></h2>
                                                <p><?php echo __('Create a community map! Visitors to your page can add new location markers to the map. You will receive a notification to approve each location before it is published.', 'open-user-map'); ?></p>
                                                <div id="plus_button_label">
                                                    <strong><?php echo __('Custom "+" Button Label:', 'open-user-map'); ?></strong><br>
                                                    <input class="regular-text" type="text" name="oum_plus_button_label"
                                                           id="oum_plus_button_label"
                                                           placeholder="<?php echo __('Add location', 'open-user-map'); ?>"
                                                           value="<?php echo esc_textarea($oum_plus_button_label); ?>">
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="option">
                                        <label>
                                            <div class="map-type-preview" data-type="simple"></div>
                                            <div class="label-text">
                                                <input type='radio' name='oum_map_type'
                                                       value='2' <?php echo ($oum_enable_add_location != 'on') ? 'checked' : ''; ?>>
                                                <h2><?php echo __('Simple Map', 'open-user-map'); ?></h2>
                                                <p><?php echo __('A customized and clear map showcasing only your own location markers, without the option for other users to add new locations.', 'open-user-map'); ?></p>
                                                <br>
                                                <p><?php echo __('<a href="edit.php?post_type=oum-location">Manage all Locations here</a>', 'open-user-map'); ?></p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <label for="oum_enable_add_location"></label><input type="checkbox"
                                                                                    id="oum_enable_add_location"
                                                                                    name="oum_enable_add_location" <?php echo ($oum_enable_add_location == 'on') ? 'checked' : ''; ?>>

                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('Map Style', 'open-user-map'); ?>
                            </th>
                            <td>
                                <div class="map_styles">
                                    <?php
                                    $map_style = get_option('oum_map_style') ? get_option('oum_map_style') : 'Esri.WorldStreetMap';
                                    $items = $this->map_styles;

                                    foreach ($items as $val => $label) {
                                        $selected = ($map_style == $val) ? 'checked' : '';
                                        echo '<label class="' . $selected . '"><div class="map_style_preview" data-style="' . esc_attr($val) . '"><div>' . esc_textarea($label) . '</div></div><input type="radio" name="oum_map_style" ' . $selected . ' value="' . esc_attr($val) . '"></label>';
                                    }

                                    ?>

                                    <?php
                                    //custom map styles
                                    $custom_items = $this->custom_map_styles;

                                    foreach ($custom_items as $val => $label) {
                                        $selected = ($map_style == $val) ? 'checked' : '';
                                        echo '<label class="' . $selected . '"><div class="map_style_preview custom" data-style="' . esc_attr($val) . '"><div>' . esc_textarea($label) . '</div></div><input type="radio" name="oum_map_style" ' . $selected . ' value="' . esc_attr($val) . '"></label>';
                                    }
                                    ?>

                                    <?php
                                    //commercial map styles
                                    $commercial_items = $this->commercial_map_styles;

                                    foreach ($commercial_items as $val => $label) {
                                        $selected = ($map_style == $val) ? 'checked' : '';
                                        echo '<label class="' . $selected . '"><div class="map_style_preview commercial" data-style="' . esc_attr($val) . '"><div>' . esc_textarea($label) . '</div></div><input type="radio" name="oum_map_style" ' . $selected . ' value="' . esc_attr($val) . '"></label>';
                                    }
                                    ?>
                                </div>

                                <div class="wrap-tile-provider-settings">
                                    <?php
                                    $oum_tile_provider_mapbox_key = get_option('oum_tile_provider_mapbox_key', '');
                                    ?>
                                    <div class="tile-provider-mapbox">
                                        <strong><?php echo __('MapBox API Key:', 'open-user-map'); ?></strong><br>
                                        <label for="oum_tile_provider_mapbox_key"></label><input class="regular-text"
                                                                                                 type="text"
                                                                                                 name="oum_tile_provider_mapbox_key"
                                                                                                 id="oum_tile_provider_mapbox_key"
                                                                                                 value="<?php echo esc_attr($oum_tile_provider_mapbox_key); ?>">
                                        <br><br>
                                        /* translators: %s is the URL to the "MapBox API key" page */
                                        <span class="description"><?php echo sprintf(__('You can get a MapBox API key <a href="%s">here</a>. It is free to use with up to 200,000 map tile requests per month. Please attribute MapBox service if you use their free plan.', 'open-user-map'), 'https://account.mapbox.com/signup/'); ?></span><br>
                                    </div>
                                </div>

                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('Default Marker Icon', 'open-user-map'); ?>
                            </th>
                            <td>
                                <div class="marker_icons">
                                    <?php
                                    $marker_icon = get_option('oum_marker_icon') ? get_option('oum_marker_icon') : 'default';
                                    $items = $this->marker_icons;

                                    foreach ($items as $val) {
                                        $selected = ($marker_icon == $val) ? 'checked' : '';
                                        echo '<label class="' . $selected . '"><div class="marker_icon_preview" data-style="' . esc_attr($val) . '"></div><input type="radio" name="oum_marker_icon" ' . $selected . ' value="' . esc_attr($val) . '"></label>';
                                    }
                                    ?>
                                    <?php
                                    $marker_user_icon = get_option('oum_marker_user_icon');
                                    $custom_items = $this->pro_marker_icons;

                                    foreach ($custom_items as $val) {
                                        $selected = ($marker_icon == $val) ? 'checked' : '';
                                        $user_icon_style = ($marker_user_icon) ? 'style="background-image: url(' . esc_attr($marker_user_icon) . ')"' : '';

                                        echo '<label class="' . $selected . ' label_marker_user_icon"><div id="oum_marker_user_icon_preview" class="marker_icon_preview" data-style="' . esc_attr($val) . '" ' . $user_icon_style . '></div><input type="radio" name="oum_marker_icon" ' . $selected . ' value="' . esc_attr($val) . '">';
                                        echo "<div class='icon_upload'><a href='#' class='oum_upload_icon_button button button-secondary'>" . __('Upload Icon', 'open-user-map') . "</a><p class='description'>PNG, max. 100px</p><input type='hidden' id='oum_marker_user_icon' name='oum_marker_user_icon' value='" . esc_attr($marker_user_icon) . "'></div>";
                                        echo "</label>";
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr style="vertical-align: top;">
                            <?php
                            $oum_ui_color = get_option('oum_ui_color') ? get_option('oum_ui_color') : 'rgba(40, 127, 214, 1)'; // Standaard RGBA
                            ?>
                            <th scope="row">
                                <?php echo __('UI Elements color', 'open-user-map'); ?>
                            </th>
                            <td>
                                <div id="oum_ui_color_wrap">
                                    <label>
                                        <input type="text" class="oum_colorpicker_alpha" id="oum_ui_color" name="oum_ui_color"
                                               value="<?php echo esc_attr($oum_ui_color); ?>">
                                    </label>
                                    <br>
                                    <strong><?php echo __('RGBA Preview:', 'open-user-map'); ?></strong>
                                    <div id="oum_ui_rgba_preview"
                                         style="width: 50px; height: 20px; border: 1px solid #000; background-color: <?php echo esc_attr($oum_ui_color); ?>;">
                                    </div>
                                </div>
                            </td>
                        </tr>


                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('Map size', 'open-user-map'); ?>
                            </th>
                            <td>
                                <label for="oum_map_size"></label><select name="oum_map_size" id="oum_map_size">
                                    <?php
                                    $map_size = get_option('oum_map_size') ? get_option('oum_map_size') : 'default';
                                    $oum_map_height = get_option('oum_map_height');
                                    $items = $this->oum_map_sizes;

                                    foreach ($items as $val => $label) {
                                        $selected = ($map_size == $val) ? 'selected' : '';
                                        echo '<option value="' . esc_textarea($val) . '" ' . $selected . '>' . esc_textarea($label) . '</option>';
                                    }
                                    ?>
                                </select>
                                <br><br>
                                <strong><?php echo __('Custom Height:', 'open-user-map'); ?></strong><br>
                                <label for="oum_map_height"></label><input class="regular-text" type="text"
                                                                           name="oum_map_height" id="oum_map_height"
                                                                           placeholder="e.g. 400px"
                                                                           value="<?php echo esc_attr($oum_map_height); ?>"><br><br>
                                <div class="description"><?php echo __('Don\'t forget to add a unit like <b>px</b>.', 'open-user-map'); ?></div>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('Map size (mobile)', 'open-user-map'); ?>
                            </th>
                            <td>
                                <?php
                                $oum_map_height_mobile = get_option('oum_map_height_mobile');
                                ?>
                                <strong><?php echo __('Custom Height:', 'open-user-map'); ?></strong><br>
                                <label for="oum_map_height_mobile"></label><input class="regular-text" type="text"
                                                                                  name="oum_map_height_mobile"
                                                                                  id="oum_map_height_mobile"
                                                                                  placeholder="e.g. 400px"
                                                                                  value="<?php echo esc_attr($oum_map_height_mobile); ?>"><br><br>
                                <div class="description"><?php echo __('Don\'t forget to add a unit like <b>px</b>.', 'open-user-map'); ?></div>
                            </td>
                        </tr>

                        <tr class="top">
                            <th scope="row">
                                <label><?php echo __('Initial map view', 'open-user-map'); ?></label><br>
                                <span class="description"><?php echo __('This can be customized in the Block / Shortcode settings.', 'open-user-map'); ?></span><br>
                            </th>
                            <td>
                                <?php
                                $start_lat = get_option('oum_start_lat');
                                $start_lng = get_option('oum_start_lng');
                                $start_zoom = get_option('oum_start_zoom');
                                $oum_enable_fixed_map_bounds = get_option('oum_enable_fixed_map_bounds');
                                $oum_searchaddress_label = get_option('oum_searchaddress_label') ? get_option('oum_searchaddress_label') : $this->oum_searchaddress_label_default;
                                ?>
                                <div class="form-field geo-coordinates-wrap">
                                    <div class="map-wrap">
                                        <div id="mapGetInitial"
                                             class="leaflet-map map-style_<?php echo esc_attr($map_style); ?>">
                                        </div>
                                    </div>
                                    <div class="input-wrap">
                                        <div class="latlng-wrap">
                                            <div class="form-field lat-wrap">
                                                <label class="meta-label" for="oum_start_lat">
                                                    <?php echo __('Lat', 'open-user-map'); ?>
                                                </label>
                                                <input type="text" readonly class="widefat" id="oum_start_lat"
                                                       name="oum_start_lat" value="<?php echo esc_attr($start_lat); ?>">
                                            </div>
                                            <div class="form-field lng-wrap">
                                                <label class="meta-label" for="oum_start_lng">
                                                    <?php echo __('Lng', 'open-user-map'); ?>
                                                </label>
                                                <input type="text" readonly class="widefat" id="oum_start_lng"
                                                       name="oum_start_lng" value="<?php echo esc_attr($start_lng); ?>">
                                            </div>
                                            <div class="form-field zoom-wrap">
                                                <label class="meta-label" for="oum_start_zoom">
                                                    <?php echo __('Zoom', 'open-user-map'); ?>
                                                </label>
                                                <input type="text" readonly class="widefat" id="oum_start_zoom"
                                                       name="oum_start_zoom"
                                                       value="<?php echo esc_attr($start_zoom) ? esc_attr($start_zoom) : ''; ?>">
                                            </div>
                                        </div>

                                        <div class="geo-coordinates-hint">
                                            <strong><?php echo __('How to adjust the initial view:', 'open-user-map'); ?></strong>
                                            <ol>
                                                <li><?php echo __('Use the map to the left to find your area of interest', 'open-user-map'); ?></li>
                                                <li><?php echo __('Zoom and pan the map to set the perfect initial view', 'open-user-map'); ?>
                                                    <br><br><strong><?php echo __('Tip:', 'open-user-map'); ?></strong> <?php echo __('Hold down the Shift key + mouse to zoom in on an area.', 'open-user-map'); ?>
                                                </li>
                                            </ol>
                                        </div>

                                        <div class="additional-map-settings">
                                            <input class="oum-switch" type="checkbox" id="oum_enable_fixed_map_bounds"
                                                   name="oum_enable_fixed_map_bounds" <?php echo ($oum_enable_fixed_map_bounds == 'on') ? 'checked' : ''; ?>>
                                            <label for="oum_enable_fixed_map_bounds"><?php echo __('Keep map focus in fixed position', 'open-user-map'); ?></label><br>
                                            <span class="description"><?php echo __('If enabled, the visible map will try to stay in the boundaries. (Initial Map View).', 'open-user-map'); ?><?php echo __('This does not work when using Custom Map Positions (e.g. Regions).', 'open-user-map'); ?></span>
                                        </div>
                                    </div>

                                    <script type="text/javascript" data-category="functional" class="cmplz-native"
                                            id="oum-inline-js">
                                        const lat = '<?php echo esc_attr($start_lat) ? esc_attr($start_lat) : '28'; ?>';
                                        const lng = '<?php echo esc_attr($start_lng) ? esc_attr($start_lng) : '0'; ?>';
                                        const zoom = '<?php echo esc_attr($start_zoom) ? esc_attr($start_zoom) : '1'; ?>';
                                        const mapStyle = '<?php echo esc_html($map_style); ?>';
                                        const oum_tile_provider_mapbox_key = `<?php echo esc_attr($oum_tile_provider_mapbox_key); ?>`;
                                        let oum_geosearch_selected_provider = ``;
                                        const oum_geosearch_provider = `<?php echo get_option('oum_geosearch_provider') ? get_option('oum_geosearch_provider') : 'osm'; ?>`;
                                        const oum_geosearch_provider_geoapify_key = `<?php echo get_option('oum_geosearch_provider_geoapify_key', ''); ?>`;
                                        const oum_geosearch_provider_here_key = `<?php echo get_option('oum_geosearch_provider_here_key', ''); ?>`;
                                        const oum_geosearch_provider_mapbox_key = `<?php echo get_option('oum_geosearch_provider_mapbox_key', ''); ?>`;
                                        const oum_searchaddress_label = `<?php echo esc_attr($oum_searchaddress_label); ?>`;
                                    </script>
                                    <?php
                                    // load map base scripts
                                    $this->include_map_scripts();
                                    wp_enqueue_script('oum_backend_settings_js', $this->plugin_url . 'src/js/backend-settings.js', array('oum_leaflet_providers_js', 'oum_leaflet_markercluster_js', 'oum_leaflet_subgroups_js', 'oum_leaflet_geosearch_js', 'oum_leaflet_locate_js', 'oum_leaflet_fullscreen_js', 'oum_leaflet_search_js', 'oum_leaflet_gesture_js', 'wp-i18n', 'oum_global_leaflet_js'), $this->plugin_version);
                                    ?>
                                </div>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_cluster = get_option('oum_enable_cluster', 'on');
                            ?>
                            <th scope="row"><?php echo __('Pins Clustering (group nearby markers)', 'open-user-map'); ?></th>
                            <td>
                                <input class="oum-switch" type="checkbox" name="oum_enable_cluster"
                                       id="oum_enable_cluster" <?php echo ($oum_enable_cluster === 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_cluster"></label><br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_scrollwheel_zoom_map = get_option('oum_enable_scrollwheel_zoom_map');
                            ?>
                            <th scope="row"><?php echo __('Scroll Wheel Zoom', 'open-user-map'); ?></th>
                            <td>
                                <input class="oum-switch" type="checkbox" name="oum_enable_scrollwheel_zoom_map"
                                       id="oum_enable_scrollwheel_zoom_map" <?php echo ($oum_enable_scrollwheel_zoom_map === 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_scrollwheel_zoom_map"></label><br><br>
                            </td>
                        </tr>
                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('Geosearch Provider', 'open-user-map'); ?>
                            </th>
                            <td>
                                <label for="oum_geosearch_provider"></label>
                                <select name="oum_geosearch_provider" id="oum_geosearch_provider">
                                    <?php
                                    $oum_geosearch_provider = get_option('oum_geosearch_provider', 'osm');
                                    $available_geosearch_providers = array_merge($this->oum_geosearch_provider, $this->pro_oum_geosearch_provider);

                                    foreach ($available_geosearch_providers as $val => $label) {
                                        $selected = ($oum_geosearch_provider == $val) ? 'selected' : '';
                                        echo '<option value="' . esc_attr($val) . '" ' . $selected . '>' . esc_html($label) . '</option>';
                                    }
                                    ?>
                                </select><br><br>
                                <div class="wrap-geosearch-provider-settings">
                                    <?php
                                    $providers = [
                                        'geoapify' => [
                                            'label' => __('Geoapify API Key:', 'open-user-map'),
                                            'option' => 'oum_geosearch_provider_geoapify_key',
                                            'url' => 'https://www.geoapify.com/get-started-with-maps-api',
                                            'description' => __('You can get a Geoapify API key <a href="%s">here</a>. It is free to use with up to 3000 requests per day. Please attribute Geoapify service if you use their free plan.', 'open-user-map')
                                        ],
                                        'here' => [
                                            'label' => __('Here API Key:', 'open-user-map'),
                                            'option' => 'oum_geosearch_provider_here_key',
                                            'url' => 'https://developer.here.com/',
                                            'description' => __('You can get a Here API key <a href="%s">here</a>. It is free to use with up to 30,000 requests per month. Please attribute Here service if you use their free plan.', 'open-user-map')
                                        ],
                                        'mapbox' => [
                                            'label' => __('MapBox API Key:', 'open-user-map'),
                                            'option' => 'oum_geosearch_provider_mapbox_key',
                                            'url' => 'https://account.mapbox.com/signup/',
                                            'description' => __('You can get a MapBox API key <a href="%s">here</a>. It is free to use with up to 100,000 geocoding requests per month. Please attribute MapBox service if you use their free plan.', 'open-user-map')
                                        ]
                                    ];

                                    foreach ($providers as $key => $data) :
                                        $api_key = get_option($data['option'], '');
                                        ?>
                                        <div class="geosearch-provider-<?php echo esc_attr($key); ?>">
                                            /* translators: %s is the URL to the "<?php echo esc_html($key); ?> API key"
                                            page */
                                            <strong><?php echo esc_html($data['label']); ?></strong><br>
                                            <label for="<?php echo esc_attr($data['option']); ?>"></label>
                                            <input class="regular-text" type="text"
                                                   name="<?php echo esc_attr($data['option']); ?>"
                                                   id="<?php echo esc_attr($data['option']); ?>"
                                                   value="<?php echo esc_attr($api_key); ?>">
                                            <br><br>
                                            <span class="description"><?php echo sprintf(esc_html__($data['description'], 'open-user-map'), esc_url($data['url'])); ?></span><br>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('Geosearch Provider', 'open-user-map'); ?>
                            </th>
                            <td>
                                <label for="oum_geosearch_provider"></label><select name="oum_geosearch_provider"
                                                                                    id="oum_geosearch_provider">
                                    <?php
                                    $available_geosearch_providers = $this->oum_geosearch_provider;
                                    $not_available_geosearch_providers = $this->pro_oum_geosearch_provider;

                                    foreach ($available_geosearch_providers as $val => $label) {
                                        echo '<option value="' . esc_textarea($val) . '" selected>' . esc_textarea($label) . '</option>';
                                    }

                                    foreach ($not_available_geosearch_providers as $val => $label) {
                                        echo '<option disabled>' . esc_textarea($label) . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_fullscreen = get_option('oum_enable_fullscreen', 'on');
                            ?>
                            <th scope="row"><?php echo __('Full Screen Button', 'open-user-map'); ?></th>
                            <td>
                                <input class="oum-switch" type="checkbox" name="oum_enable_fullscreen"
                                       id="oum_enable_fullscreen" <?php echo ($oum_enable_fullscreen === 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_fullscreen"></label><br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_currentlocation = get_option('oum_enable_currentlocation');
                            ?>
                            <th scope="row">
                                <?php echo __('"Show me where I am" Button', 'open-user-map'); ?>
                            </th>
                            <td>
                                <input class="oum-switch" type="checkbox" name="oum_enable_currentlocation"
                                       id="oum_enable_currentlocation" <?php echo ($oum_enable_currentlocation) ? 'checked' : ''; ?>>
                                <label for="oum_enable_currentlocation"></label><br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;" class="oum-gopro-tr">
                            <th scope="row">
                                <?php echo __('"Show me where I am" Button', 'open-user-map'); ?>
                            </th>
                            <td>
                                <label>
                                    <input class="oum-switch" type="checkbox" disabled>
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
                            $oum_form_headline = get_option('oum_form_headline');
                            ?>
                            <th scope="row"><?php echo __('Headline', 'open-user-map'); ?></th>
                            <td>
                                <label for="oum_form_headline"></label><input class="regular-text" type="text"
                                                                              name="oum_form_headline"
                                                                              id="oum_form_headline"
                                                                              placeholder="<?php echo __('Add a new location', 'open-user-map'); ?>"
                                                                              value="<?php echo esc_textarea($oum_form_headline); ?>"><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_title = get_option('oum_enable_title', 'on');
                            $oum_title_required = get_option('oum_title_required', 'on');
                            $oum_title_label = get_option('oum_title_label');
                            $oum_title_maxlength = get_option('oum_title_maxlength');
                            ?>
                            <th scope="row"><?php echo __('"Title" field', 'open-user-map'); ?></th>
                            <td>
                                <div class="oum_2cols">
                                    <div>
                                        <input class="oum-switch" type="checkbox" name="oum_enable_title"
                                               id="oum_enable_title" <?php echo ($oum_enable_title == 'on') ? 'checked' : ''; ?>>
                                        <label for="oum_enable_title"><?php echo __('Enable', 'open-user-map'); ?></label>
                                    </div>
                                    <div>
                                        <input class="oum-switch" type="checkbox" name="oum_title_required"
                                               id="oum_title_required" <?php echo ($oum_title_required) ? 'checked' : ''; ?>>
                                        <label for="oum_title_required"><?php echo __('Required', 'open-user-map'); ?></label>
                                    </div>
                                    <div>
                                        <input class="small-text oum_title_maxlength" type="number" min="0"
                                               name="oum_title_maxlength" id="oum_title_maxlength"
                                               value="<?php echo isset($oum_title_maxlength) ? esc_attr($oum_title_maxlength) : ''; ?>"/>
                                        <label for="oum_title_maxlength"><?php echo __('Max. length', 'open-user-map'); ?></label>
                                    </div>
                                </div>
                                <br>
                                <strong><?php echo __('Custom Label:', 'open-user-map'); ?></strong><br>
                                <label for="oum_title_label"></label><input class="regular-text" type="text"
                                                                            name="oum_title_label" id="oum_title_label"
                                                                            placeholder="<?php echo esc_attr($this->oum_title_label_default); ?>"
                                                                            value="<?php echo esc_attr($oum_title_label); ?>">
                                <br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_map_label = get_option('oum_map_label');
                            ?>
                            <th scope="row"><?php echo __('"Map" field', 'open-user-map'); ?></th>
                            <td>
                                <strong><?php echo __('Custom Label:', 'open-user-map'); ?></strong><br>
                                <label for="oum_map_label"></label><input class="regular-text" type="text"
                                                                          name="oum_map_label" id="oum_map_label"
                                                                          placeholder="<?php echo esc_attr($this->oum_map_label_default); ?>"
                                                                          value="<?php echo esc_attr($oum_map_label); ?>">
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('Custom fields', 'open-user-map'); ?>
                            </th>
                            <td>
                                <div class="oum_custom_fields_wrapper">
                                    <?php
                                    $oum_custom_fields = get_option('oum_custom_fields');
                                    ?>
                                    <table>
                                        <thead>
                                        <tr>
                                            <th><?php echo __('Label', 'open-user-map'); ?></th>
                                            <th><?php echo __('Required', 'open-user-map'); ?></th>
                                            <th><?php echo __('Private', 'open-user-map'); ?></th>
                                            <th><?php echo __('Max. length', 'open-user-map'); ?></th>
                                            <th><?php echo __('Field type', 'open-user-map'); ?>
                                            </th>
                                            <th><?php echo __('Options', 'open-user-map'); ?></th>
                                            <th><?php echo __('Description', 'open-user-map'); ?></th>
                                            <th></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <?php if (is_array($oum_custom_fields)): ?>
                                            <?php foreach ($oum_custom_fields as $index => $custom_field): ?>
                                                <tr>
                                                    <td>
                                                        <label>
                                                            <input type="text"
                                                                   class="field-type-text field-type-link field-type-email field-type-checkbox field-type-radio field-type-select"
                                                                   name="oum_custom_fields[<?php echo $index; ?>][label]"
                                                                   placeholder="<?php echo __('Enter label', 'open-user-map'); ?>"
                                                                   value="<?php echo esc_attr($custom_field['label']); ?>"/>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <input class="oum-switch field-type-text field-type-link field-type-email field-type-checkbox field-type-radio field-type-select"
                                                               id="oum_custom_fields_<?php echo $index; ?>_required"
                                                               type="checkbox"
                                                               name="oum_custom_fields[<?php echo $index; ?>][required]" <?php echo (isset($custom_field['required'])) ? 'checked' : ''; ?> /><label
                                                                class="field-type-text field-type-link field-type-email field-type-checkbox field-type-radio field-type-select"
                                                                for="oum_custom_fields_<?php echo $index; ?>_required"></label>
                                                    </td>
                                                    <td>
                                                        <input class="oum-switch field-type-text field-type-link field-type-email field-type-checkbox field-type-radio field-type-select"
                                                               id="oum_custom_fields_<?php echo $index; ?>_private"
                                                               type="checkbox"
                                                               name="oum_custom_fields[<?php echo $index; ?>][private]" <?php echo (isset($custom_field['private'])) ? 'checked' : ''; ?> /><label
                                                                class="field-type-text field-type-link field-type-email field-type-checkbox field-type-radio field-type-select"
                                                                for="oum_custom_fields_<?php echo $index; ?>_private"></label>
                                                    </td>
                                                    <td>
                                                        <label>
                                                            <input class="small-text field-type-text field-type-link field-type-email"
                                                                   type="number" min="0"
                                                                   name="oum_custom_fields[<?php echo $index; ?>][maxlength]"
                                                                   value="<?php echo isset($custom_field['maxlength']) ? esc_attr($custom_field['maxlength']) : ''; ?>"/>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label>
                                                            <select class="oum-custom-field-fieldtype"
                                                                    name="oum_custom_fields[<?php echo esc_attr($index); ?>][fieldtype]">
                                                                <?php
                                                                $available_field_types = $this->oum_custom_field_fieldtypes;
                                                                foreach ($available_field_types as $value => $label):
                                                                    $selected = (isset($custom_field['fieldtype']) && $custom_field['fieldtype'] == $value) ? 'selected' : '';
                                                                    ?>
                                                                    <option value="<?php echo esc_attr($value); ?>" <?php echo esc_attr($selected); ?>>
                                                                        <?php echo esc_html($label); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label>
                                                            <input type="text"
                                                                   class="regular-text field-type-checkbox field-type-radio field-type-select"
                                                                   name="oum_custom_fields[<?php echo $index; ?>][options]"
                                                                   placeholder="Red|Blue|Green"
                                                                   value="<?php echo isset($custom_field['options']) ? esc_attr($custom_field['options']) : ''; ?>"/>
                                                        </label>
                                                        <label class="field-type-select oum-custom-field-allow-empty"><input
                                                                    class="field-type-select" type="checkbox"
                                                                    name="oum_custom_fields[<?php echo $index; ?>][emptyoption]" <?php echo isset($custom_field['emptyoption']) ? 'checked' : ''; ?> ><?php echo __('add empty option', 'open-user-map'); ?>
                                                        </label>
                                                        <label class="field-type-link oum-custom-field-use-label-as-text"><input
                                                                    class="field-type-link" type="checkbox"
                                                                    name="oum_custom_fields[<?php echo $index; ?>][uselabelastextoption]" <?php echo isset($custom_field['uselabelastextoption']) ? 'checked' : ''; ?> ><?php echo __('use label as text', 'open-user-map'); ?>
                                                        </label>
                                                        <label>
                                                            <textarea class="regular-text field-type-html"
                                                                      name="oum_custom_fields[<?php echo $index; ?>][html]"
                                                                      placeholder="Enter HTML here"><?php echo isset($custom_field['html']) ? esc_attr($custom_field['html']) : ''; ?></textarea>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <label>
                                                            <input type="text"
                                                                   class="field-type-text field-type-link field-type-email field-type-checkbox field-type-radio field-type-select"
                                                                   name="oum_custom_fields[<?php echo $index; ?>][description]"
                                                                   placeholder="<?php echo __('Enter description (optional)', 'open-user-map'); ?>"
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
                                    <a href="#" class="oum_add_button button" title="Add field">Add field</a>
                                </div>
                                <br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_address = get_option('oum_enable_address', 'on');
                            $oum_hide_address = get_option('oum_hide_address');
                            $oum_enable_gmaps_link = get_option('oum_enable_gmaps_link', 'on');
                            $oum_address_label = get_option('oum_address_label');
                            ?>
                            <th scope="row"><?php echo __('"Subtitle" field', 'open-user-map'); ?></th>
                            <td>
                                <input class="oum-switch" type="checkbox" name="oum_enable_address"
                                       id="oum_enable_address" <?php echo ($oum_enable_address === 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_address"><?php echo __('Enable', 'open-user-map'); ?></label><br>

                                <input class="oum-switch" type="checkbox" name="oum_hide_address"
                                       id="oum_hide_address" <?php echo ($oum_hide_address) ? 'checked' : ''; ?>>
                                <label for="oum_hide_address"><?php echo __('Don\'t show inside Location Pop-Up', 'open-user-map'); ?></label><br>

                                <input class="oum-switch" type="checkbox" name="oum_enable_gmaps_link"
                                       id="oum_enable_gmaps_link" <?php echo ($oum_enable_gmaps_link === 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_gmaps_link"><?php echo __('Link to Google Maps', 'open-user-map'); ?></label><br>

                                <strong><?php echo __('Custom Label:', 'open-user-map'); ?></strong><br>
                                <label for="oum_address_label"></label><input class="regular-text" type="text"
                                                                              name="oum_address_label"
                                                                              id="oum_address_label"
                                                                              placeholder="<?php echo esc_attr($this->oum_address_label_default); ?>"
                                                                              value="<?php echo esc_attr($oum_address_label); ?>"><br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_description = get_option('oum_enable_description', 'on');
                            $oum_description_required = get_option('oum_description_required');
                            $oum_description_label = get_option('oum_description_label');
                            ?>
                            <th scope="row"><?php echo __('"Description" field', 'open-user-map'); ?></th>
                            <td>
                                <div class="oum_2cols">
                                    <div>
                                        <input class="oum-switch" type="checkbox" name="oum_enable_description"
                                               id="oum_enable_description" <?php echo ($oum_enable_description === 'on') ? 'checked' : ''; ?>>
                                        <label for="oum_enable_description"><?php echo __('Enable', 'open-user-map'); ?></label>
                                    </div>
                                    <div>
                                        <input class="oum-switch" type="checkbox" name="oum_description_required"
                                               id="oum_description_required" <?php echo ($oum_description_required) ? 'checked' : ''; ?>>
                                        <label for="oum_description_required"><?php echo __('Required', 'open-user-map'); ?></label>
                                    </div>
                                </div>
                                <br>
                                <strong><?php echo __('Custom Label:', 'open-user-map'); ?></strong><br>
                                <label for="oum_description_label"></label><input class="regular-text" type="text"
                                                                                  name="oum_description_label"
                                                                                  id="oum_description_label"
                                                                                  placeholder="<?php echo esc_attr($this->oum_description_label_default); ?>"
                                                                                  value="<?php echo esc_attr($oum_description_label); ?>">
                                <br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_image = get_option('oum_enable_image', 'on');
                            $oum_image_required = get_option('oum_image_required');
                            $oum_enable_video = get_option('oum_enable_video', false);
                            $oum_video_required = get_option('oum_video_required');
                            $oum_upload_media_label = get_option('oum_upload_media_label');
                            ?>
                            <th scope="row"><?php echo __('"Media upload" fields', 'open-user-map'); ?></th>
                            <td>

                                <div class="oum_2cols">
                                    <div>
                                        <input class="oum-switch" type="checkbox" name="oum_enable_image"
                                               id="oum_enable_image" <?php echo ($oum_enable_image === 'on') ? 'checked' : ''; ?>>
                                        <label for="oum_enable_image"><?php echo __('Image', 'open-user-map'); ?></label>
                                    </div>
                                    <div>
                                        <input class="oum-switch" type="checkbox" name="oum_image_required"
                                               id="oum_image_required" <?php echo ($oum_image_required) ? 'checked' : ''; ?>>
                                        <label for="oum_image_required"><?php echo __('Required', 'open-user-map'); ?></label>
                                    </div>
                                </div>
                                <br><br>
                                <div class="oum_2cols">
                                    <div>
                                        <input class="oum-switch" type="checkbox" name="oum_enable_video"
                                               id="oum_enable_video" <?php echo ($oum_enable_video === 'on') ? 'checked' : ''; ?>>
                                        <label for="oum_enable_video"><?php echo __('Video (YouTube, Vimeo)', 'open-user-map'); ?></label>
                                    </div>
                                    <div>
                                        <input class="oum-switch" type="checkbox" name="oum_video_required"
                                               id="oum_video_required" <?php echo ($oum_video_required) ? 'checked' : ''; ?>>
                                        <label for="oum_video_required"><?php echo __('Required', 'open-user-map'); ?></label>
                                    </div>
                                </div>
                                <br><br>

                                <div class="oum_2cols">
                                    <div class="oum-gopro-div">
                                        <label>
                                            <input class="oum-switch" type="checkbox" disabled>
                                        </label>
                                        <label><?php echo __('Video (YouTube, Vimeo)', 'open-user-map'); ?></label>
                                    </div>
                                    <div class="oum-gopro-div">
                                        <label>
                                            <input class="oum-switch" type="checkbox" disabled>
                                        </label>
                                        <label><?php echo __('Required', 'open-user-map'); ?></label>
                                    </div>
                                    <div>

                                    </div>
                                </div>
                                <br><br><br><br>

                                <strong><?php echo __('Custom Label:', 'open-user-map'); ?></strong><br>
                                <label for="oum_upload_media_label"></label><input class="regular-text" type="text"
                                                                                   name="oum_upload_media_label"
                                                                                   id="oum_upload_media_label"
                                                                                   placeholder="<?php echo esc_attr($this->oum_upload_media_label_default); ?>"
                                                                                   value="<?php echo esc_attr($oum_upload_media_label); ?>">
                                <br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_max_image_filesize = get_option('oum_max_image_filesize') ? get_option('oum_max_image_filesize') : 10;
                            ?>
                            <th scope="row">
                                /* translators: Information regarding max upload-size for users */
                                <?php echo __('Max upload size', 'open-user-map'); ?>
                                <br><br>
                            </th>
                            <td>
                                <div class="oum_2cols">
                                    <div>
                                        <strong><?php echo __('Image'); ?>:</strong><br>
                                        <label for="oum_max_image_filesize"></label><input class="small-text"
                                                                                           type="number" min="1"
                                                                                           name="oum_max_image_filesize"
                                                                                           id="oum_max_image_filesize"
                                                                                           value="<?php echo esc_attr($oum_max_image_filesize); ?>">MB
                                    </div>
                                </div>
                                <br><br>
                            </td>
                        </tr>


                        <tr style="vertical-align: top;" class="oum-gopro-tr">
                            <th scope="row">
                                <?php echo __('Max upload size', 'open-user-map'); ?>
                            </th>
                            <td>
                                <div class="oum_2cols">
                                    <div>
                                        <strong><?php echo __('Image'); ?>:</strong><br>
                                        <label>
                                            <input disabled class="small-text" type="number" min="1" value="10">
                                        </label>MB
                                    </div>
                                </div>
                                <br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_user_notification = get_option('oum_enable_user_notification');
                            $oum_user_notification_label = get_option('oum_user_notification_label');
                            $oum_user_notification_subject = get_option('oum_user_notification_subject') ? get_option('oum_user_notification_subject') : __('Your location has been approved', 'open-user-map');
                            $oum_user_notification_message = get_option('oum_user_notification_message') ? get_option('oum_user_notification_message') : __('Hey %name%! Your location proposal on %website_url% has been published!', 'open-user-map');
                            ?>
                            <th scope="row">
                                <?php echo __('User email notification', 'open-user-map'); ?>
                                <br><br>
                            </th>
                            <td>
                                <input class="oum-switch" type="checkbox" id="oum_enable_user_notification"
                                       name="oum_enable_user_notification" <?php echo ($oum_enable_user_notification == 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_user_notification"><?php echo __('Enable'); ?></label><br><br>

                                <strong><?php echo __('Custom Label:', 'open-user-map'); ?></strong><br>
                                <label for="oum_user_notification_label"></label><input class="regular-text"
                                                                                        type="text"
                                                                                        name="oum_user_notification_label"
                                                                                        id="oum_user_notification_label"
                                                                                        placeholder="<?php echo esc_attr($this->oum_user_notification_label_default); ?>"
                                                                                        value="<?php echo esc_textarea($oum_user_notification_label); ?>">
                                <br><br>

                                <strong><?php echo __('Subject'); ?>:</strong><br>
                                <label for="oum_user_notification_subject"></label><input class="regular-text"
                                                                                          type="text"
                                                                                          name="oum_user_notification_subject"
                                                                                          id="oum_user_notification_subject"
                                                                                          value="<?php echo esc_textarea($oum_user_notification_subject); ?>"><br><br>

                                <strong><?php echo __('Message'); ?>:</strong><br>
                                <label for="oum_user_notification_message"></label><textarea
                                        class="regular-text" name="oum_user_notification_message"
                                        id="oum_user_notification_message" rows="8"
                                        cols="50"><?php echo esc_textarea($oum_user_notification_message); ?></textarea><br>
                                <span class="description"><?php echo __('Available tags'); ?>: %name%, %website_url%, %website_name%</span>
                                <br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;" class="oum-gopro-tr">
                            <th scope="row">
                                <?php echo __('User email notification', 'open-user-map'); ?>
                            </th>
                            <td>
                                <label>
                                    <input class="oum-switch" type="checkbox" disabled>
                                </label>
                                <label></label><br><br>

                                <strong><?php echo __('Subject'); ?>:</strong><br>
                                <label>
                                    <input disabled class="regular-text" type="text"
                                           placeholder="<?php echo __('Your location has been approved', 'open-user-map'); ?>">
                                </label><br><br>

                                <strong><?php echo __('Message'); ?>:</strong><br>
                                <label>
<textarea disabled class="regular-text" rows="8" cols="50"
          placeholder="<?php echo __('Hey %name%! Your location proposal on %website_url% has been published!', 'open-user-map'); ?>"></textarea>
                                </label><br><br>
                                <span class="description"><?php echo __('Available tags'); ?>: %name%, %website_url%, %website_name%</span>
                                <br><br>
                            </td>
                        </tr>

                        <?php
                        $oum_submit_button_label = get_option('oum_submit_button_label');
                        ?>
                        <tr style="vertical-align: top;">
                            <th scope="row"><?php echo __('"Submit" Button text', 'open-user-map'); ?></th>
                            <td>
                                <label for="oum_submit_button_label"></label><input class="regular-text" type="text"
                                                                                    name="oum_submit_button_label"
                                                                                    id="oum_submit_button_label"
                                                                                    placeholder="<?php echo __('Submit location for review', 'open-user-map'); ?>"
                                                                                    value="<?php echo esc_textarea($oum_submit_button_label); ?>"><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row"><?php echo __('Action after submit', 'open-user-map'); ?></th>
                            <td>
                                <label for="oum_action_after_submit"></label><select name="oum_action_after_submit"
                                                                                     id="oum_action_after_submit">
                                    <?php
                                    $oum_action_after_submit = get_option('oum_action_after_submit') ? get_option('oum_action_after_submit') : 'text';
                                    $items = array(
                                        'text' => __('Display message', 'open-user-map'),
                                        'refresh' => __('Refresh', 'open-user-map'),
                                        'redirect' => __('Redirect', 'open-user-map')
                                    );

                                    foreach ($items as $val => $label) {
                                        $selected = ($oum_action_after_submit == $val) ? 'selected' : '';
                                        echo '<option value="' . esc_textarea($val) . '" ' . $selected . '>' . esc_textarea($label) . '</option>';
                                    }
                                    ?>
                                </select>
                                <br><br>
                                <div id="oum_action_after_submit_text">
                                    <?php
                                    $oum_thankyou_headline = get_option('oum_thankyou_headline');
                                    $oum_thankyou_text = get_option('oum_thankyou_text');
                                    ?>
                                    <label for="oum_thankyou_headline"></label><input class="regular-text" type="text"
                                                                                      name="oum_thankyou_headline"
                                                                                      id="oum_thankyou_headline"
                                                                                      placeholder="<?php echo __('Thank you!', 'open-user-map'); ?>"
                                                                                      value="<?php echo esc_textarea($oum_thankyou_headline); ?>"><br><br>
                                    <label for="oum_thankyou_text"></label><textarea class="regular-text"
                                                                                     name="oum_thankyou_text"
                                                                                     id="oum_thankyou_text"
                                                                                     rows="4" cols="50"
                                                                                     placeholder="<?php echo __('We will check your location suggestion and release it as soon as possible.', 'open-user-map'); ?>"><?php echo esc_textarea($oum_thankyou_text); ?></textarea><br><br>
                                </div>
                                <div id="oum_action_after_submit_redirect">
                                    <?php
                                    $oum_thankyou_redirect = get_option('oum_thankyou_redirect');
                                    ?>
                                    <label for="oum_thankyou_redirect"></label><input class="regular-text" type="text"
                                                                                      name="oum_thankyou_redirect"
                                                                                      id="oum_thankyou_redirect"
                                                                                      placeholder="<?php echo 'https://redirectlink.here'; ?>"
                                                                                      value="<?php echo esc_textarea($oum_thankyou_redirect); ?>">
                                </div>
                            </td>
                        </tr>

                    </table>

                </div>

                <div id="tab-3" class="tab-pane">
                    <table class="form-table">
                        <tr style="vertical-align: top;">
                            <th scope="row"><?php echo __('Searchbar', 'open-user-map'); ?></th>
                            <td>
                                <?php
                                $oum_enable_searchbar = get_option('oum_enable_searchbar', 'on');
                                ?>
                                <input class="oum-switch" type="checkbox" name="oum_enable_searchbar"
                                       id="oum_enable_searchbar" <?php echo ($oum_enable_searchbar === 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_searchbar"></label><br><br>
                                <div class="wrap-searchbar-settings">
                                    <?php
                                    $oum_searchbar_type = get_option('oum_searchbar_type') ?: 'address';
                                    $items = $this->oum_searchbar_types; // Bevat nu alle opties, inclusief wat eerder pro was
                                    ?>

                                    <div id="oum_searchbar_type_options">
                                        <?php foreach ($items as $val => $label):
                                            $checked = ($oum_searchbar_type == $val) ? 'checked' : '';
                                            ?>
                                            <label>
                                                <input type="radio" name="oum_searchbar_type"
                                                       value="<?php echo esc_attr($val); ?>" <?php echo esc_attr($checked); ?>>
                                                <strong><?php echo esc_html($label); ?></strong>
                                                <br>
                                                <?php
                                                $descriptions = [
                                                    'address' => __('Find a specific address – type to see matching suggestions below and locate them on the map.', 'open-user-map'),
                                                    'markers' => __('Search for specific markers and see suggestions below as you type.', 'open-user-map'),
                                                    'live_filter' => __('Filter markers live as you type to instantly refine the map view.', 'open-user-map'),
                                                ];
                                                if (isset($descriptions[$val])): ?>
                                                    <small><?php echo esc_html($descriptions[$val]); ?></small>
                                                <?php endif; ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row"><?php echo __('Searchbar', 'open-user-map'); ?></th>
                            <td>
                                <?php
                                $oum_enable_searchbar = get_option('oum_enable_searchbar', 'on');
                                ?>
                                <input class="oum-switch" type="checkbox" name="oum_enable_searchbar"
                                       id="oum_enable_searchbar" <?php echo ($oum_enable_searchbar === 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_searchbar"></label><br><br>
                                <div class="wrap-searchbar-settings">
                                    <?php
                                    $oum_searchbar_type = get_option('oum_searchbar_type') ? get_option('oum_searchbar_type') : 'address';
                                    $items = $this->oum_searchbar_types;
                                    ?>

                                    <div id="oum_searchbar_type_options">
                                        <?php foreach ($items as $val => $label):
                                            $checked = ($oum_searchbar_type == $val) ? 'checked' : '';
                                            ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('"Search for Address (Geosearch)" Button', 'open-user-map'); ?>
                            </th>
                            <td>
                                <?php
                                $oum_enable_searchaddress_button = get_option('oum_enable_searchaddress_button', 'on');
                                $oum_searchaddress_label = get_option('oum_searchaddress_label');
                                ?>
                                <input class="oum-switch" type="checkbox" name="oum_enable_searchaddress_button"
                                       id="oum_enable_searchaddress_button" <?php echo ($oum_enable_searchaddress_button === 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_searchaddress_button"></label><br><br>
                                <strong><?php echo __('Custom Label:', 'open-user-map'); ?></strong><br>
                                <label for="oum_searchaddress_label"></label><input class="regular-text" type="text"
                                                                                    name="oum_searchaddress_label"
                                                                                    id="oum_searchaddress_label"
                                                                                    placeholder="<?php echo esc_attr($this->oum_searchaddress_label_default); ?>"
                                                                                    value="<?php echo esc_attr($oum_searchaddress_label); ?>">
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('"Search for location markers" Button', 'open-user-map'); ?>
                            </th>
                            <td>
                                <?php
                                $oum_enable_searchmarkers_button = get_option('oum_enable_searchmarkers_button', 'on');
                                $oum_searchmarkers_label = get_option('oum_searchmarkers_label');
                                $oum_searchmarkers_zoom = get_option('oum_searchmarkers_zoom');
                                ?>
                                <input class="oum-switch" type="checkbox" name="oum_enable_searchmarkers_button"
                                       id="oum_enable_searchmarkers_button" <?php echo ($oum_enable_searchmarkers_button === 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_searchmarkers_button"></label><br><br>
                                <strong><?php echo __('Custom Label:', 'open-user-map'); ?></strong><br>
                                <label for="oum_searchmarkers_label"></label><input class="regular-text" type="text"
                                                                                    name="oum_searchmarkers_label"
                                                                                    id="oum_searchmarkers_label"
                                                                                    placeholder="<?php echo esc_attr($this->oum_searchmarkers_label_default); ?>"
                                                                                    value="<?php echo esc_attr($oum_searchmarkers_label); ?>"><br><br>
                                <strong><?php echo __('Zoom level:', 'open-user-map'); ?></strong><br>
                                <label for="oum_searchmarkers_zoom"></label><input class="small-text" type="number"
                                                                                   min="1" max="19"
                                                                                   name="oum_searchmarkers_zoom"
                                                                                   id="oum_searchmarkers_zoom"
                                                                                   placeholder="<?php echo esc_attr($this->oum_searchmarkers_zoom_default); ?>"
                                                                                   value="<?php echo esc_attr($oum_searchmarkers_zoom); ?>"><br><br>
                                <span class="description"><?php echo __('Set a value between 1 (far away) and 19 (very close).', 'open-user-map'); ?></span><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_marker_types = get_option('oum_enable_marker_types');
                            $oum_enable_empty_marker_type = get_option('oum_enable_empty_marker_type', true);
                            $oum_enable_multiple_marker_types = get_option('oum_enable_multiple_marker_types', false);
                            $oum_collapse_filter = get_option('oum_collapse_filter');
                            $oum_marker_types_label = get_option('oum_marker_types_label') ? get_option('oum_marker_types_label') : $this->oum_marker_types_label_default;
                            ?>
                            <th scope="row">
                                <?php echo __('Marker Categories', 'open-user-map'); ?>
                                <br><br>
                            </th>
                            <td>
                                <input class="oum-switch" type="checkbox" name="oum_enable_marker_types"
                                       id="oum_enable_marker_types" <?php echo ($oum_enable_marker_types) ? 'checked' : ''; ?>>
                                <label for="oum_enable_marker_types"><?php echo __('Enable', 'open-user-map'); ?></label><br>
                                <?php if ($oum_enable_marker_types): ?>
                                    <div class="description"><?php echo __('You can manage Marker Categories <a href="edit-tags.php?taxonomy=oum-type&post_type=oum-location">here</a>', 'open-user-map'); ?></div>
                                    <br>
                                <?php endif; ?>
                                <br>

                                <div class="wrap-marker-categories-settings">
                                    <strong><?php echo __('Custom Label:', 'open-user-map'); ?></strong><br>
                                    <label for="oum_marker_types_label"></label><input class="regular-text"
                                                                                       type="text"
                                                                                       name="oum_marker_types_label"
                                                                                       id="oum_marker_types_label"
                                                                                       placeholder="<?php echo esc_attr($this->oum_marker_types_label_default); ?>"
                                                                                       value="<?php echo esc_attr($oum_marker_types_label); ?>">
                                    <br><br><br>

                                    <input class="oum-switch" type="checkbox"
                                           name="oum_enable_multiple_marker_types"
                                           id="oum_enable_multiple_marker_types" <?php echo ($oum_enable_multiple_marker_types) ? 'checked' : ''; ?>>
                                    <label for="oum_enable_multiple_marker_types"><?php echo __('Allow multiple selections', 'open-user-map'); ?></label><br>
                                    <div class="description"><?php echo __('<strong>Important:</strong> If enabled all locations will fallback to the <a href="edit.php?post_type=oum-location&page=open-user-map-settings">Default Marker Icon</a> instead of a specific category icon.', 'open-user-map'); ?></div>
                                    <br><br><br>

                                    <input class="oum-switch" type="checkbox"
                                           name="oum_enable_empty_marker_type"
                                           id="oum_enable_empty_marker_type" <?php echo ($oum_enable_empty_marker_type) ? 'checked' : ''; ?>>
                                    <label for="oum_enable_empty_marker_type"><?php echo __('Allow empty selection', 'open-user-map'); ?></label>
                                    <br><br><br>

                                    <input class="oum-switch" type="checkbox" name="oum_collapse_filter"
                                           id="oum_collapse_filter" <?php echo ($oum_collapse_filter) ? 'checked' : ''; ?>>
                                    <label for="oum_collapse_filter"><?php echo __('Collapsed Filterbox', 'open-user-map'); ?></label><br>
                                    <div class="description"><?php echo __('If enabled the filterbox will take less space and just open on mouseover.', 'open-user-map'); ?></div>
                                </div>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;" class="oum-gopro-tr">
                            <th scope="row">
                                <?php echo __('Filterbox', 'open-user-map'); ?>
                            </th>
                            <td>
                                <label>
                                    <input class="oum-switch" type="checkbox" disabled>
                                </label>
                                <label><?php echo __('Collapsed design', 'open-user-map'); ?></label>
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
                            $oum_enable_regions = get_option('oum_enable_regions');
                            ?>
                            <th scope="row">
                                <?php echo __('Enable', 'open-user-map'); ?>
                            </th>
                            <td>
                                <input class="oum-switch" type="checkbox" name="oum_enable_regions"
                                       id="oum_enable_regions" <?php echo ($oum_enable_regions) ? 'checked' : ''; ?>>
                                <label for="oum_enable_regions"></label><br><br>

                                <?php if ($oum_enable_regions): ?>
                                    <div class="description"><?php echo __('You can manage Regions <a href="edit-tags.php?taxonomy=oum-region&post_type=oum-location">here</a>', 'open-user-map'); ?></div>
                                    <br>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr style="vertical-align: top;">
                            <?php
                            $oum_regions_layout_style = get_option('oum_regions_layout_style', 'layout-1');
                            $items = $this->oum_regions_layout_styles;
                            ?>
                            <th scope="row">
                                <?php echo __('Layout', 'open-user-map'); ?>
                            </th>
                            <td>
                                <?php
                                echo "<select id='oum_regions_layout_style' name='oum_regions_layout_style'>";
                                foreach ($items as $value => $label) {
                                    $selected = ($oum_regions_layout_style == $value) ? 'selected="selected"' : '';
                                    echo '<option value="' . esc_textarea($value) . '" ' . $selected . '>' . esc_textarea($label) . '</option>';
                                }
                                echo "</select>";
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>

                <div id="tab-5" class="tab-pane">
                    <table class="form-table">
                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_location_date = get_option('oum_enable_location_date');
                            ?>
                            <th scope="row">
                                <?php echo __('Show location date', 'open-user-map'); ?>
                            </th>
                            <td>
                                <input class="oum-switch" type="checkbox" id="oum_enable_location_date"
                                       name="oum_enable_location_date" <?php echo ($oum_enable_location_date == 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_location_date"></label><br><br>
                                <span class="description"><?php echo __('Displays the date when the location was modified or published inside the location bubble.', 'open-user-map'); ?></span><br>
                                <br>
                                <?php
                                $oum_location_date_type = get_option('oum_location_date_type', 'modified');
                                $items = array(
                                    'modified' => __('Date of Last Modification', 'open-user-map'),
                                    'created' => __('Publishing Date', 'open-user-map')
                                );
                                echo "<select id='oum_location_date_type' name='oum_location_date_type'>";
                                foreach ($items as $value => $label) {
                                    $selected = ($oum_location_date_type == $value) ? 'selected="selected"' : '';
                                    echo '<option value="' . esc_textarea($value) . '" ' . $selected . '>' . esc_textarea($label) . '</option>';
                                }
                                echo "</select>";
                                ?>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_single_page = get_option('oum_enable_single_page');
                            ?>
                            <th scope="row">
                                <?php echo __('Public pages for locations (Single pages)', 'open-user-map'); ?>
                                <br><br>
                            </th>
                            <td>
                                <input class="oum-switch" type="checkbox" id="oum_enable_single_page"
                                       name="oum_enable_single_page" <?php echo ($oum_enable_single_page == 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_single_page"></label><br><br>
                                <span class="description"><?php echo __('This will add a "Read more"-Button to the location bubble. It will link to the location\'s single page.', 'open-user-map'); ?></span><br>
                                <span class="description"><?php echo __('In the backend on the "Edit location" page an additional content editor will become available. You can use shortcodes to display individual values of a location. <strong>See the Help section for details.</strong>', 'open-user-map'); ?></span><br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_user_restriction = get_option('oum_enable_user_restriction');
                            $oum_enable_redirect_to_registration = get_option('oum_enable_redirect_to_registration');
                            ?>
                            <th scope="row">
                                <?php echo __('Restrict "Add location" to logged in users only', 'open-user-map'); ?>
                                <br><br>
                            </th>
                            <td>
                                <input class="oum-switch" type="checkbox" id="oum_enable_user_restriction"
                                       name="oum_enable_user_restriction" <?php echo ($oum_enable_user_restriction == 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_user_restriction"></label><br><br>
                                <span class="description"><?php echo __('If enabled, only registered users can add new locations. The minimum required role is "Subscriber". <a target="_blank" href="https://www.open-user-map.com/knowledge-base/redirect-users-to-the-map-after-login/?ref=settings">Here</a> is an article on how to redirect users to the map page after login.', 'open-user-map'); ?></span><br><br>
                                <div id="redirect_to_registration">
                                    <input class="oum-switch" type="checkbox"
                                           id="oum_enable_redirect_to_registration"
                                           name="oum_enable_redirect_to_registration" <?php echo ($oum_enable_redirect_to_registration == 'on') ? 'checked' : ''; ?>>
                                    <label for="oum_enable_redirect_to_registration"><?php echo __('Redirect "Add location"-Button to registration page'); ?></label><br><br>
                                </div>
                            </td>
                        </tr>
                        <tr style="vertical-align: top;" class="oum-gopro-tr">
                            <th scope="row">
                                <?php echo __('Restrict "Add location" to logged in users only', 'open-user-map'); ?>
                            </th>
                            <td>
                                <label>
                                    <input class="oum-switch" type="checkbox" disabled>
                                </label>
                                <label></label><br><br>
                                <label>
                                    <input class="oum-switch" type="checkbox" disabled>
                                </label>
                                <label><?php echo __('Redirect "Add location"-Button to registration page', 'open-user-map'); ?></label><br><br>
                            </td>
                        </tr>
                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_auto_publish = get_option('oum_enable_auto_publish');
                            ?>
                            <th scope="row">
                                <?php echo __('Auto-Publish for registered users', 'open-user-map'); ?>
                                <br><br>
                            </th>
                            <td>
                                <input class="oum-switch" type="checkbox" id="oum_enable_auto_publish"
                                       name="oum_enable_auto_publish" <?php echo ($oum_enable_auto_publish == 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_auto_publish"></label><br><br>
                                <span class="description"><?php echo __('This works only for users with "edit posts" capabilities.', 'open-user-map'); ?></span><br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;" class="oum-gopro-tr">
                            <th scope="row">
                                <?php echo __('Auto-Publish for registered users', 'open-user-map'); ?>
                            </th>
                            <td>
                                <label>
                                    <input class="oum-switch" type="checkbox" disabled>
                                </label>
                                <label></label><br><br>
                                <span class="description"><?php echo __('This works only for users with "edit posts" capabilities.', 'open-user-map'); ?></span><br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_auto_publish_for_everyone = get_option('oum_enable_auto_publish_for_everyone');
                            ?>
                            <th scope="row">
                                <?php echo __('Auto-Publish for unregistered users', 'open-user-map'); ?>
                                <br><br>
                            </th>
                            <td>
                                <input class="oum-switch" type="checkbox"
                                       id="oum_enable_auto_publish_for_everyone"
                                       name="oum_enable_auto_publish_for_everyone" <?php echo ($oum_enable_auto_publish_for_everyone == 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_auto_publish_for_everyone"></label><br><br>
                                <span class="description"><strong><?php echo __('USE WITH CAUTION!', 'open-user-map'); ?></strong> <?php echo __('Every location proposal will be published directly without your verification. No user registration is necessary.', 'open-user-map'); ?></span><br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;" class="oum-gopro-tr">
                            <th scope="row">
                                <?php echo __('Auto-Publish for unregistered users', 'open-user-map'); ?>
                            </th>
                            <td>
                                <label>
                                    <input class="oum-switch" type="checkbox" disabled>
                                </label>
                                <label></label><br><br>
                                <span class="description"><strong><?php echo __('USE WITH CAUTION!', 'open-user-map'); ?></strong> <?php echo __('Every location proposal will be published directly without your verification. No user registration is necessary.', 'open-user-map'); ?></span><br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_add_user_location = get_option('oum_enable_add_user_location');
                            ?>
                            <th scope="row">
                                <?php echo __('Extend WordPress user registration form with "Add location" map', 'open-user-map'); ?>
                                <br><br>
                            </th>
                            <td>
                                <input class="oum-switch" type="checkbox" id="oum_enable_add_user_location"
                                       name="oum_enable_add_user_location" <?php echo ($oum_enable_add_user_location == 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_add_user_location"></label><br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;" class="oum-gopro-tr">
                            <th scope="row">
                                <?php echo __('Extend WordPress user registration form with "Add location" map', 'open-user-map'); ?>
                            </th>
                            <td>
                                <label>
                                    <input class="oum-switch" type="checkbox" disabled>
                                </label>
                                <label></label>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_admin_notification = get_option('oum_enable_admin_notification');
                            $oum_admin_notification_email = get_option('oum_admin_notification_email') ? get_option('oum_admin_notification_email') : get_option('admin_email');
                            $oum_admin_notification_subject = get_option('oum_admin_notification_subject') ? get_option('oum_admin_notification_subject') : __('New Open User Map location', 'open-user-map');
                            $oum_admin_notification_message = get_option('oum_admin_notification_message') ? get_option('oum_admin_notification_message') : __('A new location with the title "%title%" on %website_url% has been added! Please verify and publish or use the "auto-publish" feature.', 'open-user-map');
                            ?>
                            <th scope="row">
                                <?php echo __('Admin email notification on new location proposals', 'open-user-map'); ?>
                                <br><br>
                            </th>
                            <td>
                                <input class="oum-switch" type="checkbox" id="oum_enable_admin_notification"
                                       name="oum_enable_admin_notification" <?php echo ($oum_enable_admin_notification == 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_admin_notification"></label><br><br>

                                <strong><?php echo __('Email address'); ?>:</strong><br>
                                <label for="oum_admin_notification_email"></label><input class="regular-text"
                                                                                         type="text"
                                                                                         name="oum_admin_notification_email"
                                                                                         id="oum_admin_notification_email"
                                                                                         value="<?php echo esc_textarea($oum_admin_notification_email); ?>"><br><br>

                                <strong><?php echo __('Subject'); ?>:</strong><br>
                                <label for="oum_admin_notification_subject"></label><input class="regular-text"
                                                                                           type="text"
                                                                                           name="oum_admin_notification_subject"
                                                                                           id="oum_admin_notification_subject"
                                                                                           value="<?php echo esc_textarea($oum_admin_notification_subject); ?>"><br><br>

                                <strong><?php echo __('Message'); ?>:</strong><br>
                                <label for="oum_admin_notification_message"></label><textarea
                                        class="regular-text" name="oum_admin_notification_message"
                                        id="oum_admin_notification_message" rows="8"
                                        cols="50"><?php echo esc_textarea($oum_admin_notification_message); ?></textarea><br>
                                <span class="description"><?php echo __('Available tags'); ?>: %title%, %website_url%, %website_name%, %edit_location_url%, %user_name%, %user_email%</span>
                                <br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;" class="oum-gopro-tr">
                            <th scope="row">
                                <?php echo __('Admin email notification on new location proposals', 'open-user-map'); ?>
                            </th>
                            <td>
                                <label>
                                    <input class="oum-switch" type="checkbox" disabled>
                                </label>
                                <label></label><br><br>

                                <strong><?php echo __('Email address'); ?>:</strong><br>
                                <label>
                                    <input disabled class="regular-text" type="text"
                                           placeholder="<?php echo __('john@doe.com', 'open-user-map'); ?>">
                                </label><br><br>

                                <strong><?php echo __('Subject'); ?>:</strong><br>
                                <label>
                                    <input disabled class="regular-text" type="text"
                                           placeholder="<?php echo __('New Open User Map location', 'open-user-map'); ?>">
                                </label><br><br>

                                <strong><?php echo __('Message'); ?>:</strong><br>
                                <label>
<textarea disabled class="regular-text" rows="8" cols="50"
          placeholder="<?php echo __('A new location with the title "%title%" on %website_url% has been added! Please verify and publish or use the "auto-publish" feature. \n\n %edit_location_url%', 'open-user-map'); ?>"></textarea>
                                </label><br><br>
                                <span class="description"><?php echo __('Available tags'); ?>: %title%, %website_url%, %website_name%, %edit_location_url%, %user_name%, %user_email%</span>
                                <br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_enable_webhook_notification = get_option('oum_enable_webhook_notification');
                            $oum_webhook_notification_url = get_option('oum_webhook_notification_url');
                            ?>
                            <th scope="row">
                                <?php echo __('Trigger Webhook on new or updated Locations', 'open-user-map'); ?>
                                <br><br>
                            </th>
                            <td>
                                <input class="oum-switch" type="checkbox" id="oum_enable_webhook_notification"
                                       name="oum_enable_webhook_notification" <?php echo ($oum_enable_webhook_notification == 'on') ? 'checked' : ''; ?>>
                                <label for="oum_enable_webhook_notification"></label><br><br>

                                <strong><?php echo __('Webhook URL'); ?>:</strong><br>
                                <label for="oum_webhook_notification_url"></label><input class="regular-text"
                                                                                         type="text"
                                                                                         name="oum_webhook_notification_url"
                                                                                         id="oum_webhook_notification_url"
                                                                                         value="<?php echo esc_url($oum_webhook_notification_url); ?>">

                            </td>
                        </tr>

                        <tr style="vertical-align: top;" class="oum-gopro-tr">
                            <th scope="row">
                                <?php echo __('Trigger Webhook on new or updated Locations', 'open-user-map'); ?>
                            </th>
                            <td>
                                <label>
                                    <input class="oum-switch" type="checkbox" disabled>
                                </label>
                                <label></label><br><br>

                                <strong><?php echo __('Webhook URL'); ?>:</strong><br>
                                <label>
                                    <input disabled class="regular-text" type="text">
                                </label>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <?php
                            $oum_custom_js = get_option('oum_custom_js');
                            ?>
                            <th scope="row">
                                <?php echo __('Custom JS', 'open-user-map'); ?>
                            </th>
                            <td>
                                <strong><?php echo __('This JS code will be executed after the map has been loaded:'); ?></strong><br>
                                <label for="oum_custom_js"></label><textarea class="regular-text" name="oum_custom_js"
                                                                             id="oum_custom_js" rows="8"
                                                                             cols="50"
                                                                             placeholder="<?php echo __("e.g. console.log('The map is ready')", "open-user-map"); ?>"><?php echo $oum_custom_js; ?></textarea><br><br>
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
                                <?php echo __('Export all Locations', 'open-user-map'); ?>
                                <br><br>
                            </th>
                            <td>
                                <button class="oum_export_csv_button button button-secondary"><?php echo __('Export to CSV', 'open-user-map'); ?></button>
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
                        <tr style="vertical-align: top;" class="oum-gopro-tr">
                            <th scope="row">
                                <?php echo __('Export all Locations', 'open-user-map'); ?>
                            </th>
                            <td>
                                <button disabled
                                        class="button button-secondary"><?php echo __('Export to CSV', 'open-user-map'); ?></button>
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
                                <?php echo __('Import all Locations', 'open-user-map'); ?>
                                <br><br>
                            </th>
                            <td>
                                <div class="csv_upload">
                                    <button class="oum_upload_csv_button button button-secondary"><?php echo __('Upload CSV & Import', 'open-user-map'); ?></button>
                                    <br><br>
                                    <div class="description">
                                        <strong>This is important to make the import work:</strong><br>
                                        <ul>
                                            <li>Be patient, this can take a while.</li>
                                            <li>Be aware that every location with matching POST ID will be
                                                overwritten. <span style="color: red">Consider creating a DB Backup before!</span>
                                            </li>
                                            <li>To import new locations leave values in the post_id column
                                                empty
                                            </li>
                                            <li>Download an Export file first and use it as template for your
                                                import
                                            </li>
                                            <li>Comma or Semicolon work as delimiter</li>
                                            <li>Non-existing Marker Categories will be created automatically
                                            </li>
                                            <li>Multiselect values need to be written like so: Red|Green|Blue
                                            </li>
                                            <li>All imported locations will have status "Draft". You need to
                                                publish them yourself.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr style="vertical-align: top;" class="oum-gopro-tr">
                            <th scope="row">
                                <?php echo __('Import all Locations', 'open-user-map'); ?>
                            </th>
                            <td>
                                <div class="csv_upload">
                                    <button disabled
                                            class="button button-secondary"><?php echo __('Upload CSV & Import', 'open-user-map'); ?></button>
                                    <br><br>
                                    <div class="description">
                                        <strong>This is important to make the import work:</strong><br>
                                        <ul>
                                            <li>Be patient, this can take a while.</li>
                                            <li>Be aware that every location with matching POST ID will be
                                                overwritten. <span style="color: red">Consider creating a DB Backup before!</span>
                                            </li>
                                            <li>To import new locations leave values in the post_id column empty
                                            </li>
                                            <li>Download an Export file first and use it as template for your
                                                import
                                            </li>
                                            <li>Comma or Semicolon work as delimiter</li>
                                            <li>Non-existing Marker Categories will be created automatically</li>
                                            <li>Multiselect values need to be written like so: Red|Green|Blue</li>
                                            <li>All imported locations will have status "Draft". You need to publish
                                                them yourself.
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
                                <?php echo __('🚀 Getting started', 'open-user-map'); ?>
                            </th>
                            <td class="top-padding-20">
                                <?php echo sprintf(__('<ol><li>Use the page editor or Elementor to insert the <b>"Open User Map"</b> block onto a page. Alternatively, you can use the shortcode <code>[open-user-map]</code></li><li>You can <a href="%s">manage Locations</a> under <i>Open User Map > All Locations</i></li><li><a href="%s">Customize</a> styles and features under <i>Open User Map > Settings</i></li></ol>', 'open-user-map'), 'edit.php?post_type=oum-location', 'edit.php?post_type=oum-location&page=open-user-map-settings'); ?>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('Gutenberg Block', 'open-user-map'); ?>
                            </th>
                            <td class="top-padding-20">
                                <?php echo __('Use the "Open User Map" block to integrate the map inside your page. <br>You can set custom map position and filter for categories and locations inside the block settings.', 'open-user-map'); ?>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('Elementor Widget', 'open-user-map'); ?>
                            </th>
                            <td class="top-padding-20">
                                <?php echo __('Use the Elementor Widget "Open User Map" to integrate the map inside your page. <br>You can set custom map position and filter for categories and locations inside the widget settings.', 'open-user-map'); ?>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row"><?php echo __('Place the shortcode anywhere in your content or integrate it within your theme template with PHP', 'open-user-map'); ?></th>
                            <td class="top-padding-20">
                                <strong>Shortcode:</strong><br><br>
                                <code>[open-user-map]</code> or with PHP <code>&lt;?php echo
                                    do_shortcode('[open-user-map]'); ?&gt;</code><br><br>
                                <p class="hint"><?php echo __('Displays the Map with all locations.', 'open-user-map'); ?></p>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row"><?php echo __('Shortcode attributes', 'open-user-map'); ?></th>
                            <td class="top-padding-20">
                                <p class="hint"><?php echo __('You can use shortcode attributes to override the <a href="edit.php?post_type=oum-location&page=open-user-map-settings">global settings</a>. This allows for custom individual maps.', 'open-user-map'); ?></p>

                                <div class="oum-shortcode-docs">
                                    <!-- Group 1: Map Position & View -->
                                    <h4><?php echo __('Map Position & View', 'open-user-map'); ?></h4>
                                    <table class="widefat oum-attribute-table">
                                        <thead>
                                        <tr>
                                            <th><?php echo __('Attribute', 'open-user-map'); ?></th>
                                            <th><?php echo __('Values/Example', 'open-user-map'); ?></th>
                                            <th><?php echo __('Description', 'open-user-map'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><code>lat</code>, <code>long</code>, <code>zoom</code></td>
                                            <td><code>lat="51.50665" long="-0.12752" zoom="13"</code></td>
                                            <td><?php echo __('Set an individual map position with latitude, longitude and zoom level.', 'open-user-map'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><code>region</code></td>
                                            <td><code>region="Europe"</code></td>
                                            <td><?php echo __('Pre-select a region.', 'open-user-map'); ?><?php echo __('This works only if you enabled the regions feature in the settings.', 'open-user-map'); ?></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <!-- Group 2: Content Filtering -->
                                    <h4><?php echo __('Content Filtering', 'open-user-map'); ?></h4>
                                    <table class="widefat oum-attribute-table">
                                        <thead>
                                        <tr>
                                            <th><?php echo __('Attribute', 'open-user-map'); ?></th>
                                            <th><?php echo __('Values/Example', 'open-user-map'); ?></th>
                                            <th><?php echo __('Description', 'open-user-map'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><code>types</code></td>
                                            <td>
                                                <code>types="food"</code><br>
                                                <code>types="food|drinks|hotel"</code>
                                            </td>
                                            <td><?php echo __('Filter locations by types (Marker Categories). Separate multiple types with a | symbol.', 'open-user-map'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><code>ids</code></td>
                                            <td>
                                                <code>ids="123"</code><br>
                                                <code>ids="123|456|789"</code>
                                            </td>
                                            <td><?php echo __('Filter locations by Post ID. Separate multiple IDs with a | symbol.', 'open-user-map'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><code>user</code></td>
                                            <td>
                                                <code>user="current"</code><br>
                                                <code>user="123"</code><br>
                                                <code>user="role:subscriber"</code>
                                            </td>
                                            <td><?php echo __('Filter locations by user. Use "current" to show only locations from the currently logged-in user, a specific user ID, or "role:rolename" to show locations from users with a specific role.', 'open-user-map'); ?></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <!-- Group 3: Display & Appearance -->
                                    <h4><?php echo __('Display & Appearance', 'open-user-map'); ?></h4>
                                    <table class="widefat oum-attribute-table">
                                        <thead>
                                        <tr>
                                            <th><?php echo __('Attribute', 'open-user-map'); ?></th>
                                            <th><?php echo __('Values/Example', 'open-user-map'); ?></th>
                                            <th><?php echo __('Description', 'open-user-map'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><code>size</code></td>
                                            <td>
                                                <code>size="default"</code><br>
                                                <code>size="fullwidth"</code>
                                            </td>
                                            <td><?php echo __('Set a custom size for desktop view.', 'open-user-map'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><code>size_mobile</code></td>
                                            <td>
                                                <code>size_mobile="square"</code><br>
                                                <code>size_mobile="landscape"</code><br>
                                                <code>size_mobile="portrait"</code>
                                            </td>
                                            <td><?php echo __('Set a custom size for mobile view.', 'open-user-map'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><code>height</code></td>
                                            <td><code>height="400px"</code></td>
                                            <td><?php echo __('Set a custom height for desktop view. Don\'t forget to add a unit like <b>px</b>.', 'open-user-map'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><code>height_mobile</code></td>
                                            <td><code>height_mobile="300px"</code></td>
                                            <td><?php echo __('Set a custom height for mobile view. Don\'t forget to add a unit like <b>px</b>.', 'open-user-map'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><code>map_type</code></td>
                                            <td>
                                                <code>map_type="interactive"</code><br>
                                                <code>map_type="simple"</code>
                                            </td>
                                            <td><?php echo __('Enable or disable the "Add location" button.', 'open-user-map'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><code>map_style</code></td>
                                            <td><code>map_style="Esri.WorldStreetMap"</code></td>
                                            <td><?php echo __('Override the map style.', 'open-user-map'); ?></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <!-- Group 4: Features & Controls -->
                                    <h4><?php echo __('Features & Controls', 'open-user-map'); ?></h4>
                                    <table class="widefat oum-attribute-table">
                                        <thead>
                                        <tr>
                                            <th><?php echo __('Attribute', 'open-user-map'); ?></th>
                                            <th><?php echo __('Values/Example', 'open-user-map'); ?></th>
                                            <th><?php echo __('Description', 'open-user-map'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><code>disable_regions</code></td>
                                            <td>
                                                <code>disable_regions="true"</code><br>
                                                <code>disable_regions="false"</code>
                                            </td>
                                            <td><?php echo __('Enable or disable Regions.', 'open-user-map'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><code>enable_cluster</code></td>
                                            <td>
                                                <code>enable_cluster="true"</code><br>
                                                <code>enable_cluster="false"</code>
                                            </td>
                                            <td><?php echo __('Enable or disable Marker Clustering.', 'open-user-map'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><code>enable_searchbar</code></td>
                                            <td>
                                                <code>enable_searchbar="true"</code><br>
                                                <code>enable_searchbar="false"</code>
                                            </td>
                                            <td><?php echo __('Enable or disable the searchbar.', 'open-user-map'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><code>enable_searchaddress_button</code></td>
                                            <td>
                                                <code>enable_searchaddress_button="true"</code><br>
                                                <code>enable_searchaddress_button="false"</code>
                                            </td>
                                            <td><?php echo __('Enable or disable the "Search for Address (Geosearch)" button.', 'open-user-map'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><code>enable_searchmarkers_button</code></td>
                                            <td>
                                                <code>enable_searchmarkers_button="true"</code><br>
                                                <code>enable_searchmarkers_button="false"</code>
                                            </td>
                                            <td><?php echo __('Enable or disable the "Search for Markers" button.', 'open-user-map'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><code>enable_fullscreen</code></td>
                                            <td>
                                                <code>enable_fullscreen="true"</code><br>
                                                <code>enable_fullscreen="false"</code>
                                            </td>
                                            <td><?php echo __('Enable or disable the fullscreen button.', 'open-user-map'); ?></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <!-- Complete Examples -->
                                    <h4><?php echo __('Complete Examples', 'open-user-map'); ?></h4>
                                    <div class="oum-examples">
                                        <p>
                                            <strong><?php echo __('Example 1: Map of London with food locations only', 'open-user-map'); ?></strong>
                                        </p>
                                        <code>[open-user-map lat="51.50665" long="-0.12752" zoom="13" types="food"
                                            size="fullwidth" height="500px"]</code>

                                        <p>
                                            <strong><?php echo __('Example 2: Simple map showing only locations from the current user', 'open-user-map'); ?></strong>
                                        </p>
                                        <code>[open-user-map map_type="simple" user="current" enable_fullscreen="true"
                                            enable_searchbar="false"]</code>

                                        <p>
                                            <strong><?php echo __('Example 3: Interactive map for a specific region with custom appearance', 'open-user-map'); ?></strong>
                                        </p>
                                        <code>[open-user-map region="Europe" map_type="interactive" height="600px"
                                            enable_cluster="false" enable_currentlocation="true"]</code>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row"><?php echo __('Additional Shortcodes', 'open-user-map'); ?></th>
                            <td class="top-padding-20">
                                <code>[open-user-map-location value="Favorite color"
                                    post_id="12345"]</code>
                                <br><br>
                                <span class="hint"><?php echo __('Display specific values from a location. The POST_ID attribute is optional. Alternatively use the PHP function <code>oum_get_location_value( $value, $post_id )</code> in case you just want to return the value.', 'open-user-map'); ?></span>
                                <br><br>
                                <strong><?php echo __('These values are available:', 'open-user-map'); ?></strong>
                                <ul>
                                    <li>title</li>
                                    <li>image</li>
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
                                <br><br>

                                <code>[open-user-map-gallery]</code>
                                <br><br>
                                <span class="hint"><?php echo __('Get a nice gallery view of all the location images. Each image is linked to the location marker on the map.', 'open-user-map'); ?></span>
                                <br><br>
                                <strong><?php echo __('Available attributes:', 'open-user-map'); ?></strong>
                                <ul>
                                    <li><code>url="https://mysite.com/"</code>
                                        - <?php echo __('Link the images to another page.', 'open-user-map'); ?></li>
                                    <li><code>number="10"</code>
                                        - <?php echo __('Limit the number of images displayed.', 'open-user-map'); ?>
                                    </li>
                                    <li><code>user="current"</code>
                                        - <?php echo __('Filter images by user. Accepts "current", a user ID, or "role:rolename".', 'open-user-map'); ?>
                                    </li>
                                </ul>
                                <br><br>

                                <code>[open-user-map-list]</code>
                                <br><br>
                                <span class="hint"><?php echo __('Get a list view of all the locations. The list view is paginated. This number of items per page can be adjusted under <i>Settings > Reading</i>.', 'open-user-map'); ?></span>
                                <br><br>
                                <strong><?php echo __('Available attributes:', 'open-user-map'); ?></strong>
                                <ul>
                                    <li><code>user="current"</code>
                                        - <?php echo __('Filter locations by user. Accepts "current", a user ID, or "role:rolename".', 'open-user-map'); ?>
                                    </li>
                                    <li><code>types="food|drinks"</code>
                                        - <?php echo __('Filter by marker categories. Separate multiple types with a | symbol.', 'open-user-map'); ?>
                                    </li>
                                    <li><code>ids="123|456"</code>
                                        - <?php echo __('Filter by location IDs. Separate multiple IDs with a | symbol.', 'open-user-map'); ?>
                                    </li>
                                </ul>
                                <br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row"><?php echo __('URL parameters', 'open-user-map'); ?></th>
                            <td class="top-padding-20">
                                <code>?markerid=123</code> <span
                                        class="hint"><?php echo __('123 can be the post_id of any public location. Add the parameter to the URL to auto-open a specific location.', 'open-user-map'); ?></span><br><br>
                                <code>?region=Europe</code> <span
                                        class="hint"><?php echo __('Pre-select a region.', 'open-user-map'); ?><?php echo __('This works only if you enabled the regions feature in the settings.', 'open-user-map'); ?></span><br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row"><?php echo __('Conditional Fields (experimental)', 'open-user-map'); ?></th>
                            <td class="top-padding-20">
                                <span class="hint"><?php echo __('Show or Hide a Custom Field based on the selected value of a field.', 'open-user-map'); ?></span><br><br>
                                <strong><?php echo __('Use this Javascript function in your template:', 'open-user-map'); ?></strong><br><br>
                                <code class="block">/**
                                    * OUM: Conditional Field
                                    *
                                    * sourceField Element that defines the condition
                                    * targetField Element to show or hide
                                    * condShow Array of values that lead to show
                                    * condHide Array of values that lead to hide
                                    */
                                    oumConditionalField(sourceField, targetField, condShow, condHide);
                                </code><br><br>
                                <strong><?php echo __('Example:', 'open-user-map'); ?></strong><br><br>
                                <code>
                                    oumConditionalField('[name="oum_marker_icon[]"]',
                                    '[name="oum_location_custom_fields[1645650268221]"]', ['1', '2'], ['3', '']);
                                </code>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('Hooks', 'open-user-map'); ?>
                            </th>
                            <td class="top-padding-20">
                                <span class="hint"><?php echo __('Make use of filter hooks to extend the functionality of the Open User Map plugin.', 'open-user-map'); ?><?php echo __('Find more info on how to use hooks <a href="https://www.open-user-map.com/knowledge-base/change-or-extend-content-of-each-location-bubble/?ref=pluginsettings">here</a>.', 'open-user-map'); ?></span><br><br>
                                <strong><?php echo __('Customize location bubble content:', 'open-user-map'); ?></strong><br><br>
                                <code class="block"><pre>add_filter('oum_location_bubble_content', function ( $content, $location ) {
                                        // extend or change content $content .= 'Post ID: ' . $location['post_id'];
                                        return $content; }, 10, 2);</pre>
                                </code>
                                <br><br><br>

                                <strong><?php echo __('Customize location list item content:', 'open-user-map'); ?></strong><br><br>
                                <pre><code class="block">
                                    add_filter('oum_location_bubble_content', function ($content, $location) {
                                    $content .= 'Post ID: ' . $location['post_id'];
                                    return $content;
                                    }, 10, 2);
                                </code></pre>
                                <br><br><br>
                                <strong><?php echo __('Customize location bubble image (eg. to add a lightbox):', 'open-user-map'); ?></strong><br><br>
                                <code class="block"><pre>add_filter('oum_location_bubble_image', function ( $image, $location ) {
                                    $image = '&lt;a class=&quot;lightbox&quot; href=&quot;' . $location['image'] . '&quot;&gt;' . $image . '&lt;/a&gt;';
                                    return $image;
                                    }, 10, 2);
                                    </pre>
                                </code>
                                <br><br><br>
                            </td>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('Support', 'open-user-map'); ?>
                            </th>
                        </tr>

                        <tr style="vertical-align: top;">
                            <th scope="row">
                                <?php echo __('Debug Info', 'open-user-map'); ?>
                            </th>
                            <td class="top-padding-20">
                                <?php echo __('You can copy & paste this info and send it as email to our support in case we need to debug something:', 'open-user-map'); ?>
                                <br><br>
                                <div class="oum-debug-info">
                                    <ul>
                                        <li>
                                            Plugin: <?php echo get_plugin_data($this->plugin_path . 'open-user-map.php', false)['Name']; ?></li>
                                        <li>Plugin version: <?php echo $this->plugin_version; ?></li>
                                        <li>Server: <?php echo $_SERVER['SERVER_NAME']; ?></li>
                                        <li>Server Software: <?php echo $_SERVER['SERVER_SOFTWARE']; ?></li>
                                        <li>PHP version: <?php echo phpversion(); ?></li>
                                        <li>log_errors: <?php echo ini_get('log_errors'); ?></li>
                                        <li>output_buffering: <?php echo ini_get('output_buffering'); ?></li>
                                        <li>memory_limit: <?php echo ini_get('memory_limit'); ?></li>
                                        <li>upload_max_filesize: <?php echo ini_get('upload_max_filesize'); ?></li>
                                        <li>max_file_uploads: <?php echo ini_get('max_file_uploads'); ?></li>
                                        <li>max_input_vars: <?php echo ini_get('max_input_vars'); ?></li>
                                        <li>post_max_size: <?php echo ini_get('post_max_size'); ?></li>
                                        <li>
                                            <br>
                                            Last PHP error/warning:
                                            <pre><?php print_r(error_get_last()); ?></pre>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php submit_button(); ?>

        <?php endif; ?>
    </form>
</div>
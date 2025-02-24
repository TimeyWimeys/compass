<tr class="term-latlng">
    <?php
    // Set map style
    $map_style = get_option('cbn_map_style') ? get_option('cbn_map_style') : 'Esri.WorldStreetMap';
    $cbn_tile_provider_mapbox_key = get_option('cbn_tile_provider_mapbox_key', '');
    $t_id = $tag->term_id;
    $term_lat = get_term_meta($t_id, 'cbn_lat', true);
    $term_lng = get_term_meta($t_id, 'cbn_lng', true);
    $term_zoom = get_term_meta($t_id, 'cbn_zoom', true);
    ?>
    <th scope="row">
        <label><?php echo __('Adjust Region view', 'Compass'); ?></label>
    </th>
    <td>
        <div class="form-field geo-coordinates-wrap">
            <div class="map-wrap">
                <div id="mapGetRegion" class="leaflet-map map-style_<?php echo esc_attr($map_style); ?>"></div>
            </div>
            <div class="input-wrap">
                <div class="latlng-wrap">
                    <div class="form-field lat-wrap">
                        <label class="meta-label" for="cbn_lat">
                            <?php echo __('Lat', 'Compass'); ?>
                        </label>
                        <input type="text" readonly class="widefat" id="cbn_lat" name="cbn_lat" value="<?php echo esc_attr($term_lat) ? esc_attr($term_lat) : ''; ?>">
                    </div>
                    <div class="form-field lng-wrap">
                        <label class="meta-label" for="cbn_lng">
                            <?php echo __('Lng', 'Compass'); ?>
                        </label>
                        <input type="text" readonly class="widefat" id="cbn_lng" name="cbn_lng" value="<?php echo esc_attr($term_lng) ? esc_attr($term_lng) : ''; ?>">
                    </div>
                    <div class="form-field zoom-wrap">
                        <label class="meta-label" for="cbn_zoom">
                            <?php echo __('Zoom', 'Compass'); ?>
                        </label>
                        <input type="text" readonly class="widefat" id="cbn_zoom" name="cbn_zoom" value="<?php echo esc_attr($term_zoom) ? esc_attr($term_zoom) : ''; ?>">
                    </div>
                </div>

                <div class="geo-coordinates-hint">
                    <strong><?php echo __('How to adjust the Region view:', 'Compass'); ?></strong>
                    <ol>
                    <li><?php echo __('Use the map to find your area of interest', 'Compass'); ?></li>
                    <li><?php echo __('Zoom and pan the map to set the perfect initial view', 'Compass'); ?><br><br><strong><?php echo __('Tip:', 'Compass'); ?></strong> <?php echo __('Hold down the Shift key + mouse to zoom in on an area.', 'Compass'); ?></li>
                </ol>
                </div>
            </div>

            <script type="text/javascript" data-category="functional" class="cmplz-native" id="cbn-inline-js">
            const lat = '<?php echo esc_attr($term_lat) ? esc_attr($term_lat) : '0'; ?>';
            const lng = '<?php echo esc_attr($term_lng) ? esc_attr($term_lng) : '0'; ?>';
            const zoom = '<?php echo esc_attr($term_zoom) ? esc_attr($term_zoom) : '1'; ?>';
            const mapStyle = '<?php echo $map_style; ?>';
            let cbn_tile_provider_mapbox_key = `<?php echo esc_attr($cbn_tile_provider_mapbox_key); ?>`;
            let cbn_geosearch_selected_provider = ``; 
            const cbn_geosearch_provider = `<?php echo get_option('cbn_geosearch_provider') ? get_option('cbn_geosearch_provider') : 'osm'; ?>`;
            const cbn_geosearch_provider_geoapify_key = `<?php echo get_option('cbn_geosearch_provider_geoapify_key', ''); ?>`;
            const cbn_geosearch_provider_here_key = `<?php echo get_option('cbn_geosearch_provider_here_key', ''); ?>`;
            const cbn_geosearch_provider_mapbox_key = `<?php echo get_option('cbn_geosearch_provider_mapbox_key', ''); ?>`;
            const cbn_searchaddress_label = `<?php echo esc_attr(get_option('cbn_searchaddress_label') ? get_option('cbn_searchaddress_label') : $this->cbn_searchaddress_label_default); ?>`;
            </script>

            <?php
            // load map base scripts
            $this->include_map_scripts();

    wp_enqueue_script('cbn_backend_region_js', $this->plugin_url . 'src/js/backend-region.js', array('cbn_leaflet_providers_js', 'cbn_leaflet_markercluster_js', 'cbn_leaflet_subgroups_js', 'cbn_leaflet_geosearch_js', 'cbn_leaflet_locate_js', 'cbn_leaflet_fullscreen_js', 'cbn_leaflet_search_js', 'cbn_leaflet_gesture_js', 'wp-i18n', 'cbn_global_leaflet_js'), $this->plugin_version);
    ?>
            
        </div>
    </td>
</tr>
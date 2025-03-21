<?php require_once oum_get_template('partial-map-init.php'); ?>

<div class="open-user-map">
    <div class="add-user-location">
        <label><?php echo $oum_map_label; ?></label>
        <div class="map-wrap">
            <div id="mapGetLocation" class="leaflet-map map-style_<?php echo $map_style; ?>"></div>
        </div>
        <input type="hidden" id="oum_location_lat" name="oum_location_lat" required
               placeholder="<?php echo __('Latitude', 'open-user-map'); ?>*"/>
        <input type="hidden" id="oum_location_lng" name="oum_location_lng" required
               placeholder="<?php echo __('Longitude', 'open-user-map'); ?>*"/>

        <script type="text/javascript" id="oum-inline-js"
                data-category="functional"
                class="cmplz-native"
                data-minify="0"
                data-no-optimize="1"
                data-no-defer="1"
                data-no-combine="1"
                data-cfasync="false"
                data-pagespeed-no-defer
                data-boot="1">
            var map_el = `map-<?php echo $unique_id; ?>`;

            <?php if($marker_icon == 'user1' && $marker_user_icon): ?>
            var marker_icon_url = `<?php echo esc_url($marker_user_icon); ?>`;
            <?php else: ?>
            var marker_icon_url = `<?php echo esc_url($this->plugin_url); ?>src/leaflet/images/marker-icon_<?php echo esc_attr($marker_icon); ?>-2x.webp`;
            <?php endif; ?>

            var marker_shadow_url = `<?php echo esc_url($this->plugin_url); ?>src/leaflet/images/marker-shadow.webp`;
            var mapStyle = `<?php echo esc_attr($map_style); ?>`;
            var oum_tile_provider_mapbox_key = `<?php echo esc_attr($oum_tile_provider_mapbox_key); ?>`;
            var oum_searchaddress_label = `<?php echo esc_attr($oum_searchaddress_label); ?>`;

            var oum_geosearch_selected_provider = ``;
            var oum_geosearch_provider = `<?php echo $oum_geosearch_provider; ?>`;
            var oum_geosearch_provider_geoapify_key = `<?php echo esc_attr($oum_geosearch_provider_geoapify_key); ?>`;
            var oum_geosearch_provider_here_key = `<?php echo esc_attr($oum_geosearch_provider_here_key); ?>`;
            var oum_geosearch_provider_mapbox_key = `<?php echo esc_attr($oum_geosearch_provider_mapbox_key); ?>`;

            var oum_enable_cluster = <?php echo $oum_enable_cluster; ?>;
            var oum_enable_fullscreen = <?php echo $oum_enable_fullscreen; ?>;
            var oum_enable_currentlocation = <?php echo $oum_enable_currentlocation; ?>;
            var start_lat = `<?php echo esc_attr($start_lat); ?>`;
            var start_lng = `<?php echo esc_attr($start_lng); ?>`;
            var start_zoom = `<?php echo esc_attr($start_zoom); ?>`;
            var oum_enable_fixed_map_bounds = `<?php echo $oum_enable_fixed_map_bounds; ?>`;
        </script>
    </div>
</div>

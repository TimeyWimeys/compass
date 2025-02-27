<?php
declare(strict_types=1);
require_once oum_get_template('partial-map-init.php'); ?>

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

        <script type="text/javascript" data-category="functional" class="cmplz-native" id="oum-inline-js">
            const map_el = `map-<?php echo $unique_id; ?>`;

            <?php if($marker_icon == 'user1' && $marker_user_icon): ?>
            const marker_icon_url = `<?php echo esc_url($marker_user_icon); ?>`;
            <?php else: ?>
            let marker_icon_url = `<?php echo esc_url($this->plugin_url); ?>src/leaflet/images/marker-icon_<?php echo esc_attr($marker_icon); ?>-2x.png`;
            <?php endif; ?>

            const marker_shadow_url = `<?php echo esc_url($this->plugin_url); ?>src/leaflet/images/marker-shadow.png`;
            const mapStyle = `<?php echo esc_attr($map_style); ?>`;
            const oum_tile_provider_mapbox_key = `<?php echo esc_attr($oum_tile_provider_mapbox_key); ?>`;
            const oum_searchaddress_label = `<?php echo esc_attr($oum_searchaddress_label); ?>`;

            const oum_geosearch_selected_provider = ``;
            const oum_geosearch_provider = `<?php echo $oum_geosearch_provider; ?>`;
            const oum_geosearch_provider_geoapify_key = `<?php echo esc_attr($oum_geosearch_provider_geoapify_key); ?>`;
            const oum_geosearch_provider_here_key = `<?php echo esc_attr($oum_geosearch_provider_here_key); ?>`;
            const oum_geosearch_provider_mapbox_key = `<?php echo esc_attr($oum_geosearch_provider_mapbox_key); ?>`;

            let oum_enable_cluster =;
            let oum_enable_fullscreen =;
            let oum_enable_currentlocation =;
            const start_lat = `<?php echo esc_attr($start_lat); ?>`;
            const start_lng = `<?php echo esc_attr($start_lng); ?>`;
            const start_zoom = `<?php echo esc_attr($start_zoom); ?>`;
            const oum_enable_fixed_map_bounds = `<?php echo $oum_enable_fixed_map_bounds; ?>`;
        </script>
    </div>
</div>

<?php require_once cbn_get_template('partial-map-init.php'); ?>

<div class="Compass">
  <div class="add-user-location">
    <label><?php echo $cbn_map_label; ?></label>
    <div class="map-wrap">
      <div id="mapGetLocation" class="leaflet-map map-style_<?php echo $map_style; ?>"></div>
    </div>
    <input type="hidden" id="cbn_location_lat" name="cbn_location_lat" required placeholder="<?php echo __('Latitude', 'Compass'); ?>*" />
    <input type="hidden" id="cbn_location_lng" name="cbn_location_lng" required placeholder="<?php echo __('Longitude', 'Compass'); ?>*" />

    <script type="text/javascript" data-category="functional" class="cmplz-native" id="cbn-inline-js">
        let map_el = `map-<?php echo $unique_id; ?>`;

        <?php if($marker_icon == 'user1' && $marker_user_icon): ?>
        let marker_icon_url;
        <?php else: ?>
        marker_icon_url = `<?php echo esc_url($this->plugin_url); ?>src/leaflet/images/marker-icon_<?php echo esc_attr($marker_icon); ?>-2x.png`;
        <?php endif; ?>
      
      let marker_shadow_url = `<?php echo esc_url($this->plugin_url); ?>src/leaflet/images/marker-shadow.png`;
        let mapStyle = `<?php echo esc_attr($map_style); ?>`;
        let cbn_tile_provider_mapbox_key = `<?php echo esc_attr($cbn_tile_provider_mapbox_key); ?>`;
        let cbn_searchaddress_label = `<?php echo esc_attr($cbn_searchaddress_label); ?>`;

        let cbn_geosearch_selected_provider = ``;
      let cbn_geosearch_provider = `<?php echo $cbn_geosearch_provider; ?>`;
      let cbn_geosearch_provider_geoapify_key = `<?php echo esc_attr($cbn_geosearch_provider_geoapify_key); ?>`;
      let cbn_geosearch_provider_here_key = `<?php echo esc_attr($cbn_geosearch_provider_here_key); ?>`;
      let cbn_geosearch_provider_mapbox_key = `<?php echo esc_attr($cbn_geosearch_provider_mapbox_key); ?>`;

      let cbn_enable_cluster = <?php echo $cbn_enable_cluster; ?>;
      let cbn_enable_fullscreen = <?php echo $cbn_enable_fullscreen; ?>;
      let cbn_enable_currentlocation = <?php echo $cbn_enable_currentlocation; ?>;
      let start_lat = `<?php echo esc_attr($start_lat); ?>`;
      let start_lng = `<?php echo esc_attr($start_lng); ?>`;
      let start_zoom = `<?php echo esc_attr($start_zoom); ?>`;
      let cbn_enable_fixed_map_bounds = `<?php echo $cbn_enable_fixed_map_bounds; ?>`;
    </script>
  </div>
</div>

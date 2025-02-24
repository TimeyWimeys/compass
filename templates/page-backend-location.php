<table class="form-table">
    <tbody>

        <tr style="vertical-align: top;">
            <th scope="row">
                <?php echo __('Marker', 'Compass'); ?>
            </th>
            <td>
                <div class="geo-coordinates-wrap">
                    <div class="map-wrap">
                        <div id="mapGetLocation" class="leaflet-map map-style_<?php echo esc_attr($map_style); ?>"></div>
                    </div>
                    <div class="input-wrap">
                        <div class="geo-coordinates-hint">
                            <div class="hint"><?php echo __('Click on the map to set a location marker or <a href="#" id="showLatLngInputs">edit GPS coordinates manually</a>.', 'Compass'); ?></div>

                            <div class="latlng-wrap" id="latLngInputs" style="display: none;">
                                <div class="hint"><?php echo __('Edit GPS coordinates manually:', 'Compass'); ?></div>
                                <div>
                                    <div>
                                        <label class="meta-label" for="cbn_location_lat">
                                            <?php echo __('Lat', 'Compass'); ?>
                                        </label>
                                        <input type="text" class="widefat" id="cbn_location_lat" name="cbn_location_lat" value="<?php echo esc_attr($lat); ?>">
                                    </div>
                                    <div>
                                        <label class="meta-label" for="cbn_location_lng">
                                            <?php echo __('Lng', 'Compass'); ?>
                                        </label>
                                        <input type="text" class="widefat" id="cbn_location_lng" name="cbn_location_lng" value="<?php echo esc_attr($lng); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script type="text/javascript" data-category="functional" class="cmplz-native" id="cbn-inline-js">
                    const lat = '<?php echo esc_attr($lat); ?>';
                    const lng = '<?php echo esc_attr($lng); ?>';
                    const zoom = '<?php echo get_option('cbn_searchmarkers_zoom') ? get_option('cbn_searchmarkers_zoom') : $this->cbn_searchmarkers_zoom_default; ?>';
                    const mapStyle = '<?php echo esc_attr($map_style); ?>';
                    const cbn_tile_provider_mapbox_key = `<?php echo esc_attr($cbn_tile_provider_mapbox_key); ?>`;
                    const cbn_enable_currentlocation = '<?php echo (bool)get_option('cbn_enable_currentlocation'); ?>';
                    const enableCurrentLocation = !!cbn_enable_currentlocation;
                    let cbn_geosearch_selected_provider = ``; 
                    const cbn_geosearch_provider = `<?php echo get_option('cbn_geosearch_provider') ? get_option('cbn_geosearch_provider') : 'osm'; ?>`;
                    const cbn_geosearch_provider_geoapify_key = `<?php echo get_option('cbn_geosearch_provider_geoapify_key', ''); ?>`;
                    const cbn_geosearch_provider_here_key = `<?php echo get_option('cbn_geosearch_provider_here_key', ''); ?>`;
                    const cbn_geosearch_provider_mapbox_key = `<?php echo get_option('cbn_geosearch_provider_mapbox_key', ''); ?>`;
                    const cbn_searchaddress_label = `<?php echo esc_attr(get_option('cbn_searchaddress_label') ? get_option('cbn_searchaddress_label') : $this->cbn_searchaddress_label_default); ?>`;

                    <?php if($marker_icon == 'user1' && $marker_user_icon): ?>
                        const marker_icon_url = `<?php echo esc_url($marker_user_icon); ?>`;
                    <?php else: ?>
                        const marker_icon_url = `<?php echo esc_url($this->plugin_url); ?>src/leaflet/images/marker-icon_<?php echo esc_attr($marker_icon); ?>-2x.png`;
                    <?php endif; ?>

                    const marker_shadow_url = '<?php echo esc_url($this->plugin_url); ?>src/leaflet/images/marker-shadow.png';
                    </script>

                    <?php
                    // load map base scripts
                    $this->include_map_scripts();

                wp_enqueue_script('cbn_backend_location_js', esc_url($this->plugin_url) . 'src/js/backend-location.js', array('cbn_leaflet_providers_js', 'cbn_leaflet_markercluster_js', 'cbn_leaflet_subgroups_js', 'cbn_leaflet_geosearch_js', 'cbn_leaflet_locate_js', 'cbn_leaflet_fullscreen_js', 'cbn_leaflet_search_js', 'cbn_leaflet_gesture_js', 'wp-i18n', 'cbn_global_leaflet_js'), esc_attr($this->plugin_version));
                ?>
                </div>
            </td>
        </tr>

        <tr style="vertical-align: top;">
            <th scope="row">
                <?php echo __('Subtitle', 'Compass'); ?>
            </th>
            <td>
                <input type="text" class="regular-text" id="cbn_location_address" name="cbn_location_address" value="<?php echo esc_attr($address); ?>">
            </td>
        </tr>

        <tr style="vertical-align: top;">
            <th scope="row">
                <?php echo __('Image', 'Compass'); ?>
            </th>
            <td>
                <a href="#" class="cbn_upload_image_button button button-secondary"><?php echo __('Upload Image', 'Compass'); ?></a>
                <input type="hidden" id="cbn_location_image" name="cbn_location_image" value="<?php echo esc_attr($image); ?>">
                <br><br>
                <div id="cbn_location_image_preview"></div>
                <p class="description"><?php echo __('Maximum 5 images. Images will be shown in a gallery.', 'Compass'); ?></p>
            </td>
        </tr>

        <?php  ?>
            <?php  ?>

                <tr style="vertical-align: top;">
                    <th scope="row">
                        <?php echo __('Video', 'Compass'); ?>
                    </th>
                    <td>
                        <input type="text" class="regular-text" id="cbn_location_video" name="cbn_location_video" placeholder="YouTube or Vimeo URL" value="<?php echo esc_attr($video); ?>">
                        <br><br>
                        <div id="cbn_location_video_preview" class="<?php echo $has_video; ?>">
                            <?php echo $video_tag; ?>
                            <div onclick="cbnRemoveVideoUpload()" class="remove-upload">&times;</div>
                        </div>
                    </td>
                </tr>

        <tr style="vertical-align: top;">
            <th scope="row">
                <?php echo __('Audio', 'Compass'); ?>
            </th>
            <td>
                <a href="#" class="cbn_upload_audio_button button button-secondary"><?php echo __('Upload Audio', 'Compass'); ?></a>
                <input type="hidden" id="cbn_location_audio" name="cbn_location_audio" value="<?php echo esc_attr($audio); ?>">
                <br><br>
                <div id="cbn_location_audio_preview" class="<?php echo $has_audio; ?>">
                    <?php echo $audio_tag; ?>
                    <div onclick="cbnRemoveAudioUpload()" class="remove-upload">&times;</div>
                </div>
            </td>
        </tr>

        <tr style="vertical-align: top;">
            <th scope="row">
                <?php echo __('Description', 'Compass'); ?>
            </th>
            <td>
                <?php
                wp_editor($text, 'cbn_location_text', array(
                'tinymce' => false,
                'quicktags' => true,
                'media_buttons' => false
                ));
                ?>
            </td>
        </tr>

        <?php if (is_array($active_custom_fields)): ?>
            <?php foreach($active_custom_fields as $index => $custom_field): ?>

                <?php
                $custom_field['fieldtype'] = $custom_field['fieldtype'] ?? 'text';
                $custom_field['description'] = $custom_field['description'] ?? '';

                $label = esc_attr($custom_field['label']) . ((isset($custom_field['required'])) ? '*' : '');
                $description = ($custom_field['description']) ? '<div class="cbn_custom_field_description">' . $custom_field['description'] . '</div>' : '';
                ?>
                
                <?php if($custom_field['fieldtype'] == 'text'): ?>

                    <tr style="vertical-align: top;" class="section-id_cf-<?php echo $index; ?>">
                        <th scope="row">
                            <?php echo $label; ?>
                        </th>
                        <td>
                            <input type="text" class="regular-text" name="cbn_location_custom_fields[<?php echo $index; ?>]" value="<?php echo isset($meta_custom_fields[$index]) ? esc_attr($meta_custom_fields[$index]) : ''; ?>">
                            <?php echo $description; ?>
                        </td>
                    </tr>

                <?php endif; ?>


                <?php if($custom_field['fieldtype'] == 'link'): ?>

                    <tr style="vertical-align: top;" class="section-id_cf-<?php echo $index; ?>">
                        <th scope="row">
                            <?php echo $label; ?>
                        </th>
                        <td>
                            <input type="text" class="regular-text" name="cbn_location_custom_fields[<?php echo $index; ?>]" value="<?php echo isset($meta_custom_fields[$index]) ? esc_attr($meta_custom_fields[$index]) : ''; ?>">
                            <?php echo $description; ?>
                        </td>
                    </tr>

                <?php endif; ?>


                <?php if($custom_field['fieldtype'] == 'email'): ?>

                    <tr style="vertical-align: top;" class="section-id_cf-<?php echo $index; ?>">
                        <th scope="row">
                            <?php echo $label; ?>
                        </th>
                        <td>
                            <input type="email" class="regular-text" name="cbn_location_custom_fields[<?php echo $index; ?>]" value="<?php echo isset($meta_custom_fields[$index]) ? esc_attr($meta_custom_fields[$index]) : ''; ?>">
                            <?php echo $description; ?>
                        </td>
                    </tr>

                <?php endif; ?>


                <?php if($custom_field['fieldtype'] == 'checkbox'): ?>

                    <tr style="vertical-align: top;" class="section-id_cf-<?php echo $index; ?>">
                        <th scope="row">
                            <?php echo $label; ?>
                        </th>
                        <td>
                            <fieldset>
                                <?php
                                $options = isset($custom_field['options']) ? explode('|', $custom_field['options']) : array();
                    ?>
                                <?php foreach($options as $option): ?>
                                    <div>
                                        <label>
                                            <input type="checkbox" name="cbn_location_custom_fields[<?php echo $index; ?>][]" value="<?php echo esc_attr($option); ?>" <?php echo (isset($meta_custom_fields[$index]) && (is_array($meta_custom_fields[$index])) && in_array(esc_attr($option), $meta_custom_fields[$index])) ? 'checked' : ''; ?>>
                                            <span><?php echo $option; ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </fieldset>
                            <?php echo $description; ?>
                        </td>
                    </tr>

                <?php endif; ?>


                <?php if($custom_field['fieldtype'] == 'radio'): ?>

                    <tr style="vertical-align: top;" class="section-id_cf-<?php echo $index; ?>">
                        <th scope="row">
                            <?php echo $label; ?>
                        </th>
                        <td>
                            <fieldset>
                                <?php
                    $options = isset($custom_field['options']) ? explode('|', $custom_field['options']) : array();
                    ?>
                                <?php foreach($options as $option): ?>
                                    <div>
                                        <label>
                                            <input type="radio" name="cbn_location_custom_fields[<?php echo $index; ?>]" value="<?php echo esc_attr($option); ?>" <?php echo (isset($meta_custom_fields[$index]) && esc_attr($option) == $meta_custom_fields[$index]) ? 'checked' : ''; ?>>
                                            <span><?php echo $option; ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </fieldset>
                            <?php echo $description; ?>
                        </td>
                    </tr>

                <?php endif; ?>


                <?php if($custom_field['fieldtype'] == 'select'): ?>

                    <tr style="vertical-align: top;" class="section-id_cf-<?php echo $index; ?>">
                        <th scope="row">
                            <?php echo $label; ?>
                        </th>
                        <td>
                            <select name="cbn_location_custom_fields[<?php echo $index; ?>]" <?php echo (isset($custom_field['required'])) ? 'required' : ''; ?>>
                                <?php
                    $options = isset($custom_field['options']) ? explode('|', $custom_field['options']) : array();
                    ?>
                                <?php foreach($options as $option): ?>
                                    <option value="<?php echo esc_attr($option); ?>" <?php echo (isset($meta_custom_fields[$index]) && esc_attr($option) == $meta_custom_fields[$index]) ? 'selected' : ''; ?>><?php echo $option; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo $description; ?>
                        </td>
                    </tr>

                <?php endif; ?>

            <?php endforeach; ?>
        <?php endif;?>

        <?php if (true): ?>
            <?php if (true): ?>

                <tr style="vertical-align: top;">
                    <th scope="row">
                        <?php echo __('User email notification', 'Compass'); ?>
                    </th>
                    <td>
                        <input class="cbn-switch" type="checkbox" id="cbn_location_notification" name="cbn_location_notification" <?php echo ($notification) ? 'checked' : ''; ?>>
                        <label for="cbn_location_notification"><?php echo $text_notify_me_on_publish_label; ?></label>
                        <br><br>
                        <label class="meta-label" for="cbn_location_author_name">
                            <?php echo $text_notify_me_on_publish_name; ?>
                        </label>
                        <input type="text" class="regular-text" id="cbn_location_author_name" name="cbn_location_author_name" value="<?php echo esc_attr($author_name); ?>"></input>
                        <br><br>

                        <label class="meta-label" for="cbn_location_author_email">
                            <?php echo $text_notify_me_on_publish_email; ?>
                        </label>
                        <input type="email" class="regular-text" id="cbn_location_author_email" name="cbn_location_author_email" value="<?php echo esc_attr($author_email); ?>"></input>
                        <br><br>

                        <?php echo $notified_tag; ?>
                    </td>
                </tr>

            <?php endif;?>
        <?php endif;?>

    </tbody>
</table>
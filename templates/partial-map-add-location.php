<div class="Compass cbn-container-for-fullscreen">
  <div id="add-location-overlay" class="add-location">
    <div class="location-overlay-content">
      <div id="close-add-location-overlay">&#x2715;</div>
      <form id="cbn_add_location" enctype="multipart/form-data">
        <h2 class="cbn-add-location-headline"><?php echo get_option('cbn_form_headline') ? get_option('cbn_form_headline') : __('Add a new location', 'Compass'); ?></h2>
        <h2 class="cbn-edit-location-headline"><?php echo __('Edit Location', 'Compass'); ?></h2>
        <?php wp_nonce_field('cbn_location', 'cbn_location_nonce'); ?>

        <?php if(get_option('cbn_enable_title', 'on')): ?>
          <?php
          $maxlength = (get_option('cbn_title_maxlength') > 0) ? 'maxlength="' . get_option('cbn_title_maxlength') . '"' : '';
            ?>
          <input type="text" id="cbn_location_title" name="cbn_location_title" <?php if(get_option('cbn_title_required', 'on')): ?>required<?php endif; ?> placeholder="<?php echo $cbn_title_label; ?><?php if(get_option('cbn_title_required', 'on')): ?>*<?php endif; ?>" <?php echo $maxlength; ?> />
        <?php endif; ?>
        
        <label class="cbn-label"><?php echo $cbn_map_label; ?></label>
        <div class="map-wrap">
          <div id="mapGetLocation" class="leaflet-map map-style_<?php echo $map_style; ?>"></div>
        </div>
        <input type="hidden" id="cbn_location_lat" name="cbn_location_lat" required placeholder="<?php echo __('Latitude', 'Compass'); ?>*" />
        <input type="hidden" id="cbn_location_lng" name="cbn_location_lng" required placeholder="<?php echo __('Longitude', 'Compass'); ?>*" />

        <input type="hidden" id="cbn_post_id" name="cbn_post_id" value="">
        <input type="hidden" id="cbn_delete_location" name="cbn_delete_location" value="">

        <?php  ?>
          <?php  ?>

            <?php if(get_option('cbn_enable_marker_types') && $types): ?>

              <?php if(get_option('cbn_enable_multiple_marker_types')): ?>

                <fieldset id="cbn_marker_icon">
                  <legend><?php echo $cbn_marker_types_label; ?></legend>
                  <?php
                    foreach($types as $tag):
                        ?>
                    <div>
                      <label>
                        <input style="accent-color: <?php echo $cbn_ui_color; ?>" type="checkbox" name="cbn_marker_icon[]" value="<?php echo $tag->term_id; ?>">
                        <span><?php echo $tag->name; ?></span>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </fieldset>

              <?php else: ?>

                <label class="cbn-label"><?php echo $cbn_marker_types_label; ?></label>
                <select name="cbn_marker_icon[]" id="cbn_marker_icon">
                  <?php if(get_option('cbn_enable_empty_marker_type', true) && $types): ?>
                    <option value></option>
                  <?php endif; ?>
                  <?php foreach($types as $tag): ?>
                    <option value="<?php echo $tag->term_id; ?>"><?php echo $tag->name; ?></option>
                  <?php endforeach; ?>
                </select>

              <?php endif; ?>
            <?php endif; ?>
        
        <?php
          $cbn_custom_fields = get_option('cbn_custom_fields');
        ?>
        <?php if(is_array($cbn_custom_fields)): ?>
          <div class="cbn_custom_fields_wrapper">
          <?php foreach($cbn_custom_fields as $index => $custom_field): ?>
            <?php
              if($custom_field['label'] == '' && $custom_field['fieldtype'] != 'html') {
                  continue;
              }

              $custom_field['fieldtype'] = isset($custom_field['fieldtype']) ? $custom_field['fieldtype'] : 'text';
              $custom_field['description'] = isset($custom_field['description']) ? $custom_field['description'] : '';

              $label = esc_attr($custom_field['label']) . ((isset($custom_field['required'])) ? '*' : '');
              $description = ($custom_field['description']) ? '<div class="cbn_custom_field_description">' . $custom_field['description'] . '</div>' : '';
              $maxlength = ($custom_field['maxlength']) ? 'maxlength="' . $custom_field['maxlength'] . '"' : '';
              $html = ($custom_field['html']) ? $custom_field['html'] : '';
              ?>

            <?php if($custom_field['fieldtype'] == 'text'): ?>
              <div>
                <input type="text" name="cbn_location_custom_fields[<?php echo $index; ?>]" placeholder="<?php echo $label; ?>" <?php echo (isset($custom_field['required'])) ? 'required' : ''; ?> value="" <?php echo $maxlength; ?> />
                <?php echo $description; ?>
              </div>
            <?php endif; ?>

            <?php if($custom_field['fieldtype'] == 'link'): ?>
              <div>
                <input type="url" name="cbn_location_custom_fields[<?php echo $index; ?>]" placeholder="<?php echo $label; ?>" <?php echo (isset($custom_field['required'])) ? 'required' : ''; ?> value="" <?php echo $maxlength; ?> />
                <?php echo $description; ?>
              </div>
            <?php endif; ?>

            <?php if($custom_field['fieldtype'] == 'email'): ?>
              <div>
                <input type="email" name="cbn_location_custom_fields[<?php echo $index; ?>]" placeholder="<?php echo $label; ?>" <?php echo (isset($custom_field['required'])) ? 'required' : ''; ?> value="" <?php echo $maxlength; ?> />
                <?php echo $description; ?>
              </div>
            <?php endif; ?>

            <?php if($custom_field['fieldtype'] == 'checkbox'): ?>
              <div>
                <fieldset class="<?php echo (isset($custom_field['required'])) ? 'is-required' : ''; ?>">
                  <legend><?php echo $label; ?></legend>
                  <?php
                    $options = isset($custom_field['options']) ? explode('|', $custom_field['options']) : array();
                foreach($options as $option):
                    ?>
                    <div>
                      <label>
                        <input style="accent-color: <?php echo $cbn_ui_color; ?>" type="checkbox" name="cbn_location_custom_fields[<?php echo $index; ?>][]" value="<?php echo esc_attr(trim($option)); ?>" <?php echo (isset($custom_field['required'])) ? 'required' : ''; ?>>
                        <span><?php echo trim($option); ?></span>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </fieldset>
                <?php echo $description; ?>
              </div>
            <?php endif; ?>

            <?php if($custom_field['fieldtype'] == 'radio'): ?>
              <div>
                <fieldset class="<?php echo (isset($custom_field['required'])) ? 'is-required' : ''; ?>">
                  <legend><?php echo $label; ?></legend>
                  <?php
                    $options = isset($custom_field['options']) ? explode('|', $custom_field['options']) : array();
                foreach($options as $option):
                    ?>
                    <div>
                      <label>
                        <input style="accent-color: <?php echo $cbn_ui_color; ?>" type="radio" name="cbn_location_custom_fields[<?php echo $index; ?>]" value="<?php echo esc_attr(trim($option)); ?>" <?php echo (isset($custom_field['required'])) ? 'required' : ''; ?>>
                        <span><?php echo trim($option); ?></span>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </fieldset>
                <?php echo $description; ?>
              </div>
            <?php endif; ?>

            <?php if($custom_field['fieldtype'] == 'select'): ?>
              <div>
                <label class="cbn-label"><?php echo esc_attr($label); ?></label>
                <select name="cbn_location_custom_fields[<?php echo $index; ?>]" <?php echo (isset($custom_field['required'])) ? 'required' : ''; ?>>
                  <?php
                    $options = isset($custom_field['options']) ? explode('|', $custom_field['options']) : array();

                if(isset($custom_field['emptyoption'])):
                    ?>
                    <option></option>
                  <?php
                endif;

                foreach($options as $option):
                    ?>
                    <option value="<?php echo esc_attr(trim($option)); ?>"><?php echo trim($option); ?></option>
                  <?php endforeach; ?>
                </select>
                <?php echo $description; ?>
              </div>
            <?php endif; ?>

            <?php if($custom_field['fieldtype'] == 'html'): ?>
              <div class="cbn-custom-field-html">
                <?php echo $html; ?>
              </div>
            <?php endif; ?>

          <?php endforeach; ?>
          </div>
        <?php endif; ?>
        

        <?php if(get_option('cbn_enable_address', 'on') === 'on'): ?>
          <input type="text" id="cbn_location_address" name="cbn_location_address" placeholder="<?php echo $cbn_address_label; ?>" />
        <?php endif; ?>

        <?php if(get_option('cbn_enable_description', 'on') === 'on'): ?>
          <textarea id="cbn_location_text" name="cbn_location_text" placeholder="<?php echo $cbn_description_label; ?><?php echo (get_option('cbn_description_required')) ? '*' : ''; ?>" <?php echo (get_option('cbn_description_required')) ? 'required' : ''; ?>></textarea>
        <?php endif; ?>
        
        <div class="cbn_media">
          <?php if(get_option('cbn_enable_image', 'on') === 'on'): ?>
            <div class="media-upload cbn-image-upload">
              <div class="media-upload-top">
                <label for="cbn_location_images" title="<?php echo __('Upload Images', 'Compass'); ?>">
                  <span class="dashicons dashicons-format-image"></span>
                  <span class="multi-upload-indicator">+</span>
                </label>
                <p class="cbn-image-upload-description"><?php echo __('Add up to 5 images to create a gallery for this location.', 'Compass'); ?></p>
              </div>
              <input type="file" 
                id="cbn_location_images" 
                name="cbn_location_images[]" 
                accept="image/*" 
                multiple 
                <?php if(get_option('cbn_image_required')): ?>required<?php endif; ?> 
                data-max-files="5"
              />
              <div class="preview">
                <span></span>
                <div id="cbn_remove_image" class="remove-upload">×</div>
              </div>
              <input type="hidden" id="cbn_remove_existing_image" name="cbn_remove_existing_image" value="0" />
            </div>

            <div class="cbn-image-preview-grid" id="cbn_location_images_preview"></div>
          <?php endif; ?>

          <?php  ?>
            <?php  ?>
              <?php if(get_option('cbn_enable_video') === 'on'): ?>
                <div class="media-upload cbn-video-upload">
                  <label style="color: #e02aaf" for="cbn_location_video" title="<?php echo __('YouTube or Vimeo Video', 'Compass'); ?>">
                    <span class="dashicons dashicons-format-video"></span>
                  </label>
                  <input type="text" id="cbn_location_video" name="cbn_location_video" placeholder="<?php echo __('YouTube or Vimeo URL', 'Compass'); ?>">
                </div>
              <?php endif; ?>

          <?php if(get_option('cbn_enable_audio', 'on') === 'on'): ?>
            <div class="media-upload cbn-audio-upload">
              <label style="color: #e02aaf" for="cbn_location_audio" title="<?php echo __('Upload Audio', 'Compass'); ?>">
                <span class="dashicons dashicons-format-audio"></span>
              </label>
              <input type="file" 
                id="cbn_location_audio" 
                name="cbn_location_audio" 
                accept="audio/mp3,audio/mpeg3,audio/wav,audio/mp4,audio/mpeg,audio/x-m4a" 
                multiple="false"
              />
              <div class="preview">
                <div class="audio-preview"></div>
                <div id="cbn_remove_audio" class="remove-upload">×</div>
              </div>
              <input type="hidden" id="cbn_remove_existing_audio" name="cbn_remove_existing_audio" value="1" />
            </div>
          <?php endif; ?>
        </div>

        <?php  ?>
          <?php  ?>

            <?php if($cbn_enable_user_notification): ?>
              <div>
                <input class="cbn-switch" type="checkbox" id="cbn_location_notification" name="cbn_location_notification">
                <label for="cbn_location_notification"><?php echo $text_notify_me_on_publish_label; ?></label>
              </div>
              <div id="cbn_author">
                <input type="text" id="cbn_location_author_name" name="cbn_location_author_name" placeholder="<?php echo $text_notify_me_on_publish_name; ?>*" />
                <input type="email" id="cbn_location_author_email" name="cbn_location_author_email" placeholder="<?php echo $text_notify_me_on_publish_email; ?>*" />
              </div>
            <?php endif; ?>

        <input type="submit" id="cbn_submit_btn" style="background-color: <?php echo $cbn_ui_color; ?>" value="<?php echo get_option('cbn_submit_button_label') ? get_option('cbn_submit_button_label') : __('Submit location for review', 'Compass'); ?>" />
        <div id="cbn_delete_location_btn"><span style="color: <?php echo $cbn_ui_color; ?>"><?php echo __('Delete this location', 'Compass'); ?></span></div>
      </form>

      <div id="cbn_add_location_error" style="display: none"></div>

      <div id="cbn_add_location_thankyou" style="display: none">
        <h3><?php echo $thankyou_headline ? $thankyou_headline : __('Thank you!', 'Compass'); ?></h3>
        <p class="cbn-add-location-thankyou-text"><?php echo $thankyou_text ? $thankyou_text : __('We will check your location suggestion and release it as soon as possible.', 'Compass'); ?></p>
      </div>
    </div>
  </div>
  <div id="location-fullscreen-container"><div class="location-content-wrap"></div><div id="close-location-fullscreen" onClick="cbnMap.closePopup()">✕</div></div>
</div>
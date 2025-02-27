<div class="open-user-map oum-container-for-fullscreen">
    <div id="add-location-overlay" class="add-location">
        <div class="location-overlay-content">
            <div id="close-add-location-overlay">&#x2715;</div>
            <form id="oum_add_location" enctype="multipart/form-data">
                <h2 class="oum-add-location-headline"><?php echo get_option('oum_form_headline') ? get_option('oum_form_headline') : __('Add a new location', 'open-user-map'); ?></h2>
                <h2 class="oum-edit-location-headline"><?php echo __('Edit Location', 'open-user-map'); ?></h2>
                <?php wp_nonce_field('oum_location', 'oum_location_nonce'); ?>

                <?php if (get_option('oum_enable_title', 'on')): ?>
                    <?php
                    $maxlength = (get_option('oum_title_maxlength') > 0) ? 'maxlength="' . get_option('oum_title_maxlength') . '"' : '';
                    ?>
                    <label for="oum_location_title"></label><input type="text" id="oum_location_title"
                                                                   name="oum_location_title"
                                                                   <?php if (get_option('oum_title_required', 'on')): ?>required<?php endif; ?>placeholder="<?php echo $oum_title_label; ?><?php if (get_option('oum_title_required', 'on')): ?>*<?php endif; ?>" <?php echo $maxlength; ?> />
                <?php endif; ?>

                <label class="oum-label"><?php echo $oum_map_label; ?></label>
                <div class="map-wrap">
                    <div id="mapGetLocation" class="leaflet-map map-style_<?php echo $map_style; ?>"></div>
                </div>
                <input type="hidden" id="oum_location_lat" name="oum_location_lat" required
                       placeholder="<?php echo __('Latitude', 'open-user-map'); ?>*"/>
                <input type="hidden" id="oum_location_lng" name="oum_location_lng" required
                       placeholder="<?php echo __('Longitude', 'open-user-map'); ?>*"/>

                <input type="hidden" id="oum_post_id" name="oum_post_id" value="">
                <input type="hidden" id="oum_delete_location" name="oum_delete_location" value="">

                <?php if (true): ?>
                    <?php if (true): ?>

                        <?php if (get_option('oum_enable_marker_types') && $types): ?>

                            <?php if (get_option('oum_enable_multiple_marker_types')): ?>

                                <fieldset id="oum_marker_icon">
                                    <legend><?php echo $oum_marker_types_label; ?></legend>
                                    <?php
                                    foreach ($types as $tag):
                                        ?>
                                        <div>
                                            <label>
                                                <input style="accent-color: <?php echo $oum_ui_color; ?>"
                                                       type="checkbox" name="oum_marker_icon[]"
                                                       value="<?php echo $tag->term_id; ?>">
                                                <span><?php echo $tag->name; ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </fieldset>

                            <?php else: ?>

                                <label class="oum-label"><?php echo $oum_marker_types_label; ?></label>
                                <label for="oum_marker_icon"></label><select name="oum_marker_icon[]"
                                                                             id="oum_marker_icon">
                                    <?php if (get_option('oum_enable_empty_marker_type', true) && $types): ?>
                                        <option value></option>
                                    <?php endif; ?>
                                    <?php foreach ($types as $tag): ?>
                                        <option value="<?php echo $tag->term_id; ?>"><?php echo $tag->name; ?></option>
                                    <?php endforeach; ?>
                                </select>

                            <?php endif; ?>

                        <?php endif; ?>

                    <?php endif; ?>
                <?php endif; ?>


                <?php
                $oum_custom_fields = get_option('oum_custom_fields');
                ?>
                <?php if (is_array($oum_custom_fields)): ?>
                    <div class="oum_custom_fields_wrapper">
                        <?php foreach ($oum_custom_fields as $index => $custom_field): ?>
                            <?php
                            if ($custom_field['label'] == '' && $custom_field['fieldtype'] != 'html') continue;

                            $custom_field['fieldtype'] = isset($custom_field['fieldtype']) ? $custom_field['fieldtype'] : 'text';
                            $custom_field['description'] = isset($custom_field['description']) ? $custom_field['description'] : '';

                            $label = esc_attr($custom_field['label']) . ((isset($custom_field['required'])) ? '*' : '');
                            $description = ($custom_field['description']) ? '<div class="oum_custom_field_description">' . $custom_field['description'] . '</div>' : '';
                            $maxlength = ($custom_field['maxlength']) ? 'maxlength="' . $custom_field['maxlength'] . '"' : '';
                            $html = ($custom_field['html']) ? $custom_field['html'] : '';
                            ?>

                            <?php if ($custom_field['fieldtype'] == 'text'): ?>
                                <div>
                                    <label>
                                        <input type="text" name="oum_location_custom_fields[<?php echo $index; ?>]"
                                               placeholder="<?php echo $label; ?>" <?php echo (isset($custom_field['required'])) ? 'required' : ''; ?>
                                               value="" <?php echo $maxlength; ?> />
                                    </label>
                                    <?php echo $description; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($custom_field['fieldtype'] == 'link'): ?>
                                <div>
                                    <label>
                                        <input type="url" name="oum_location_custom_fields[<?php echo $index; ?>]"
                                               placeholder="<?php echo $label; ?>" <?php echo (isset($custom_field['required'])) ? 'required' : ''; ?>
                                               value="" <?php echo $maxlength; ?> />
                                    </label>
                                    <?php echo $description; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($custom_field['fieldtype'] == 'email'): ?>
                                <div>
                                    <label>
                                        <input type="email" name="oum_location_custom_fields[<?php echo $index; ?>]"
                                               placeholder="<?php echo $label; ?>" <?php echo (isset($custom_field['required'])) ? 'required' : ''; ?>
                                               value="" <?php echo $maxlength; ?> />
                                    </label>
                                    <?php echo $description; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($custom_field['fieldtype'] == 'checkbox'): ?>
                                <div>
                                    <fieldset
                                            class="<?php echo (isset($custom_field['required'])) ? 'is-required' : ''; ?>">
                                        <legend><?php echo $label; ?></legend>
                                        <?php
                                        $options = isset($custom_field['options']) ? explode('|', $custom_field['options']) : array();
                                        foreach ($options as $option):
                                            ?>
                                            <div>
                                                <label>
                                                    <input style="accent-color: <?php echo $oum_ui_color; ?>"
                                                           type="checkbox"
                                                           name="oum_location_custom_fields[<?php echo $index; ?>][]"
                                                           value="<?php echo esc_attr(trim($option)); ?>" <?php echo (isset($custom_field['required'])) ? 'required' : ''; ?>>
                                                    <span><?php echo trim($option); ?></span>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </fieldset>
                                    <?php echo $description; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($custom_field['fieldtype'] == 'radio'): ?>
                                <div>
                                    <fieldset
                                            class="<?php echo (isset($custom_field['required'])) ? 'is-required' : ''; ?>">
                                        <legend><?php echo $label; ?></legend>
                                        <?php
                                        $options = isset($custom_field['options']) ? explode('|', $custom_field['options']) : array();
                                        foreach ($options as $option):
                                            ?>
                                            <div>
                                                <label>
                                                    <input style="accent-color: <?php echo $oum_ui_color; ?>"
                                                           type="radio"
                                                           name="oum_location_custom_fields[<?php echo $index; ?>]"
                                                           value="<?php echo esc_attr(trim($option)); ?>" <?php echo (isset($custom_field['required'])) ? 'required' : ''; ?>>
                                                    <span><?php echo trim($option); ?></span>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </fieldset>
                                    <?php echo $description; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($custom_field['fieldtype'] == 'select'): ?>
                                <div>
                                    <label class="oum-label"><?php echo esc_attr($label); ?></label>
                                    <label>
                                        <select name="oum_location_custom_fields[<?php echo $index; ?>]" <?php echo (isset($custom_field['required'])) ? 'required' : ''; ?>>
                                            <?php
                                            $options = isset($custom_field['options']) ? explode('|', $custom_field['options']) : array();

                                            if (isset($custom_field['emptyoption'])):
                                                ?>
                                                <option></option>
                                            <?php
                                            endif;

                                            foreach ($options as $option):
                                                ?>
                                                <option value="<?php echo esc_attr(trim($option)); ?>"><?php echo trim($option); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>
                                    <?php echo $description; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($custom_field['fieldtype'] == 'html'): ?>
                                <div class="oum-custom-field-html">
                                    <?php echo $html; ?>
                                </div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>


                <?php if (get_option('oum_enable_address', 'on') === 'on'): ?>
                    <label for="oum_location_address"></label><input type="text" id="oum_location_address"
                                                                     name="oum_location_address"
                                                                     placeholder="<?php echo $oum_address_label; ?>"/>
                <?php endif; ?>

                <?php if (get_option('oum_enable_description', 'on') === 'on'): ?>
                    <label for="oum_location_text"></label><textarea id="oum_location_text" name="oum_location_text"
                                                                     placeholder="<?php echo $oum_description_label; ?><?php echo (get_option('oum_description_required')) ? '*' : ''; ?>" <?php echo (get_option('oum_description_required')) ? 'required' : ''; ?>></textarea>
                <?php endif; ?>

                <?php if (get_option('oum_enable_image', 'on') === 'on' || get_option('oum_enable_audio', 'on') === 'on' || get_option('oum_enable_video') === 'on'): ?>
                    <label class="oum-label"><?php echo $oum_upload_media_label; ?></label>
                    <div class="oum_media">

                        <?php if (get_option('oum_enable_image', 'on') === 'on'): ?>
                            <div class="media-upload oum-image-upload">
                                <div class="media-upload-top">
                                    <label for="oum_location_images"
                                           title="<?php echo __('Upload Images', 'open-user-map'); ?>">
                                        <span class="dashicons dashicons-format-image"></span>
                                        <span class="multi-upload-indicator">+</span>
                                    </label>
                                    <p class="oum-image-upload-description"><?php echo __('Add up to 5 images to create a gallery for this location.', 'open-user-map'); ?></p>
                                </div>
                                <input type="file"
                                       id="oum_location_images"
                                       name="oum_location_images[]"
                                       accept="image/*"
                                       multiple
                                       <?php if (get_option('oum_image_required')): ?>required<?php endif; ?>
                                       data-max-files="5"
                                />
                                <input type="hidden" id="oum_remove_existing_image" name="oum_remove_existing_image"
                                       value="0"/>
                            </div>

                            <div class="oum-image-preview-grid" id="oum_location_images_preview"></div>
                        <?php endif; ?>

                        <?php if (true): ?>
                            <?php if (true): ?>
                                <?php if (get_option('oum_enable_video') === 'on'): ?>
                                    <div class="media-upload oum-video-upload">
                                        <label style="color: #e02aaf" for="oum_location_video"
                                               title="<?php echo __('YouTube or Vimeo Video', 'open-user-map'); ?>">
                                            <span class="dashicons dashicons-format-video"></span>
                                        </label>
                                        <input type="text" id="oum_location_video" name="oum_location_video"
                                               placeholder="<?php echo __('YouTube or Vimeo URL', 'open-user-map'); ?>">
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (get_option('oum_enable_audio', 'on') === 'on'): ?>
                            <div class="media-upload oum-audio-upload">
                                <label style="color: #e02aaf" for="oum_location_audio"
                                       title="<?php echo __('Upload Audio', 'open-user-map'); ?>">
                                    <span class="dashicons dashicons-format-audio"></span>
                                </label>
                                <input type="file"
                                       id="oum_location_audio"
                                       name="oum_location_audio"
                                       accept="audio/mp3,audio/mpeg3,audio/wav,audio/mp4,audio/mpeg,audio/x-m4a"
                                       multiple="multiple"
                                />
                                <div class="preview">
                                    <div class="audio-preview"></div>
                                    <div id="oum_remove_audio" class="remove-upload">×</div>
                                </div>
                                <input type="hidden" id="oum_remove_existing_audio" name="oum_remove_existing_audio"
                                       value="1"/>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (true): ?>
                    <?php if (true): ?>

                        <?php if ($oum_enable_user_notification): ?>
                            <div>
                                <input class="oum-switch" type="checkbox" id="oum_location_notification"
                                       name="oum_location_notification">
                                <label for="oum_location_notification"><?php echo $text_notify_me_on_publish_label; ?></label>
                            </div>
                            <div id="oum_author">
                                <label for="oum_location_author_name"></label><input type="text"
                                                                                     id="oum_location_author_name"
                                                                                     name="oum_location_author_name"
                                                                                     placeholder="<?php echo $text_notify_me_on_publish_name; ?>*"/>
                                <label for="oum_location_author_email"></label><input type="email"
                                                                                      id="oum_location_author_email"
                                                                                      name="oum_location_author_email"
                                                                                      placeholder="<?php echo $text_notify_me_on_publish_email; ?>*"/>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>
                <?php endif; ?>

                <input type="submit" id="oum_submit_btn" style="background-color: <?php echo $oum_ui_color; ?>"
                       value="<?php echo get_option('oum_submit_button_label') ? get_option('oum_submit_button_label') : __('Submit location for review', 'open-user-map'); ?>"/>
                <div id="oum_delete_location_btn"><span
                            style="color: <?php echo $oum_ui_color; ?>"><?php echo __('Delete this location', 'open-user-map'); ?></span>
                </div>
            </form>

            <div id="oum_add_location_error" style="display: none"></div>

            <div id="oum_add_location_thankyou" style="display: none">
                <h3><?php echo $thankyou_headline ? $thankyou_headline : __('Thank you!', 'open-user-map'); ?></h3>
                <p class="oum-add-location-thankyou-text"><?php echo $thankyou_text ? $thankyou_text : __('We will check your location suggestion and release it as soon as possible.', 'open-user-map'); ?></p>
            </div>
        </div>
    </div>
    <div id="location-fullscreen-container">
        <div class="location-content-wrap"></div>
        <div id="close-location-fullscreen" onClick="oumMap.closePopup()">✕</div>
    </div>
</div>
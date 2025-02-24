<?php
// Load settings
$cbn_enable_gmaps_link = get_option('cbn_enable_gmaps_link', 'on');
$cbn_location_date_type = get_option('cbn_location_date_type', 'modified');

// Build query
$count = get_option('posts_per_page', 10);
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

$query = array(
  'post_type' => 'cbn-location',
  'fields' => 'ids',
  'posts_per_page' => $count,
  'paged' => $paged
);

// Custom Attribute: Filter for types
if(isset($block_attributes['types']) && $block_attributes['types'] != '') {
    $selected_types_slugs = explode('|', $block_attributes['types']);
    $query['tax_query'] = array(
      array(
        'taxonomy' => 'cbn-type',
        'field'    => 'slug',
        'terms'    => $selected_types_slugs,      // provide the term slugs
      ),
    );
};

// Custom Attribute: Filter for ids
if(isset($block_attributes['ids']) && $block_attributes['ids'] != '') {
    $selected_ids = explode('|', $block_attributes['ids']);
    $query['post__in'] = $selected_ids;
};

// Init WP_Query
$locations_query = new WP_Query($query);

$locations_list = array();
if ($locations_query->have_posts()) :
    while ($locations_query->have_posts()) : $locations_query->the_post();
        $post_id = get_the_ID();

        // Prepare data
        $location_meta = get_post_meta($post_id, '_cbn_location_key', true);

        $name = str_replace("'", "\'", strip_tags(get_the_title($post_id)));
        $address = isset($location_meta['address']) ? str_replace("'", "\'", (preg_replace('/\r|\n/', '', $location_meta['address']))) : '';
        $text = isset($location_meta["text"]) ? str_replace("'", "\'", str_replace(array("\r\n", "\r", "\n"), "<br>", $location_meta["text"])) : '';
        $video = isset($location_meta["video"]) ? $location_meta["video"] : '';

        $image = get_post_meta($post_id, '_cbn_location_image', true);
        $image_thumb = null;

        if(stristr($image, 'cbn-useruploads')) {
            //image uploaded from frontend - always use original image
            $image_thumb = $image;
        } else {
            //image uploaded from backend
            $image_id = attachment_url_to_postid($image);

            if($image_id > 0) {
                $image_thumb = wp_get_attachment_image_url($image_id, 'medium');
            }
        }

        if(isset($image_thumb) && $image_thumb != '') {
            //use thumbnail if available
            $image = $image_thumb;
        }

        //make image url relative
        $site_url = 'http://';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $site_url = 'https://';
        }
        $site_url .= $_SERVER['SERVER_NAME'];

        $image = str_replace($site_url, '', $image);

        $audio = get_post_meta($post_id, '_cbn_location_audio', true);

        // custom fields
        $custom_fields = [];
        $meta_custom_fields = isset($location_meta['custom_fields']) ? $location_meta['custom_fields'] : false;
        $active_custom_fields = get_option('cbn_custom_fields');
        if (is_array($meta_custom_fields) && is_array($active_custom_fields)) {

            foreach($active_custom_fields as $index => $active_custom_field) {

                //don't add if private
                if(isset($active_custom_field['private'])) {
                    continue;
                }

                if(isset($meta_custom_fields[$index])) {
                    array_push($custom_fields, array(
                      'label' => $active_custom_field['label'],
                      'val' => $meta_custom_fields[$index],
                      'fieldtype' => $active_custom_field['fieldtype']
                    ));
                }
            }

        }

        if (!isset($location_meta['lat']) && !isset($location_meta['lng'])) {
            continue;
        }

        $geolocation = array(
            'lat' => $location_meta['lat'],
            'lng' => $location_meta['lng'],
        );




        //PRO Feature: use types
        $location_types = (get_the_terms($post_id, 'cbn-type') && !is_wp_error(get_the_terms($post_id, 'cbn-type'))) ? get_the_terms($post_id, 'cbn-type') : false;

        if(isset($location_types) && is_array($location_types) && count($location_types) == 1 && !get_option('cbn_enable_multiple_marker_types')) {
            //get current location icon from cbn-type taxonomy
            $type = $location_types[0];
            $current_marker_icon = get_term_meta($type->term_id, 'cbn_marker_icon', true) ? get_term_meta($type->term_id, 'cbn_marker_icon', true) : 'default';
            $current_marker_user_icon = get_term_meta($type->term_id, 'cbn_marker_user_icon', true);
        } else {
            //get current location icon from settings
            $current_marker_icon = get_option('cbn_marker_icon') ? get_option('cbn_marker_icon') : 'default';
            $current_marker_user_icon = get_option('cbn_marker_user_icon');
        }

        if($current_marker_icon == 'user1' && $current_marker_user_icon) {
            $icon = esc_url($current_marker_user_icon);
        } else {
            $icon = esc_url($this->plugin_url) . 'src/leaflet/images/marker-icon_' . esc_attr($current_marker_icon) .'-2x.png';
        }

        // Date: modified or published
        if($cbn_location_date_type == 'created') {
            $date = get_the_date('', $post_id);
        } else {
            $date = get_the_modified_date('', $post_id);
        }

        // collect locations for JS use
        $location = array(
          'post_id' => $post_id,
          'date' => $date,
          'name' => $name,
          'address' => $address,
          'lat' => $geolocation['lat'],
          'lng' => $geolocation['lng'],
          'text' => $text,
          'image' => $image,
          'audio' => $audio,
          'video' => $video,
          'icon' => $icon,
          'custom_fields' => $custom_fields,
        );

        if(isset($location_types) && is_array($location_types) && count($location_types) > 0) {
            foreach ($location_types as $term) {
                $location['types'][] = (string) $term->term_taxonomy_id;
            }
        }

        $locations_list[] = $location;
    endwhile;
endif;


?>

<div class="Compass-locations-list">

  <div class="cbn-locations-list-items">
    <?php foreach($locations_list as $location): ?>

      <?php
      if(get_option('cbn_enable_location_date') === 'on') {
          $date_tag = '<div class="cbn_location_date">' . wp_kses_post($location['date']) . '</div>';
      } else {
          $date_tag = '';
      }

        $name_tag = (get_option('cbn_enable_title', 'on') == 'on') ? '<h3 class="cbn_location_name">' . esc_attr($location['name']) . '</h3>' : '';

        //error_log(print_r($location, true));

        $media_tag = '';

        if($location['image']) {
            // Split image URLs if multiple images exist
            $images = explode('|', $location['image']);

            if(count($images) > 1) {
                // Multiple images - use carousel
                $media_tag = '<div class="cbn-carousel">';
                $media_tag .= '<div class="cbn-carousel-inner">';

                foreach($images as $index => $image_url) {
                    if(!empty($image_url)) {
                        $active_class = ($index === 0) ? ' active' : '';
                        $media_tag .= '<div class="cbn-carousel-item' . $active_class . '">';
                        $media_tag .= '<img class="skip-lazy" src="' . esc_url_raw($image_url) . '" alt="' . esc_attr($location['name']) . '">';
                        $media_tag .= '</div>';
                    }
                }

                $media_tag .= '</div>';
                $media_tag .= '</div>';
            } else {
                // Single image - use regular image display
                $media_tag = '<div class="cbn_location_image"><img class="skip-lazy" src="'. esc_url_raw($location['image']) .'"></div>';
            }
        }




        if($location['video']) {
            $video_embed = apply_filters('the_content', esc_url_raw($location['video']));
            $media_tag = '<div class="cbn_location_video"><div>' . $video_embed . '</div></div>';
        }

        //HOOK: modify location image
        $media_tag = apply_filters('cbn_location_bubble_image', $media_tag, $location);


        $audio_tag = $location['audio'] ? '<audio controls="controls" style="width:100%"><source type="audio/mp4" src="'.$location['audio'].'"><source type="audio/mpeg" src="'.$location['audio'].'"><source type="audio/wav" src="'.$location['audio'].'"></audio>' : '';

        $address_tag = '';

        if(get_option('cbn_enable_address', 'on') === 'on') {
            $address_tag = ($location['address'] && !get_option('cbn_hide_address')) ? esc_attr($location['address']) : '';

            if(($cbn_enable_gmaps_link === 'on') && $address_tag) {
                $address_tag = '<a title="' . __('go to Google Maps', 'Compass') . '" href="https://www.google.com/maps/search/?api=1&amp;query=' . esc_attr($location['lat']) . '%2C' . esc_attr($location['lng']) . '" target="_blank">' . $address_tag . '</a>';
            }
        }

        $address_tag = ($address_tag != '') ? '<div class="cbn_location_address">'. $address_tag .'</div>' : '';

        if(get_option('cbn_enable_description', 'on') === 'on') {
            $description_tag = '<div class="cbn_location_description">' . wp_kses_post($location['text']) . '</div>';
        } else {
            $description_tag = '';
        }

        $custom_fields = '';
        if(isset($location['custom_fields']) && is_array($location['custom_fields'])) {
            $custom_fields .= '<div class="cbn_location_custom_fields">';
            foreach($location['custom_fields'] as $custom_field) {

                if (!$custom_field['val'] || $custom_field['val'] == '') {
                    continue;
                }

                if(is_array($custom_field['val'])) {
                    array_walk($custom_field['val'], function (&$x) {$x = '<span data-value="' . $x . '">' . $x . '</span>';});
                    $custom_fields .= '<div class="cbn_custom_field"><strong>' . $custom_field['label'] . ':</strong> ' . implode('', $custom_field['val']) . '</div>';
                } else {
                    if(stristr($custom_field['val'], '|')) {

                        //multiple entries separated with | symbol

                        $custom_fields .= '<div class="cbn_custom_field"><strong>' . $custom_field['label'] . ':</strong> ';

                        foreach(explode('|', $custom_field['val']) as $entry) {
                            $entry = trim($entry);

                            if(wp_http_validate_url($entry)) {

                                //URL
                                $custom_fields .= '<a target="_blank" href="' . $entry . '">' . $entry . '</a> ';

                            } elseif(is_email($entry) && ($custom_field['fieldtype'] == 'email')) {

                                //Email
                                $custom_fields .= '<a target="_blank" href="mailto:' . $entry . '">' . $entry . '</a> ';

                            } else {

                                //Text
                                $custom_fields .= '<span data-value="' . $entry . '">' . $entry . '</span>';

                            }
                        }

                        $custom_fields .= '</div>';
                    } else {

                        //single entry

                        if(wp_http_validate_url($custom_field['val'])) {

                            //URL
                            $custom_fields .= '<div class="cbn_custom_field"><strong>' . $custom_field['label'] . ':</strong> <a target="_blank" href="' . $custom_field['val'] . '">' . $custom_field['val'] . '</a></div>';

                        } elseif(is_email($custom_field['val']) && ($custom_field['fieldtype'] == 'email')) {

                            //Email
                            $custom_fields .= '<div class="cbn_custom_field"><strong>' . $custom_field['label'] . ':</strong> <a target="_blank" href="mailto:' . $custom_field['val'] . '">' . $custom_field['val'] . '</a></div>';

                        } else {

                            //Text
                            $custom_fields .= '<div class="cbn_custom_field"><strong>' . $custom_field['label'] . ':</strong> <span data-value="' . $custom_field['val'] . '">' . $custom_field['val'] . '</span></div>';

                        }
                    }
                }

            }
            $custom_fields .= '</div>';
        }

        if(get_option('cbn_enable_single_page')) {
            $link_tag = '<div class="cbn_read_more"><a href="'. get_the_permalink($location['post_id']) .'">' . __('Read more', 'Compass') . '</a></div>';
        } else {
            $link_tag = '';
        }

        // building bubble block content
        $content = '<div class="cbn_location_media">' . $media_tag . '</div>';
        $content .= '<div class="cbn_location_text">';
        $content .= $date_tag;
        $content .= $address_tag;
        $content .= $name_tag;
        $content .= $custom_fields;
        $content .= $description_tag;
        $content .= $audio_tag;
        $content .= $link_tag;
        $content .= '</div>';

        // removing backslash escape
        $content = str_replace("\\", "", $content);

        //HOOK: modify location list item content
        $content = apply_filters('cbn_location_list_item_content', $content, $location);

        // set location
        $cbn_location = [
          'title' => html_entity_decode(esc_attr($location['name'])),
          'lat' => esc_attr($location["lat"]),
          'lng' => esc_attr($location["lng"]),
          'content' => $content,
          'icon' => esc_attr($location["icon"]),
          'types' => (isset($location["types"]) ? $location["types"] : []),
          'post_id' => esc_attr($location["post_id"])
        ];

        ?>

      <div class="cbn-locations-list-item">
        <?php echo $cbn_location['content']; ?>
      </div>

    <?php endforeach; ?>
  </div>

  <?php if ($locations_query->max_num_pages > 1) : ?>
    <nav class="pagination cbn-locations-list-pagination">
      <?php
        echo paginate_links(array(
          //'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
          //'format' => '?paged=%#%',
          'current' => max(1, get_query_var('paged')),
          'total' => $locations_query->max_num_pages,
          'prev_text' => __('&laquo; Prev'),
          'next_text' => __('Next &raquo;'),
        ));
      ?>
    </nav>
  <?php endif; ?>

  <?php wp_reset_postdata(); ?>

</div>
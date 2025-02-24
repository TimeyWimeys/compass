<tr>
  <th scope="row">
    <label><?php echo __('Select marker icon', 'Compass'); ?></label>
  </th>
  <td>
    <div class="marker_icons">
      <?php
      $cbn_marker_icon = get_option('cbn_marker_icon') ? get_option('cbn_marker_icon') : 'default';
    $marker_icon = get_term_meta($tag->term_id, 'cbn_marker_icon', true) ? get_term_meta($tag->term_id, 'cbn_marker_icon', true) : $cbn_marker_icon;
    $items = $this->marker_icons;

    foreach ($items as $val) {
        $selected = ($marker_icon == $val) ? 'checked' : '';
        echo "<label class='$selected'><div class='marker_icon_preview' data-style='$val'></div><input type='radio' name='cbn_marker_icon' $selected value='$val'></label>";
    }

    // Unlock all pro marker icons
    $marker_user_icon = get_term_meta($tag->term_id, 'cbn_marker_user_icon', true) ? get_term_meta($tag->term_id, 'cbn_marker_user_icon', true) : get_option('cbn_marker_user_icon');
    $pro_items = $this->pro_marker_icons;

    foreach ($pro_items as $val) {
        $selected = ($marker_icon == $val) ? 'checked' : '';
        $user_icon_style = ($marker_user_icon) ? "style='background-image: url($marker_user_icon)'" : "";

        echo "<label class='$selected label_marker_user_icon'>";
        echo "<div id='cbn_marker_user_icon_preview' class='marker_icon_preview' data-style='$val' " . $user_icon_style . "></div>";
        echo "<input type='radio' name='cbn_marker_icon' $selected value='$val'>";

        echo "
          <div class='icon_upload'>
            <a href='#' class='cbn_upload_icon_button button button-secondary'>" . __('Upload Icon', 'Compass') . "</a>
            <p class='description'>PNG, max. 100px</p>
            <input type='hidden' id='cbn_marker_user_icon' name='cbn_marker_user_icon' value='" . esc_attr($marker_user_icon) . "'>
          </div>
        ";
        echo "</label>";
    }
    ?>
    </div>
  </td>
</tr>

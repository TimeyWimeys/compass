<div class="marker_icons">
    <?php
    $marker_icon = get_option('oum_marker_icon', 'default');
    $items = $this->marker_icons;
    $marker_user_icon = get_option('oum_marker_user_icon');

    foreach ($items as $val) {
        $selected = ($marker_icon == $val) ? 'checked' : '';
        echo "<label class='$selected'><div class='marker_icon_preview' data-style='$val'></div><input type='radio' name='oum_marker_icon' $selected value='$val'></label>";
    }

    // Custom marker icon upload
    $user_icon_style = ($marker_user_icon) ? "style='background-image: url($marker_user_icon)'" : "";
    echo "<label class='label_marker_user_icon'><div id='oum_marker_user_icon_preview' class='marker_icon_preview' data-style='custom' " . $user_icon_style . "></div><input type='radio' name='oum_marker_icon' value='custom'>";

    echo "
      <div class='icon_upload'>
        <a href='#' class='oum_upload_icon_button button button-secondary'>" . __('Upload Icon', 'open-user-map') . "</a>
        <p class='description'>PNG, max. 100px</p>
        <input type='hidden' id='oum_marker_user_icon' name='oum_marker_user_icon' value='" . esc_attr($marker_user_icon) . "'>
      </div>
    ";
    echo "</label>";
    ?>
</div>

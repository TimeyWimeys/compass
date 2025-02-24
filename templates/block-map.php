<?php require cbn_get_template('partial-map-init.php'); ?>

<div class="Compass">

  <?php
  //TODO: manage variables from partial-map-init.php in a $cbn_settings[]

  $plugin_path = $this->plugin_path;

add_action('wp_footer', function () use (
    $plugin_path,
    $cbn_map_label,
    $types,
    $cbn_marker_types_label,
    $cbn_title_label,
    $cbn_address_label,
    $cbn_description_label,
    $cbn_upload_media_label,
    $cbn_searchaddress_label,
    $cbn_ui_color,
    $cbn_enable_user_notification,
    $text_notify_me_on_publish_label,
    $thankyou_text,
    $map_style,
    $text_notify_me_on_publish_name,
    $text_notify_me_on_publish_email,
    $thankyou_headline
) {

    require_once cbn_get_template('partial-map-add-location.php');

});
?>

  <?php require cbn_get_template('partial-map-render.php'); ?>

</div>
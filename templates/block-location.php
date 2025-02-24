<?php

/**
 * Shortcode Example: [Compass-location value="Favorite color" post_id="12345"(optional) ]
 *
 */

if (!isset($block_attributes['value']) || $block_attributes['value'] == '') {
    return null;
} //no value attribute

$post_id = isset($block_attributes['post_id']) ? $block_attributes['post_id'] : get_the_ID();
$value = cbn_get_location_value($block_attributes['value'], $post_id);

?>

<div class="cbn-location-value" data-value="<?php echo $block_attributes['value']; ?>"><?php echo $value; ?></div>


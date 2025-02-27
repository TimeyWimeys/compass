<?php

// Get pagination parameters
$count = !empty($block_attributes['number']) ? intval($block_attributes['number']) : 12;
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

$image_list = array();
$query = array(
    'post_type' => 'oum-location',
    'posts_per_page' => -1,
    'fields' => 'ids',
);

// Custom Attribute: Filter for user (current or specific user ID)
if (oum_fs()->is__premium_only()):
    if (oum_fs()->can_use_premium_code()):

        if (isset($block_attributes['user']) && $block_attributes['user'] != '') {
            if ($block_attributes['user'] === 'current') {
                // Get current user ID (only if logged in)
                if (is_user_logged_in()) {
                    $current_user_id = get_current_user_id();
                    $query['author'] = $current_user_id;
                } else {
                    // If user is not logged in, don't show any locations
                    $query['author'] = -1; // This will return no results
                }
            } elseif (strpos($block_attributes['user'], 'role:') === 0) {
                // Filter by user role
                $role = str_replace('role:', '', $block_attributes['user']);

                // Get all users with this role
                $users_with_role = get_users(['role' => $role, 'fields' => 'ID']);

                if (!empty($users_with_role)) {
                    $query['author__in'] = $users_with_role;
                } else {
                    // No users with this role, return no results
                    $query['author'] = -1;
                }
            } else {
                // Try to convert to numeric user ID
                $user_id = intval($block_attributes['user']);
                if ($user_id > 0) {
                    $query['author'] = $user_id;
                }
            }
        }

    endif;
endif;

$locations = get_posts($query);

$target_url = (isset($block_attributes['url']) && $block_attributes['url'] != '') ? $block_attributes['url'] : '';

// Collect all images from all locations
foreach ($locations as $post_id) {
    $image_string = get_post_meta($post_id, '_oum_location_image', true);

    if ($image_string) {
        // Split multiple images
        $images = explode('|', $image_string);

        foreach ($images as $image) {
            if (!empty(trim($image))) {
                $image_list[] = array(
                    'post_id' => $post_id,
                    'image_url' => trim($image)
                );
            }
        }
    }
}

// Calculate pagination
$total_images = count($image_list);
$total_pages = ceil($total_images / $count);
$offset = ($paged - 1) * $count;

// Get current page images
$paginated_images = array_slice($image_list, $offset, $count);
?>

<div class="open-user-map-image-gallery">
    <?php foreach ($paginated_images as $image): ?>
        <div class="oum-gallery-item">
            <a href="<?php echo add_query_arg('markerid', $image['post_id'], $target_url); ?>">
                <?php
                // Convert relative path to absolute URL if needed
                $image_url = (strpos($image['image_url'], 'http') !== 0) ? site_url() . $image['image_url'] : $image['image_url'];
                ?>
                <img src="<?php echo esc_url($image_url); ?>" alt="">
            </a>
        </div>
    <?php endforeach; ?>

    <?php if ($total_pages > 1) : ?>
        <nav class="pagination oum-gallery-pagination">
            <?php
            echo paginate_links(array(
                'current' => $paged,
                'total' => $total_pages,
                'prev_text' => __('&laquo; Prev', 'open-user-map'),
                'next_text' => __('Next &raquo;', 'open-user-map'),
            ));
            ?>
        </nav>
    <?php endif; ?>
</div>
<?php
declare(strict_types=1);

// Get pagination parameters
$count = !empty($block_attributes['number']) ? intval($block_attributes['number']) : 12;
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

$image_list = [];
$query = [
    'post_type' => 'cbn-location',
    'posts_per_page' => -1,
    'fields' => 'ids',
];

$locations = get_posts($query);

$target_url = (isset($block_attributes['url']) && $block_attributes['url'] != '') ? $block_attributes['url'] : '';

// Collect all images from all locations
foreach ($locations as $post_id) {
    $image_string = get_post_meta($post_id, '_cbn_location_image', true);

    if ($image_string) {
        // Split multiple images
        $images = explode('|', $image_string);

        foreach ($images as $image) {
            if (!empty(trim($image))) {
                $image_list[] = [
                    'post_id' => $post_id,
                    'image_url' => trim($image),
                ];
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

<div class="Compass-image-gallery">
    <?php foreach ($paginated_images as $image) : ?>
        <div class="cbn-gallery-item">
            <a href="<?php echo add_query_arg('markerid', $image['post_id'], $target_url); ?>">
                <img src="<?php echo $image['image_url']; ?>" alt="">
            </a>
        </div>
    <?php endforeach; ?>

    <?php if ($total_pages > 1) : ?>
        <nav class="pagination cbn-gallery-pagination">
            <?php
            echo paginate_links(
                [
                    'current' => $paged,
                    'total' => $total_pages,
                    'prev_text' => __('&laquo; Prev', 'compass'),
                    'next_text' => __('Next &raquo;', 'compass'),
                ]
            );
            ?>
        </nav>
    <?php endif; ?>
</div>
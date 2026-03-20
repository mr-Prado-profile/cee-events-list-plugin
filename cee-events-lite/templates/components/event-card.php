<?php if (!defined('ABSPATH')) exit; 

$post_id = get_the_ID();
$date_raw = get_post_meta($post_id, 'cee_date', true);
$time_raw = get_post_meta($post_id, 'cee_time', true);
$location = get_post_meta($post_id, 'cee_location', true);
$cta = get_post_meta($post_id, 'cee_cta', true);
$cta_text = get_post_meta($post_id, 'cee_cta_text', true) ?: 'Register Now';
$extra_class = $args['extra_class'] ?? '';

$day = ''; $month = '';
if ($date_raw) {
    $time = strtotime($date_raw);
    if ($time) {
        $day = date('d', $time);
        $month = strtoupper(date('M', $time));
        $display_date = date('F d, Y', $time);
    }
}

$categories = get_the_terms($post_id, 'cee_event_category');
$category_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : '';

$has_thumbnail = has_post_thumbnail();
$thumbnail_url = $has_thumbnail ? get_the_post_thumbnail_url($post_id, 'large') : '';
?>

<div class="cee-item <?= esc_attr($extra_class) ?>">
    <?php if (strpos($extra_class, 'featured-vertical') !== false): ?>
        <!-- Vertical Layout for Featured Events -->
        <div class="cee-item-image">
            <a href="<?= get_permalink() ?>">
                <?php if ($has_thumbnail): ?>
                    <img src="<?= esc_url($thumbnail_url) ?>" alt="<?= get_the_title() ?>">
                <?php endif; ?>
            </a>
            <div class="cee-item-date-overlay">
                <span class="cee-day"><?= esc_html($day) ?></span>
                <span class="cee-month"><?= esc_html($month) ?></span>
            </div>
        </div>
        <div class="cee-item-content">
            <h3><a href="<?= get_permalink() ?>"><?= get_the_title() ?></a></h3>
            <p><?= get_the_excerpt() ?></p>
        </div>

    <?php else: ?>
        <!-- Two-Column Horizontal Layout for Main List -->
        <div class="cee-item-image">
            <a href="<?= get_permalink() ?>">
                <?php if ($has_thumbnail): ?>
                    <img src="<?= esc_url($thumbnail_url) ?>" alt="<?= get_the_title() ?>">
                <?php endif; ?>
            </a>
        </div>
        <div class="cee-item-details">
            <div class="cee-item-top-info">
                <?php if ($category_name): ?>
                    <span class="cee-item-category"><?= esc_html($category_name) ?></span>
                <?php endif; ?>
                <span class="cee-item-datetime">
                    <i class="eicon-calendar"></i> <?= esc_html($display_date) ?> 
                    <?php if ($time_raw): ?> | <i class="eicon-clock"></i> <?= esc_html(date('h:i A', strtotime($time_raw))) ?><?php endif; ?>
                </span>
            </div>
            <h3><a href="<?= get_permalink() ?>"><?= get_the_title() ?></a></h3>
            <p><?= get_the_excerpt() ?></p>
            <?php if ($location): ?>
                <div class="cee-item-location">
                    <i class="eicon-location-arrow"></i> <?= esc_html($location) ?>
                </div>
            <?php endif; ?>
            <div class="cee-item-actions">
                <a href="<?= get_permalink() ?>" class="cee-btn"><?= __('View Event Details', 'cee-events-lite') ?></a>
                <button class="cee-btn cee-open-modal" style="margin-left: 10px;"><?= __('Register', 'cee-events-lite') ?></button>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
/**
 * Single Event Template.
 */

get_header();

// For Block Themes
if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() && ! locate_template( 'header.php' ) ) {
	block_template_part( 'header' );
}

while (have_posts()) :
    the_post();
    $id = get_the_ID();
    $date_raw = get_post_meta($id, 'cee_date', true);
    $time_raw = get_post_meta($id, 'cee_time', true);
    $location = get_post_meta($id, 'cee_location', true);
    $display_date = $date_raw ? date('F d, Y', strtotime($date_raw)) : '';
    $display_time = $time_raw ? date('h:i A', strtotime($time_raw)) : '';
    $img = get_the_post_thumbnail_url($id, 'large');

    $categories = get_the_terms($id, 'cee_event_category');
    $category_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : '';
    ?>

    <div class="cee-single-container">
        
        <!-- Row 1: Header Banner -->
        <section class="cee-header-banner">
            <div class="cee-banner-overlay"></div>
            <div class="cee-banner-inner">
                <h1><?php the_title(); ?></h1>
                <div class="cee-breadcrumb-nav">
                    <?php if (function_exists('cee_breadcrumbs')) cee_breadcrumbs(); ?>
                </div>
            </div>
        </section>

        <div class="cee-container-narrow">
            <!-- Row 2: Profile Overview -->
            <section class="cee-profile-overview">
                <div class="cee-profile-left">
                    <?php if ($img): ?>
                        <div class="cee-featured-img">
                            <img src="<?php echo esc_url($img); ?>" alt="<?php the_title_attribute(); ?>">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="cee-profile-right">
                    <div class="cee-info-card">
                        <ul class="cee-details-list">
                            <?php if ($category_name): ?>
                                <li>
                                    <i class="dashicons dashicons-tag"></i>
                                    <span class="label"><?php _e('Category:', 'cee-events-lite'); ?></span>
                                    <span class="value"><?php echo esc_html($category_name); ?></span>
                                </li>
                            <?php endif; ?>
                            <li>
                                <i class="dashicons dashicons-id"></i>
                                <span class="label"><?php _e('Event Title:', 'cee-events-lite'); ?></span>
                                <span class="value"><?php the_title(); ?></span>
                            </li>
                            <li>
                                <i class="dashicons dashicons-calendar-alt"></i>
                                <span class="label"><?php _e('Date & Time:', 'cee-events-lite'); ?></span>
                                <span class="value">
                                    <?php echo esc_html($display_date); ?>
                                    <?php if ($display_time) echo ' | ' . esc_html($display_time); ?>
                                </span>
                            </li>
                            <?php if ($location): ?>
                                <li>
                                    <i class="dashicons dashicons-location"></i>
                                    <span class="label"><?php _e('Location:', 'cee-events-lite'); ?></span>
                                    <span class="value"><?php echo esc_html($location); ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Row 3: Detailed Content & Registration -->
            <section class="cee-detailed-content">
                <div class="cee-full-description">
                    <?php the_content(); ?>
                </div>
                <div class="cee-registration-action">
                    <button class="cee-btn-register cee-open-modal"><?php _e('Register', 'cee-events-lite'); ?></button>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal Placeholder -->
    <div id="cee-reg-modal" class="cee-modal">
        <div class="cee-modal-content">
            <span class="cee-modal-close">&times;</span>
            <h3><?php _e('Registration Coming Soon', 'cee-events-lite'); ?></h3>
            <p><?php _e('We are currently finalizing the registration details for this event. Please check back soon!', 'cee-events-lite'); ?></p>
        </div>
    </div>

    <?php
endwhile;

// For Block Themes
if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() && ! locate_template( 'footer.php' ) ) {
	block_template_part( 'footer' );
}

get_footer();

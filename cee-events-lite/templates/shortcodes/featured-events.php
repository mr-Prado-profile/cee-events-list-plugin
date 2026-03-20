<?php if (!defined('ABSPATH')) exit; ?>

<div class="cee-featured-section">
    <div class="cee-grid">
        <?php if ($query->have_posts()) : ?>
            <?php while ($query->have_posts()) : $query->the_post(); 
                cee_get_template('components/event-card', ['extra_class' => 'featured-vertical']);
            endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
            <p>No featured events at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Registration Modal -->
<div id="cee-reg-modal" class="cee-modal">
    <div class="cee-modal-content">
        <span class="cee-modal-close">&times;</span>
        <h3><?php _e('Registration Coming Soon', 'cee-events-lite'); ?></h3>
        <p><?php _e('We are currently finalizing the registration details for this event. Please check back soon!', 'cee-events-lite'); ?></p>
    </div>
</div>

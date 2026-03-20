<?php if (!defined('ABSPATH')) exit; ?>

<div class="cee-container">
    <div class="cee-main">
        <div class="cee-top-row">
            <div class="cee-search-wrap">
                <div class="cee-search-input-wrap">
                    <i class="eicon-search"></i>
                    <input type="text" id="cee-search" placeholder="Search events...">
                </div>
            </div>
            <div class="cee-category-wrap">
                <select id="cee-category">
                    <option value="">All Categories</option>
                    <?php if (!empty($categories)) : ?>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= esc_attr($cat->slug) ?>"><?= esc_html($cat->name) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>



        <div id="cee-results" class="cee-grid"></div>
        <div id="cee-pagination"></div>
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

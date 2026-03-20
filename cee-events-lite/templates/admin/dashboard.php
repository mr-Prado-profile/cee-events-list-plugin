<?php 
if (!defined('ABSPATH')) exit; 
$is_edit = $is_edit ?? false;
$event = $event ?? [];
?>

<div class="wrap">
    <h1><?= $is_edit ? 'Edit Event' : 'Quick Register New Event' ?></h1>
    <?= $message ?>
    
    <form method="post" enctype="multipart/form-data" style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 600px; margin-top: 20px; border-radius: 4px;">
        <?php wp_nonce_field('cee_quick_register', 'cee_quick_register_nonce'); ?>
        
        <table class="form-table">
            <tr>
                <th><label for="event_title">Event Title</label></th>
                <td><input type="text" name="event_title" id="event_title" class="regular-text" value="<?= esc_attr($event['title'] ?? '') ?>" required></td>
            </tr>
            <tr>
                <th><label for="event_image">Featured Image</label></th>
                <td>
                    <?php $has_img = !empty($event['image_url']); ?>
                    <div id="cee-image-preview" style="margin-bottom: 10px; <?= $has_img ? '' : 'display: none;' ?>">
                        <img src="<?= esc_url($event['image_url'] ?? '') ?>" style="max-width: 150px; height: auto; border: 1px solid #ccd0d4; padding: 5px;">
                    </div>
                    <input type="hidden" name="event_image_id" id="event_image_id" value="<?= esc_attr($event['image_id'] ?? '') ?>">
                    <button type="button" class="button cee-select-image"><?= $has_img ? 'Change Image' : 'Select Image' ?></button>
                    <button type="button" class="button cee-remove-image" style="<?= $has_img ? '' : 'display: none;' ?> color: #a00; border-color: #a00;">Remove Image</button>
                </td>
            </tr>
            <tr>
                <th><label for="event_date">Event Date</label></th>
                <td><input type="date" name="event_date" id="event_date" class="regular-text" value="<?= esc_attr($event['date'] ?? '') ?>" required></td>
            </tr>
            <tr>
                <th><label for="event_time">Event Time</label></th>
                <td><input type="time" name="event_time" id="event_time" class="regular-text" value="<?= esc_attr($event['time'] ?? '') ?>"></td>
            </tr>
            <tr>
                <th><label for="event_location">Event Location</label></th>
                <td><input type="text" name="event_location" id="event_location" class="regular-text" value="<?= esc_attr($event['location'] ?? '') ?>" placeholder="e.g. Grand Ballroom, Hotel"></td>
            </tr>
            <tr>
                <th><label for="event_category">Category</label></th>
                <td>
                    <select name="event_category" id="event_category" style="width: 25em;">
                        <option value="0">Select Category</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat->term_id ?>" <?php selected($event['category'] ?? 0, $cat->term_id); ?>><?= esc_html($cat->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="event_description">Description</label></th>
                <td><textarea name="event_description" id="event_description" rows="5" class="large-text"><?= esc_textarea($event['description'] ?? '') ?></textarea></td>
            </tr>
            <tr>
                <th><label for="cta_link">CTA Link (URL)</label></th>
                <td><input type="url" name="cta_link" id="cta_link" class="regular-text" value="<?= esc_url($event['cta_link'] ?? '') ?>" placeholder="https://example.com"></td>
            </tr>
            <tr>
                <th><label for="cta_text">CTA Button Text</label></th>
                <td><input type="text" name="cta_text" id="cta_text" class="regular-text" value="<?= esc_attr($event['cta_text'] ?? 'Register') ?>"></td>
            </tr>
            <tr>
                <th><label for="event_featured">Featured Event</label></th>
                <td><input type="checkbox" name="event_featured" id="event_featured" value="1" <?php checked($event['featured'] ?? '', 'yes'); ?>> Highlight on homepage</td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?= $is_edit ? 'Update Event' : 'Create Event' ?>">
            <?php if ($is_edit): ?>
                <a href="<?= admin_url('edit.php?post_type=cee_event') ?>" class="button" style="margin-left: 10px;">Back to Events</a>
            <?php endif; ?>
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($){
    var frame;
    $('.cee-select-image').on('click', function(e) {
        e.preventDefault();
        
        if (frame) {
            frame.open();
            return;
        }
        
        frame = wp.media({
            title: 'Select Event Featured Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#event_image_id').val(attachment.id);
            $('#cee-image-preview img').attr('src', attachment.url);
            $('#cee-image-preview').show();
            $('.cee-remove-image').show();
            $('.cee-select-image').text('Change Image');
        });
        
        frame.open();
    });
    
    $('.cee-remove-image').on('click', function(e) {
        e.preventDefault();
        $('#event_image_id').val('');
        $('#cee-image-preview').hide().find('img').attr('src', '');
        $(this).hide();
        $('.cee-select-image').text('Select Image');
    });
});
</script>

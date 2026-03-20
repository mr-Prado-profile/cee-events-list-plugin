<?php
if (!defined('ABSPATH')) exit;

/**
 * Add "Quick Register" and "Edit" submenus
 */
add_action('admin_menu', function() {
    // Remove default "Add New"
    remove_submenu_page('edit.php?post_type=cee_event', 'post-new.php?post_type=cee_event');

    add_submenu_page(
        'edit.php?post_type=cee_event',
        'Quick Register Event',
        'Quick Register',
        'manage_options',
        'cee-quick-register',
        'cee_render_quick_register_form'
    );

    // Hidden Edit Page
    add_submenu_page(
        null, // Hidden
        'Edit Event',
        'Edit Event',
        'manage_options',
        'cee-edit-event',
        'cee_render_quick_register_form'
    );
});

/**
 * Filter Edit link to point to custom editor
 */
add_filter('get_edit_post_link', function($link, $post_id, $context) {
    if (get_post_type($post_id) === 'cee_event') {
        return admin_url('edit.php?post_type=cee_event&page=cee-edit-event&post_id=' . $post_id);
    }
    return $link;
}, 10, 3);

/**
 * Redirect standard post.php editor for cee_event
 */
add_action('admin_init', function() {
    global $pagenow;
    if ($pagenow === 'post.php' && isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] === 'edit') {
        $post_id = intval($_GET['post']);
        if (get_post_type($post_id) === 'cee_event') {
            wp_redirect(admin_url('edit.php?post_type=cee_event&page=cee-edit-event&post_id=' . $post_id));
            exit;
        }
    }
});

/**
 * Hide default admin UI elements
 */
add_action('admin_head', function() {
    $screen = get_current_screen();
    if ($screen->post_type === 'cee_event') {
        // Hide Add New button on list
        if ($screen->id === 'edit-cee_event') {
            echo '<style>.page-title-action { display: none !important; }</style>';
        }
        // Redirect if on default editor screen (fallback)
        if ($screen->id === 'cee_event') {
             // Redirection handled above in admin_init
        }
    }
});

/**
 * Enqueue Media scripts
 */
add_action('admin_enqueue_scripts', function($hook) {
    if (strpos($hook, 'cee-quick-register') !== false || strpos($hook, 'cee-edit-event') !== false) {
        wp_enqueue_media();
    }
});

/**
 * Render the Registration/Edit Form
 */
function cee_render_quick_register_form() {
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    $is_edit = ($post_id > 0);
    $message = '';

    // Process Form
    if (isset($_POST['cee_quick_register_nonce']) && wp_verify_nonce($_POST['cee_quick_register_nonce'], 'cee_quick_register')) {
        $title = sanitize_text_field($_POST['event_title']);
        $date = sanitize_text_field($_POST['event_date']);
        $time = sanitize_text_field($_POST['event_time']);
        $location = sanitize_text_field($_POST['event_location']);
        $description = wp_kses_post($_POST['event_description']);
        $cta_link = esc_url_raw($_POST['cta_link']);
        $cta_text = sanitize_text_field($_POST['cta_text']);
        $category = intval($_POST['event_category']);
        $featured = isset($_POST['event_featured']) ? 'yes' : 'no';
        $image_id = intval($_POST['event_image_id'] ?? 0);

        if ($title && $date) {
            $post_data = [
                'post_title'   => $title,
                'post_content' => $description,
                'post_status'  => 'publish',
                'post_type'    => 'cee_event',
            ];

            if ($is_edit) {
                $post_data['ID'] = $post_id;
                $result = wp_update_post($post_data);
            } else {
                $result = wp_insert_post($post_data);
            }

            if ($result && !is_wp_error($result)) {
                $target_id = $is_edit ? $post_id : $result;
                update_post_meta($target_id, 'cee_date', $date);
                update_post_meta($target_id, 'cee_time', $time);
                update_post_meta($target_id, 'cee_location', $location);
                update_post_meta($target_id, 'cee_cta', $cta_link);
                update_post_meta($target_id, 'cee_cta_text', $cta_text);
                update_post_meta($target_id, 'cee_featured', $featured);
                
                if ($image_id > 0) {
                    set_post_thumbnail($target_id, $image_id);
                } else if ($is_edit) {
                    delete_post_thumbnail($target_id);
                }

                if ($category > 0) {
                    wp_set_post_terms($target_id, [$category], 'cee_event_category');
                }

                $msg_text = $is_edit ? 'Event updated successfully!' : 'Event registered successfully!';
                $message = '<div class="updated"><p>' . $msg_text . ' <a href="' . get_permalink($target_id) . '" target="_blank">View Event</a></p></div>';
                
                if (!$is_edit) {
                    // Force refresh with post_id for redirect to edit mode if desired, or just stay
                }
            } else {
                $message = '<div class="error"><p>Failed to save event.</p></div>';
            }
        } else {
            $message = '<div class="error"><p>Please provide both Title and Date.</p></div>';
        }
    }

    $event_data = null;
    if ($is_edit) {
        $post = get_post($post_id);
        if ($post && $post->post_type === 'cee_event') {
            $terms = wp_get_post_terms($post_id, 'cee_event_category');
            $event_data = [
                'title'       => $post->post_title,
                'description' => $post->post_content,
                'date'        => get_post_meta($post_id, 'cee_date', true),
                'time'        => get_post_meta($post_id, 'cee_time', true),
                'location'    => get_post_meta($post_id, 'cee_location', true),
                'cta_link'    => get_post_meta($post_id, 'cee_cta', true),
                'cta_text'    => get_post_meta($post_id, 'cee_cta_text', true),
                'featured'    => get_post_meta($post_id, 'cee_featured', true),
                'category'    => !empty($terms) ? $terms[0]->term_id : 0,
                'image_id'    => get_post_thumbnail_id($post_id),
                'image_url'   => get_the_post_thumbnail_url($post_id, 'medium'),
            ];
        } else {
            $is_edit = false; // Post not found or wrong type
        }
    }

    $categories = get_terms([
        'taxonomy' => 'cee_event_category',
        'hide_empty' => false,
    ]);

    cee_get_template('admin/dashboard', [
        'message'    => $message,
        'categories' => $categories,
        'is_edit'    => $is_edit,
        'event'      => $event_data
    ]);
}

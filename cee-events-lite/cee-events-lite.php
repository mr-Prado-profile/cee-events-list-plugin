
<?php
/**
 * Plugin Name: CEE Events Lite
 * Description: Lightweight Events plugin with search, filter, calendar, and Elementor support.
 * Version: 1.7.1
 */

if (!defined('ABSPATH')) exit;

define('CEE_EVENTS_VERSION', '1.7');
define('CEE_EVENTS_PATH', plugin_dir_path(__FILE__));
define('CEE_EVENTS_URL', plugin_dir_url(__FILE__));

// Load Core Logic
require_once CEE_EVENTS_PATH . 'inc/post-type.php';
require_once CEE_EVENTS_PATH . 'inc/meta-fields.php';
require_once CEE_EVENTS_PATH . 'inc/ajax.php';
require_once CEE_EVENTS_PATH . 'inc/shortcode.php';
require_once CEE_EVENTS_PATH . 'inc/admin-dashboard.php';

/**
 * Template Loader Helper
 */
function cee_get_template($template_name, $args = []) {
    if ($args && is_array($args)) {
        extract($args);
    }

    $template_path = CEE_EVENTS_PATH . 'templates/' . $template_name . '.php';

    if (file_exists($template_path)) {
        include $template_path;
    }
}

// Data Migration: Normalize dates to Y-m-d
function cee_migrate_dates(){
    $events = get_posts(['post_type'=>'cee_event','posts_per_page'=>-1, 'post_status' => 'any']);
    foreach($events as $event){
        $date = get_post_meta($event->ID,'cee_date',true);
        if($date && strpos($date,'-') === false && strlen($date) === 8){
            // Convert YYYYMMDD to YYYY-MM-DD
            $new_date = substr($date,0,4).'-'.substr($date,4,2).'-'.substr($date,6,2);
            update_post_meta($event->ID,'cee_date',$new_date);
        }
    }
}
add_action('init', 'cee_migrate_dates');

function cee_assets(){
    $css_path = CEE_EVENTS_PATH . 'assets/css/events.css';
    $js_path  = CEE_EVENTS_PATH . 'assets/js/events.js';

    $css_version = file_exists($css_path) ? filemtime($css_path) : '1.0';
    $js_version  = file_exists($js_path) ? filemtime($js_path) : '1.0';

    wp_enqueue_style('cee-style', CEE_EVENTS_URL . 'assets/css/events.css', [], $css_version);
    wp_enqueue_style('dashicons');
    wp_enqueue_script('cee-js', CEE_EVENTS_URL . 'assets/js/events.js', ['jquery'], $js_version, true);

    wp_localize_script('cee-js','cee_ajax',[
        'ajax_url'=>admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts','cee_assets');

/**
 * Load single event template from plugin.
 */
function cee_single_template($single) {
    global $post;
    if ($post->post_type === 'cee_event') {
        if (file_exists(CEE_EVENTS_PATH . 'templates/single-event.php')) {
            return CEE_EVENTS_PATH . 'templates/single-event.php';
        }
    }
    return $single;
}
add_filter('single_template', 'cee_single_template');

/**
 * Breadcrumb Helper.
 */
function cee_breadcrumbs() {
    global $post;
    $home_url = home_url('/');
    $events_url = home_url('/events/');

    echo '<nav class="cee-breadcrumbs">';
    echo '<a href="' . esc_url($home_url) . '">' . __('Home', 'cee-events-lite') . '</a>';
    echo ' <span class="sep">></span> ';
    echo '<a href="' . esc_url($events_url) . '">' . __('Events', 'cee-events-lite') . '</a>';
    echo ' <span class="sep">></span> ';
    echo '<span class="current">' . get_the_title() . '</span>';
    echo '</nav>';
}

// Register Elementor Widget
add_action('elementor/widgets/register', function($widgets_manager){
    if(file_exists(CEE_EVENTS_PATH . 'inc/elementor-widget.php')){
        require_once CEE_EVENTS_PATH . 'inc/elementor-widget.php';
        $widgets_manager->register(new \CEE_Events_Widget());
    }
});

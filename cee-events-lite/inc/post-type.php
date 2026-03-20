
<?php
if (!defined('ABSPATH')) exit;

function cee_register_event_cpt(){
    $labels = [
        'name'               => 'Events',
        'singular_name'      => 'Event',
        'add_new'            => 'Register New',
        'add_new_item'       => 'Register Custom Event',
        'edit_item'          => 'Edit Event',
        'new_item'           => 'New Event',
        'view_item'          => 'View Event',
        'search_items'       => 'Search Events',
        'not_found'          => 'No events found',
        'not_found_in_trash' => 'No events found in Trash',
        'menu_name'          => 'Events',
    ];

    register_post_type('cee_event',[
        'labels'=>$labels,
        'public'=>true,
        'menu_icon'=>'dashicons-calendar-alt',
        'supports'=>['title','editor','thumbnail'],
        'has_archive'=>true,
        'show_in_rest'=>true
    ]);

    register_taxonomy('cee_event_category', 'cee_event', [
        'labels' => [
            'name' => 'Event Categories',
            'singular_name' => 'Event Category',
        ],
        'hierarchical' => true,
        'show_in_rest' => true,
        'public' => true,
    ]);
}
add_action('init','cee_register_event_cpt');

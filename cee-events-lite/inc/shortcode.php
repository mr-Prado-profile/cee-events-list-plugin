
<?php
function cee_shortcode(){
    ob_start();
    $categories = get_terms([
        'taxonomy' => 'cee_event_category',
        'hide_empty' => false,
    ]);
    
    cee_get_template('shortcodes/main-events', ['categories' => $categories]);

    return ob_get_clean();
}
add_shortcode('cee_events','cee_shortcode');

/**
 * Featured Events Shortcode [cee_featured_events]
 */
function cee_featured_events_shortcode($atts) {
    $atts = shortcode_atts(['limit' => 4], $atts);
    $today = current_time('Y-m-d');

    $args = [
        'post_type'      => 'cee_event',
        'posts_per_page' => intval($atts['limit']),
        'meta_key'       => 'cee_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => [
            'relation' => 'AND',
            [
                'key'   => 'cee_featured',
                'value' => 'yes'
            ],
            [
                'relation' => 'OR',
                [
                    'key'     => 'cee_date',
                    'value'   => $today,
                    'compare' => '>=',
                    'type'    => 'DATE'
                ],
                [
                    'key'     => 'cee_date',
                    'value'   => str_replace('-', '', $today),
                    'compare' => '>=',
                    'type'    => 'NUMERIC'
                ]
            ]
        ]
    ];

    $query = new WP_Query($args);
    
    ob_start();
    cee_get_template('shortcodes/featured-events', ['query' => $query]);
    return ob_get_clean();
}
add_shortcode('cee_featured_events', 'cee_featured_events_shortcode');

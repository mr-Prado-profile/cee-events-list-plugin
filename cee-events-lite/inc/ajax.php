
<?php
add_action('wp_ajax_cee_filter','cee_filter_events');
add_action('wp_ajax_nopriv_cee_filter','cee_filter_events');

function cee_filter_events(){

    $keyword=sanitize_text_field($_POST['keyword'] ?? '');
    $category=sanitize_text_field($_POST['category'] ?? '');
    $paged = intval($_POST['paged'] ?? 1);
    
    // Use WordPress local time
    $today = current_time('Y-m-d');

    $meta_query = ['relation' => 'AND'];
    $compare = '>='; // Always upcoming

    // Build robust date query to handle both formats (hyphenated and legacy)
    $date_query = [
        'relation' => 'OR',
        [
            'key'     => 'cee_date',
            'value'   => $today,
            'compare' => $compare,
            'type'    => 'DATE'
        ],
        [
            'key'     => 'cee_date',
            'value'   => str_replace('-', '', $today),
            'compare' => $compare,
            'type'    => 'NUMERIC'
        ]
    ];
    
    $meta_query['date_clause'] = $date_query;

    $args = [
        'post_type'      => 'cee_event',
        's'              => $keyword,
        'meta_query'     => $meta_query,
        'meta_key'       => 'cee_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'posts_per_page' => 4,
        'paged'          => $paged,
        'no_found_rows'  => false, // Need for pagination calculation
    ];

    if($category){
        $args['tax_query'] = [
            [
                'taxonomy' => 'cee_event_category',
                'field'    => 'slug',
                'terms'    => $category,
            ]
        ];
    }

    $query=new WP_Query($args);
    
    $html = '';
    
    if($query->have_posts()){
        while($query->have_posts()){ $query->the_post();
            ob_start();
            cee_get_template('components/event-card', ['extra_class' => 'fade-in']);
            $html .= ob_get_clean();
        }
    }else{
        $html = '<p>No events found.</p>';
    }

    $pagination = paginate_links( [
        'total'   => $query->max_num_pages,
        'current' => $paged,
        'format'  => '?paged=%#%',
        'type'    => 'plain',
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
    ] );

    wp_send_json_success([
        'html' => $html,
        'pagination' => $pagination,
        'max_pages' => $query->max_num_pages,
        'current_page' => $paged
    ]);
}

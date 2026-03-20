
<?php
function cee_add_event_metabox(){
    add_meta_box('cee_event_data','Event Settings','cee_event_fields','cee_event');
}
add_action('add_meta_boxes','cee_add_event_metabox');

function cee_event_fields($post){
    $date=get_post_meta($post->ID,'cee_date',true);
    // If old format YYYYMMDD, convert to YYYY-MM-DD for the date input
    if($date && strpos($date,'-') === false && strlen($date) === 8) {
        $date = substr($date,0,4).'-'.substr($date,4,2).'-'.substr($date,6,2);
    }
    $cta=get_post_meta($post->ID,'cee_cta',true);
    ?>
    <label>Event Date</label>
    <input type="date" name="cee_date" value="<?=esc_attr($date)?>" style="width:100%;">
    <br><br>
    <label>CTA Link</label>
    <input type="url" name="cee_cta" value="<?=esc_attr($cta)?>" style="width:100%;">
    <br><br>
    <label>CTA Button Text</label>
    <input type="text" name="cee_cta_text" value="<?=esc_attr(get_post_meta($post->ID,'cee_cta_text',true) ?: 'Register')?>" style="width:100%;" placeholder="e.g. Register Now">
    <br><br>
    <label>
        <input type="checkbox" name="cee_featured" value="yes" <?php checked(get_post_meta($post->ID,'cee_featured',true), 'yes'); ?>>
        Featured Event (Highlights this event)
    </label>
    <?php
}

function cee_save_event($post_id){
    if(isset($_POST['cee_date'])){
        update_post_meta($post_id,'cee_date',sanitize_text_field($_POST['cee_date']));
    }
    if(isset($_POST['cee_cta'])){
        update_post_meta($post_id,'cee_cta',esc_url_raw($_POST['cee_cta']));
    }
    if(isset($_POST['cee_cta_text'])){
        update_post_meta($post_id,'cee_cta_text',sanitize_text_field($_POST['cee_cta_text']));
    }
    update_post_meta($post_id,'cee_featured', isset($_POST['cee_featured']) ? 'yes' : 'no');
}
add_action('save_post','cee_save_event');

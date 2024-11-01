<?php
/*
 * =======================================
 * WEBSITE SPEED CORE
 * =======================================
 *
 *
*/
$options = get_option('scfw_speed_test_options');

if ( empty($options['scfw_google_api_key']) ) {
    wp_clear_scheduled_hook('scfw_run_api_cron');
    update_option('scfw_speed_test_options', $options);
    return;
}

if (! isset( $_POST['referer'] ) || ! wp_verify_nonce( $_POST['referer'], $action ) ){ 
    if ( isset($_POST['run_tester']) ) {

        wp_clear_scheduled_hook('scfw_run_api_cron');
        $options['check_interval'] = 900;
        $options['is_mobile_finished'] = false;
        $options['is_desktop_finished'] = false;
        update_option('scfw_speed_test_options', $options);
          $msg = 'The Google PageSpeedis running in the background to gather report for each URL. To display a report for each url, it could take some time. During that time, you can navigate away';
         scfw_notice_success($msg);
    }
 }
function scfw_get_url_to_check($st) {
    $url_to_check = array();
    $options = get_option('scfw_speed_test_options');
    //Get PAGE URLs
    $pages = get_pages();
    foreach ( $pages as $page ) {
        if (!isset($options['scfw_url_to_check'][$page->ID]['is_check_' . $st])) {
            $url_to_check[$page->ID]['url'] = get_page_link($page->ID);
            $url_to_check[$page->ID]['id'] = $page->ID;
            $url_to_check[$page->ID]['type'] = 'PAGE';
        }
    }
    //Get POST URLs
     $args =  array( 
        'numberposts'  => -1, 
        'post_type'         => 'post',
        'post_status'       => 'publish'
    );
    $posts = get_posts();
    foreach ($posts as $post) {
        if ( !isset($options['scfw_url_to_check'][$post->ID]['is_check_' . $st]) ) {
            $url_to_check[$post->ID]['url'] = get_page_link($post->ID);
            $url_to_check[$post->ID]['id'] = $post->ID;
            $url_to_check[$post->ID]['type'] = 'POST';
        }
    }

    if ( empty($url_to_check) ) {
        $options['is_' . $st . '_finished'] = true;
        update_option('scfw_speed_test_options', $options);
    }
    return $url_to_check;
}
function scfw_run_api_cron($schedules) {
    $options = get_option('scfw_speed_test_options');
    if ( $options['is_mobile_finished'] == true and $options['is_desktop_finished'] == true ) {
        $options['check_interval'] = 60 * 60* 24;
        $options['scfw_url_to_check'] = array();
        update_option('scfw_speed_test_options', $options);
    } else {
        $options['check_interval'] = 300;
        update_option('scfw_speed_test_options', $options);
    }
    if ( !isset($schedules["ss_every_day_get"]) ) {
        $schedules["ss_every_day_get"] = array('interval' => $options['check_interval'], 'display' => __('Once Daily'));
    }
    return $schedules;
}
add_filter('cron_schedules', 'scfw_run_api_cron');
if ( !wp_next_scheduled('scfw_run_api_cron') ) {
    wp_schedule_event(time(), 'ss_every_day_get', 'scfw_run_api_cron');
}
add_action('scfw_run_api_cron', 'scfw_run_api');
function scfw_run_api() {
    $options = get_option('scfw_speed_test_options');
    $st = array('desktop', 'mobile');
    for ( $i = 0;$i < count($st);$i++ ) {
        $urls_to_check = scfw_get_url_to_check($st[$i]);
        foreach ( $urls_to_check as $url_to_check ) {
            scfw_run_pagespeed_API($url_to_check['url'], $url_to_check['id'], $url_to_check['type'], $st[$i]);
        }
    }
}
?>
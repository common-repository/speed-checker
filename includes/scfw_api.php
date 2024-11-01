<?php
/*
 * =======================================
 * WEBSITE SPEED API
 * =======================================
 *
 *
*/
function scfw_run_pagespeed_API($url, $id, $type, $strategy) {
    $api_baseurl = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';
    $options = scfw_get_option();
    $query_args = array('url' => esc_url_raw($url), 'key' => sanitize_text_field($options['scfw_google_api_key']), 'locale' => 'en_US', 'strategy' => $strategy);
    $api_url = add_query_arg($query_args, $api_baseurl);
    $api_request = wp_remote_get($api_url, array('timeout' => 5000));
    $api_response_code = wp_remote_retrieve_response_code($api_request);
    $api_response_body = json_decode(wp_remote_retrieve_body($api_request));
    $score = 0;
    foreach ($api_response_body->lighthouseResult->audits as $index) {
        $score = $api_response_body->lighthouseResult->categories->performance->score * 100;
    }
    if ( $api_response_code != 400) {
            $options['scfw_report'][$id]['url'] = $url;
            $options['scfw_report'][$id]['type'] = $type;
            $options['scfw_report'][$id][$strategy . '_score'] = $score;
            $options['scfw_report'][$id]['last_updated'] = time();
             if($strategy == 'desktop') {
                 $options['scfw_url_to_check'][$id]['is_check_desktop'] = true;
                 $options['scfw_url_to_check'][$id]['is_check_mobile'] =  $options['scfw_url_to_check'][$id]['is_check_mobile'];
             }
             if($strategy == 'mobile') {
                 $options['scfw_url_to_check'][$id]['is_check_mobile'] = true;
                 $options['scfw_url_to_check'][$id]['is_check_desktop'] =  $options['scfw_url_to_check'][$id]['is_check_desktop'];

             }    
          }
    update_option('scfw_speed_test_options', $options);  
 }
?>
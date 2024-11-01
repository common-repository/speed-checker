<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}
$option_name = 'scfw_speed_test_options';

delete_option( $option_name );
?>
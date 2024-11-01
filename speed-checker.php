<?php
/**
 * Plugin Name:     Speed Checker
 * Description:     Get your WordPress webiste mobile and desktop score from the Google Pagespeed API to your WordPress dashboard
 * Version:         1.0
 * Author:          Anand Chandwani
 * Author URI:      https://anandchandwani.com
 * Text Domain:     scfw
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
// If this file is called directly, abort.

if ( !defined( 'ABSPATH' ) ) {
  die( 'We\'re sorry, but you can not directly access this file.' );
}
define('SCFW_PLUGIN_VERSION','1.0');
define('SCFW_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SCFW_PLUGIN_DIR',plugin_dir_path(__FILE__));

function scfw_plugin_activation() {
    if (!get_option('scfw_speed_test_options')) {
        //not present, so add new options
        $options = array(
             'scfw_script_run_check' => false,
             'scfw_google_api_key'   => '',
             'scfw_type'              => 'desktop',
             'scfw_report'            => array(),
             'scfw_url_to_check'      => array(),
             'check_interval'         => 600,
             'is_mobile_finished'     => false,
             'is_desktop_finished'     => false,
         );
        update_option('scfw_speed_test_options', $options);
    }
}
 register_activation_hook( __FILE__, 'scfw_plugin_activation' );
 function scfw_get_option() {
    return get_option('scfw_speed_test_options');
 }
 
 add_filter('plugin_action_links_'. plugin_basename( __FILE__ ),'scfw_plugin_action_links');

 function scfw_plugin_action_links($actions) {
  $link = array(
      '<a href="' . admin_url( 'tools.php?page=scfw' ) . '">Report</a>',
   );
   $actions = array_merge( $actions, $link );
   return $actions;

 }

 function scfw_notice_success ($msg) {?>

  <div class="notice notice-success is-dismissible">
    <p><?php esc_html_e($msg,'scfw');?></p>
  </div>
  
  <?php }
function scfw_notice_error ($msg) {
   ?>
   <div class="notice notice-fail is-dismissible">
     <p><?php esc_html_e( $msg, 'scfw' );?><p>
    </div>
<?php }
 require 'admin/scfw_admin.php';
 require 'includes/scfw_core.php';
 require 'includes/scfw_api.php';  
?>
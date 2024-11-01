<?php
/*
 * =======================================
 * WEBSITE SPEED ADMIN
 * =======================================
 *
 *
*/
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
//add_action('init', 'wsc_plugin_activation');
add_action('admin_menu', 'scfw_register_my_custom_submenu_page');
function scfw_register_my_custom_submenu_page() {
    add_submenu_page('tools.php', 'Speed Checker', 'Speed Checker', 'manage_options', 'scfw', 'scfw_page_callback');
}
function scfw_page_callback() { ?>

    <div style="margin-top:20px;">
       <form method="post">    
         <span style="font-size:30px;font-weight:bold;"><?php _e('Website Speed Test Report( Based on Google Page Speed)', 'scfw'); ?></span>
       <input type="submit" class="button-primary" name="run_tester" value="<?php _e('Run Tester') ?>" />
      </form>
      <h2 class="nav-tab-wrapper">
        <a href="?page=scfw&render=report-list" class="nav-tab <?php echo isset($_GET['render']) && $_GET['render'] == 'report-list' ? 'nav-tab-active' : ''; ?>">
            <?php _e('Report List', 'scfw'); ?>
       </a>
        <a href="?page=scfw&render=options" class="nav-tab <?php echo isset($_GET['render']) && $_GET['render'] == 'options' ? 'nav-tab-active' : ''; ?>">
           <?php _e('Options', 'scfw'); ?>
       </a>
         <a href="?page=scfw&render=contact" class="nav-tab <?php echo isset($_GET['render']) && $_GET['render'] == 'contact' ? 'nav-tab-active' : ''; ?>">
           <?php _e('Contact', 'scfw'); ?>
       </a>
       
     </div>

   </h2>   
   <?php
    if (  isset($_GET['render']) ) {
           $page =  sanitize_text_field ( $_GET['render'] );
           if($page == 'report-list'){
               require SCFW_PLUGIN_DIR.'/includes/scfw_list_table.php';
            } 
            elseif ($page == 'options') {
              require SCFW_PLUGIN_DIR.'/includes/scfw_options.php';
            }
             elseif ($page == 'contact') {
              require SCFW_PLUGIN_DIR.'/html/contact.php';
            }
     }
     else {
             require SCFW_PLUGIN_DIR.'/includes/scfw_list_table.php'; 
          }
  }
add_action('admin_enqueue_scripts', 'scfw_enqueue_admin_script');
function scfw_enqueue_admin_script() {
     wp_enqueue_style('scfw-style_css', SCFW_PLUGIN_URL . '/assets/css/style.css');
     wp_enqueue_script('scfw-script', SCFW_PLUGIN_URL . '/assets/js/script.js', array( 'jquery'),'1.0',true);
 }


?>
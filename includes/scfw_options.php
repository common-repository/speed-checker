<?php
/*
 * =======================================
 * WEBSITE SPEED OPTIONS
 * =======================================
 * 
 * 
 */
$options =  scfw_get_option();
if (! isset( $_POST['referer'] ) || ! wp_verify_nonce( $_POST['referer'], $action ) ){ 
  if( isset($_POST['scfw_save-options'] ) and $_POST['scfw_google_api_key'] !='' ) {
  	     $options['scfw_google_api_key'] = sanitize_text_field($_POST['scfw_google_api_key']);
  	     update_option('scfw_speed_test_options',$options);   
  	     $msg = 'The Google PageSpeedis running in the background to gather report for each URL. To display a report for each url, it could take some time. During that time, you can navigate away';
  	     scfw_notice_success($msg);
  	      
  }
  elseif( isset($_POST['scfw_save-options'] ))  {
  	      $msg =  'API Key is required';
  	      scfw_notice_error($msg);
  }
}
?>
<form method="POST">
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e('Google API Key', 'scfw'); ?>:</th>
				<td><input type="text" name="scfw_google_api_key" value="<?php echo esc_attr( ($options['scfw_google_api_key'] != '' ? $options['scfw_google_api_key'] : '')); ?>" class="regular-text code" placeholder='AIzaSyDaAimIth96jpXk9sfviTpr....'>
                 <p><?php _e('This field must be filled out. obtain google API KEY here- ', 'scfw'); ?> <a href="https://console.developers.google.com" target="_blank">https://console.developers.google.com</a> Make sure pagespeed service is enable</p>
				</td>
	         </tr>
	   </tbody>
	 </table>
	  <input type="hidden" name="action" value="save-options" />
    	 <?php wp_nonce_field( 'scfw-save-setting' );?>
	    <p class="submit">
	      <input type="submit" class="button-primary" value="<?php _e('Save changes') ?>" name='scfw_save-options' />
	    </p>
</form>
<?php 
add_action( 'admin_enqueue_scripts', 'import_epanel_javascript' );
function import_epanel_javascript( $hook_suffix ) {
	if ( 'admin.php' == $hook_suffix && 'wordpress' == $_GET['import'] && '1' == $_GET['step'] )
		add_action( 'admin_head', 'admin_headhook' );
}

function admin_headhook(){ ?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$("p.submit").before("<p><input type='checkbox' id='importepanel' name='importepanel' value='1' style='margin-right: 5px;'><label for='importepanel'>Import epanel settings</label></p>");
		});
	</script>
<?php }

add_action('import_end','importend');
function importend(){
	global $wpdb, $shortname;
	
	#make custom fields image paths point to sampledata/sample_images folder
	$sample_images_postmeta = $wpdb->get_results("SELECT meta_id, meta_value FROM $wpdb->postmeta WHERE meta_value REGEXP 'http://et_sample_images.com'");
	if ( $sample_images_postmeta ) {
		foreach ( $sample_images_postmeta as $postmeta ){
			$template_dir = get_template_directory_uri();
			if ( is_multisite() ){
				switch_to_blog(1);
				$main_siteurl = site_url();
				restore_current_blog();
				
				$template_dir = $main_siteurl . '/wp-content/themes/' . get_template();
			}
			preg_match( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $postmeta->meta_value, $matches );
			$image_path = $matches[1];
			
			$local_image = preg_replace( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $template_dir . '/sampledata/sample_images/$1.jpg', $postmeta->meta_value );
			
			$local_image = preg_replace( '/s:55:/', 's:' . strlen( $template_dir . '/sampledata/sample_images/' . $image_path . '.jpg' ) . ':', $local_image );
			
			$wpdb->update( $wpdb->postmeta, array( 'meta_value' => $local_image ), array( 'meta_id' => $postmeta->meta_id ), array( '%s' ) );
		}
	}

	if ( !isset($_POST['importepanel']) )
		return;
	
	$importOptions = 'YToxMDQ6e3M6MDoiIjtOO3M6MTA6IldlYmx5X2xvZ28iO3M6MDoiIjtzOjEzOiJXZWJseV9mYXZpY29uIjtzOjA6IiI7czoxODoiV2VibHlfY29sb3Jfc2NoZW1lIjtzOjc6IkRlZmF1bHQiO3M6MTY6IldlYmx5X2Jsb2dfc3R5bGUiO047czoxNjoiV2VibHlfZ3JhYl9pbWFnZSI7TjtzOjE4OiJXZWJseV9jYXRudW1fcG9zdHMiO3M6MToiNiI7czoyMjoiV2VibHlfYXJjaGl2ZW51bV9wb3N0cyI7czoxOiI1IjtzOjIxOiJXZWJseV9zZWFyY2hudW1fcG9zdHMiO3M6MToiNSI7czoxODoiV2VibHlfdGFnbnVtX3Bvc3RzIjtzOjE6IjUiO3M6MTc6IldlYmx5X2RhdGVfZm9ybWF0IjtzOjY6Ik0gaiwgWSI7czoxNzoiV2VibHlfdXNlX2V4Y2VycHQiO047czoxMzoiV2VibHlfYnV0dG9ucyI7czoyOiJvbiI7czoyMDoiV2VibHlfZGlzcGxheV9ibHVyYnMiO3M6Mjoib24iO3M6MTk6IldlYmx5X2Rpc3BsYXlfbWVkaWEiO3M6Mjoib24iO3M6MTg6IldlYmx5X2Zvb3Rlcl9xdW90ZSI7czoyOiJvbiI7czoyMzoiV2VibHlfZGlzcGxheV9sYW5kc2NhcGUiO3M6Mjoib24iO3M6MTg6IldlYmx5X2J1dHRvbjFfdGV4dCI7czoxMzoiVmlldyBUaGUgRGVtbyI7czoxNzoiV2VibHlfYnV0dG9uMV91cmwiO3M6MToiIyI7czoxODoiV2VibHlfYnV0dG9uMl90ZXh0IjtzOjEzOiJTaWduIFVwIFRvZGF5IjtzOjE3OiJXZWJseV9idXR0b24yX3VybCI7czoxOiIjIjtzOjE3OiJXZWJseV9ob21lX3BhZ2VfMSI7czo1OiJBYm91dCI7czoxNzoiV2VibHlfaG9tZV9wYWdlXzIiO3M6OToiV2hhdCBJIERvIjtzOjE3OiJXZWJseV9ob21lX3BhZ2VfMyI7czo4OiJXaG8gSSBBbSI7czoyMzoiV2VibHlfZm9vdGVyX3F1b3RlX3RleHQiO3M6NzU6IldlIG9mZmVyIHRoZSBoaWdoZXN0IHF1YWxpdHkgYW5kIG1vc3QgYWR2YW5jZWQgd2ViIGRlc2lnbiBzZXJ2aWNlcyBhbnl3aGVyZSI7czoyMjoiV2VibHlfZm9vdGVyX3F1b3RlX3VybCI7czoxOiIjIjtzOjE5OiJXZWJseV9leGxjYXRzX21lZGlhIjtOO3M6MjA6IldlYmx5X2hvbWVwYWdlX3Bvc3RzIjtzOjE6IjciO3M6MjA6IldlYmx5X2V4bGNhdHNfcmVjZW50IjtOO3M6MTQ6IldlYmx5X2ZlYXR1cmVkIjtzOjI6Im9uIjtzOjE1OiJXZWJseV9kdXBsaWNhdGUiO3M6Mjoib24iO3M6MTQ6IldlYmx5X2ZlYXRfY2F0IjtzOjg6IkZlYXR1cmVkIjtzOjE4OiJXZWJseV9mZWF0dXJlZF9udW0iO3M6MToiMyI7czoxNToiV2VibHlfdXNlX3BhZ2VzIjtOO3M6MTY6IldlYmx5X2ZlYXRfcGFnZXMiO047czoxOToiV2VibHlfc2xpZGVyX2VmZmVjdCI7czo0OiJmYWRlIjtzOjE3OiJXZWJseV9zbGlkZXJfYXV0byI7TjtzOjE3OiJXZWJseV9wYXVzZV9ob3ZlciI7TjtzOjIyOiJXZWJseV9zbGlkZXJfYXV0b3NwZWVkIjtzOjQ6IjcwMDAiO3M6MTU6IldlYmx5X21lbnVwYWdlcyI7TjtzOjIyOiJXZWJseV9lbmFibGVfZHJvcGRvd25zIjtzOjI6Im9uIjtzOjE1OiJXZWJseV9ob21lX2xpbmsiO3M6Mjoib24iO3M6MTY6IldlYmx5X3NvcnRfcGFnZXMiO3M6MTA6InBvc3RfdGl0bGUiO3M6MTY6IldlYmx5X29yZGVyX3BhZ2UiO3M6MzoiYXNjIjtzOjIzOiJXZWJseV90aWVyc19zaG93bl9wYWdlcyI7czoxOiIzIjtzOjE0OiJXZWJseV9tZW51Y2F0cyI7TjtzOjMzOiJXZWJseV9lbmFibGVfZHJvcGRvd25zX2NhdGVnb3JpZXMiO3M6Mjoib24iO3M6MjI6IldlYmx5X2NhdGVnb3JpZXNfZW1wdHkiO3M6Mjoib24iO3M6Mjg6IldlYmx5X3RpZXJzX3Nob3duX2NhdGVnb3JpZXMiO3M6MToiMyI7czoxNDoiV2VibHlfc29ydF9jYXQiO3M6NDoibmFtZSI7czoxNToiV2VibHlfb3JkZXJfY2F0IjtzOjM6ImFzYyI7czoyMToiV2VibHlfZGlzYWJsZV90b3B0aWVyIjtOO3M6MTU6IldlYmx5X3Bvc3RpbmZvMiI7YTo0OntpOjA7czo2OiJhdXRob3IiO2k6MTtzOjQ6ImRhdGUiO2k6MjtzOjEwOiJjYXRlZ29yaWVzIjtpOjM7czo4OiJjb21tZW50cyI7fXM6MTY6IldlYmx5X3RodW1ibmFpbHMiO3M6Mjoib24iO3M6MjM6IldlYmx5X3Nob3dfcG9zdGNvbW1lbnRzIjtzOjI6Im9uIjtzOjIxOiJXZWJseV9wYWdlX3RodW1ibmFpbHMiO047czoyNDoiV2VibHlfc2hvd19wYWdlc2NvbW1lbnRzIjtOO3M6MTU6IldlYmx5X3Bvc3RpbmZvMSI7YTo0OntpOjA7czo2OiJhdXRob3IiO2k6MTtzOjQ6ImRhdGUiO2k6MjtzOjEwOiJjYXRlZ29yaWVzIjtpOjM7czo4OiJjb21tZW50cyI7fXM6MjI6IldlYmx5X3RodW1ibmFpbHNfaW5kZXgiO3M6Mjoib24iO3M6MTk6IldlYmx5X2N1c3RvbV9jb2xvcnMiO047czoxNToiV2VibHlfY2hpbGRfY3NzIjtOO3M6MTg6IldlYmx5X2NoaWxkX2Nzc3VybCI7czowOiIiO3M6MjA6IldlYmx5X2NvbG9yX21haW5mb250IjtzOjA6IiI7czoyMDoiV2VibHlfY29sb3JfbWFpbmxpbmsiO3M6MDoiIjtzOjIwOiJXZWJseV9jb2xvcl9wYWdlbGluayI7czowOiIiO3M6Mjc6IldlYmx5X2NvbG9yX3BhZ2VsaW5rX2FjdGl2ZSI7czowOiIiO3M6MjA6IldlYmx5X2NvbG9yX2hlYWRpbmdzIjtzOjA6IiI7czoyNToiV2VibHlfY29sb3Jfc2lkZWJhcl9saW5rcyI7czowOiIiO3M6MTc6IldlYmx5X2Zvb3Rlcl90ZXh0IjtzOjA6IiI7czoyMzoiV2VibHlfY29sb3JfZm9vdGVybGlua3MiO3M6MDoiIjtzOjIwOiJXZWJseV9zZW9faG9tZV90aXRsZSI7TjtzOjI2OiJXZWJseV9zZW9faG9tZV9kZXNjcmlwdGlvbiI7TjtzOjIzOiJXZWJseV9zZW9faG9tZV9rZXl3b3JkcyI7TjtzOjI0OiJXZWJseV9zZW9faG9tZV9jYW5vbmljYWwiO047czoyNDoiV2VibHlfc2VvX2hvbWVfdGl0bGV0ZXh0IjtzOjA6IiI7czozMDoiV2VibHlfc2VvX2hvbWVfZGVzY3JpcHRpb250ZXh0IjtzOjA6IiI7czoyNzoiV2VibHlfc2VvX2hvbWVfa2V5d29yZHN0ZXh0IjtzOjA6IiI7czoxOToiV2VibHlfc2VvX2hvbWVfdHlwZSI7czoyNzoiQmxvZ05hbWUgfCBCbG9nIGRlc2NyaXB0aW9uIjtzOjIzOiJXZWJseV9zZW9faG9tZV9zZXBhcmF0ZSI7czozOiIgfCAiO3M6MjI6IldlYmx5X3Nlb19zaW5nbGVfdGl0bGUiO047czoyODoiV2VibHlfc2VvX3NpbmdsZV9kZXNjcmlwdGlvbiI7TjtzOjI1OiJXZWJseV9zZW9fc2luZ2xlX2tleXdvcmRzIjtOO3M6MjY6IldlYmx5X3Nlb19zaW5nbGVfY2Fub25pY2FsIjtOO3M6Mjg6IldlYmx5X3Nlb19zaW5nbGVfZmllbGRfdGl0bGUiO3M6OToic2VvX3RpdGxlIjtzOjM0OiJXZWJseV9zZW9fc2luZ2xlX2ZpZWxkX2Rlc2NyaXB0aW9uIjtzOjE1OiJzZW9fZGVzY3JpcHRpb24iO3M6MzE6IldlYmx5X3Nlb19zaW5nbGVfZmllbGRfa2V5d29yZHMiO3M6MTI6InNlb19rZXl3b3JkcyI7czoyMToiV2VibHlfc2VvX3NpbmdsZV90eXBlIjtzOjIxOiJQb3N0IHRpdGxlIHwgQmxvZ05hbWUiO3M6MjU6IldlYmx5X3Nlb19zaW5nbGVfc2VwYXJhdGUiO3M6MzoiIHwgIjtzOjI1OiJXZWJseV9zZW9faW5kZXhfY2Fub25pY2FsIjtOO3M6Mjc6IldlYmx5X3Nlb19pbmRleF9kZXNjcmlwdGlvbiI7TjtzOjIwOiJXZWJseV9zZW9faW5kZXhfdHlwZSI7czoyNDoiQ2F0ZWdvcnkgbmFtZSB8IEJsb2dOYW1lIjtzOjI0OiJXZWJseV9zZW9faW5kZXhfc2VwYXJhdGUiO3M6MzoiIHwgIjtzOjI5OiJXZWJseV9pbnRlZ3JhdGVfaGVhZGVyX2VuYWJsZSI7czoyOiJvbiI7czoyNzoiV2VibHlfaW50ZWdyYXRlX2JvZHlfZW5hYmxlIjtzOjI6Im9uIjtzOjMyOiJXZWJseV9pbnRlZ3JhdGVfc2luZ2xldG9wX2VuYWJsZSI7czoyOiJvbiI7czozNToiV2VibHlfaW50ZWdyYXRlX3NpbmdsZWJvdHRvbV9lbmFibGUiO3M6Mjoib24iO3M6MjI6IldlYmx5X2ludGVncmF0aW9uX2hlYWQiO3M6MDoiIjtzOjIyOiJXZWJseV9pbnRlZ3JhdGlvbl9ib2R5IjtzOjA6IiI7czoyODoiV2VibHlfaW50ZWdyYXRpb25fc2luZ2xlX3RvcCI7czowOiIiO3M6MzE6IldlYmx5X2ludGVncmF0aW9uX3NpbmdsZV9ib3R0b20iO3M6MDoiIjtzOjE2OiJXZWJseV80NjhfZW5hYmxlIjtOO3M6MTU6IldlYmx5XzQ2OF9pbWFnZSI7czowOiIiO3M6MTM6IldlYmx5XzQ2OF91cmwiO3M6MDoiIjtzOjE3OiJXZWJseV80NjhfYWRzZW5zZSI7czowOiIiO30';
	
	/*global $options;
	
	foreach ($options as $value) {
		if( isset( $value['id'] ) ) { 
			update_option( $value['id'], $value['std'] );
		}
	}*/
	
	$importedOptions = unserialize(base64_decode($importOptions));
	
	foreach ($importedOptions as $key=>$value) {
		if ($value != '') update_option( $key, $value );
	}
	
	update_option( $shortname . '_use_pages', 'false' );
} ?>
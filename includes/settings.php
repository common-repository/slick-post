<?php
add_action( 'admin_menu', 'slickpost_admin_menu' );
add_action( 'admin_init', 'slickpost_settings_save');
// Menu and Sub Menu Page Add
function slickpost_admin_menu(){
	add_options_page(  	
						__( 'Slick Post', 'slick-post' ),
						__( 'Slick Post', 'slick-post' ),
						'administrator',
						'slick_post',
						'slickpost_general_settings'
					);
}
function slickpost_settings_save() {
	register_setting( 'slick-post-settings', 'slick_post_type' );
	register_setting( 'slick-post-settings', 'slick_post_category' );
	register_setting( 'slick-post-settings', 'slick_show_form' );
	register_setting( 'slick-post-settings', 'slick_theme_select' );
	register_setting( 'slick-post-settings', 'slick_post_page' );
}

// General Settings Callback
function slickpost_general_settings(){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( 'You do not have sufficient permissions to access this page.' );
	}

	$post_types = get_post_types( array('public'=>true), 'names', 'and' ); 
	
	$categories = get_categories( array( 'orderby' => 'name', 'parent'  => 0 ) );
	
	


?>
<div class="wrap">
	<h2><?php _e('Slick Post Settings','slick-post'); ?></h2>
	<form method="post" action="options.php">
	<?php settings_fields( 'slick-post-settings' ); ?>
	<?php do_settings_sections( 'slick-post-settings' ); ?>
	<table class="form-table">
		
		<tr valign="top">
			<th scope="row"><?php _e('Post Type','slick-post'); ?> : </th>
			<?php $post_type_var = esc_attr( get_option('slick_post_type','post') ); ?>
			<td>
				<select name="slick_post_type">
				<option value=""><?php _e('All','slick-post'); ?></option>
				<?php
				$post_types = get_post_types( array('public'=>true), 'names', 'and' ); 
				foreach ( $post_types  as $post_type ){
					$var = '';
					if ($post_type_var == $post_type) { $var = 'selected'; }
					echo '<option '.$var.' value="'.$post_type.'">'.$post_type.'</option>';
				}
				?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Show this Only in Single Page','slick-post'); ?> : </th>
			<?php $post_page_var = esc_attr( get_option('slick_post_page','0') ); ?>
			<td>
				<input type="checkbox" name="slick_post_page" value="1" <?php checked( $post_page_var ); ?> />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Category Slug','slick-post'); ?> : </th>
			<?php $post_category_var = esc_attr( get_option('slick_post_category','') ); ?>
			<td>
				<select name="slick_post_category">
					<option <?php if ($post_category_var == '') { echo 'selected'; } ?> value=""><?php _e('All','slick-post'); ?></option>
					<?php
					if(is_array($categories)){
						if(!empty($categories)){
							foreach ($categories as $value) {
								//$cat_list[$value->slug]] = $value->name;
								$var = '';
								if ($post_category_var == $value->slug){ $var = 'selected'; }
								echo '<option '.$var.' value="'.$value->slug.'">'.$value->name.'</option>';
							}
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Show From the','slick-post'); ?> : </th>
			<td>
				<select name="slick_show_form">
					<?php $show = esc_attr( get_option('slick_show_form','bottom-right') ); ?>
					<option <?php if ($show == 'bottom-right') { echo 'selected'; } ?> value="bottom-right"><?php _e('Bottom Right','slick-post'); ?></option>
					<option <?php if ($show == 'bottom-left') { echo 'selected'; } ?> value="bottom-left"><?php _e('Bottom Left','slick-post'); ?></option>
					<option <?php if ($show == 'top-left') { echo 'selected'; } ?> value="top-left"><?php _e('Top Left','slick-post'); ?></option>
					<option <?php if ($show == 'top-right') { echo 'selected'; } ?> value="top-right"><?php _e('Top Right','slick-post'); ?></option>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Select The Theme','slick-post'); ?> : </th>
			<td>
				<select name="slick_theme_select">
					<?php $theme = esc_attr( get_option('slick_theme_select','light-slick') ); ?>
					<option <?php if ($theme == 'light-slick') { echo 'selected'; } ?> value="light-slick"><?php _e('Light Slick','slick-post'); ?></option>
					<option <?php if ($theme == 'dark-slick') { echo 'selected'; } ?> value="dark-slick"><?php _e('Dark Slick','slick-post'); ?></option>
				</select>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
	</form>
</div>
<?php } 


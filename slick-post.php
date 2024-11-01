<?php
/*
 * Plugin Name: Slick Post
 * Plugin URI: http://renoyes.com/
 * Description: Slick Post is plugins to show your recent content, next previous content in different position in your site. Has settings for customization to display output.
 * Version: 1.1
 * Author: Anik Biswas
 * Author URI: http://renoyes.com/
 * License: GPL2+
 * Domain Path: /languages/
 * Text Domain: slick-post
*/
// Define 
if ( !defined( 'SLICKPOST' ) )
	define( 'SLICKPOST', dirname( __FILE__ ) . '/' );

if ( !defined( 'WPPB_URL' ) )
	define( 'WPPB_URL', trailingslashit( plugins_url( '' , __FILE__ ) ) );


// Text Domain Add
function slickpost_text_domain() {
	load_plugin_textdomain( 'slick-post', SLICKPOST . 'languages', basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'slickpost_text_domain' );


// Add admin assets
function slickpost_admin_assets(){
	//CSS
	wp_enqueue_style( 'slick-style',  WPPB_URL.'assets/css/slick-style.css', false );
	//JS
	wp_enqueue_script( 'slick-js',  WPPB_URL.'assets/js/slick-js.js', array('jquery') );
}
add_action( 'wp_enqueue_scripts', 'slickpost_admin_assets' );

// Add Menus Settings
require_once( SLICKPOST . 'includes/settings.php' );


// Return Only Data
function slickpost_data_output( $class, $theme, $post_type, $cat ){
	$args = array(
				'post_type'			=> $post_type,
				'posts_per_page'	=> 1,
				'order'				=> 'DESC'
			);
	if($cat!=''){
		$args['tax_query'] = array(
								array(
									'taxonomy' => 'category',
									'field'    => 'slug',
									'terms'    => $cat,
								)
							);
	}
	if(is_single()){
		$args['post__not_in'] = array( get_the_ID() );
	}
	$query = new WP_Query( $args );


	while ( $query->have_posts() ) {
		$query->the_post();
		echo '<div class="'.$theme.'">';
			echo '<a href="'.get_permalink( $query->post->ID ).'">';
				echo '<div class="slick-content slick-position-'.$class.'" data-animation="slick-'.$class.'">';
					if ( has_post_thumbnail() ) {
						echo '<div class="slick-photo">';
							echo the_post_thumbnail('thumbnail', array('class' => 'slick-image'));
						echo '</div>';
					}
					echo '<div class="slick-desc">';
						echo '<h6>'.get_the_title( $query->post->ID ).'</h6>';
						echo '<span class="slick-date">'.get_the_date('F	d, Y').'</div>';
						echo '<span class="slick-close">x</span>';
					echo '</div>';
				echo '</div>';
			echo '</a>';
		echo '</div>';
	}
	wp_reset_postdata();
}

	
// Insert Footer Content
function slickpost_footer_content() {

	$class = esc_attr( get_option('slick_show_form','bottom-right') );
	$theme = esc_attr( get_option('slick_theme_select','light-slick') );
	$post_type = esc_attr( get_option('slick_post_type','post') );
	$cat = esc_attr( get_option('slick_post_category','') );
	$post_only_in_page = esc_attr( get_option('slick_post_page','0') );

	if( $post_only_in_page==1 ){
		if(is_singular( $post_type ) ){
			slickpost_data_output( $class, $theme, $post_type, $cat );
		}
	}else{
		slickpost_data_output(  $class, $theme, $post_type, $cat );
	}
}
add_action( 'wp_footer', 'slickpost_footer_content' );
<?php

if (!session_id()) 
{
    session_start();
}

/**
 * Destroy session variables from newsletter categorie with log out
*/
add_action('wp_logout', 'destroy_newsletter_session');

function destroy_newsletter_session() {

   if ( isset( $_SESSION['suscribedToNewsletter'] ) ) 
   {
      unset( $_SESSION['suscribedToNewsletter'] );
   }
   if ( isset( $_SESSION['idListNewsletter'] ) ) 
   {
      unset( $_SESSION['idListNewsletter'] );
   }

}

function wpse29210_admin_bar_toogle()
{
    add_filter( 'show_admin_bar', '__return_false' );

    //$user = get_userdata( $GLOBALS['current_user'] )->data->ID;

    return;
}
add_action( 'init', 'wpse29210_admin_bar_toogle' );


/**
 * Roots initial setup and constants
 */
function roots_setup() {
  // Make theme available for translation
  load_theme_textdomain('roots', get_template_directory() . '/lang');

  // Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
  register_nav_menus(array(
    'primary_navigation' => __('Primary Navigation', 'roots'),
  ));

  // Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
  add_theme_support('post-thumbnails');
  // set_post_thumbnail_size(150, 150, false);
  // add_image_size('category-thumb', 300, 9999); // 300px wide (and unlimited height)

  // Add post formats (http://codex.wordpress.org/Post_Formats)
  // add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

  // Tell the TinyMCE editor to use a custom stylesheet
  add_editor_style('/assets/css/editor-style.css');
}
add_action('after_setup_theme', 'roots_setup');

/**
 * If an email address is entered in the username box, then look up the matching username and authenticate as per normal, using that.
 *
 * @param string $user
 * @param string $username
 * @param string $password
 * @return Results of autheticating via wp_authenticate_username_password(), using the username found when looking up via email.
 */
function dr_email_login_authenticate( $user, $username, $password ) {
	if ( is_a( $user, 'WP_User' ) )
		return $user;

	if ( !empty( $username ) ) 
	{
		$username = str_replace( '&', '&amp;', stripslashes( $username ) );
		$user     = get_user_by( 'email', $username );
		
		if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status )
			$username = $user->user_login;
	}

	return wp_authenticate_username_password( null, $username, $password );
}

remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
add_filter( 'authenticate', 'dr_email_login_authenticate', 20, 3 );

/**
 * Redirect non-admins to the homepage after logging into the site.
 *
 * @since 	1.0
 */
function soi_login_redirect( $redirect_to, $request, $user  ) {

	$year   = date('Y-m-d');
	$limite = get_user_meta($user->ID, 'date_abo_active' ,true); 
	
	$page   = get_ID_by_slug('reactiver-votre-compte');
	
	$redirect = (!empty($redirect_to) ? $redirect_to : site_url() );
	
	// Return url
	$return = add_query_arg( array('user' => $user->ID ) , get_permalink($page) );
	
	if($limite)
	{
		if($year < $limite)
		{
			$url = site_url();
		}
		else
		{
			wp_logout();
			$url = $return;
		}
	}
	else
	{
		wp_logout();
		$url = $return;
	}
			
	return $url;
	
} // end soi_login_redirect

add_filter( 'login_redirect', 'soi_login_redirect', 30, 3 );
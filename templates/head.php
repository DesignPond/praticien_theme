<?php

if(!empty($_REQUEST['email_newsletter']))
{
	$email = $_REQUEST['email_newsletter'];
	$id    = $_REQUEST['id_newsletter'];
	
	// Is the email suscribed to the list?
	$isSuscribed = isEmailSuscribed($id,$email);
	
	if($isSuscribed)
	{
		// Reset session
		$_SESSION = array();
		
		// Set new values in session
		$_SESSION['suscribedToNewsletter'] = $email;
		$_SESSION['idListNewsletter']      = $id;
	}
	else
	{
		// Redirect to inscription to the newsletter  	
		// Get page with form	
	   	$page = get_ID_by_slug('inscription-a-la-newsletter-droit-pour-le-praticien');
	   	$url  = add_query_arg( array( 'id' => $id ) , get_permalink($page) );
	   	
	   	wp_redirect( $url );
	   	exit;
	}
}

?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php wp_title('|', true, 'right'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php wp_head(); ?>

  <link rel="alternate" type="application/rss+xml" title="<?php echo get_bloginfo('name'); ?> Feed" href="<?php echo esc_url(get_feed_link()); ?>">
</head>

<?php

/**
 * Redirect from old theme to correct page with search query
*/
if(!empty($_REQUEST['search-type']))
{
	$type     = $_REQUEST['search-type'];

	$resumes  = get_ID_by_slug('resultats-recherche'); 
	$decision = get_ID_by_slug('resultats-recherche-decisions'); 
	
	if($type == 'keyarret')
	{
		$page = $decision;
	}
	
	if($type == 'keyword')
	{
		$page = $resumes;
	}
		
	$location = add_query_arg( array('term' => $_REQUEST['s'] , 'search' => 'simple') , get_permalink($page) );
	wp_redirect( $location);
	exit;
}

/**
 * Redirect from old theme to correct page with category
*/
if(!empty($_REQUEST['cat']) && empty($_REQUEST['page_id']))
{
	$page     = get_ID_by_slug('categories'); 
	$annee    = get_query_var( 'annee' );	
	$location = add_query_arg( array('cat' => $_REQUEST['cat'] , 'annee' => $annee) , get_permalink($page) );
	
	wp_redirect( $location);
	exit;
}

/**
 * Set session for newsletter user
*/
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
	<!--[if gte IE 9]>
	  <style type="text/css">
	    .gradient {
	       filter: none;
	    }
	  </style>
	<![endif]-->
  <?php wp_head(); ?>

  <link rel="alternate" type="application/rss+xml" title="<?php echo get_bloginfo('name'); ?> Feed" href="<?php echo esc_url(get_feed_link()); ?>">
</head>

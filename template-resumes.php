<?php
/*
Template Name: Catgories Template
*/


// if category var in url
$cat = get_query_var('cat');

if(!empty($cat))
{
	// Make sur $topCat is a top category	  	
	$topCat = get_top_parent_category($cat);			  		
}

/**
 * Test if user is logged in
 * If not we show the login form and the newsletter suscribe form if category is linked to newsletter
*/
if ( is_user_logged_in() ) 
{
	get_template_part('templates/page', 'header'); 
	get_template_part('templates/search', 'resumes');
	get_template_part('templates/content', 'categories'); 
	
	// If we choose a category, show the articles
	if($cat){ get_template_part('templates/content', 'articles'); }

}
else
{
	// Sessions for user suscribed to newsletter	
	$suscribedToNewsletter = (!empty($_SESSION['suscribedToNewsletter']) ? $_SESSION['suscribedToNewsletter'] : null );
	$idListNewsletter      = (!empty($_SESSION['idListNewsletter']) ? $_SESSION['idListNewsletter'] : null );
	
	// Get categories, if we can see some categories...
	get_template_part('templates/content', 'categories'); 
	
	if($suscribedToNewsletter)
	{	
		// retrive the categories we can see	
		$list = getAllCatsByNewsletterList($idListNewsletter);
		
		// If the current category can be seen, show the articles
		if(in_array($topCat, $list))
		{  
			get_template_part('templates/content', 'articles');  
		}
	}
	else
	{
		get_template_part('templates/content', 'login');		
	}

}
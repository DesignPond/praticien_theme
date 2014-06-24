<?php

/**
 * Newsletter functions
 *
 * Grant access to a few categories if user is suscribed to a particular newsletter mailing list
*/


/**
 * Return campagin name by id
*/
function getAllCampaignName(){
	
	global $wpdb;
	
	$query      = 'SELECT name , list_id FROM wp_wysija_list';
	$campaigns  = $wpdb->get_results($query);
	
	return $campaigns;
}

/**
 * Return campagin name by id
*/
function getCampaignName($id){
	
	global $wpdb;
	
	$query = 'SELECT name FROM wp_wysija_list WHERE list_id = "'.$id.'" ';
	$news  = $wpdb->get_row($query);

	$nom   = $news->name;
	
	return $nom;
}


/**
 * Return campagin url by id
*/
function getCampaingUrl($id){
	
	global $wpdb;
	
	$query = 'SELECT url FROM wp_newsletter_auth WHERE id_list = "'.$id.'" ';
	$news = $wpdb->get_row($query);

	$url = $news->url;
	
	return $url;
}

/**
 * Return id of list visible for this category
*/
function getCatNewsletter($cat){
	
	global $wpdb;
	
	$query = 'SELECT * FROM wp_newsletter_auth WHERE cat_auth = "'.$cat.'" ';
	$in    = $wpdb->get_row( $query );
	
	if(!empty($in))
	{
		return $in->id_list;
	}
	else
	{
		return false;
	}
}

/**
 * Return list of catgories for this list id
*/
function getAllCatsByNewsletterList($id_list){
	
	global $wpdb;
	
	$list = array(); 
	
	$query      = 'SELECT * FROM wp_newsletter_auth WHERE id_list = "'.$id_list.'" ';
	$categories = $wpdb->get_results( $query );
	
	if(!empty($categories))
	{
		foreach($categories as $categorie)
		{
			$list[] = $categorie->cat_auth;
		}
		
		return $list;
	}
	else
	{
		return false;
	}
}

/**
 *  Is the email suscribed to the newsletter
*/
function isEmailSuscribed($id,$email){
	
	global $wpdb;
	
	$query  = 'SELECT email,wp_wysija_user.user_id,status 
			   FROM wp_wysija_user 
			   LEFT JOIN wp_wysija_user_list on wp_wysija_user_list.user_id = wp_wysija_user.user_id
			   WHERE wp_wysija_user.email = "'.$email.'" AND (wp_wysija_user.status = "1" OR  wp_wysija_user.status = "-1") AND wp_wysija_user_list.list_id = "'.$id.'" ';
			   
			   
	$result = $wpdb->get_row($query);
	
	if(!empty($result))
	{
		return true;
	}
	
	return false;
}

/**
 * Get archives for newsletter by list id
*/
function getArchivesNewsletter($list){
	
	global $wpdb;
	
	$query = 'SELECT wp_wysija_list.* , wp_wysija_campaign_list.* , wp_wysija_email.email_id, wp_wysija_email.subject , wp_wysija_email.sent_at FROM wp_wysija_list 
			 		   LEFT JOIN wp_wysija_campaign_list on wp_wysija_campaign_list.list_id = wp_wysija_list.list_id
			 		   LEFT JOIN wp_wysija_email on wp_wysija_campaign_list.campaign_id = wp_wysija_email.campaign_id
			 		   WHERE wp_wysija_list.list_id = "'.$list.'" ORDER BY  wp_wysija_email.campaign_id DESC';
			 		   
	$campaigns = $wpdb->get_results( $query );
	
	return $campaigns;
}

/**
 * Shortcode for display newsltter archive list
*/
function dp_archives_newsletter( $atts ) {

	extract( shortcode_atts( array( 'list' => 'list' ), $atts ) );

	global $wpdb;
	
	$campaigns = getArchivesNewsletter($list);
	
	/* Contruct the list */
	
	$content = '';
	
	if(!empty($campaigns)){
				
		$content .= '<div class="list-group">';
		
		foreach($campaigns as $campaign)
		{
			$url = get_bloginfo('url').'?wysija-page=1&controller=email&action=view&email_id='.$campaign->email_id.'&wysijap=subscriptions';
			
			$content .=  '<a class="list-group-item" target="_blank" href="'.$url.'"><span class="glyphicon glyphicon-inbox"></span> &nbsp;'.$campaign->subject.'</a>';
		}
		
		$content .=  '</div>';
	}
	
	return $content;		
}

add_shortcode( 'archives_newsletter', 'dp_archives_newsletter' );
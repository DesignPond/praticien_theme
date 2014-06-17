<?php

/**
 * Newsletter functions
 *
 * Grant access to a few categories if user is suscribed to a particular newsletter mailing list
*/


/**
 * Return campagin name by id
*/
function get_campaing_name($id){
	
	global $wpdb;
	
	$query = 'SELECT name FROM wp_wysija_list WHERE list_id = "'.$id.'" ';
	$news  = $wpdb->get_row($query);

	$nom   = $news->name;
	
	return $nom;
}

/**
 * Return campagin url by id
*/
function get_campaing_url($id){
	
	global $wpdb;
	
	$query = 'SELECT url FROM wp_newsletter_auth WHERE id_list = "'.$id.'" ';
	$news = $wpdb->get_row($query);

	$url = $news->url;
	
	return $url;
}

/**
 * Return id of list visible for this category
*/
function get_cat_newsletter($cat){
	
	global $wpdb;
	
	$query = 'SELECT * FROM wp_newsletter_auth WHERE cat_auth = "'.$cat.'" ';
	$in    = $wpdb->get_row( $query );
	
	if(!empty($in))
	{
		$id_list = $in->id_list;
		return $id_list;
	}
	else
	{
		return -1;
	}
}

/**
 * Get archives for newsletter by list id
*/
function get_archives_newsletter($list){
	
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
	
	$campaigns = get_archives_newsletter($list);
	
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
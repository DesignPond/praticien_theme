<?php
/*
Template Name: Articles Template
*/

if ( is_user_logged_in() ) 
{
	get_template_part('templates/page', 'header'); 
	get_template_part('templates/content', 'articles'); 
}
else
{
	get_template_part('templates/content', 'login');	
}
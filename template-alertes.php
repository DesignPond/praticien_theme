<?php
/*
Template Name: Alertes Template
*/

if ( is_user_logged_in() ) 
{
	get_template_part('templates/page', 'header'); 
	get_template_part('templates/content', 'alertes'); 
}
else
{
	get_template_part('templates/content', 'login');	
}
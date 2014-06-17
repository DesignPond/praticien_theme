<?php
/*
Template Name: Arrêts Template
*/


get_template_part('templates/page', 'header'); 

if ( is_user_logged_in() ) 
{
	get_template_part('templates/search', 'decisions');
	get_template_part('templates/content', 'arrets-list'); 
}
else
{
	get_template_part('templates/content', 'login');	
}
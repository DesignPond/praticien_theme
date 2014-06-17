<?php
/*
Template Name: Newsletter Template
*/

get_template_part('templates/page', 'header'); 

if(!empty($_REQUEST['id']))
{
	get_template_part('templates/content', 'inscription'); 
}
else
{
	get_template_part('templates/content', 'newsletter'); 
}
	


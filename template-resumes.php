<?php
/*
Template Name: Catgories Template
*/

get_template_part('templates/page', 'header'); 
get_template_part('templates/search', 'resumes');
get_template_part('templates/content', 'categories'); 

// if cat var in url
$cat = get_query_var('cat');

if($cat){  get_template_part('templates/content', 'articles');  }


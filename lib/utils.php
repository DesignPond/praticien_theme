<?php
/**
 * Utility functions
 */
function add_filters($tags, $function) {
  foreach($tags as $tag) {
    add_filter($tag, $function);
  }
}

function is_element_empty($element) {
  $element = trim($element);
  return empty($element) ? false : true;
}

function limit_words($string, $word_limit){

	$words = explode(" ",$string);
	$new   = implode(" ",array_splice($words,0,$word_limit));	
	$nbr   = str_word_count($string);
	
	if( !empty($new) && ($nbr >= $word_limit) ){
		$new = $new.'...';
	}
	
	return $new;
}

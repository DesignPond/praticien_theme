<?php


function simpleTermSearch(){
	
}

function trailCategoriesPost($post_id){
	
	$post_categories = wp_get_post_categories( $post_id );
	
	$cats = array();
	$html = '';
		
	foreach($post_categories as $c)
	{
		$cat    = get_category( $c );
		// $cats[] = array( 'name' => $cat->name, 'slug' => $cat->slug );
		$cats[] = $cat->name;
	}
	
	$html = implode(" / ", $cats);
	
	return $html;
	
}


function prepareSearch($search){
	
	// decode spécial char
	$search =  htmlspecialchars_decode($search);
	// stripslashes added by wordpress
	$search =  stripcslashes($search);
	
	// remove  , and replace by space
	$search = str_replace(',', ' ', $search);
	
    preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $search, $matches);
	
	$recherche = $matches[0];
	
	foreach($recherche as $rech)
	{
		// there is quotes "
		if (preg_match('/\"([^\"]*?)\"/', $rech, $m)) 
		{
		   $string = $m[1];
		   $string = str_replace('"', '', $string);
		   $item   = str_replace('"', '', $string);
		   $string = trim($string);
		   
	 	   $find[] = $item;   
		}
		else // no quotes
		{
		   $string = str_replace(',', '', $rech);
		   $string = trim($string); 
		   
		   if( $string != '')
		   {
			   $find[] = $string;   
		   }			   
		}			
	}
	
	return $find;
	
}


function trailSearch(){

	$terms  = $_REQUEST;
	$html   = array();
	$string = '';
	
	// order of terms
	$orderArray  = array('article', 'loi', 'alinea', 'lettre', 'chiffre');
	
	// unset unvanted args
	unset($terms['search']);
	unset($terms['page_id']);
	
	// Filter empty arrays
	$terms  = array_filter($terms);
	
	// count length of terms
	$length = count($terms['loi']);
	
	if(!empty($terms))
	{
		foreach($orderArray as $order)
		{
			for($j = 0; $j < $length ; $j++)
			{
				if( isset($terms[$order][$j]) && !empty($terms[$order][$j]) )
				{
					$html[$j][] = $terms[$order][$j];
				}					
			}			
		}
	}
	
	if(!empty($html))
	{
		$count = count($html);
		$count = $count -1;
		
		foreach($html as $nbr => $s)
		{
			$string .= implode(" ", $s);
			
			if($nbr != $count)
			{
				$string .= ' | ';
			}
		}
	}

	return $string;
	
}

function articleTermsSearch(){
	
	global $wpdb;

	// Reset session args
	session_unset();
	
	// Get all terms requested	
	$article = $_REQUEST['article'];
	$loi     = $_REQUEST['loi'];
	$alinea  = $_REQUEST['alinea'];
	$chiffre = $_REQUEST['chiffre'];
	$lettre  = $_REQUEST['lettre'];
	$annee   = $_REQUEST['annee'];

	$article = array_filter($article);
	$loi     = array_filter($loi);
	$alinea  = array_filter($alinea);
	$chiffre = array_filter($chiffre);
	$lettre  = array_filter($lettre);
	
	if($article) { $_SESSION['search']['article'] = $article; }
	if($loi)     { $_SESSION['search']['loi']     = $loi; }
	if($alinea)  { $_SESSION['search']['alinea']  = $alinea; }
	if($lettre)  { $_SESSION['search']['lettre']  = $lettre; }
	if($chiffre) { $_SESSION['search']['chiffre'] = $chiffre; }
	if($annee)   { $_SESSION['annee'] = $annee; }
	
	$orderArray  = array('article', 'loi', 'alinea', 'lettre', 'chiffre');
	$termsSearch = $_SESSION['search'];
	
	function sortArrayByArray( $array , $orderArray) 
	{
	    $ordered = array();
	    
	    foreach($orderArray as $key) 
	    {
	        if(array_key_exists($key,$array)) 
	        {
	            $ordered[$key] = $array[$key];
				unset($array[$key]);
	        }
	    }
	    return $ordered + $array;
	}

	$values = sortArrayByArray($termsSearch , $orderArray);
	
	$lois   = $values['loi'];
		  			
  	foreach($values as $terms) 
  	{ 
  		foreach($terms as $number => $term) 
  		{ 
  			$termsArranged[$number][] = $term;
  		}
  	}
	
	foreach($termsArranged as $termsReversed) 
  	{ 
		$separated        = implode(":", $termsReversed);
  		$termsSeparated[] = $separated;  	
	}

	$query = 'SELECT * FROM wp_postmeta	
			  JOIN wp_posts  ON wp_posts.ID = wp_postmeta.post_id 			
			  JOIN wp_term_relationships r ON r.object_id = wp_posts.ID
			  JOIN wp_term_taxonomy t ON r.term_taxonomy_id = t.term_taxonomy_id
			  JOIN wp_terms terms ON terms.term_id = t.term_id
			  WHERE meta_key = "termes_rechercher" 
			  AND t.taxonomy = "annee" ';
	
	foreach($termsSeparated as $meta_value)
	{
		$pieces    = explode(":", $meta_value);
		$firstItem = $pieces[0]; 
		$second    = $pieces[1]; 
		
		if( is_numeric($firstItem) and is_string($second) ) 
		{
			$query .= 'AND meta_value LIKE "'.$meta_value.'%" OR meta_value LIKE "%,'.$meta_value.'%" ';
		}
		else
		{
			$query .= 'AND meta_value LIKE "%'.$meta_value.'%" ';
		}
	}
	
	if (!empty($_SESSION['annee']) &&( $_SESSION['annee'] != 'all') )
	{ 
		$query .= ' AND terms.slug = "'.$annee.'" '; 
	}
	
	$query .= ' GROUP BY post_id '; 
	
	// return  $query;
	
	$myrows = $wpdb->get_results($query);
	
	return $myrows;

}
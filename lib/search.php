<?php

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

/**
 * Terms search 
*/
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
	$annee   = (!empty($_REQUEST['annee']) ? $_REQUEST['annee'] : null);

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
	
	$orderArray  = array('article', 'loi', 'alinea', 'lettre', 'chiffre');
	$termsSearch = $_SESSION['search'];	

	$query = 'SELECT * FROM wp_postmeta	
			  JOIN wp_posts  ON wp_posts.ID = wp_postmeta.post_id 			
			  JOIN wp_term_relationships r ON r.object_id = wp_posts.ID
			  JOIN wp_term_taxonomy t ON r.term_taxonomy_id = t.term_taxonomy_id
			  JOIN wp_terms terms ON terms.term_id = t.term_id
			  WHERE meta_key = "termes_rechercher" ';
			  
		
	if (!empty($annee) )
	{ 
		$query .= ' AND (t.taxonomy = "annee" AND terms.slug = "'.$annee.'") '; 
	}
			  
	if (!empty($_SESSION['search']) )
	{
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
	}	
	
	$query .= ' GROUP BY post_id '; 
	
	// return  $query;
	
	$myrows = $wpdb->get_results($query);
	
	return $myrows;

}


/**
 * Search in decisions from TF
*/
function decisionSearch($s) {

	global $wpdb;
		
	$searchArray =  array();
	// Decode special chars
	$s =  htmlspecialchars_decode($s);
	
    preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $s, $matches);
	
	$recherche = $matches[0];
	
	foreach($recherche as $rech)
	{
		if (preg_match('/\"([^\"]*?)\"/', $rech, $m)) 
		{
		   $string = $m[1];
		   $string = str_replace('"', '', $string);
		   $item   = str_replace('"', '', $string);
		   
	 	   $rechercheArray['quote'][] = $item;   
		}
		else
		{
		   $string = str_replace('"', '', $rech);
		   $item   = str_replace('"', '', $string);
		   
		   $rechercheArray['normal'][] = $string;   
		}
	}
	
	$search = $_REQUEST['search-type'];
								
	// contruction de la requete
	$query = 'SELECT wp_nouveautes.id_nouveaute ,
					 wp_nouveautes.datep_nouveaute ,
					 wp_nouveautes.dated_nouveaute ,
					 wp_nouveautes.categorie_nouveaute ,
					 wp_nouveautes.numero_nouveaute , 
					 wp_nouveautes.langue_nouveaute , 
					 wp_nouveautes.publication_nouveaute , 
					 wp_custom_categories.name as nameCat , 
					 wp_subcategories.name as nameSub 
			  FROM wp_nouveautes 
			  JOIN wp_custom_categories on wp_custom_categories.term_id = wp_nouveautes.categorie_nouveaute 
			  JOIN wp_subcategories on wp_subcategories.refNouveaute = wp_nouveautes.id_nouveaute 
			  WHERE ';

	
	$quotes = (!empty( $rechercheArray['quote'])  ? $rechercheArray['quote']  : array() );
	$normal = (!empty( $rechercheArray['normal']) ? $rechercheArray['normal'] : array() );
	
	// count nbr of items in arrays		  
	$nbrItemQuote  = count($quotes);
	$nbrItemNormal = count($normal);
	
	$i = 1; 
	
	// use REGEXP for strings in quotes
	if($quotes)
	{
		foreach($quotes as $q)
		{	
			$query .= '( wp_nouveautes.texte_nouveaute REGEXP "[[:<:]]'.$q.'[[:>:]]" ';
			
			$query .= 'OR wp_subcategories.name REGEXP "[[:<:]]'.$q.'[[:>:]]" ) ';
			
			$searchArray[] = $q;
			
			$query .= ($i < $nbrItemQuote ? ' AND ' : '');
			
			$i++;
		}
	}
	
	$j = 1;
	
	// use LIKE for simple strings
	if($normal)
	{
		foreach($normal as $n)
		{					
			$query .= ($nbrItemQuote > 0 ? ' AND ' : '');			
			$query .= '( wp_nouveautes.texte_nouveaute LIKE "%'.$n.'%" ';
			$query .= 'OR wp_subcategories.name LIKE "%'.$n.'%" ) ';
			
			$searchArray[] = $n;

			$query .= ($j < $nbrItemNormal ? ' AND ' : '');
			
			$j++;
		}
	}
	
	$query .= ' GROUP BY id_nouveaute ORDER BY wp_nouveautes.datep_nouveaute  DESC  ';
	
	// Get result with the query
	$result = $wpdb->get_results($query);
	
	$resultat['terms']  = $searchArray;
	$resultat['result'] = $result;

	return $query;  
		
}

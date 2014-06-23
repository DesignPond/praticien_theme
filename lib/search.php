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
				$query .= 'AND (meta_value LIKE "'.$meta_value.'%" OR meta_value LIKE "%,'.$meta_value.'%") ';
			}
			else
			{
				$query .= 'AND meta_value LIKE "%'.$meta_value.'%" ';
			}
		}
	}	
	
	$query .= ' GROUP BY post_id '; 
	
	$myrows = $wpdb->get_results($query);
	
	return $myrows;

}


/**
 * Format keywords for search
*/		 
function formatSearch($search){

	$search = htmlspecialchars_decode($search);
	$search = stripcslashes($search);
	$search = trim($search);
	
	/**
	 *  Remove string in double quotes and put each in array
	 *  Get rest and replace virgule by space
	 *  Explode string by space and put in array
	*/
	
	if (preg_match_all('/"([^"]+)"/', $search, $m)) 
	{
	    $quotes   = $m[0]; 
	    $noquotes = $m[1];   
	} 
	
	if(!empty($quotes))
	{	
		// remove string in quotes	
		$newsearch = str_replace($quotes , '' , $search);	
		// remove commas and dots		
		$newsearch = str_replace(array('.', ','), '' , $newsearch); 
		
		$find   = explode(" ", $newsearch);	
		// merge quotes string and normal words		
		$result = array_merge($noquotes , $find);
	}
	else
	{
		// remove commas and dots
		$search = str_replace(array('.', ','), '' , $search); 
		$result = explode(" ", $search);
	}
	
	// remove empty
	$result = array_filter($result);
	
	return $result;	
}

/**
 * Other search function
*/

function newDecisionSearch($search){

	global $wpdb;
		
	$nbrItem = count($search);
	
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
	$i = 1;
	
	// use LIKE for simple strings
	if($search)
	{
		foreach($search as $word)
		{							
			$query .= '( wp_nouveautes.texte_nouveaute LIKE "%'.$word.'%" OR wp_subcategories.name LIKE "%'.$word.'%" ) ';

			$query .= ($i < $nbrItem ? ' AND ' : '');
			
			$i++;
		}
	}
	
	$query .= ' GROUP BY id_nouveaute ORDER BY wp_nouveautes.datep_nouveaute  DESC  ';
	
	// Get result with the query
	$result = $wpdb->get_results($query);
	
	$resultat['terms']  = $s;
	$resultat['result'] = $result;

	return $query;  
}


/**
 *  Search for Lois
*/

function getListLois(){

	global $wpdb;
	
	$list = array();
	$html = '';
	
	$queryLois = 'SELECT meta_id, meta_value FROM wp_postmeta 
											 LEFT JOIN wp_posts ON (wp_posts.ID = wp_postmeta.post_id)
											 WHERE wp_postmeta.meta_key = "termes_rechercher" AND wp_posts.post_status = "publish" ';

	$lois = $wpdb->get_results($queryLois);	
	
	$page = get_ID_by_slug('lois');
	
	if(!empty($lois))
	{
		foreach($lois as $loi)
		{				
			$termes = explode(',',$loi->meta_value);
			
			$id = $loi->meta_id;
			
			if( !empty($termes) )
			{
				foreach($termes as $terme)
				{
					$alltermes[$id] = $terme; 
				}
			}
		}
		
		foreach($alltermes as $id => $lloi)
		{
			$termes  = trim($lloi);
			$termes  = explode(':',$termes); 
			$allterm = $termes[1]; 
			
			$t = explode(',',$allterm);
			
			$laloi = trim($t[0]);
			
			if(!empty($laloi))
			{
				if( !in_array($laloi,$list) ){
					//$list[] = $laloi.' ('.$id.')';
					$list[] = $laloi;
				}
			}
		}
		
		array_filter($list, 'strlen');
		array_values($list);
		
		natcasesort($list);
		
		$html .= '<div id="sidebar-categories">';
		
		foreach($list as $loi)
		{
			$html .= '<h3><a href="'.get_permalink($page).'&amp;loi='.$loi.'">'.$loi.'</a></h3>';
		}
		
		$html .= '</div>';
	}
	
	return $html;

}

/**
 * Get all article corresponding to loi requested
*/
function getLoiArticles($loi){
	
	global $wpdb;
	
	$list_ids  = array();
	
	$queryLois = 'SELECT post_id, meta_value 
						 FROM wp_postmeta 
						 LEFT JOIN wp_posts ON (wp_posts.ID = wp_postmeta.post_id)
						 WHERE wp_postmeta.meta_key = "termes_rechercher" 
						 AND ( wp_postmeta.meta_value LIKE "%:'.$loi.':%" OR wp_postmeta.meta_value LIKE "%:'.$loi.'" )
						 AND wp_posts.post_status = "publish" 
						 GROUP BY post_id ';
	
	$lois = $wpdb->get_results($queryLois);	
	
	if(!empty($lois))
	{

	  foreach($lois as $post)
	  {					
			$explode = explode(',', $post->meta_value);
			
			if(!empty($explode))
			{
				foreach($explode as $exp)
				{
					if (preg_match('/\b:'.$loi.'\b/i', $exp))
					{
						$first = explode(':', $exp);
	
						if(isset($first[2]) && is_numeric($first[2]) )
						{								
							$third = trim($first[2]);
	
							$arrange[$first[0]][$post->post_id]['rang']  = $third;	
							$arrange[$first[0]][$post->post_id]['terme'] = $exp;	
						}
						else
						{								
					    	$arrange[$first[0]][$post->post_id]['rang']  = 0;
					    	$arrange[$first[0]][$post->post_id]['terme'] = $exp;
					    }
					}
				}
			}						
	   }
		
		ksort($arrange);
		
		$id_arrange = array();
		
		foreach($arrange as $rang => $each)
		{
			$sort = array();
	
			foreach($each as $id => $term)
			{
				$sort[$term['rang']][] = $id;
			}
			
			ksort($sort);
			
			$id_arrange[$rang] = $sort;				
		}
		
		$list_ids = array();
		
		foreach($id_arrange as $arr)
		{
			foreach($arr as $idall)
			{
				foreach($idall as $id)
				{	
					$list_ids[$id] = $id;
				}
			}
		}	   
	   
	}
	
	return $list_ids;
	
}

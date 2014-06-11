<?php


function simpleTermSearch(){
	
}

function articleTermsSearch(){
	session_unset();
				
			$article = $_REQUEST['article'];
			$loi = $_REQUEST['loi'];
			$alinea = $_REQUEST['alinea'];
			$chiffre = $_REQUEST['chiffre'];
			$lettre = $_REQUEST['lettre'];
			$annee = $_REQUEST['annee'];

			$article = array_filter($article);
			$loi = array_filter($loi);
			$alinea = array_filter($alinea);
			$chiffre = array_filter($chiffre);
			$lettre = array_filter($lettre);
			
			if($article) { $_SESSION['search']['article'] = $article; }
			if($loi)     { $_SESSION['search']['loi']     = $loi; }
			if($alinea)  { $_SESSION['search']['alinea']  = $alinea; }
			if($lettre)  { $_SESSION['search']['lettre']  = $lettre; }
			if($chiffre) { $_SESSION['search']['chiffre'] = $chiffre; }
			if($annee)   { $_SESSION['annee'] = $annee; }
			
			//print_r($_SESSION['search']);
			
			$orderArray = array('article', 'loi', 'alinea', 'lettre', 'chiffre');
			$termsSearch =  $_SESSION['search'];
			
			function sortArrayByArray( $array , $orderArray) {
			    $ordered = array();
			    foreach($orderArray as $key) {
			        if(array_key_exists($key,$array)) {
			                $ordered[$key] = $array[$key];
			                unset($array[$key]);
			        }
			    }
			    return $ordered + $array;
			}

			$values = sortArrayByArray($termsSearch , $orderArray);
			
			$lois = $values['loi'];
				  			
		  	foreach($values as $terms) 
		  	{ 
		  		foreach($terms as $number => $term) 
		  		{ 
		  			$termsArranged[$number][] = $term;
		  		}
		  	}
		  	//print_r($termsArranged);
			
			foreach($termsArranged as $termsReversed) 
		  		{ 
					$separated = implode(":", $termsReversed);
		  			$termsSeparated[] = $separated;
		  		}
				
					
			
			$annee = '20082009';
	
			$query = 'SELECT * 
					FROM wp_postmeta	
					JOIN wp_posts  ON wp_posts.ID = wp_postmeta.post_id 			
					JOIN wp_term_relationships r ON r.object_id = wp_posts.ID
					JOIN wp_term_taxonomy t ON r.term_taxonomy_id = t.term_taxonomy_id
					JOIN wp_terms terms ON terms.term_id = t.term_id
					WHERE meta_key = "termes_rechercher" 
					AND t.taxonomy = "annee" ';
			
			foreach($termsSeparated as $meta_value)
			{
				$pieces = explode(":", $meta_value);
				$firstItem = $pieces[0]; 
				$second = $pieces[1]; 
				
				if( is_numeric($firstItem) and is_string($second) ) {
					$query .= 'AND meta_value LIKE "'.$meta_value.'%" OR meta_value LIKE "%,'.$meta_value.'%" ';
				}
				else{
					$query .= 'AND meta_value LIKE "%'.$meta_value.'%" ';
				}
			}
			
			if (!empty($_SESSION['annee']) &&( $_SESSION['annee'] != 'all') )
			{ $query .= ' AND terms.slug = "'.$annee.'"	 '; }
			
			$query .= ' GROUP BY post_id '; 
			
			//echo $query;
			
			$myrows = $wpdb->get_results($query);

}
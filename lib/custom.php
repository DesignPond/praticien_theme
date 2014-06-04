<?php

/**
 * homepage fucntion for calculating date of last update
*/

function lastDayUpdated(){

	global $wpdb;
			
	// Get last date
	$lastDate = $wpdb->get_row('SELECT datep_nouveaute FROM wp_nouveautes ORDER BY datep_nouveaute DESC LIMIT 0,1 ');	
	
	$date = ( !empty($lastDate) ? $lastDate->datep_nouveaute : '');
	
	return mysql2date('j M Y', $date );
	
}	

/**
 * Test where we are for arrets links
*/

function arretLinkArret($lien){
	
	$resumes  = array( '' );
	$nouveaux = array( 'liste-des-nouveaux-arrets' );
	
	$pagename = get_query_var('pagename');
	
	if( in_array($pagename, $nouveaux) && ($lien == 'nouveaux'))
	{
		return 'active';
	}

	if( in_array($pagename, $resumes) && ($lien == 'resumes'))
	{
		return 'active';
	}
	
	return '';
	
}

function getAllCategories(){
	
	global $wpdb;
	
	$query = "SELECT wp_custom_categories.term_id as id , wp_custom_categories.* ,wp_parent_categories.*
												 FROM  wp_custom_categories 
			  									 LEFT JOIN wp_parent_categories on  wp_parent_categories.term_id  = wp_custom_categories.terme_parent
												 LEFT JOIN wp_extracategories on  wp_extracategories.parent_extra = wp_parent_categories.term_id
												 GROUP BY wp_custom_categories.name ORDER BY wp_parent_categories.nom ";
	    
	/**
	 * Get all categories	, subcategories and extra categories
	 * Dispatch children in parent array if parent isn't 0
	 * Return 2 array for loops in sidebar 
	*/
		
	$categories = $wpdb->get_results($query);
			   
	$children = array();
	$parents  = array();
	
	if(!empty($categories))
	{
		foreach($categories as $row)
		{
			if($row->terme_parent != 0)
			{
				$parents[$row->nom] = $row->term_id; 
				
				if(!empty($row->general))
				{
					$children[$row->term_id][$row->general] = $row->id;
				}
				else
				{
					$children[$row->term_id][$row->name] = $row->id;
				}
			}
			
			
		}
	}
	
	$parents = array_flip($parents);
	
	return array('parents' => $parents , 'children' => $children);
	
}

function prepareSidebar($array , $theCategorie = NULL , $choixStar = NULL){

	$parents  = $array['parents'];
	$children = $array['children'];

	/**
	 * Prepare html for sidebar in nouveuax arrets
	*/
	
	$html  = '';
	
	$html .= '<div id="sidebarNewArrets">';
				
	foreach($parents as $idparent => $termparent)
	{
		$html .=  '<h3 class="headlink"><strong>'.$termparent.'</strong></h3>';
		
		foreach($children[$idparent] as $nameCat => $idCat) 
		{
			
			$html .=  '<h3 class="';
			
			if ( $theCategorie == $idCat) { $html .=  ' active';}
			
			$html .=  '"><a href="'.get_permalink().'&amp;categorie='.$idCat; if(!empty($choixStar)){$html .= '&star=1';} $html .= '">'.$nameCat.'</a>';
			$html .=  '</h3>';
		}
	}
		
	$html .=  '</div>';
	
	return $html;
	
}


function getLastArrets($nbr , $page , $categorie = NULL , $annee = NULL , $mois = NULL){

	global $wpdb;
	
	$langue = array('Fr','All','It');

	$query = 'SELECT 
				wp_nouveautes.id_nouveaute ,wp_nouveautes.datep_nouveaute ,wp_nouveautes.dated_nouveaute , wp_nouveautes.categorie_nouveaute , wp_nouveautes.numero_nouveaute , 
				wp_nouveautes.langue_nouveaute , 
				wp_nouveautes.publication_nouveaute , 
				wp_custom_categories.name as nameCat , wp_custom_categories.*, 
				wp_subcategories.name as nameSub , wp_subcategories.* 
			  FROM wp_nouveautes 
			  LEFT JOIN wp_custom_categories on wp_custom_categories.term_id  = wp_nouveautes.categorie_nouveaute 
			  LEFT JOIN wp_subcategories     on wp_subcategories.refNouveaute = wp_nouveautes.id_nouveaute  ';

/*					  
	if( $categorie )
	{
		if(in_array($categorie,$extraCatArray)) {
			
			$query = '';
			
			$query = 'SELECT wp_extracategories.* , wp_nouveautes.* , cat.name as nameCat , wp_custom_categories.*, 
					  wp_subcategories.name as nameSub , wp_subcategories.*
					  FROM wp_extracategories 
					  JOIN wp_custom_categories on  wp_custom_categories.term_id = wp_extracategories.parent_extra
					  JOIN wp_nouveautes on  wp_extracategories.`nouveaute_extra` = wp_nouveautes.id_nouveaute
						  JOIN wp_custom_categories as cat on  cat.term_id = wp_nouveautes.categorie_nouveaute
					  LEFT JOIN wp_subcategories on wp_subcategories.refNouveaute = wp_nouveautes.id_nouveaute 
					  WHERE wp_extracategories.parent_extra  = "'.$categorie.'"   ';
					  
			$limit = 'all';

		}
		else 
		{
			$query .= ' WHERE wp_nouveautes.categorie_nouveaute  = "'.$categorie.'"   ';
			$limit = 'all';
		}
	}


	// test category and dates
	if( $annee  && $categorie ) {

		$debut = $choixAnnee;
		$fin   = $choixAnnee2;
		$query .= ' AND wp_nouveautes.datep_nouveaute  BETWEEN  "'.$debut.'" AND "'.$fin.'" ';
		$limit = 'all';
	}
	else if( $annee && !$categorie){
		$debut = $annee;
		$fin   = $annee;
		$query .= ' WHERE wp_nouveautes.datep_nouveaute  BETWEEN  "'.$debut.'" AND "'.$fin.'" ';
		$limit = 'all';
	}
	
	// test category and star publications
	if( $choixStar && ($categorie or $annee) ) {

		$query .= ' AND wp_nouveautes.publication_nouveaute = "1" ';
		$limit = 'all';
	}
	else if( $choixStar && !$categorie && !$annee){

		$query .= ' WHERE wp_nouveautes.publication_nouveaute = "1" ';
		$limit = 'all';
	}			
	
	$query .= ' ORDER BY wp_nouveautes.datep_nouveaute  DESC  ';
	
	if($limit == 'notall'){
	  $query .= ' LIMIT 0,50  ';
	}
*/
		
	$arrets = $wpdb->get_results($query);	
	
	$html   = '<tbody>';
	
	if(!empty($arrets))
	{
		foreach($arrets as $arret)
		{
			$html .= '<tr>';
			
				$html .= '<td>'.$arret->datep_nouveaute.'</td>';
				$html .= '<td>'.$arret->dated_nouveaute.'</td>';
				$html .= '<td><a href="'.$page.'&amp;arret='.$arret->numero_nouveaute.'&amp;choixMois='.$mois.'&amp;choixAnnee='.$annee.'">';
				
				if($arret->publication_nouveaute == "1") { $html .= '*';}
				
				$html .= ''.$arret->numero_nouveaute.'</a></td>';
				$html .= '<td>'.$arret->nameCat.'</td>';
				$html .= '<td>'.$arret->nameSub.'</td>';
				$html .= '<td>'.$langue[$arret->langue_nouveaute].'</td>';
				
			$html .= '</tr>';			
		}
	}

	$html .= '</tbody>';	
	
	return $html;
	
}


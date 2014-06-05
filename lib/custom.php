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
	
	$resumes  = array( 'categories' );
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

function getAllCategories( $onlyChilden = false){
	
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
	
	if($onlyChilden)
	{
		return $children;
	}
	
	return array('parents' => $parents , 'children' => $children);
	
}

function prepareSidebar($array , $categorie = NULL , $star = NULL){

	$parents  = $array['parents'];
	$children = $array['children'];

	/**
	 * Prepare html for sidebar in nouveuax arrets
	*/
	
	$html  = '';
	
	$html .= '<div id="sidebarNewArrets">';
				
	foreach($parents as $idparent => $termparent)
	{
		$html .= '<h3 class="headlink"><strong>'.$termparent.'</strong></h3>';
		
		foreach($children[$idparent] as $nameCat => $idCat) 
		{						
			// Rebuild url with new args			
			$url   = add_query_arg( array( 'categorie' => $idCat, 'star' => $star) , get_permalink() );
			
			$html .= '<h3 class="';
			
			if ( $categorie == $idCat) { $html .= ' active';}
			
			$html .= '"><a href="'.$url.'">'.$nameCat.'</a>';
			$html .= '</h3>';
		}
	}
		
	$html .= '</div>';
	
	return $html;
	
}

				
/*********************************
	Get extra categories
***********************************/

function getExtraCategories(){	

	global $wpdb;
		
	$extraCatArray = array();
	
    $queryExtra = "SELECT * FROM  wp_extracategories 
     						JOIN  wp_custom_categories on  wp_custom_categories.term_id = wp_extracategories.parent_extra
							GROUP BY wp_custom_categories.name ";
	 
	$dataExtra = $wpdb->get_results($queryExtra);	
	
	if(!empty($dataExtra))
	{
		foreach ($dataExtra as $cat_extra ) 
		{
			$extraCatArray[] = $cat_extra->parent_extra;
		}			
	}	
	
	return $extraCatArray;
	
}


function getLastArrets( $page , $categorie = NULL , $dateStart = NULL , $dateEnd = NULL , $star = NULL){

	global $wpdb;
	
	$langue = array('Fr','All','It');
	$limit  = NULL;

	/**
	 * Query for simple list
	 * Last inserted arrets
	*/
	$querySimple  =  'SELECT 
						wp_nouveautes.id_nouveaute ,wp_nouveautes.datep_nouveaute ,wp_nouveautes.dated_nouveaute , wp_nouveautes.categorie_nouveaute , wp_nouveautes.numero_nouveaute , 
						wp_nouveautes.langue_nouveaute , 
						wp_nouveautes.publication_nouveaute , 
						wp_custom_categories.name as nameCat , wp_custom_categories.*, 
						wp_subcategories.name as nameSub , wp_subcategories.* 
					  FROM wp_nouveautes 
					  LEFT JOIN wp_custom_categories on wp_custom_categories.term_id  = wp_nouveautes.categorie_nouveaute 
					  LEFT JOIN wp_subcategories     on wp_subcategories.refNouveaute = wp_nouveautes.id_nouveaute  ';
					  
	/**
	 * Query for extracategories list
	 * the categorie is a parent and we have to get all children categories
	*/
	$queryExtra   =  'SELECT wp_extracategories.* , wp_nouveautes.* , 
							 cat.name as nameCat , wp_custom_categories.*,
							 wp_subcategories.name as nameSub , 
							 wp_subcategories.*
					  FROM wp_extracategories 
					  JOIN wp_custom_categories on  wp_custom_categories.term_id = wp_extracategories.parent_extra
					  JOIN wp_nouveautes on  wp_extracategories.`nouveaute_extra` = wp_nouveautes.id_nouveaute
					  JOIN wp_custom_categories as cat on  cat.term_id = wp_nouveautes.categorie_nouveaute
					  LEFT JOIN wp_subcategories on wp_subcategories.refNouveaute = wp_nouveautes.id_nouveaute ';
					  
					  
	if( $categorie )
	{
		
		// Extracategorie array list		
		$extraCategories = getExtraCategories();
		
		// if the categorie we want is an extra 
		if(in_array($categorie,$extraCategories)) 
		{			
			$query  = $queryExtra;
			$query .= ' WHERE wp_extracategories.parent_extra  = "'.$categorie.'" ';					  
			$limit  = 'all';
		}
		else 
		{
			$query  = $querySimple;
			$query .= ' WHERE wp_nouveautes.categorie_nouveaute  = "'.$categorie.'" ';
			$limit  = 'all';
		}
	}
	else
	{
		$query  = $querySimple;
	}


	// test category and dates
	if( $dateStart && $categorie ) 
	{
		$dateEnd = ($dateEnd ? $dateEnd : $dateStart);
		
		$query  .= ' AND wp_nouveautes.datep_nouveaute  BETWEEN  "'.$dateStart.'" AND "'.$dateEnd.'" ';
	}
	else if( $dateStart && !$categorie)
	{
		$query  .= ' WHERE wp_nouveautes.datep_nouveaute  BETWEEN  "'.$dateStart.'" AND "'.$dateEnd.'" ';
	}

	// test category and star publications
	if( $star && ($categorie or $dateStart) ) {

		$query .= ' AND wp_nouveautes.publication_nouveaute = "1" ';
		$limit  = 'all';
	}
	else if( $star && !$categorie && !$dateStart){

		$query .= ' WHERE wp_nouveautes.publication_nouveaute = "1" ';
		$limit  = 'all';
	}			

	// Order by date
	$query .= ' ORDER BY wp_nouveautes.datep_nouveaute DESC ';
	
	// limit if we have any
	$query .= (!$limit ? ' LIMIT 0,50' : '');
	
	// Run query and get results
	$arrets = $wpdb->get_results($query);	
	
	$html   = '<tbody>';
	
	if(!empty($arrets))
	{
		foreach($arrets as $arret)
		{
			$html .= '<tr>';
			
				$html .= '<td>'.$arret->datep_nouveaute.'</td>';
				$html .= '<td>'.$arret->dated_nouveaute.'</td>';
				$html .= '<td><a href="'.$page.'&amp;arret='.$arret->numero_nouveaute.'&amp;dateEnd='.$dateStart.'&amp;dateEnd='.$dateEnd.'">';
				
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

/**
 * Get all wordpress categories
 * Display as waal of links
*/

function getAllArretsCategories(){

	$args = array(
		'orderby'    => 'name',
	    'order'      => 'ASC',
		'hide_empty' => 0,
		'exclude'    => '1,156,166,168,220,336'
	); 
	
	$categories  = get_categories($args);
	
	$all = array();
	
	foreach($categories as $counting) 
	{
		if ($counting->category_parent == 0)
		{
		    $all[$counting->term_id] = $counting->name;
		}
	}
	
	return $all;
}


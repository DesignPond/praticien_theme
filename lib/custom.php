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
	
	$resumes  = array( 'categories' , 22534 , 22550);
	$nouveaux = array( 'liste-des-nouveaux-arrets', 1143 , 22552);
	
	$pagename = get_query_var('pagename');
	$pageid   = get_query_var('page_id');
	
	if( ( in_array($pagename, $nouveaux) || in_array($pageid, $nouveaux) ) && ($lien == 'nouveaux'))
	{
		return 'active';
	}

	if( ( in_array($pagename, $resumes) || in_array($pageid, $resumes) ) && ($lien == 'resumes'))
	{
		return 'active';
	}
	
	return '';
	
}


function getDecisionByRef($reference){
	
	global $wpdb;
			
	// Get last date
	$decision = $wpdb->get_row('SELECT wp_nouveautes.* , wp_custom_categories.name as nameCat , wp_subcategories.name as nameSub
								FROM wp_nouveautes 
								LEFT JOIN wp_custom_categories on wp_custom_categories.term_id  = wp_nouveautes.categorie_nouveaute 
								LEFT JOIN wp_subcategories     on wp_subcategories.refNouveaute = wp_nouveautes.id_nouveaute 
								WHERE numero_nouveaute = "'.$reference.'" ');
	
	if(!empty($decision))
	{
		return $decision;
	}
	
	return array();	
	
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
	
	$html .= '<div id="sidebar-list">';
				
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


function getLastArrets( $categorie = NULL , $dateStart = NULL , $dateEnd = NULL , $star = NULL ){

	global $wpdb;
	
	$limit  = NULL;

	/**
	 * Query for simple list
	 * Last inserted arrets
	*/
	$querySimple  =  'SELECT 
						wp_nouveautes.id_nouveaute ,wp_nouveautes.datep_nouveaute ,wp_nouveautes.dated_nouveaute , wp_nouveautes.categorie_nouveaute , wp_nouveautes.numero_nouveaute , 
						wp_nouveautes.langue_nouveaute , 
						wp_nouveautes.publication_nouveaute , 
						wp_custom_categories.name as nameCat
					  FROM wp_nouveautes 
					  LEFT JOIN wp_custom_categories on wp_custom_categories.term_id  = wp_nouveautes.categorie_nouveaute  ';
					  
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
	$query .= (!$limit ? ' LIMIT 0,35' : '');	
	
	return $query;
}

function prepareListDecisions($query , $page , $retour = NULL , $term = NULL , $dateStart = NULL, $dateEnd = NULL ){

	global $wpdb;
	
	$langue = array('Fr','All','It');
	
	// Run query and get results
	$arrets = $wpdb->get_results($query);	
	
	$html   = '<tbody>';
	
	if(!empty($arrets))
	{
		foreach($arrets as $arret)
		{
			// Prepapre url to link to
			$url = add_query_arg( array('arret' => $arret->numero_nouveaute ,'retour' => $retour ,'dateStart' => $dateStart,'dateEnd' => $dateEnd),get_permalink($page));
			
			// Subcategories
			$subcat  = $wpdb->get_row(' SELECT name as nameSub FROM wp_subcategories WHERE refNouveaute = "'.$arret->id_nouveaute.'" ');			
			$nameSub = ( !empty($subcat) ? $subcat->nameSub : '' );
			
			$html .= '<tr>';
			
				$html .= '<td>'.$arret->datep_nouveaute.'</td>';
				$html .= '<td>'.$arret->dated_nouveaute.'</td>';
				$html .= '<td><a href="'.$url.'&'.$term.'">';
				
				if($arret->publication_nouveaute == "1") { $html .= '*';}
				
				$html .= ''.$arret->numero_nouveaute.'</a></td>';
				$html .= '<td>'.$arret->nameCat.'</td>';
				$html .= '<td>'.$nameSub.'</td>';
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
		'hide_empty' => 1,
		'exclude'    => '3'
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

/**
 * Get hompage bloc with latest arret 
*/

function homepageBloc($nbr,$offset){

	global $wpdb;
	
	// Page to link arret to
	$page = get_ID_by_slug('arrets-tf');
	
	$html  = '';
	
	$query = 'SELECT 
				wp_nouveautes.id_nouveaute ,wp_nouveautes.datep_nouveaute ,wp_nouveautes.dated_nouveaute , wp_nouveautes.categorie_nouveaute , wp_nouveautes.numero_nouveaute , 
				wp_nouveautes.publication_nouveaute , 
				wp_custom_categories.name as nameCat  
			  FROM wp_nouveautes 
			  JOIN wp_custom_categories on wp_custom_categories.term_id  = wp_nouveautes.categorie_nouveaute 
			  GROUP BY wp_nouveautes.id_nouveaute ORDER BY wp_nouveautes.datep_nouveaute DESC  LIMIT '.$offset.','.$nbr.'';
					  
	$arrets = $wpdb->get_results($query);					  
	
	if( !empty($arrets))
	{
		foreach($arrets as $arret)
		{	
			$subcat  = $wpdb->get_row(' SELECT name as nameSub FROM wp_subcategories WHERE refNouveaute = "'.$arret->id_nouveaute.'" ');			
			$nameSub = ( !empty($subcat) ? $subcat->nameSub : '' );
			
			$url = add_query_arg( array('categorie' => $arret->categorie_nouveaute) , get_permalink($page) );
				
			$html .= '<div class="col-md-4">';
				$html .= '<div class="bloc blocBorder tf_bloc">';
					$html .= '<h3>'.$arret->nameCat.'</h3>';
					$html .= '<h4>'.$arret->nameCat.'</h4>';	
					$html .= '<p>'.$nameSub.'</p>';	
					$html .= '<a class="btn btn-blue btn-sm" href="'.$url.'">Consulter</a>';	
					$html .= '<p class="calendar">Décision du '.mysql2date('j M Y', $arret->dated_nouveaute ).'</p>';	
				$html .= '</div>';	
			$html .= '</div>';			
		}
	}
	
	return $html;
	
}

/**
 * Newsletter bloc!
*/

function newsletterBloc(){
	
	$page = get_ID_by_slug('inscription-a-la-newsletter-droit-pour-le-praticien'); 
	
	$url  = add_query_arg( array('id' => 4) , get_permalink($page) );
	
	// url to categories
	// Assurance sociales
	$categories     = get_ID_by_slug('categories');
	$assurance      = add_query_arg( array('cat' => '10#Assurances sociales') , get_permalink($categories) );
	$responsabilite = add_query_arg( array('cat' => '3455#Responsabilité civile') , get_permalink($categories) );	
	
	$html = '<div class="col-md-4"><!-- start col 4 -->
			
				<div class="bloc newsletterHome">
			  	  <h3>Newsletter</h3>
			  	  
				  <div class="row">
				  	 <div class="col-md-6">
				  	 	Responsabilité civile<br/>
						Assurances sociales<br/>
						Assurances privées
				  	 </div>
				  	 <div class="col-md-6">
				  	 	<img src="'.get_bloginfo('template_directory').'/assets/img/newsletter_logo.png" />
				  	 </div>
				  </div>
	
				  <div class="row newsletterLinks">
				  	 <div class="col-md-6">
				  	 	<a class="btn btn-default btn-sm" href="'.$url.'">Inscription</a>
				  	 	<a class="btn btn-praticien btn-sm" href="'.$assurance.'">Assurances sociales</a>
				  	 </div>
				  	 <div class="col-md-6">
				  	 	<a class="btn btn-default btn-sm" href="'.$url.'">Archives</a>
				  	 	<a class="btn btn-praticien btn-sm" href="'.$responsabilite.'">Responsabilité civile</a>
				  	 </div>
				  </div>
				  
			  </div><!-- end newsletterHome -->
			  					
			</div><!-- end col 4 -->';
	
	return $html;
}

/**
 * Get aurot for post
*/

function getAutor($post,$cat,$annee){
	
	$html = '';
	$customposts = '';
	
	$auteurPost = get_field( "auteur" , $post->ID );
	
	if( $auteurPost )
	{
		$html .= '<h5>Auteur : <cite>';
		$html .= $auteurPost;
		$html .= '</cite></h5>';
	}
	else
	{		
		$args = array(
			'post_type'  => 'auteur',
			'taxonomies' => array('category','annee'),
			'cat'        => $cat ,
			'annee'      => $annee
		);
		
		$customposts = get_posts($args);
		
		if(!empty($customposts))
		{
			$html .= '<h5>Auteur(s) : <cite>';
		
			$i = 1;
		
			foreach($customposts as $auteur)
			{
				if($i >1 && !empty($auteur)){echo ' ';}
				$html .= $auteur->post_title;
				$i++;
			}
			
			$html .= '</cite></h5>';
		}
	}
	
	return $html;
}

/**
 *  Get anne for post
*/

function getAnne($post_id){
	
	 $annee = '';
	 $terms = get_the_terms( $post_id, 'annee' );
	 
	 if( !empty($terms))
	 {
		foreach($terms as $term)
		{
			$annee = $term->slug;
		}
	 }
	 
	 return $annee;
}

/**
 * Get top categorie
*/

function get_top_parent_category($cat_ID)
{
	$cat = get_category( $cat_ID );
	$new = $cat->category_parent;
	
	if($new != "0")
	{
		return (get_top_parent_category($new));
	}
	
	return $cat_ID;
}	

function get_category_depth($childcat)
{
	$cat        = get_category( $childcat );
	$cats_str   = get_category_parents($cat->cat_ID, false, '%#%');
	$cats_array = explode('%#%', $cats_str);
	$cat_depth  = sizeof($cats_array)-2;
	
	return $cat_depth;
}

/**
 * Sidebar for subcategories in arrêts resumés
*/

function getResumesSidebat($categorie, $annee = NULL){

	$html = '';
	
	// Get top categorie
	$topCat = get_top_parent_category($categorie);
	
	if($annee)
	{		

		$tax_query[] = 	array(
			'relation' => 'AND',
			'taxonomy' => 'annee',
			'field'    => 'slug',
			'terms'    => $annee
		);
				
		$argums = array(
			'nopaging'  => true,
			'cat'       => $topCat,
			'tax_query' => $tax_query 
		);	
		
		$temp     = $wp_query;
		$wp_query = null;
		$wp_query = new WP_Query( $argums );
		
		while ( $wp_query->have_posts() ) : $wp_query->the_post();
		
			$id   = $post->ID; 			
			$post = get_the_category( $id );
			
			foreach($post as $c) 
			{ 
				$allCat[] = $c->cat_ID;  
			}	
					
		endwhile;

		$allCat[]  = $topCat;
		$allUnique = array_unique($allCat);
		
		$args = array(
		    'orderby' => 'name',
		    'order'   => 'ASC',
			'include' => $allUnique
		 );
		  
		$categories = get_categories($args);

	}
	else 
	{
		
		$args = array(
		   'orderby'    => 'name',
		   'order'      => 'ASC',
		   'hide_empty' => 0,
		   'exclude'    => '3',
		   'depth'      => 1,
		   'child_of'   => $topCat
		 );
		  
		$categories = get_categories($args);
		
		$childrens  = $categories;
	}
	
	$html .= '<div id="sidebar-categories">';
	
	// current category for active state
	$catCurretActive = get_query_var('cat');	

	if(!empty($categories))
	{	
		foreach($categories as $childcat) 
		{
			if(get_category_depth($childcat) == 1)
			{
				 $url    = add_query_arg( array( 'cat' => $childcat->cat_ID, 'section' => $section.'#'.$childcat->name , 'annee' => $annee ) , get_permalink() );
				 
				 $active = ($childcat->cat_ID == $catCurretActive ? 'class="active"' : '' );
				 
				 $html  .= '<h3 '.$active.'><a href="'.$url.'">'.$childcat->name.'</a></h3>';
				  
				 foreach($categories as $children) 
				 {
					 if((get_category_depth($children) == 2) and (cat_is_ancestor_of( $childcat, $children )))
					 {
					 
					 	 $url1  = add_query_arg( array( 'cat' => $children->cat_ID, 'section' => $section.'#'.$children->name , 'annee' => $annee ) , get_permalink() );
				 
						 $act   = ($children->cat_ID == $catCurretActive ? 'active' : '' );
						 
						 $html .= '<h3 class="'.$act.'"><a href="'.$url1.'">'.$children->name.'</a></h3>';
					 }
				 }				 			   
			}		
		}
	}
	
	$html .= '</div>';

	return $html;

}

/* ================================================================ 
	
	Taxonomy , Filters & Actions wordpress	
	
 ================================================================ */

/**
 * Taxonomy
*/
 
function add_custom_taxonomies() {
	
	/*------------
		Sections
	--------------*/
	
	register_taxonomy('section', 'post', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => _x( 'Sections', 'taxonomy general name' ),
			'singular_name' => _x( 'Section', 'taxonomy singular name' ),
			'search_items' =>  __( 'Chercher section' ),
			'all_items' => __( 'Toute les section' ),
			'parent_item' => __( 'Parent section' ),
			'parent_item_colon' => __( 'Parent section:' ),
			'edit_item' => __( '&Eacute;diter section' ),
			'update_item' => __( 'MAJ section' ),
			'add_new_item' => __( 'Ajouter New section' ),
			'new_item_name' => __( 'New section nom' ),
			'menu_name' => __( 'Sections' ),
		),
		// Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug' => 'sections', // This controls the base slug that will display before each term
			'with_front' => false, // Don't display the category base before "/locations/"
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
		),
	));
	
	/*------------
		Année
	--------------*/

	register_taxonomy('annee', 'post', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => _x( 'Années', 'taxonomy general name' ),
			'singular_name' => _x( 'Année', 'taxonomy singular name' ),
			'search_items' =>  __( 'Chercher Année' ),
			'all_items' => __( 'Toutes les Années' ),
			'parent_item' => __( 'Parent Année' ),
			'parent_item_colon' => __( 'Parent Année:' ),
			'edit_item' => __( '&Eacute;diter Année' ),
			'update_item' => __( 'MAJ Année' ),
			'add_new_item' => __( 'Ajouter Nouvelle Année' ),
			'new_item_name' => __( 'Nouvelle Année' ),
			'menu_name' => __( 'Année' ),
		),
		// Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug' => 'annees', // This controls the base slug that will display before each term
			'with_front' => false, // Don't display the category base before "/locations/"
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
		),
	));
	
}
add_action( 'init', 'add_custom_taxonomies', 0 );


add_action('init', 'auteur_register_post_type');
 
function auteur_register_post_type() {

    register_post_type('auteur', array(
	    'labels' => array(
	    'name' => 'Auteurs',
	    'singular_name' => 'Auteur',
	    'add_new' => 'Ajouter nouvel Auteur',
	    'edit_item' => 'Edit Auteur',
	    'new_item' => 'Nouvel Auteur',
	    'view_item' => 'Voir Auteur',
	    'search_items' => 'Chercher Auteur',
	    'not_found' => 'No Auteur found',
	    'not_found_in_trash' => 'No Auteur found in Trash'
    ),
	    'public' => true,
	    'supports' => array(
	    'title',
	    'excerpt'
    )
    
  ));
  
  register_taxonomy_for_object_type('category', 'auteur');
  register_taxonomy_for_object_type('annee', 'auteur');

}

function get_ID_by_slug($page_slug) {

    $page = get_page_by_path($page_slug);
    
    if ($page) 
    {
        return $page->ID;
    } 
    else 
    {
        return null;
    }
}


/**
 * Hightlight terms
*/
function highlightTerms($text_string,$keywords) {
    // $text_sting:    the text from which you want to highlight and return...
    // $keywords:      either string or array or words that should be highlighted in the text.		
	if (!is_array($keywords)) 
	{
        //explode the keywords
        $keywords = explode(",",$keywords);
    }
    //find matches
    for ($x = 0; $x < count($keywords); $x++) 
    {		
       if (strlen($keywords[$x]) > 1) 
       {
           preg_match_all('~\b(?<!["\'])' . $keywords[$x] . '~i', $text_string, $items);

           for ($y=0;$y<count($items);$y++) 
           {
               if (isset($items[$y][0])) 
               {				  
                   $text_string = str_replace($items[$y][0],'<span class="highlight">'.$items[$y][0].'</span>',$text_string);
               }
           }        
        }
    }
	return $text_string;
}

/**
 * custom pagination
*/

function wpc_pagination($pages = '', $range = 2)
{
     $showitems = ($range * 2)+1;
     
     global $paged;
     
     if( empty($paged)) $paged = 1;
     
     if($pages == '')
     {
         global $wp_query;
         
         $pages = $wp_query->max_num_pages;
         
         if(!$pages)
         {
             $pages = 1;
         }
     }

     if(1 != $pages)
     {
         echo '<ul class="pagination text-center">';
         
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo '<li><a href="'.get_pagenum_link(1).'">Première</a></li>';
         if($paged > 1 && $showitems < $pages) echo '<li><a href="' .get_pagenum_link($paged - 1). '" rel="prev"> &lt;&lt; </a></li>';

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? '<li class="active"><a href="#">'. $i .'</a></li>':'<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
             }
         }

         if ($paged < $pages && $showitems < $pages) echo '<li><a href="'.get_pagenum_link($paged + 1).'" rel="next"> &gt;&gt; </a></li>';
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo '<li><a href="'.get_pagenum_link($pages).'">Dernière</a></li>';
         
         echo '</ul>';
     }
}



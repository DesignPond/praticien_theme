<?php

/* ================================================================ 
	
	Abonnements for users functions
	
 ================================================================ */
 
function getUserAboList(){
	 
	global $wpdb;
	 
	$current_user = wp_get_current_user();
	 
	$user_id = $current_user->ID; 
	 
	$userHasCat  = array();
	$userHasKey  = array();
	$userHasPub  = array();
	
	$userCategories = $wpdb->get_results('SELECT * FROM wp_user_abo WHERE refUser = "'.$user_id.'" ');
	
	if($userCategories)
	{
		foreach($userCategories as $hasCat)
		{
			$userHasCat[] = $hasCat->refCategorie;
			$userHasKey[$hasCat->refCategorie]['keywords'][$hasCat->id_abo] = $hasCat->keywords;
		}	
	}

	/*----------------------------------------------------------
		Liste des catégories que publications de l'user
	-----------------------------------------------------------*/
	
	$userIsPub = $wpdb->get_results('SELECT * FROM wp_user_abo_pub WHERE refUser = "'.$user_id.'" ');
	
	if($userIsPub)
	{
		foreach($userIsPub as $hasPub)
		{
			$userHasPub[] = $hasPub->refCategorie;
		}	
	}
			 							
	return array( 'userHasCat' => $userHasCat , 'userHasKey' => $userHasKey , 'userHasPub' => $userHasPub );						

}

function prepareAlertesCategories(){

	global $wpdb;
			
	$parents = array();	
	$liste   = array();
	$sorted  = array();
	
	// First get parents categories
	$cat_list_parent = $wpdb->get_results('SELECT * FROM wp_parent_categories ORDER BY nom ASC');
	
	foreach ($cat_list_parent as $parent) 
	{
		$parents[$parent->term_id] = $parent->nom; 
	}
	
	// Get children categories
	$cat_list = $wpdb->get_results('SELECT * FROM wp_custom_categories WHERE terme_parent != 0 ORDER BY name,terme_parent,rang ASC ');
	
	foreach ($cat_list as $cat) 
	{ 		
		$liste[$cat->terme_parent][$cat->term_id]['name']    = $cat->name;
		$liste[$cat->terme_parent][$cat->term_id]['general'] = $cat->general;
		$liste[$cat->terme_parent][$cat->term_id]['rang']    = $cat->rang;
	}

	// On récupére les valeur de la première colonne
	foreach ($parents as $row => $o) 
	{
	   	$colSort[]  = $row;
	}

	foreach($colSort as $sort)
	{
		$sorted[$sort] = $liste[$sort];
	}
	
	return array( 'parents' => $parents , 'children' => $sorted);
	
}


/**
 * Prepare list of categories with checked and publications
 * Add the general categori to start of categories array
*/

function createCheckedAbos($categories, $user_id , $abosUser, $general_id){

	// User abos
	$userHasCat  = $abosUser['userHasCat'];
	$userHasPub  = $abosUser['userHasPub'];
	$userHasKey  = $abosUser['userHasKey'];	
	
	$html = '';
	$i    = 0; // counter for ajax
	
	$number = count($categories);
	$numberGeneral = $number +1;
	
	// Add general categorie
	$general = array(
		$numberGeneral => array(
			$general_id => array(
				'name'    => 'Général',
				'general' => '',
				'rang'    => 1
			)			
		)
	);
	
	$all = $general + $categories;
	
	/* The loop over all parent categories , test if we want general or not */
	foreach ($all as $categorie_id => $children) 
	{ 
	
		$html .= '<div id="tabs-'.$categorie_id.'">';
			$html .= '  <fieldset class="bgcategorie">';
				$html .= '	<ul class="checklist">';

				// Loop over children	
				foreach ($children as $id => $terms) 
				{ 	
					// Get variables with user abos				
					$checked = ( $userHasCat && (in_array( $id , $userHasCat)) ? ' checked="checked ': '');
					$visible = ( in_array($id , $userHasPub) ? ' style="display:none;" ' : '');									
					$general = (!empty($terms['general']) ? $terms['general'] :  $terms['name']);
					
					
					$html .= '<li data-id="'.$id.'">';// Start of li
						
						$html .= '<input id="choice_'.$i.'" name="selectCat[]" '.$checked.' value="'.$id.'" type="checkbox">';									
						$html .= '<label for="choice_'.$i.'">'.$general.'</label>';

						// is pub checked
						$html .= '<i class="limite-pub" '.$visible.'>Limité aux arrêts proposé pour la publication</i>';
						
						// check the categorie
						$html .= '<div class="check-title">';
							$html .= '<a class="checkbox-select selectlink" href="#">sélectionner </a>';
							$html .= '<a class="checkbox-deselect" href="#">Désabonner ou Modfier</a>';
						$html .= '</div>';
						
						// Keywords choices
						$html .= '<div class="check-choice">';
							
							// If the user has keywords for this category
							if(isset($userHasKey[$id]['keywords']))
							{
								foreach($userHasKey[$id]['keywords'] as $idkeys => $keys)
								{
									$html .= '<p class="new_input">
										  	<input class="key" type="text" name="selectKey[]" placeholder="Mots clés séparés par virgules" value="'.$keys.'" />
										  	<i id="'.$idkeys.'" class="remove_input"></i>
										  </p>';
								}
							}
							
							//Start choosen keywords	
							$html .= '<div class="keywords"><strong>Mots clés</strong>';
							
								// List of keywords choosen
								$html .= '<div class="listeCles">';	
														
									if(isset($userHasKey[$id]['keywords']))
									{
										foreach($userHasKey[$id]['keywords'] as $idkeys => $keys)
										{
											$html .= '<p>'.$keys.'</p>';
										}
									}	
																
								$html .= '</div>';	
							
							//End choosen keywords		
							$html .= '</div>';	
						
							// Add keyword button							
							$html .= '<a href="#" class="addKeywords">Limiter par mots-clés</a><p class="successMsg">Abonnement enregistré!</p>';	
							
						// End check choices
						$html .= '</div><hr class="clearfix"/>';	
						
						// checkbox for ispub							
						$html .= '<div class="check-ispub">
							 	<input class="ispub" id="ispub_'.$i.'" type="checkbox" name="ispub" value="1" />Limiter aux arrêts proposé pour la publication
							 </div>';
						
					$html .= '</li>';// End of li

				 $i++;
					 
				} // end foreach				
							
		
				$html .= '</ul>';
			$html .= '</fieldset>';
		$html .= '</div>';
		
	}			
	
	return $html;
	
}

/*----------------------------------------------------------
	AJAX functions
-----------------------------------------------------------*/


/** 
 * Set selected categories, keywords and if ispub
*/
function implement_ajax() {

	if( isset($_POST['catid']))
	{
		global $wpdb;
		global $current_user;
		
		get_currentuserinfo();
		$user_id = $current_user->ID;
			
		$categorie   = $_POST['catid'];
		$ispub       = $_POST['ispub'];
		
		$catKeywords = (!empty($_POST['keywords']) ? $_POST['keywords'] : '');
		
		array_filter($catKeywords);
		
		// Set default rythme just to be sure the user has one , default to one email per week	
		$already = $wpdb->query(' SELECT * FROM wp_usermeta WHERE user_id = "'.$user_id.'" AND  meta_key = "rythme_abo" ');
		
		if(empty($already))
		{		
			$wpdb->query(' INSERT INTO wp_usermeta SET user_id = "'.$user_id.'" , meta_key = "rythme_abo" , meta_value = "one"  ');			
		}
		
		// si il y a des mots clés		
		if(!empty($catKeywords))
		{
			foreach($catKeywords as $keys)
			{				
				$wpdb->query(' INSERT INTO wp_user_abo SET refUser = "'.$user_id.'" , refCategorie = "'.$categorie.'" , keywords = "'.$keys.'" ');
			}
		}
		else
		{
			$wpdb->query(' INSERT INTO wp_user_abo SET refUser = "'.$user_id.'" , refCategorie = "'.$categorie.'" ');
		}

		/* Is publications */		
		$isPubAlready = $wpdb->query(' SELECT * FROM wp_user_abo_pub WHERE refUser = "'.$user_id.'" AND  refCategorie = "'.$categorie.'" ');
		
		$res = '';
		
		if( $isPubAlready )
		{
			if($ispub == 0)
			{
				$wpdb->query(' DELETE FROM wp_user_abo_pub WHERE refCategorie = "'.$categorie.'" AND refUser = "'.$user_id.'" ');
				$res = 'deleted';
			}
		}
		else
		{
			if($ispub == 1)
			{
				$wpdb->query(' INSERT INTO wp_user_abo_pub SET refUser = "'.$user_id.'" , refCategorie = "'.$categorie.'", ispub = "'.$ispub.'" ');
				$res = 'inserted';
			}
		}

		/* END is publications */
		
		echo $ispub; 
		die();		
		
	} 
}

add_action('wp_ajax_set_abos', 'implement_ajax');
add_action('wp_ajax_nopriv_set_abos', 'implement_ajax');//for users that are not logged in.

/** 
 * Delete abos categories
*/
function implement_ajax_delete() {

	if( isset($_POST['catid']))
	{
		global $wpdb;
		global $current_user;
		
		get_currentuserinfo();
		
		$user_id   = $current_user->ID;			
		$categorie = $_POST['catid'];

		$wpdb->query(' DELETE FROM wp_user_abo WHERE refCategorie = "'.$categorie.'" AND refUser = "'.$user_id.'" ');
			
		echo $user_id.' deleted '.$categorie; 
		
	} 
	
}
add_action('wp_ajax_delete_abos', 'implement_ajax_delete');
add_action('wp_ajax_nopriv_delete_abos', 'implement_ajax_delete');//for users that are not logged in.

/** 
 * Set rythm for user on select change
*/
function implement_ajax_rythme() {

	if( isset($_POST['rythme']))
	{
		global $wpdb;
		global $current_user;
		
		get_currentuserinfo();
		$user_id = $current_user->ID;
			
		$rythme  = $_POST['rythme'];
		
		$already = $wpdb->query(' SELECT * FROM wp_usermeta WHERE user_id = "'.$user_id.'" AND  meta_key = "rythme_abo" ');
		
		if($already)
		{		
			$wpdb->query(' UPDATE wp_usermeta SET meta_value = "'.$rythme.'" WHERE user_id = "'.$user_id.'" AND meta_key = "rythme_abo"  ');			
		}
		else
		{		
			$wpdb->query(' INSERT INTO wp_usermeta SET user_id = "'.$user_id.'" , meta_key = "rythme_abo" , meta_value = "'.$rythme.'"  ');			
		}	
		
		echo $rythme.' '.$already; 
		
	} // end if
}
add_action('wp_ajax_set_rythme', 'implement_ajax_rythme');
add_action('wp_ajax_nopriv_set_rythme', 'implement_ajax_rythme');//for users that are not logged in.



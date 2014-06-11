<?php

	$abosUser    = getUserAboList();
	$userHasCat  = $abosUser['userHasCat'];
	$userHasPub  = $abosUser['userHasPub'];
	$userHasKey  = $abosUser['userHasKey'];	
		
	$categories = prepareAlertesCategories();
	$parents    = $categories['parents'];
	$children   = $categories['children'];
			
   /*========================================
		 Rythme des envois
   =========================================*/
   
	$current_user = wp_get_current_user();
	
	$user_id = $current_user->ID;		
	$rythme  =  get_user_meta($user_id, 'rythme_abo' , true); 



?>


<h2>Alertes e-mail aux derniers arrêts du TF </h2>

<div class="row">
	<div class="col-md-12">	
        <div class="infoBloc">
       		<p>Recevez les nouveaux arrêts rendus par le TF correspondants aux rubriques choisies avec ou sans mots clés. 
            Vous pouvez aussi choisir de ne recevoir que les 
                arrêts correspondants à certains mots clés en les indiquant dans la rubrique "Général".</p>
	        <p>Les mots clés recherchés doivent être séparés par virgules. Ils peuvent être sous la forme d'un groupe de mots entre guillemets, 
                exemple => <strong>"Grand Conseil de Genève"</strong> ou seulement d'un mot, exemple => <strong>CPC</strong>. Les arrêts trouvés sont ceux qui comprennent 
                l'ensemble des mots clés séparés par virgules. Plusieurs listes de mots clés peuvent être créées sous la même rubrique. 
                Il est possible de mettre les mots clés en plusieurs langues afin d'obtenir plus de résultats.</p>
            
        </div>
    </div>
</div>

<div class="row">
   
   <!-- Start tab section -->
   <div id="tabs">	
	   <div class="col-md-4">	
	   
		   <!-- rythm of sending -->
		   <h4><strong>Fréquence des envois</strong></h4>
	        <div id="rythmeBox">
	        	<form action="<?php echo get_permalink(); ?>" method="post" id="choixRythme">
			        <select name="rythme" id="rythme">
		             	<option value="one" <?php if($rythme == 'one'){echo 'selected="selected"';} ?>>Une fois par semaine</option>
				        <option value="all" <?php if($rythme == 'all'){echo 'selected="selected"';} ?>>Tous les jours</option>
			        </select>
	            </form>
	        </div>

			<?php
				
				$number = count($children);
				$numberGeneral = $number +1;
				
				echo '<ul id="tabbed">';			
					echo '<li><a href="#tabs-'.$numberGeneral.'">Général</a></li>';
					
					foreach ($children as $catid => $cats) 
					{ 
						if(isset($parents[$catid]) && $catid != $numberGeneral)
						{
							echo '<li><a href="#tabs-'.$catid.'">'.$parents[$catid].'</a></li>';
						}
					}
				
				echo '</ul>';
		 	
			?>
			
	   </div>
	   <div class="col-md-8">	

	       <form action="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" method="post">  
	       
	       	   <?php  echo createCheckedAbos($children,$user_id,$abosUser, 247); ?>
	        
		   	</form>
		   	
		</div><!-- end col -->
		
		<div class="clearfix"></div>
		
	</div><!-- end #tabs -->
</div><!-- end row -->
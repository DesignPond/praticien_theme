<?php

	$abosUser = getUserAboList();
	$userHasCat  = $abosUser['userHasCat'];
	$userHasPub  = $abosUser['userHasPub'];
	$userHasKey  = $abosUser['userHasKey'];	
		
	$categories = prepareAlertesCategories();
	$parents    = $categories['parents'];
	$children   = $categories['children'];

?>

<div class="row">
   
   <div id="tabs">	
	   <div class="col-md-3">	
	   
			<?php
			
				echo '<ul id="tabbed">';			
				echo '<li><a href="#tabs-22">Général</a></li>';
				
				foreach ($children as $catid => $cats) 
				{ 
					if(isset($parents[$catid]) && $catid != 22)
					{
						echo '<li><a href="#tabs-'.$catid.'">'.$parents[$catid].'</a></li>';
					}
				}
				
				echo '</ul>';
		 	
			?>
			
	   </div>
	   <div class="col-md-9">	

	       <form action="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" method="post">  
	        
		   	   <!-- GENERAL OUTSIDE LOOP -->	
			   <div id="tabs-22">
		            <fieldset class="bgcategorie">
						<ul class="checklist">
			            <?php
								
							$checked = ($userHasCat && in_array( '247' , $userHasCat) ? ' checked="checked ': '');
							$visible = (in_array( '247' , $userHasPub) ? ' style="display:none;" ' : '');
								
							echo '<li data-id="247">';
								echo '<input id="choice_247" name="selectCat[]" '.$checked.' value="247" type="checkbox">';							
								echo '<label for="choice_247">Général</label>';
								
								// is pub checked
								echo '<i class="limite-pub" '.$visible.'>Limité aux arrêts proposé pour la publication</i>';						
									
								echo '<div class="check-title">';
									echo '<a class="checkbox-select selectlink" href="#">sélectionner </a>';
									echo '<a class="checkbox-deselect" href="#">Désabonner ou Modfier</a>';
								echo '</div>';
								
								echo '<div class="check-choice">';
									
								if(isset($userHasKey[247]['keywords']))
								{
									foreach($userHasKey[247]['keywords'] as $idkeys => $keys)
									{
										echo '<p class="new_input">
											<input class="key" type="text" name="selectKey[]" placeholder="Mots clés séparés par virgules" value="'.$keys.'" />
											<i id="'.$idkeys.'" class="remove_input"></i>
										</p>';
									}
								}
								
								echo '<div class="keywords"><strong>Mots clés</strong><div class="listeCles">';							
									
									if(isset($userHasKey[247]['keywords']))
									{
										foreach($userHasKey[247]['keywords'] as $idkeys => $keys)
										{
											echo '<p>'.$keys.'</p>';
										}
									}
			
								echo '</div></div>';						
								echo '<a href="#" class="addKeywords">Limiter par mots-clés</a><p class="successMsg">Abonnement enregistré!</p>';
								echo '</div><hr/>';																
								echo '<div class="check-ispub"><input class="ispub" id="ispub_247" type="checkbox" name="ispub" value="1" />Limiter aux arrêts proposés pour la publication</div>';
										
							echo '</li>';
							
						?>
					 	</ul>
		            </fieldset>
		        </div>
			             
		       <?php 
		            
				$i = 0;
				
				foreach ($children as $catid => $cats) 
				{ 				
					$nbrCat = count($cats); ?>
					
				 <div id="tabs-<?php  echo $catid; ?>"><!-- start #tabs -->
		        	<fieldset class="bgcategorie">        		
						<ul class="checklist">
							<?php
								foreach ($cats as $id => $terms) 
								{ 					
									$checked = ( $userHasCat && (in_array( $id , $userHasCat)) ? ' checked="checked ': '');
									$visible = ( in_array($id , $userHasPub) ? ' style="display:none;" ' : '');									
									$general = (!empty($terms['general']) ? $terms['general'] :  $terms['name']);
								
									echo '<li data-id="'.$id.'">';
										
										echo '<input id="choice_'.$i.'" name="selectCat[]" '.$checked.' value="'.$id.'" type="checkbox">';									
										echo '<label for="choice_'.$i.'">'.$general.'</label>';
		
										// is pub checked
										echo '<i class="limite-pub" '.$visible.'>Limité aux arrêts proposé pour la publication</i>';
										
										echo '<div class="check-title">';
											echo '<a class="checkbox-select selectlink" href="#">sélectionner </a>';
											echo '<a class="checkbox-deselect" href="#">Désabonner ou Modfier</a>';
										echo '</div>';
										
										echo '<div class="check-choice">';
											
											if(isset($userHasKey[$id]['keywords']))
											{
												foreach($userHasKey[$id]['keywords'] as $idkeys => $keys)
												{
													echo '<p class="new_input">
														  <input class="key" type="text" name="selectKey[]" placeholder="Mots clés séparés par virgules" value="'.$keys.'" />
														  <i id="'.$idkeys.'" class="remove_input"></i></p>';
												}
											}
		
											echo '<div class="keywords"><strong>Mots clés</strong><div class="listeCles">';
												
												if(isset($userHasKey[$id]['keywords']))
												{
													foreach($userHasKey[$id]['keywords'] as $idkeys => $keys)
													{
														echo '<p>'.$keys.'</p>';
													}
												}
												
											echo '</div></div>';								
										echo '<a href="#" class="addKeywords">Limiter par mots-clés</a><p class="successMsg">Abonnement enregistré!</p>';	
										echo '</div><hr/>';								
										echo '<div class="check-ispub"><input class="ispub" id="ispub_'.$i.'" type="checkbox" name="ispub" value="1" />Limiter aux arrêts proposé pour la publication</div>';
										
									echo '</li>';
				
								 $i++;
							   } // end foreach
						     ?>			           		
						</ul>

		            </fieldset>			
				</div>	

				<?php } ?>
			</form>
		</div><!-- end #tabs -->
		<div class="clearfix"></div>
	</div><!-- end col -->
</div><!-- end row -->
<?php
	$lastDayUpdated = lastDayUpdated();
	
	$newsletterBloc = newsletterBloc();
?>
<div id="homeContent">
	

	<?php   if ( !is_user_logged_in() ) { ?>
	
		<!-- Homepage login, acces code and newsletter -->		
		<div class="row"><!-- start row -->			
			
			<?php echo $newsletterBloc; ?>
			
			<div class="col-md-8"><!-- start col 8 -->				
					<div id="homepageAcces">
							
							<div class="row">
								<div class="col-md-4 bloc"><!-- start col 8 -->							
					
										<!-- Acces code bloc -->
										<div id="access">
											<h2>S'inscrire sur le site</h2>									
									  		<form name="accessform" id="accessform" action="http://droitpourlepraticien.ch/wp-code.php" method="post">
												<p class="login-password">
													<label for="accescode">Code d'accès</label>
													<input type="text" name="accescode" id="accescode" class="input" value="" size="20" tabindex="20" />
												</p>
												<p class="login-submit">
													<input type="submit" name="wp-submit" id="wp-submit" class="btn btn-buy" value="Envoyer &raquo;" tabindex="100" />
													<input type="hidden" name="redirect_to" value="http://droitpourlepraticien.ch" />
												</p>						
											</form>									
										</div>	
										<!-- end Acces code bloc -->						
								
								</div>
								<div class="col-md-8 bloc"><!-- start col 8 -->
						
										<!-- Login bloc -->
										<div id="login">
											<h2>Login</h2>
											<?php
											
												$args = array(
											        'echo' => true,
											        'redirect' => site_url( $_SERVER['REQUEST_URI'] ), 
											        'form_id' => 'loginform',
											        'label_username' => __( 'Username' ),
											        'label_password' => __( 'Password' ),
											        'label_remember' => __( 'Remember Me' ),
											        'label_log_in' => __( 'Log In' ),
											        'id_username' => 'user_login',
											        'id_password' => 'user_pass',
											        'id_remember' => 'rememberme',
											        'id_submit' => 'wp-submit',
											        'remember' => true,
											        'value_username' => NULL,
											        'value_remember' => false
											     );
											     
											     wp_login_form( $args ); 
											?> 					
										</div>	
										<!-- end Login -->																
								</div>								
							</div>
								
						<div class="clearfix"></div>
							
					</div><!-- end homepageAcces -->
			
			</div><!-- end col 8 -->			
		</div><!-- end row -->		
			
	<?php } ?>
	
	<!-- Bloc TF and last publications -->
	
	<div class="row <?php if(!is_user_logged_in()){echo 'top-buffer';} ?>">
		
		<div class="col-md-4"><!-- start col 4  -->
			<div class="bloc blocBorder tf_bloc">
				<h3>Derniers arrêts rendus</h3>
				<h4>Tribunal fédéral - Jurisprudence</h4>
				<p>Liste des dernières décisions du <?php echo $lastDayUpdated; ?></p>
				<a class="btn btn-blue btn-sm" target="_blank" href="http://www.bger.ch/fr/index/juridiction/jurisdiction-inherit-template/jurisdiction-recht/jurisdiction-recht-urteile2000neu.htm">Voir la liste</a>
				<p class="calendar">Publications du <?php echo $lastDayUpdated; ?></p>
			</div>		
		</div><!-- end col 4 -->
		
		<?php  
		
			// Page to link list to
			$page_list = get_ID_by_slug('arrets-tf');
			$url_list  = add_query_arg( array('star' => 1) , get_permalink($page_list) );
		?>
		
		<div class="col-md-4"><!-- start col 4  -->
			<div class="bloc blocBorder tf_bloc">
				<h3>Arrêts destinés à la publication</h3>
				<h4>Tribunal fédéral - Jurisprudence</h4>
				<p>Liste des décisions destinées à la publication</p>
				<a class="btn btn-blue btn-sm" href="<?php echo $url_list; ?>">Voir la liste</a>
				<p class="calendar">Publications du <?php echo $lastDayUpdated; ?></p>
			</div>		
		</div><!-- end col 4 -->
				
		<?php 
		
			if(is_user_logged_in())
			{
				echo $newsletterBloc;
			}
			else
			{
				echo homepageBloc(1,0);				
			}
			
		?>
		
	</div>
	
	<?php if ( is_user_logged_in() ) { ?>
		
		<div class="row top-buffer">
			<?php echo homepageBloc(3,4); ?>
		</div>
		
	<?php } ?>

<?php

echo "<!--\n";
var_dump($wpdb->queries);
echo "\n-->";
?>

</div>
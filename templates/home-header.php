<div id="home-header">
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				
				<div id="login">
				<h2>S'identifier</h2>
				
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
								
			</div>
			<div class="col-sm-6">
			
				<div class="row">
					<div class="col-sm-5">
						<a href="http://www.publications-droit.ch/#/cat/publications/item/229" target="_blank">
							<img src="<?php echo get_bloginfo('template_directory');?>/assets/img/book.png" alt="droit pour le praticien" />
						</a>
					</div>
					<div class="col-sm-7 buythebook">
						<p>Obtenez l’accès au site pour l’année en cours grâce au code inscrit sur la dernière édition du livre.</p>
						<a href="http://www.publications-droit.ch/#/cat/publications/item/229" target="_blank" class="btn btn-buy">Acheter sur www.publications-droit.ch</a>
					</div>
				</div>	
				
			</div>
		</div>

		<div class="row">
		   <div class="col-sm-6 text-center"><a href="" class="arrets-links-bloc"><span>Consulter</span><br/>Arrêts principaux résumés</a></div>
		   <div class="col-sm-6 text-center"><a href="" class="arrets-links-bloc"><span>Consulter</span><br/>Autres arrêts</a></div>
		</div>

	</div>	
</div>
<?php


/**
 * Shortcodes
*/

function activeUserAccount( $atts = NULL ){
	
	$args = shortcode_atts( array('edition' => '2012/2013' ), $atts );
	
	$action   = admin_url( 'admin-post.php');
	
	$edition = $args['edition'];
	
	$today     = date('Y-m-d');
	$thisyear  = date('Y');
	$yearEnd   = date('Y-m-d', strtotime('12/31'));
	$nextYear  = strtotime(date("Y-m-d", strtotime($yearEnd)) . " +1 year");
	$bookIsOut = $thisyear.'-11-15';
	
	$end = ( ($today < $bookIsOut) ? $yearEnd : $nextYear);
	$end = mysql2date('j F Y', $end );
	
	$user = (!empty($_REQUEST['user']) ? $_REQUEST['user'] : null); 
	
	// Start buffer!!!
	ob_start(); ?>
	
	<div class="row top-buffer">
		<div class="col-sm-4">
			<img src="<?php echo get_bloginfo('template_directory');?>/assets/img/book.png" alt="livre le droit pour le praticien" />
		</div>
		<div class="col-sm-8">
			<h4 class="sectionTitre">La validité de votre compte est arrivé à expiration.</h4>
			<p>Afin de reactiver votre compte sur le site, merci d'indiquer le code d'accès obtenu sur le livre "<strong>Le droit pour le praticien</strong>" 
			édition <strong><?php echo $edition; ?></strong>.</p>
			<p>Votre compte sera alors actif jusqu'au <?php echo $end; ?></p>	
			<?php  
				if(isset($_GET['error']))
				{
					$errorCodes = array(1 => 'Le code n\'est pas valide' , 2 => 'Merci d\'indiquer un code d\'accès');
					echo '<p class="bg-danger text-danger">'.$errorCodes[$_GET['error']].'</p>';
				}
			?>		
			<form class="form-horizontal top-buffer codeaccess" method="post" role="form" action="">	
				<input type="hidden" name="user" id="user" value="<?php echo $user; ?>" />
				<input type="hidden" name="action" value="submit-code" />		
				<div class="form-group">
					<label for="codeaccess" class="col-sm-3 control-label">Code d'accès</label>
					<div class="col-sm-7">
						<div class="input-group">
					      <input type="text" class="form-control" id="accescode" name="accescode" size="20" placeholder="Code">				
					      <span class="input-group-btn">
					         <button type="submit" class="btn btn-buy">Envoyer</button>
					      </span>
					    </div>				    
					</div>
				</div>	
			</form>			
		</div>
	</div>
	
	<?php
	$content = ob_get_clean();
		 
	return $content;
	
}
add_shortcode( 'code_activation', 'activeUserAccount' );

function displayPubDroitPraticien( $atts = NULL ){
	
	// Start buffer!!!
	ob_start(); ?>
	
	<br/>	
	<div class="panel panel-default">	
	  <div class="panel-body">
	  
	  	<div class="row">
		  <div class="col-md-1">
			  <span class="icons icon-law"></span>
		  </div>
		  <div class="col-md-11">
		  		<p>Près de 25 domaines du droit sont abordés. Chaque thème est présenté de manière systématique et 
		  		des liens directs sur les arrêts fédéraux sont inclus. Une recherche ciblée par mots-clés ou articles de loi est également proposée.</p>
		  </div>
		</div>
		
	  </div>
	</div>
	<div class="panel panel-default">	
	  <div class="panel-body">
	  
	  	<div class="row">
		  <div class="col-md-1">
			  <span class="icons icon-tf"></span>
		  </div>
		  <div class="col-md-11">
		  		<p>Ce site permet par ailleurs de consulter la jurisprudence récente du Tribunal fédéral organisée par thèmes principaux.</p>
		  </div>
		</div>

	  </div>
	</div>	
	<div class="panel panel-default">	
	  <div class="panel-body">
	  
	  	<div class="row">
		  <div class="col-md-1">
			  <span class="icons icon-tf"></span>
		  </div>
		  <div class="col-md-11">
		  		<p>Retrouvez les dernières décisions du Tribunal fédéral proposées pour la publication.</p>
		  </div>
		</div>

	  </div>
	</div>	
	<div class="panel panel-default">	
	  <div class="panel-body">
	  
	  	<div class="row">
		  <div class="col-md-1">
			  <span class="icons icon-tf"></span>
		  </div>
		  <div class="col-md-11">
		  		<p>Recevez les nouveaux arrêts rendus par le TF correspondants aux rubriques choisies avec ou sans mots clés.</p>
		  </div>
		</div>

	  </div>
	</div>	
	<div class="panel panel-default">	
	  <div class="panel-body">
	  
	  	<div class="row">
		  <div class="col-md-1">
			  <span class="icons icon-email"></span>
		  </div>
		  <div class="col-md-11">
		  		<p>Abonnez vous à la newsletter “Responsabilité civile, assurances sociales et assurances privées” NLRCAS. 
		  		Réalisée en collaboration avec l’Association des avocats spécialistes FSA responsabilité civile et droit des assurances</p>
		  </div>
		</div>

	  </div>
	</div>	
			
	<?php
	$content = ob_get_clean();
		 
	return $content;
	
}
add_shortcode( 'pub_droitpraticien', 'displayPubDroitPraticien' );
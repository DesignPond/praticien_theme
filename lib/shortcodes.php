<?php


/**
 * Shortcodes
*/

function activeUserAccount( $atts = NULL ){
	
	$args = shortcode_atts( array('edition' => '2012/2013' ), $atts );
	
	$edition = $args['edition'];
	
	$today     = date('Y-m-d');
	$thisyear  = date('Y');
	$yearEnd   = date('Y-m-d', strtotime('12/31'));
	$nextYear  = strtotime(date("Y-m-d", strtotime($yearEnd)) . " +1 year");
	$bookIsOut = $thisyear.'-11-15';
	
	$end = ( ($today < $bookIsOut) ? $yearEnd : $nextYear);
	$end = mysql2date('j F Y', $end );
	
	// Start buffer!!!
	ob_start(); ?>
	
	<p>Afin d'activer votre compte sur le site, merci d'indiquer le code d'accès obtenu sur le livre "Le droit pour le praticien" édition <strong><?php echo $edition; ?></strong>.</p>

	<p>Votre compte sera alors actif jusqu'au <?php echo $end; ?></p>
	
	
	
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
<?php
	// Get page to be redirected to
	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', site_url( $_SERVER['REQUEST_URI'] ));
	
	$cols = (!$suscribedToNewsletter ? 'col-md-6' : 'col-md-6 col-md-offset-3' );
?>
<div class="row">

   <div class="<?php echo $cols; ?>">	
   
		<h3>Accès</h3>
		<p>Vous devez être inscrit sur le site pour voir ce contenu.</p>
		
		<form name="login" id="loginSimple" class="form-horizontal" action="<?php echo wp_login_url(); ?>" method="post">
		  <legend>S'identifier</legend>
		  <div class="form-group">
		    <label for="user_login" class="col-sm-4 control-label">Identifiant</label>
		    <div class="col-sm-8">
		      <input type="text" name="log" class="form-control" id="user_login" placeholder="email">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="user_pass" class="col-sm-4 control-label">Mot de passe</label>
		    <div class="col-sm-8">
		      <input type="password" class="form-control" name="pwd" id="user_pass" placeholder="Mot de passe">
		    </div>
		  </div>
		  <div class="form-group">
		    <div class="col-sm-offset-4 col-sm-8">
		      <div class="checkbox">
		        <label><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> Se souvenir de moi</label>
		      </div>
		    </div>
		  </div>
		  <div class="form-group">
		    <div class="col-sm-6">
				<br/><a class="text-blue" href="<?php echo wp_login_url(); ?>?action=lostpassword"><span class="glyphicon glyphicon-question-sign"></span> &nbsp;Mot de passe perdu?</a>
		    </div>			  
		    <div class="col-sm-6 text-right">
		      <input type="hidden" name="redirect_to" value="<?php echo $current_url; ?>" />
		      <button type="submit" name="wp-submit" id="wp-submit" class="btn btn-blue">Connexion</button>
		    </div>			    	    
		  </div>
		</form>
				
   </div>
   		
	<?php 
		
		// Current category
		$currentCat  = get_query_var('cat');
		// Is the categry linked to a newsletter list?
		$isSubscribe = getCatNewsletter($currentCat);
		
		// If yes and we are not in session show the form
		if( !$suscribedToNewsletter && $isSubscribe )
		{ 	 
			// Get page for inscription to the newsletter  		
	   		$page = get_ID_by_slug('inscription-a-la-newsletter-droit-pour-le-praticien');
	   		// Get name of the list
	   		$name = getCampaignName($isSubscribe);
	   		// Get list of categories we can see with this list
	   		$list = getAllCatsByNewsletterList($isSubscribe);
	   		if($list){$list = implode(",", $list);}
		  	// Retrive the categories 	
		  	$categories = getAllArretsCategories($list);
	   		// Link to page for inscription
	   		$url_inscription = add_query_arg( array( 'id' => $isSubscribe ) , get_permalink($page) );
		   	
	?>
	<div class="col-md-6">
		<h3>Inscrit à la newsletter</h3>
		<p>Si vous êtes inscrit à la newsletter <strong><?php echo $name; ?></strong></p>
		<form class="form-inline" action="<?php echo $current_url; ?>" method="post" role="form" id="bloc_newsletter">
		  <legend>Accéder au contenu</legend>
		  <p>Entrez votre email utilisé lors de votre inscription à la newsletter pour consulter le contenu de des rubriques suivantes:<br/><br/>
			  <?php
				  if( !empty($categories))
				  {
					 echo '<strong>'.implode(", ", $categories).'</strong>';
				  }
			  ?>
		  </p><br/>
		  <div class="form-group">
		    <input type="email" style="width:250px;" class="form-control" name="email_newsletter" placeholder="email">
		    <input type="hidden" name="id_newsletter" value="<?php echo $isSubscribe; ?>">
		  </div>
		  <button type="submit" class="btn btn-blue">Valider</button>
		  <div class="clearfix"></div><br/>
		  <a href="<?php echo $url_inscription; ?>"><span class="glyphicon glyphicon-envelope"></span> &nbsp;S'inscrire à la newsletter : <?php echo $name; ?></a>
		</form>
	</div> 
	  
	<?php } ?>
   
</div>
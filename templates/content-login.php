<?php
	// Get page to be redirected to
	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', site_url( $_SERVER['REQUEST_URI'] ));
	
	// Get category to test if we can see the content by suscribing to newsletter
	$cat_id  = get_query_var('cat');
	
	$isSuscribedNewseltter = (!empty($_SESSION['isSuscribedNewseltter']) ? $_SESSION['isSuscribedNewseltter'] : null );
	
	$cols = ($isSuscribedNewseltter ? 'col-md-6' : 'col-md-6 col-md-offset-3' );
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
   		
   <?php if($isSuscribedNewseltter){ ?>
   		<div class="col-md-6">
   			<div id="bloc_newsletter">
   				<h3>Inscrit à la newsletter</h3>
   			</div>
		</div>   
   <?php } ?>
   
</div>
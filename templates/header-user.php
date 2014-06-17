<?php

	global $post;
	global $current_user;
  	get_currentuserinfo();
	
	$nomUser  = $current_user->user_firstname.' '. $current_user->user_lastname;
    $postSlug = $post->post_name;
    
    // Link to page arrets
    $link = get_ID_by_slug('alertes-configuration');
    
?>	
<div id="header-user">
	<div class="container">

		<div class="row">				
			<div class="col-sm-12">
				
				<nav class="navbar navbar-info" role="navigation">
					<div class="container-fluid">					
					    <div class="navbar-header">
					    	<a class="navbar-brand">Bonjour <strong><?php echo $nomUser; ?></strong>
					    	&nbsp;&nbsp;<?php if(isset($_GET['reactivate'])){ ?><small class="text-center bg-success text-success">Votre compte est maintenant actif</small><?php } ?>
					    	</a>			    	
					    </div>	
					    <ul class="nav navbar-nav navbar-right">
					        <li class="<?php if($postSlug == 'alertes-configuration'){ echo 'active'; } ?>">
					        	<a href="<?php echo get_permalink($link); ?>"><span class="glyphicon glyphicon-envelope"></span> &nbsp;Gérer vos alertes e-mail</a>
					        </li>
					        <li><a href="<?php echo wp_logout_url( get_permalink() ); ?>"><span class="glyphicon glyphicon-off"></span> &nbsp;Déconnexion</a></li>
					    </ul>						    			
				    </div><!-- /.container-fluid -->
				</nav>
				
			</div>								
		</div>
					
	</div>						
</div>


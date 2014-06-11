<div id="header-user">
	<div class="container">
	
		<div class="row">
				
			<div class="col-sm-4">
				<?php
					global $current_user;
			      	get_currentuserinfo();
					
					$nomUser = $current_user->user_firstname.' '. $current_user->user_lastname;
					
					echo 'Bonjour '.$nomUser;
				?>	
			</div>
						
			<div class="col-sm-8">

				
			</div>
								
		</div>
					
	</div>	
					
</div>


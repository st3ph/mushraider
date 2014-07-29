<div class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
       <?php echo $this->Html->link($this->Html->image($mushraiderTheme->logo, array('alt' => $mushraiderTagline.' - MushRaider', 'class' => 'navbar-brand')), '/events', array('escape' => false));?>
    </div>
    <div class="navbar-collapse collapse">
    
	    <ul class="nav navbar-nav">
	        <li class="<?php echo strtolower($this->name) == 'events'?'active':'';?>">
	            <?php echo $this->Html->link(__('Events'), '/events', array('escape' => false));?>
	        </li>
	         <li class="<?php echo strtolower($this->name) == 'roster'?'active':'';?>">
	            <?php echo $this->Html->link(__('Roster'), '/roster', array('escape' => false));?>
	        </li>
	        <?php if(!empty($mushraiderLinks)):;?>
	            <?php foreach($mushraiderLinks as $customLink):?>
	                <li>
	                    <?php echo $this->Html->link($customLink->title, $customLink->url, array('escape' => false));?>
	                </li>
	            <?php endforeach;?>
	        <?php endif;?>                    
	    </ul>
	      
		<ul class="nav navbar-nav navbar-right">
		    <?php if($user):?>
		    	<?php if($user['User']['isAdmin'] || $user['User']['isOfficer']):?>
					<li><?php echo $this->Html->link(__('Admin'), '/admin');?></li>
				<?php endif;?>
				
				<li class="dropdown">
					<?php echo $this->Html->link($user['User']['username'] . ' <span class="caret">', '/account', array('class' => 'dropdown-toggle', 'id' => 'userMenu', 'data-toggle' => 'dropdown', ' data-target' => '#', 'escape' => false));?></a>
					<ul class="dropdown-menu" role="menu">
		                <li><?php echo $this->Html->link(__('Characters'), '/account/characters', array('escape' => false));?></li>
						<li><?php echo $this->Html->link(__('Options'), '/account/settings', array('escape' => false));?></li>
						<li><?php echo $this->Html->link(__('Password'), '/account/password', array('escape' => false, 'title' => __('Password')));?></li>
						<li class="divider"></li>
						<li><?php echo $this->Html->link('<i class="icon-signout"></i> '.__('Logout'), '/auth/logout', array('escape' => false));?></li>
					</ul>
				</li>
			<?php else:?>
				<li><i class="icon-signin"></i> <?php echo $this->Html->link(__('LOGIN / REGISTER'), '/auth/login');?></li>
			<?php endif;?>
	    </ul>
    </div><!--/.nav-collapse -->
  </div>
</div>
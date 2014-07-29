<header id="header">
	<div class="row-fluid">
		<div class="span7">
    		<h1><?php echo $this->Html->image($mushraiderTheme->logo, array('alt' => $mushraiderTagline.' - MushRaider'));?></h1>
    	</div>
    	<div class="span5 menubar">
            <div class="clearfix">
        		<ul class="pull-right inline">
        			<?php if($user):?>
        				<li>
        					<div class="dropdown">
        						<i class="icon-user"></i> <?php echo $this->Html->link($user['User']['username'].' <b class="caret"></b>', '/account', array('class' => 'dropdown-toggle', 'id' => 'userMenu', 'data-toggle' => 'dropdown', ' data-target' => '#', 'escape' => false));?>
    	    					<ul class="dropdown-menu" role="menu" aria-labelledby="userMenu">
    	    						<li><?php echo $this->Html->link('<i class="icon-unlock-alt"></i> '.__('Profile'), '/account', array('escape' => false));?></li>
                                    <li><?php echo $this->Html->link('<i class="icon-shield"></i> '.__('Characters'), '/account/characters', array('escape' => false));?></li>
    	    						<li><?php echo $this->Html->link('<i class="icon-gears"></i> '.__('Options'), '/account/settings', array('escape' => false));?></li>
    	    						<li class="divider"></li>
    	    						<li><?php echo $this->Html->link('<i class="icon-signout"></i> '.__('Logout'), '/auth/logout', array('escape' => false));?></li>
    	    					</ul>
    	    				</div>
        				</li>

        				<?php if($user['User']['isAdmin'] || $user['User']['isOfficer']):?>
        					<li><i class="icon-wrench"></i> <?php echo $this->Html->link(__('Admin'), '/admin');?></li>
        				<?php endif;?>
        			<?php else:?>
        				<li><i class="icon-signin"></i> <?php echo $this->Html->link(__('LOGIN / REGISTER'), '/auth/login');?></li>
        			<?php endif;?>

                    <?php if(!empty($appLocales)):?>
                        <li>
                            <div class="dropdown">
                                <?php echo $this->Html->link('<i class="icon-flag"></i> '.Configure::read('Config.language').' <b class="caret"></b>', '/', array('class' => 'dropdown-toggle', 'id' => 'langMenu', 'data-toggle' => 'dropdown', ' data-target' => '#', 'escape' => false));?>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="langMenu">
                                    <?php foreach($appLocales as $lang):?>
                                        <?php $checked = Configure::read('Config.language') == $lang?'<i class="icon-check"></i>':'';?>
                                        <li><?php echo $this->Html->link(ucwords(strtolower($lang)).' '.$checked, '/l/'.$lang, array('escape' => false));?></li>
                                    <?php endforeach;?>
                                </ul>
                            </div>
                        </li>
                    <?php endif;?>
        		</ul>
            </div>
            <div class="navbar">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-reorder"></span> <?php echo __('Menu');?>
                </a>

                <div class="nav-collapse collapse">
                    <ul class="nav pull-right">
                        <li class="<?php echo strtolower($this->name) == 'events'?'active':'';?>">
                            <?php echo $this->Html->link(__('Events'), '/events', array('escape' => false));?>
                        </li>
                         <li class="<?php echo strtolower($this->name) == 'roadster'?'active':'';?>">
                            <?php echo $this->Html->link(__('Rodster'), '/roadster', array('escape' => false));?>
                        </li>
                        <li class="<?php echo strtolower($this->name) == 'account'?'active':'';?>">
                            <?php echo $this->Html->link(__('My Account'), '/account', array('escape' => false));?>
                        </li>
                        <?php if(!empty($mushraiderLinks)):;?>
                            <?php foreach($mushraiderLinks as $customLink):?>
                                <li>
                                    <?php echo $this->Html->link($customLink->title, $customLink->url, array('escape' => false));?>
                                </li>
                            <?php endforeach;?>
                        <?php endif;?>                    
                    </ul>
                </div>
            </div>
    	</div>
	</div>
</header>
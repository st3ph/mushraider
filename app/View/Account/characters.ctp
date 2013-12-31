<header>
    <h1><i class="icon-shield"></i> <?php echo __('My characters');?></h1>
</header>

<div class="row">
    <div class="span3">
        <?php echo $this->element('account_menu');?>
    </div>
    <div class="span8">
		<div>
			<h3 class="blockToggle"><?php echo $this->Html->link('<i class="icon-plus-sign-alt"></i> '.__('add new character'), '', array('escape' => false));?></h3>
			<?php echo $this->Form->create('Character', array('url' => '/account/characters', 'class' => 'hide'.(isset($showForm)?' show':'')));?>
			    <div class="form-group">
			        <?php echo $this->Form->input('Character.title', array('type' => 'text', 'required' => true, 'label' => __('Character Name'), 'class' => 'span5'));?>
			    </div>
			    <div class="form-group">
			    	<?php echo $this->Form->input('Character.game_id', array('type' => 'select', 'required' => true, 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'class' => 'span5'));?>
			    </div>

			    <div id="objectsPlaceholder">
			    </div>

			    <div class="form-group">		    	
			    	 <?php echo $this->Form->submit(__('Add'), array('class' => 'btn btn-success'));?>		    	
			    </div>
			<?php echo $this->Form->end();?>
		</div>

		<?php if(!empty($characters)):?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th><?php echo __('Game');?></th>
						<th><?php echo __('Name');?></th>
						<th><?php echo __('Level');?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Race');?></th>
						<th><?php echo __('Default Role');?></th>
						<th><?php echo __('Actions');?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($characters as $character):?>
						<tr>
							<td><?php echo $character['Game']['title'];?></td>
							<td><?php echo $character['Character']['title'];?></td>
							<td><?php echo $character['Character']['level'];?></td>
							<td style="color:<?php echo $character['Classe']['color'];?>"><?php echo $character['Classe']['title'];?></td>
							<td><?php echo $character['Race']['title'];?></td>
							<td><?php echo $character['RaidsRole']['title'];?></td>
							<td>
								<?php echo $this->Html->link('<i class="icon-edit"></i>', '/account/characters/edit/c:'.$character['Character']['id'].'-'.$character['Character']['slug'], array('class' => 'btn btn-warning btn-mini', 'escape' => false));?>
								<?php echo $this->Html->link('<i class="icon-remove"></i>', '/account/characters/delete/c:'.$character['Character']['id'].'-'.$character['Character']['slug'], array('class' => 'btn btn-danger btn-mini confirm', 'data-confirm' => __('Are you sure you want to completely delete your character %s ? (this can\'t be undone)', $character['Character']['title']), 'escape' => false))?>
							</td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		<?php else:?>
			<p class="message404"><i class="icon-arrow-up"></i> <?php echo __('Add your first character');?> <i class="icon-arrow-up"></i></p>
		<?php endif;?>        
    </div>
</div>
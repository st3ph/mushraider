<header>
	<h1><i class="icon-shield"></i> <?php echo __('My characters');?></h1>
</header>

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
					<td>
						<?php echo $this->Html->link('<i class="icon-edit"></i>', '/account/characters/edit/c:'.$character['Character']['id'].'-'.$character['Character']['slug'], array('class' => 'btn btn-warning btn-mini', 'escape' => false));?>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
<?php else:?>
	<p class="message404"><i class="icon-arrow-up"></i> <?php echo __('Add your first character');?> <i class="icon-arrow-up"></i></p>
<?php endif;?>
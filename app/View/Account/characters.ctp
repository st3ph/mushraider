<header>
    <h1><i class="icon-shield"></i> <?php echo __('My characters');?></h1>
</header>

<div class="row">
    <div class="span8">
		<div>
			<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalAddchar">
			  <?php echo __('add new character')?>
			</button>
			<div class="modal fade" id="modalAddchar" tabindex="-1" role="dialog" aria-hidden="true">
				 <div class="modal-dialog">
    				<div class="modal-content">
    					  <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					        <h4 class="modal-title" id="myModalLabel"><?php echo __('add new character')?></h4>
					      </div>
    					<div class="modal-body">
						<?php echo $this->Form->create('Character', array('url' => '/account/characters', 'enctype' => 'multipart/form-data'));?>
						    <div class="form-group">
						        <?php echo $this->Form->input('Character.title', array('type' => 'text', 'required' => true, 'label' => __('Character Name'), 'class' => 'form-control'));?>
						    </div>
						    <div class="form-group">
						    	<?php echo $this->Form->input('Character.game_id', array('type' => 'select', 'required' => true, 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'class' => 'form-control'));?>
						    </div>
			
						    <div id="objectsPlaceholder">
						    </div>
			
						    <div class="form-group">		    	
						    	 <?php echo $this->Form->submit(__('Add'), array('class' => 'btn btn-success'));?>		    	
						    </div>
						     
						<?php echo $this->Form->end();?>
						</div>
						<div class="modal-footer">
					        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					      </div>
					</div>
				</div>
			</div>
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
								<?php echo $this->Html->link('<i class="icon-edit"></i>', '/account/characters/edit/c:'.$character['Character']['id'].'-'.$character['Character']['slug'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false));?>
								<?php if($character['Character']['status']):?>
									<?php echo $this->Html->link('<i class="icon-collapse-alt"></i>', '/account/characters/disable/c:'.$character['Character']['id'].'-'.$character['Character']['slug'], array('class' => 'btn btn-warning btn-mini tt', 'title' => __('Disable'), 'escape' => false));?>
								<?php else:?>
									<?php echo $this->Html->link('<i class="icon-check"></i>', '/account/characters/enable/c:'.$character['Character']['id'].'-'.$character['Character']['slug'], array('class' => 'btn btn-success btn-mini tt', 'title' => __('Enable'), 'escape' => false));?>
								<?php endif;?>
								<?php echo $this->Html->link('<i class="icon-trash"></i>', '/account/characters/delete/c:'.$character['Character']['id'].'-'.$character['Character']['slug'], array('class' => 'btn btn-danger btn-mini tt confirm', 'title' => __('Delete'), 'data-confirm' => __('Are you sure you want to completely delete your character %s ? (this can\'t be undone)', $character['Character']['title']), 'escape' => false))?>
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
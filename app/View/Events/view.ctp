<?php $registeredCharacter = $this->Tools->getRegisteredCharacter($user['User']['id'], $event['EventsCharacter']);?>
<?php $registeredCharacterId = $registeredCharacter?$registeredCharacter['id']:0;?>
<?php $registeredCharacterComment = $registeredCharacter?$registeredCharacter['comment']:'';?>
<?php $eventRoles = array();?>
<?php $dayTimestamp = $this->Tools->get_timestamp($event['Event']['time_invitation'], true);?>
<?php $todayTimestamp = mktime(0, 0, 0, date('m'), date('d'), date('Y'));?>
<?php
if($registeredCharacter) {
	switch($registeredCharacter['status']) {
		case 0:
			$icon = 'icon-remove';
			break;
		case 1:
			
			$icon = 'icon-time';		
			break;
		case 2:
			$icon = 'icon-ok';			
			break;
	}
}
?>
		
<header>
	<?php if($dayTimestamp >= $todayTimestamp && ($user['User']['isOfficer'] || $user['User']['isAdmin'])):?>
			<div class="pull-right">
				<?php echo $this->Html->link('<i class="icon-copy"></i> '.__('Copy'), '/events/view/'.$event['Event']['id'], array('id' => 'createTemplate', 'class' => 'btn btn btn-default btn-mini tt', 'title' => __('Create template'), 'escape' => false));?>
				<span id="tplName" class="form-inline" data-event="<?php echo $event['Event']['id'];?>"><input type="text" class="input-small form-control" value="" placeholder="<?php echo __('template name');?>"/> <span class="btn btn-danger"><i class="icon-remove"></i></span> <span class="btn btn-success"><i class="icon-save"></i></span></span>
				<?php echo $this->Html->link('<i class="icon-edit"></i> '.__('Edit'), '/events/edit/'.$event['Event']['id'], array('class' => 'btn btn-info btn-mini', 'escape' => false));?>
				<?php echo $this->Html->link('<i class="icon-trash"></i> '.__('Delete'), '/events/delete/'.$event['Event']['id'], array('class' => 'btn btn-danger btn-mini confirm', 'data-confirm' => __("Are you sure you want to delete this event ?\n(this can't be undone)"), 'escape' => false));?>
			</div>
		<?php endif;?>
	<h1>
		<i class="<?php echo $icon?>"></i>
		<?php echo $event['Event']['title'];?>
		
	</h1>
</header>

<div class="row">

	<div class="col-xs-9">
	
		<h3><?php echo __('Dungeon');?></h3>
		<?php echo $event['Dungeon']['title'];?>
	
		<h3><?php echo __('Event start');?></h3>
		<?php echo $this->Former->date($event['Event']['time_start']);?>
	</div>
	
	<div class="col-xs-3 text-right">
		<div id="eventSignin">
			<?php echo $this->Form->input('Character', array('options' => $charactersList, 'selected' => $registeredCharacterId, 'empty' => __('Choose a character'), 'class' => 'form-control', 'data-user' => $user['User']['id'], 'data-event' => $event['Event']['id'], 'data-error' => __('please select a character and a role'), 'data-signin' => __('your are registered to this event as'), 'data-signout' => __('your are not registered to this event'), 'label' => false, 'div' => false));?>
			<?php if(!empty($event['EventsRole'])):?>
				<select name="data[EventsRole]" id="EventsRole" class="form-control">
					<option value=""><?php echo __('Role');?></option>
					<?php foreach($event['EventsRole'] as $eventRole):?>
						<option value="<?php echo $eventRole['RaidsRole']['id'];?>" <?php echo ($registeredCharacter && $eventRole['RaidsRole']['id'] == $registeredCharacter['raids_role_id'])?'selected="selected"':'';?>><?php echo $eventRole['RaidsRole']['title'];?></option>
						<?php					
						$eventRoles['role_'.$eventRole['RaidsRole']['id']]['id'] = $eventRole['RaidsRole']['id'];
						$eventRoles['role_'.$eventRole['RaidsRole']['id']]['title'] = $eventRole['RaidsRole']['title'];
						$eventRoles['role_'.$eventRole['RaidsRole']['id']]['max'] = $eventRole['count'];
						$eventRoles['role_'.$eventRole['RaidsRole']['id']]['current'] = 0;
						$eventRoles['role_'.$eventRole['RaidsRole']['id']]['characters']['validated'] = '';
						$eventRoles['role_'.$eventRole['RaidsRole']['id']]['characters']['waiting'] = '';
						$eventRoles['role_'.$eventRole['RaidsRole']['id']]['characters']['nok'] = '';
						?>
					<?php endforeach;?>
				</select>
			<?php endif;?>
			<?php echo $this->Form->input('Comment', array('type' => 'text', 'value' => $registeredCharacterComment, 'class' => ' form-control', 'placeholder' => __('Add a comment'), 'label' => false, 'div' => false, 'maxlength' => 75));?>
			<p>
				<span class="btn btn-success" data-status="1">Présent</span>
				<span class="btn btn-danger" data-status="0">Absent</span>
			</p>
		</div>
	</div>
</div>

<h3><?php echo __('Description');?></h3>
<article class="well"><?php echo $event['Event']['description'];?></article>

<h3><?php echo __('Informations');?></h3>
<table id="eventInfos" class="table table-striped table-bordered">
	<tbody>
		<tr>
			<td class="title"><?php echo __('Dungeon');?> :</td>
			<td><?php echo $event['Dungeon']['title'];?></td>
		</tr>
		<tr>
			<td class="title"><?php echo __('Invitation start');?> :</td>
			<td><?php echo $this->Former->date($event['Event']['time_invitation']);?></td>
		</tr>
		<tr>
			<td class="title"><?php echo __('Event start');?> :</td>
			<td><?php echo $this->Former->date($event['Event']['time_start']);?></td>
		</tr>
		<tr>
			<td class="title"><?php echo __('Minimum character level');?> :</td>
			<td><?php echo $event['Event']['character_level'];?></td>
		</tr>
		<tr>
			<td class="title"><?php echo __('Created by');?> :</td>
			<td><?php echo $event['User']['username'];?></td>
		</tr>
	</tbody>
</table>

<h3><?php echo __('Roster');?></h3>
<?php if(!empty($eventRoles)):?>
	<?php $eventRoles = $this->Former->charactersToRoles($eventRoles, $event['EventsCharacter'], $user);?>
	<table id="eventRoles" class="table table-striped table-bordered" data-id="<?php echo $event['Event']['id'];?>">
		<thead>
			<tr>
				<?php foreach($eventRoles as $roleId => $eventRole):?>
					<th data-id="<?php echo $roleId;?>">
						<?php echo $eventRole['title'];?>
						<span class="current"><?php echo $eventRole['current'];?></span> / <span class="max"><?php echo $eventRole['max'];?></span>
						<?php if($user['User']['isOfficer'] || $user['User']['isAdmin']):?>
							<i class="icon-edit pull-right text-warning"></i>
						<?php endif;?>
					</th>
				<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php foreach($eventRoles as $roleId => $eventRole):?>
					<td data-id="<?php echo $roleId;?>" data-full="<?php echo __('The roster for this role is full');?>">
						<h5><?php echo __('Validated');?></h5>
						<ul class="validated">
							<?php echo $eventRole['characters']['validated'];?>
						</ul>
						<hr />
						<h5><?php echo __('Waiting');?></h5>
						<ul class="waiting sortWaiting">
							<?php echo $eventRole['characters']['waiting'];?>
						</ul>
						<hr />
						<h5><?php echo __('Rejected');?></h5>
						<ul class="rejected">
							<?php echo $eventRole['characters']['nok'];?>
						</ul>
					</td>
				<?php endforeach;?>
			</tr>
		</tbody>
	</table>
<?php endif;?>

<h3><?php echo __('Bad Kitties');?> <small><?php echo __('no answer yet...');?></small></h3>
<?php if(!empty($badGuys)):?>
	<div id="badKitties">
		<?php foreach($badGuys as $key => $badGuy):?>
			<?php echo $key?', ':'';?>
			<span><?php echo $badGuy['User']['username'];?></span>
		<?php endforeach;?>
	</div>
<?php else:?>
	<h5><?php echo __('No bad kitty for this event !');?></h5>
<?php endif;?>

<h3><?php echo __('Comments');?></h3>
<?php echo $this->Comment->show($event, 'Event', array('connected' => $user));?>
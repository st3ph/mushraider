<?php $registeredCharacter = $this->Tools->getRegisteredCharacter($user['User']['id'], $event['EventsCharacter']);?>
<?php $registeredCharacterId = $registeredCharacter?$registeredCharacter['id']:0;?>
<?php $registeredCharacterComment = $registeredCharacter?$registeredCharacter['comment']:'';?>
<?php $eventRoles = array();?>
<?php $dayTimestamp = $this->Tools->get_timestamp($event['Event']['time_inscription'], true);?>
<?php $todayTimestamp = mktime(0, 0, 0, date('m'), date('d'), date('Y'));?>
<?php $cantSignInTimestamp = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));?>
<header>
	<h1>
		<div class="row">
			<?php $eventIsClosed = $dayTimestamp <= $todayTimestamp;?>
			<?php $displayAdminButtons = ($dayTimestamp >= $todayTimestamp && (($user['User']['can']['manage_own_events'] && $user['User']['id'] == $event['User']['id']) || $user['User']['can']['manage_events'] || $user['User']['can']['full_permissions']))?true:false?>
			<?php $displayTplButtons = ($dayTimestamp >= $todayTimestamp && ($user['User']['can']['create_templates'] || $user['User']['can']['full_permissions']))?true:false?>
			<?php $displayCloseButton = ($dayTimestamp < $todayTimestamp && ($user['User']['can']['create_reports'] || ($user['User']['can']['manage_own_events'] && $user['User']['id'] == $event['User']['id']) || $user['User']['can']['full_permissions']))?true:false?>
			<?php $displayReportButton = !empty($event['Report']['id'])?true:false?>
			<div class="span<?php echo ($displayAdminButtons || $displayCloseButton || $displayReportButton || $displayTplButtons)?6:11?>">
				<i class="icon-calendar-empty"></i> <?php echo __('View event');?>
				<?php if(!empty($event['Game']['logo'])):?>
					&nbsp;<?php echo $this->Html->image($event['Game']['logo'], array('class' => 'logo', 'width' => 32));?>
				<?php endif;?>
			</div>
			<?php if($displayAdminButtons || $displayCloseButton || $displayReportButton || $displayTplButtons):?>
				<div class="pull-right text-right span5 eventAdminButtons">
					<?php if($displayTplButtons):?>
						<?php echo $this->Html->link('<i class="icon-copy"></i> '.__('Copy'), '/events/view/'.$event['Event']['id'], array('id' => 'createTemplate', 'class' => 'btn btn-mini tt', 'title' => __('Create template'), 'escape' => false));?>
						<span id="tplName" data-event="<?php echo $event['Event']['id'];?>"><input type="text" class="input-small" value="" placeholder="<?php echo __('template name');?>"/> <span class="text-error"><i class="icon-remove"></i></span> <span class="text-success"><i class="icon-save"></i></span></span>
					<?php endif;?>
					<?php if($displayAdminButtons):?>
						<?php echo $this->Html->link('<i class="icon-edit"></i> '.__('Edit'), '/events/edit/'.$event['Event']['id'], array('class' => 'btn btn-warning btn-mini', 'escape' => false));?>
						<?php echo $this->Html->link('<i class="icon-trash"></i> '.__('Delete'), '/events/delete/'.$event['Event']['id'], array('class' => 'btn btn-danger btn-mini confirm', 'data-confirm' => __("Are you sure you want to delete this event ?\n(this can't be undone)"), 'escape' => false));?>
					<?php endif;?>
					<?php if($displayReportButton):?>
						<?php echo $this->Html->link(__('View report'), '/events/report/'.$event['Event']['id'], array('class' => 'btn btn-inverse btn-mini', 'escape' => false));?>
					<?php endif;?>
					<?php if(!$displayReportButton && $displayCloseButton):?>
						<?php echo $this->Html->link('<i class="icon-lock"></i> '.__('Close & create a report'), '/events/close/'.$event['Event']['id'], array('class' => 'btn btn-success btn-mini', 'escape' => false));?>
					<?php endif;?>
				</div>
			<?php endif;?>
		</div>
	</h1>
</header>

<?php 
// Prepare roles array
foreach($event['EventsRole'] as $eventRole) {
	$eventRoles['role_'.$eventRole['RaidsRole']['id']]['id'] = $eventRole['RaidsRole']['id'];
	$eventRoles['role_'.$eventRole['RaidsRole']['id']]['title'] = $eventRole['RaidsRole']['title'];
	$eventRoles['role_'.$eventRole['RaidsRole']['id']]['max'] = $eventRole['count'];
	$eventRoles['role_'.$eventRole['RaidsRole']['id']]['current'] = 0;
	$eventRoles['role_'.$eventRole['RaidsRole']['id']]['characters']['validated'] = '';
	$eventRoles['role_'.$eventRole['RaidsRole']['id']]['characters']['waiting'] = '';
	$eventRoles['role_'.$eventRole['RaidsRole']['id']]['characters']['nok'] = '';
	$eventRoles['role_'.$eventRole['RaidsRole']['id']]['characters']['refused'] = '';
}
?>

<?php if($dayTimestamp > $cantSignInTimestamp):?>
	<div id="eventSignin">
		<div class="pull-right">
			<?php if(!empty($charactersList)):?>
				<?php
				$messageClass = '';
				$messageText = '';
				if($registeredCharacter) {
					switch($registeredCharacter['status']) {
						case 0:
							$messageClass = 'label label-warning';
							$messageText = __('your are registered as "absent"');				
							break;
						case 1:
							$messageClass = 'label label-info';
							$messageText = __('your are registered to this event as');				
							break;
						case 2:
							$messageClass = 'label label-success';
							$messageText = __('your are validated to this event as');				
							break;
						case 3:
							$messageClass = 'label label-important';
							$messageText = __('your are refused for this event');
							break;
					}
				}
				?>
				<span class="message <?php echo $messageClass;?>"><?php echo $messageText;?></span>
				<?php //echo $this->Form->input('Character', array('options' => $charactersList, 'selected' => $registeredCharacterId, 'empty' => __('Choose a character'), 'class' => 'span2', 'data-user' => $user['User']['id'], 'data-event' => $event['Event']['id'], 'data-error' => __('please select a character and a role'), 'data-signin' => __('your are registered to this event as'), 'data-signout' => __('your are not registered to this event'), 'data-validated' => __('your are validated to this event'), 'label' => false, 'div' => false));?>

				<select name="data[Character]" class="span2" data-user="<?php echo $user['User']['id'];?>" data-event="<?php echo $event['Event']['id'];?>" data-error="<?php echo __('please select a character and a role');?>" data-signin="<?php echo __('your are registered to this event as');?>" data-signout="<?php echo __('your are not registered to this event');?>" data-validated="<?php echo __('your are validated to this event');?>" id="Character">
					<option value=""><?php echo __('Choose a character');?></option>
					<?php if(!empty($charactersList)):?>
						<?php foreach($charactersList as $character):?>
							<option value="<?php echo $character['Character']['id'];?>" <?php echo $registeredCharacterId == $character['Character']['id']?'selected="selected"':'';?> style="color:<?php echo $character['Classe']['color'];?>"><?php echo $character['Character']['title'];?></option>
						<?php endforeach;?>
					<?php endif;?>
				</select>


				<?php if(!empty($event['EventsRole'])):?>
					<select name="data[EventsRole]" id="EventsRole" class="span1">
						<option value=""><?php echo __('Role');?></option>
						<?php foreach($event['EventsRole'] as $eventRole):?>
							<?php if($eventRole['count'] > 0):?>
								<option value="<?php echo $eventRole['RaidsRole']['id'];?>" <?php echo ($registeredCharacter && $eventRole['RaidsRole']['id'] == $registeredCharacter['raids_role_id'])?'selected="selected"':'';?>><?php echo $eventRole['RaidsRole']['title'];?></option>
							<?php endif;?>
						<?php endforeach;?>
					</select>
				<?php endif;?>
				<?php echo $this->Form->input('Comment', array('type' => 'text', 'value' => $registeredCharacterComment, 'class' => 'span3', 'placeholder' => __('Add a comment'), 'label' => false, 'div' => false, 'maxlength' => 75));?>
				<span class="btn" data-status="<?php echo $event['Event']['open']?'2':'1';?>"><i class="icon-thumbs-up"></i></span>
				<span class="btn" data-status="0"><i class="icon-thumbs-down"></i></span>
			<?php else:?>
				<p>
					<span class="text-warning"><?php echo __('You don\'t have any character for this game :(');?></span>
					<?php echo $this->Html->link('<span class="icon-plus"></span> '.__('Create a character'), '/account/characters', array('class' => 'btn btn-small', 'escape' => false));?>
				</p>
			<?php endif;?>
		</div>
	</div>
	<div class="clear"></div>
	<?php if($event['Event']['open']):?>
		<div class="row">
			<div class="span11 pull-right text-right">
				<span class="label label-info"><span class="icon-info-sign"></span> <?php echo __('This event is open ! You will be auto validated \o/');?></span>
			</div>
		</div>
	<?php endif;?>
<?php endif;?>

<h3><?php echo __('Title');?></h3>
<?php echo $event['Event']['title'];?>

<h3><?php echo __('Description');?></h3>
<?php echo $event['Event']['description'];?>

<h3><?php echo __('Informations');?></h3>
<table id="eventInfos" class="table table-striped table-bordered">
	<tbody>
		<tr>
			<td class="title"><?php echo __('Game');?> :</td>
			<td><?php echo $event['Game']['title'];?></td>
		</tr>
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
			<td class="title"><?php echo __('Registration end date');?> :</td>
			<td><?php echo $this->Former->date($event['Event']['time_inscription']);?></td>
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

<h3>
	<?php echo __('Roster');?>
	<?php if(($user['User']['can']['manage_own_events'] && $user['User']['id'] == $event['User']['id']) || $user['User']['can']['manage_events'] || $user['User']['can']['full_permissions']):?>
		<button type="button" class="btn btn-default btn-mini margin-left-10" data-toggle="modal" data-target="#invitCommands"><i class="icon-code"></i> <?php echo __('Invitation commands');?></button>
	<?php endif;?>
</h3>

<?php if(($user['User']['can']['manage_own_events'] && $user['User']['id'] == $event['User']['id']) || $user['User']['can']['manage_events'] || $user['User']['can']['full_permissions']):?>
	<div id="invitCommands" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel"><?php echo __('Generate invitation\'s commands');?></h3>
		</div>
		<div class="modal-body">
			<p class="text-info">
				<i class="icon-info-sign"></i>
				<?php echo __('Select the command corresponding to your game and copy/paste the generated commands to your chat to quick invit you roster');?>
			</p>
			<p class="text-info">
				<?php echo __('Invitation\'s commands are separated in blocs of 200 characters maximum to avoid game chat limitations');?>
			</p>
			<div class="btn-group open">
				<button class="btn">/i</button>
				<button class="btn">/inv</button>
				<button class="btn">/invite</button>
				<button class="btn">/inviter</button>
				<button class="btn">/raid inviter</button>
			</div>
			<div class="commands">
			
			</div>
		</div>
	</div>
<?php endif;?>


<?php if(!empty($eventRoles)):?>
	<?php $eventRoles = $this->Former->charactersToRoles($eventRoles, $event['EventsCharacter'], $user, $event);?>
	<table id="eventRoles" class="table table-striped table-bordered" data-id="<?php echo $event['Event']['id'];?>">
		<thead>
			<tr>
				<?php foreach($eventRoles as $roleId => $eventRole):?>
					<?php if($eventRole['max'] > 0):?>
						<th data-id="<?php echo $roleId;?>">
							<?php echo $eventRole['title'];?>
							<span class="current"><?php echo $eventRole['current'];?></span> / <span class="max"><?php echo $eventRole['max'];?></span>
							<?php if(!$eventIsClosed && (($user['User']['can']['manage_own_events'] && $user['User']['id'] == $event['User']['id']) || $user['User']['can']['manage_events'] || $user['User']['can']['full_permissions'])):?>
								<span class="badge badge-warning pull-right"><i class="icon-edit"></i></span>
							<?php endif;?>
						</th>
					<?php endif;?>
				<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php
				$displayedRoles = 0;
				if(!empty($eventRoles)) {
					foreach($eventRoles as $roleId => $eventRole) {
						$displayedRoles = $eventRole['max'] > 0?$displayedRoles + 1:$displayedRoles;
					}
				}
				$colWidth = $displayedRoles?floor(100 / $displayedRoles):100;
				?>
				<?php foreach($eventRoles as $roleId => $eventRole):?>
					<?php if($eventRole['max'] > 0):?>
						<td data-id="<?php echo $roleId;?>" data-full="<?php echo __('The roster for this role is full');?>" style="width:<?php echo $colWidth;?>%">
							<h5 class="text-success"><?php echo __('Validated');?> <span class="count"></span></h5>
							<ul class="validated">
								<?php echo $eventRole['characters']['validated'];?>
							</ul>
							<hr />
							<h5 class="text-info"><?php echo __('Waiting');?> <span class="count"></span></h5>
							<ul class="waiting sortWaiting">
								<?php echo $eventRole['characters']['waiting'];?>
							</ul>
							<hr />
							<h5 class="text-error"><?php echo __('Refused');?> <span class="count"></span></h5>
							<ul class="refused">
								<?php echo $eventRole['characters']['refused'];?>
							</ul>
							<hr />
							<h5 class="text-warning"><?php echo __('Rejected');?> <span class="count"></span></h5>
							<ul class="rejected">
								<?php echo $eventRole['characters']['nok'];?>
							</ul>
						</td>
					<?php endif;?>
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
			<span data-user="<?php echo $badGuy['User']['id'];?>"><?php echo $badGuy['User']['username'];?></span>
		<?php endforeach;?>
	</div>
<?php else:?>
	<h5><?php echo __('No bad kitty for this event !');?></h5>
<?php endif;?>

<h3><?php echo __('Comments');?></h3>
<?php echo $this->Comment->show($event, 'Event', array('connected' => $user));?>
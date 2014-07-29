<header>
    <h1><i class="icon-shield"></i> <?php echo __('Roadster');?></h1>
</header>

<div class="row">
    <div class="span11">
		<?php if(!empty($characters)):?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th><?php echo __('Name');?></th>
						<th><?php echo __('Level');?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Attenuement');?></th>
						<th><?php echo __('Default Role');?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($characters as $character):?>
						<tr>
							<td><?php echo $character['Character']['title'];?></td>
							<td><?php echo $character['Character']['level'];?></td>
							<td style="color:<?php echo $character['Classe']['color'];?>"><?php echo $character['Classe']['title'];?></td>
							<td><?php echo $character['Attenuement']['title'];?></td>
							<td><?php echo $character['RaidsRole']['title'];?></td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		<?php else:?>
			<p class="message404"><i class="icon-arrow-up"></i> <?php echo __('Add your first character');?> <i class="icon-arrow-up"></i></p>
		<?php endif;?>        
    </div>
</div>
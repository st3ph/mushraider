<header>
    <h1><i class="icon-shield"></i> <?php echo __('Roster');?></h1>
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
						<th><?php echo __('Attunement');?></th>
						<th><?php echo __('Default Role');?></th>
						<th><?php echo __('Ws-base');?></th>
						<th><?php echo __('Stats');?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($characters as $character):?>
						<tr>
							<td><?php echo $character['Character']['title'];?></td>
							<td><?php echo $character['Character']['level'];?></td>
							<td style="color:<?php echo $character['Classe']['color'];?>"><?php echo $character['Classe']['title'];?></td>
							<td><?php echo $character['Attunement']['title'];?></td>
							<td><?php echo $character['RaidsRole']['title'];?></td>
							<td>
								<?php if (!empty($character['Character']['build_url'])) : ?>
								<a target="_blank" href="<?php echo $character['Character']['build_url'];?>" class="btn btn-primary"><?php echo __('WS-base');?><a/>
								<?php endif; ?>
							</td>
							<td>
								<?php if (!empty($character['Character']['stat_capture'])) : ?>
								<a class="btn btn-success fancybox" href="<?php echo $character['Character']['stat_capture'];?>">
									<?php echo __('Stats');?>
								</a>
								<?php endif; ?>
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
<?php if(!empty($breadcrumb) && count($breadcrumb) > 1):?>
	<ul class="breadcrumb">
		<?php foreach($breadcrumb as $crumb):?>
			<li <?php !empty($crumb['url'])?'':'active';?>>
				<?php if(!empty($crumb['url'])):?>
					<?php echo $this->Html->link($crumb['title'], $crumb['url'], array('title' => $crumb['title'], 'escape' => false));?>
				<?php else:?>
					<?php echo $crumb['title'];?>
				<?php endif;?>
			</li>
		<?php endforeach;?>
	</ul>
<?php endif;?>
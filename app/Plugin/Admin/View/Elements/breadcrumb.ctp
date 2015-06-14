<?php if(!empty($breadcrumb)):?>
	<ul class="breadcrumb">
		<?php foreach($breadcrumb as $crumb):?>
			<li <?php echo count($breadcrumb) == 1?'class="big"':'';?>>
				<?php if(!empty($crumb['url'])):?>
					<?php echo $this->Html->link($crumb['title'], $crumb['url'], array('title' => $crumb['title'], 'escape' => false));?>
					<span class="divider"><i class="fa fa-chevron-right"></i></span>
				<?php else:?>
					<?php echo $crumb['title'];?>
				<?php endif;?>
			</li>
		<?php endforeach;?>
	</ul>
<?php endif;?>
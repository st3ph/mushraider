<h1><?php echo $page['Page']['title'];?></h1>

<div class="content">
    <?php echo $page['Page']['content'];?>
</div>

<small><em><?php echo __('Last update');?> : <?php echo $this->Former->date($page['Page']['modified']);?></em></small>
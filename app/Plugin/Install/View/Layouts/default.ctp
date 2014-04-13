<!DOCTYPE html>
<html>
<head>
	<!--[if lt Ie 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/png" href="/favicon.png" />
	<?php		
	$this->Html->css('bootstrap.min.2.3.2', null, array('inline' => false));
	$this->Html->css('bootstrap-responsive.min.2.3.2', null, array('inline' => false));
	$this->Html->css('font-awesome.min', null, array('inline' => false));
	$this->Html->css('jquery-ui-1.10.3.custom.min', null, array('inline' => false, 'media' => 'screen'));
	$this->Html->css('Install.styles', null, array('inline' => false));

	$this->Html->script('jquery-2.1.0.min', array('inline' => false));
	$this->Html->script('jquery-ui-1.10.3.custom.min', array('inline' => false));
	$this->Html->script('bootstrap.min', array('inline' => false));	
	$this->Html->script('Install.scripts', array('inline' => false));

	echo $this->fetch('meta');
	echo $this->fetch('css');		
	?>
</head>
<body>
	<div id="container">
		<div class="container">
			<header>
				<h1><?php echo $this->Html->image('/img/logo.png', array('alt' => 'MushRaider'));?> <?php echo __('installer');?></h1>
				<h2><?php echo __('Step');?> <?php echo $step;?></h2>
			</header>
			<div id="content">

				<?php echo $this->Session->flash(); ?>

				<div class="well">
					<?php echo $this->fetch('content'); ?>
				</div>
			</div>
			<?php echo $this->element('footer'); ?>
		</div>
	</div>
	<?php echo $this->fetch('script');?>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>

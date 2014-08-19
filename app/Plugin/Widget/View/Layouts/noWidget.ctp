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
    <meta name="robots" content="noindex, nofollow" />
    <link rel="icon" type="image/png" href="/favicon.png" />
    <?php
    $staticVersion = '?v='.Configure::read('mushraider.version');
    $this->Html->css('bootstrap.min.2.3.2', null, array('inline' => false));
    $this->Html->css('bootstrap-responsive.min.2.3.2', null, array('inline' => false));
    $this->Html->css('jquery-ui-1.10.3.custom.min', null, array('inline' => false, 'media' => 'screen'));
    $this->Html->css('//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css', null, array('inline' => false));
    $this->Html->css('Widget.styles.css'.$staticVersion, null, array('inline' => false));

    $this->Html->script('jquery-2.1.0.min', array('inline' => false));
    $this->Html->script('jquery-ui-1.10.3.custom.min', array('inline' => false));
    $this->Html->script('bootstrap.min', array('inline' => false));

    echo $this->fetch('meta');
    echo $this->fetch('css');
    ?>    
</head>
<body class="widget">
    <div id="container">
        <div class="container-fluid">
            <header class="widget-header">
                <?php echo __('Widget not found :(');?>
            </header>
            <div class="widget-content">
                <p class="text-center"><?php echo __('The widget is wiping somewhere');?></p>
            </div>
            <footer class="widget-footer">
                <p><?php echo $this->Html->link('MushRaider', 'http://mushraider.com', array('target' => '_blank', 'escape' => false));?> <span class="version">(v<?php echo Configure::read('mushraider.version');?>)</span></p>
            </footer>
        </div>
    </div>
    <?php echo $this->fetch('script');?>
    <?php echo $this->fetch('scriptBottom');?>
</body>
</html>
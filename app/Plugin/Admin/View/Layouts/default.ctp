<!DOCTYPE html>
<html lang="fr">
<head>
    <?php echo $this->Html->charset(); ?>
    <title><?php echo $title_for_layout;?></title>

    <!--[if lt Ie 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="icon" type="image/png" href="/favicon.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <script language="javascript">
        <?php echo "var site_url = 'http://".$_SERVER['HTTP_HOST'].$this->webroot."';"?>
        <?php echo "var controller = '".strtolower($this->name)."';"?>        
        <?php echo "var imgLoading = '".$this->Html->image('/img/loading.gif', array('alt' => 'loading', 'title' => 'Loading...', 'class' => 'loading'))."';"?>
    </script>

    <?php
    echo $this->Html->meta('title', $title_for_layout); 

    $staticVersion = '?v='.Configure::read('mushraider.version');
    $this->Html->css('bootstrap.min.2.3.2', null, array('inline' => false));
    $this->Html->css('bootstrap-responsive.min.2.3.2', null, array('inline' => false));    
    $this->Html->css('font-awesome.min', null, array('inline' => false));
    $this->Html->css('jquery-ui-1.10.3.custom.min', null, array('inline' => false));
    $this->Html->css('Admin.spectrum', null, array('inline' => false));
    $this->Html->css('Admin.jquery.dataTables', null, array('inline' => false));
    $this->Html->css('Admin.styles.css'.$staticVersion, null, array('inline' => false));

    $this->Html->script('jquery-2.1.0.min', array('inline' => false));
    $this->Html->script('jquery-ui-1.10.3.custom.min', array('inline' => false));
    $this->Html->script('bootstrap.min', array('inline' => false));
    $this->Html->script('Admin.spectrum', array('inline' => false));
    $this->Html->script('Admin.jquery.dataTables.min', array('inline' => false));
    $this->Html->script('Admin.scripts.js'.$staticVersion, array('inline' => false));

    echo $this->fetch('meta');
    echo $this->fetch('css');       
    ?>
</head>
<body>
    <div id="wrap">
        <?php echo $this->element('Admin.header'); ?>
        <?php echo $this->element('Admin.menu'); ?>

        <div id="content" class="c_<?php echo strtolower($this->name);?>">
            <div class="container-fluid outer">
                <div class="row-fluid">
                    <div class="span12 inner">
                        <?php echo $this->fetch('content'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php echo $this->element('Admin.footer'); ?>

    <?php echo $this->fetch('script');?>
    <?php echo $this->fetch('scriptBottom');?>

    <?php echo $this->element('sql_dump'); ?>
</body>
</html>

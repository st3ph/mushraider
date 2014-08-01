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
    <meta name="robots" content="noindex, nofollow" />

    <script language="javascript">
        <?php echo "var site_url = 'http://".$_SERVER['HTTP_HOST'].$this->webroot."';"?>
        <?php echo "var controller = '".strtolower($this->name)."';"?>
        <?php echo "var imgLoading = '".$this->Html->image('/img/loading.gif', array('alt' => 'loading', 'title' => 'Loading...', 'class' => 'loading'))."';"?>
    </script>

    <?php
    $staticVersion = '?v='.Configure::read('mushraider.version');
    $this->Html->css('bootstrap.min.2.3.2', null, array('inline' => false));
    $this->Html->css('bootstrap-responsive.min.2.3.2', null, array('inline' => false));
    $this->Html->css('jquery-ui-1.10.3.custom.min', null, array('inline' => false, 'media' => 'screen'));
    $this->Html->css('font-awesome.min', null, array('inline' => false));
    $this->Html->css('jquery.cleditor', null, array('inline' => false));        
    $this->Html->css('styles.css'.$staticVersion, null, array('inline' => false));
    
    $this->Html->script('jquery-2.1.0.min', array('inline' => false));
    $this->Html->script('jquery-ui-1.10.3.custom.min', array('inline' => false));
    $this->Html->script('bootstrap.min', array('inline' => false));
    $this->Html->script('jquery.cleditor.min', array('inline' => false));
    $this->Html->script('imagelightbox.min', array('inline' => false));
    $this->Html->script('scripts.js'.$staticVersion, array('inline' => false));

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->element('theme', array($mushraiderTheme));
    ?>
</head>
<body>
    <div id="container">
        <div class="container">
            <?php echo $this->element('header');?>
            <div id="content">

                <?php echo $this->Session->flash(); ?>

                <div class="well wellcontent">
                    <?php echo $this->element('breadcrumb');?>
                    <?php echo $this->fetch('content'); ?>
                </div>
            </div>
            <?php echo $this->element('footer');?>
        </div>
    </div>
    <?php echo $this->fetch('script');?>
    <?php echo $this->fetch('scriptBottom');?>
    <?php echo $this->element('sql_dump'); ?>
</body>
</html>

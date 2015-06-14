<?php if(!empty($updateAvailable)):?>
    <?php echo $this->element('flash_update', array('message' => $updateAvailable));?>
<?php endif;?>
<div id="top">
	<div class="navbar navbar-inverse navbar-static-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="fa fa-list"></span>
                </a>
                <?php echo $this->Html->link($this->Html->image('/img/logo.png', array('width' => 250)).' Admin', '/admin', array('class' => 'brand', 'escape' => false));?>
                <div class="btn-toolbar topnav">
                    <div class="btn-group">
                        <?php echo $this->Html->link('<i class="fa fa-home "></i>', '/', array('class' => 'btn btn-inverse', 'escape' => false));?>
                        <?php echo $this->Html->link('<i class="fa fa-power-off "></i>', '/auth/logout', array('class' => 'btn btn-inverse', 'escape' => false));?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    body {
        background-color:<?php echo $mushraiderTheme->bgcolor;?>;
        <?php if(!empty($mushraiderTheme->bgimage)):?>
            background-image:url(<?php echo $mushraiderTheme->bgimage;?>);
        <?php else:?>
            background-image:none;
        <?php endif;?>
        background-repeat:<?php echo $mushraiderTheme->bgrepeat;?>;
        background-size: cover;
    }
    <?php echo $mushraiderTheme->css;?>
</style>
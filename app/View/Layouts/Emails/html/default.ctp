<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <title><?php echo $title_for_layout; ?></title>
</head>
<body>
    <table width="580" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="44" valign="bottom" align="left">
                <img src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/img/logo.jpg';?>" width="253" height="44" alt="MushRaider" />
            </td>
        </tr>
        <tr>
            <td align="center">
                <?php echo $this->fetch('content'); ?>
            </td>
        </tr>
    </table>
    <p><?php echo __('This email has been sent automatically by MushRaider');?></p>
</body>
</html>
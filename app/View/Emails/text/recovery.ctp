<?php echo __('Password recovery');?>

<?php echo __('You receive this email because you have lost your password. If you don\'t, please ignore this email');?>.
<?php echo __('To change your password and recover your account follow the following link');?> :
<?php echo 'http://'.$_SERVER['HTTP_HOST'].$this->webroot.'auth/password/'.$token;?>
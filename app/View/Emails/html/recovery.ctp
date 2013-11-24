<table width="550" cellspacing="0" cellpadding="0">
    <tr align="left" valign="top">
        <td width="550" valign="top" align="left">
            <table cellspacing="0" cellpadding="4" bgcolor="#313131" style="color:#ffffff;">
                <tr>
                    <td>
                        <h2><?php echo __('Password recovery');?></h2>
                    </td>
                </tr>
            </table>

            <p><?php echo __('You receive this email because you have lost your password. If you don\'t, please ignore this email');?>.</p>
            <p><?php echo __('To change your password and recover your account follow the following link');?> :</p>
            <p><?php echo $this->Html->link('http://'.$_SERVER['HTTP_HOST'].$this->webroot.'auth/password/'.$token, 'http://'.$_SERVER['HTTP_HOST'].$this->webroot.'auth/password/'.$token, array('escape' => false));?></p>
            
            <br />
            <br />
        </td>
    </tr>
</table>
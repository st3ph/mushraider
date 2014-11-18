<table width="550" cellspacing="0" cellpadding="0">
    <tr align="left" valign="top">
        <td width="550" valign="top" align="left">
            <table cellspacing="0" cellpadding="4" bgcolor="#313131" style="color:#ffffff;">
                <tr>
                    <td>
                        <h2><?php echo __('New signup');?></h2>
                    </td>
                </tr>
            </table>

            <p><?php echo __('You receive this email because someone signup to your MushRaider');?> : <b><?php echo $username;?></b></p>
            <p><?php echo __('To activate his account use the following link : ');?> :</p>
            <p><?php echo $this->Html->link('http://'.$_SERVER['HTTP_HOST'].$this->webroot.'admin/users/enable/'.$userId, 'http://'.$_SERVER['HTTP_HOST'].$this->webroot.'admin/users/enable/'.$userId, array('escape' => false));?></p>
            
            <br />
            <br />
        </td>
    </tr>
</table>
<table width="550" cellspacing="0" cellpadding="0">
    <tr align="left" valign="top">
        <td width="550" valign="top" align="left">
            <table cellspacing="0" cellpadding="4" bgcolor="#313131" style="color:#ffffff;">
                <tr>
                    <td>
                        <h2><?php echo __('Yeah!');?></h2>
                    </td>
                </tr>
            </table>

            <p><?php echo __('Hello');?> <?php echo $username;?>,</p>
            <p><?php echo __('You receive this email because your MushRaider account has been activated');?></p>
            <p><?php echo __('To start raiding you can now create your first character using the following link : ');?> :</p>
            <p><?php echo $this->Html->link('http://'.$_SERVER['HTTP_HOST'].$this->webroot.'account/characters', 'http://'.$_SERVER['HTTP_HOST'].$this->webroot.'account/characters', array('escape' => false));?></p>
            
            <br />
            <br />
        </td>
    </tr>
</table>
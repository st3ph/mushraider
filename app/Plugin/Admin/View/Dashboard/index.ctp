<div class="tac">
    <h3><?php echo __('Welcome to the MushRaider admin panel');?></h3>
    <p>
        <?php echo __('Use the left menu to set your raid planner as you wish, like adding the games you play, the list of dungeons, classes etc...');?>
    </p>

    <hr>

    <?php $userHtml = '<i class="icon-user icon-2x"></i>';?>
    <?php $userHtml .= '<span>'.__('Users').'</span>';?>
    <?php $userHtml .= '<span class="label mabem-inverse">'.$totalUsers.'</span>';?>
    <?php if($waitingUsers > 0):?>
        <?php $userHtml .= '<span class="label label-warning mabem-inverse waiting">'.$waitingUsers.'</span>';?>
    <?php endif;?>
    <?php echo $this->Html->link($userHtml, '/admin/users', array('class' => 'quick-btn', 'escape' => false));?>

    <?php $charsHtml = '<i class="icon-shield icon-2x"></i>';?>
    <?php $charsHtml .= '<span>'.__('Roster').'</span>';?>
    <?php $charsHtml .= '<span class="label mabem-inverse">'.$totalCharacters.'</span>';?>
    <?php echo $this->Html->link($charsHtml, '/admin/rosters', array('class' => 'quick-btn', 'escape' => false));?>

    <?php $dungeonsHtml = '<i class="icon-home icon-2x"></i>';?>
    <?php $dungeonsHtml .= '<span>'.__('Dungeons').'</span>';?>
    <?php $dungeonsHtml .= '<span class="label mabem-inverse">'.$totalDungeons.'</span>';?>
    <?php echo $this->Html->link($dungeonsHtml, '/admin/dungeons', array('class' => 'quick-btn', 'escape' => false));?>

    <?php $gamesHtml = '<i class="icon-gamepad icon-2x"></i>';?>
    <?php $gamesHtml .= '<span>'.__('Games').'</span>';?>
    <?php $gamesHtml .= '<span class="label mabem-inverse">'.$totalGames.'</span>';?>
    <?php echo $this->Html->link($gamesHtml, '/admin/games', array('class' => 'quick-btn', 'escape' => false));?>

    <?php $eventsHtml = '<i class="icon-calendar icon-2x"></i>';?>
    <?php $eventsHtml .= '<span>'.__('Events').'</span>';?>
    <?php $eventsHtml .= '<span class="label mabem-inverse">'.$totalEvents.'</span>';?>
    <?php echo $this->Html->link($eventsHtml, '/events', array('class' => 'quick-btn', 'escape' => false));?>

    <div id="mushInfos">
        <p class="lead">
            MushRaider v<?php echo Configure::read('mushraider.version');?>
        </p>
        <p><?php echo __('Informations et mises à jour sur');?> <?php echo $this->Html->link('http://mushraider.com', 'http://mushraider.com', array('title' => 'MushRaider website'));?></p>
        <ul class="unstyled inline social">
            <li class="twitter">
                <a href="https://twitter.com/mushraider" class="twitter-follow-button" data-show-count="false" data-size="large"><?php echo __('Follow');?> @mushraider</a>
            </li>
            <li class="googleplus">
                <div class="g-follow" data-annotation="none" data-height="24" data-href="//plus.google.com/u/0/100559562407479145342" data-rel="publisher"></div>            
            </li>
            <li class="paypal">
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                    <div class="paypal-donations">
                        <input type="hidden" name="cmd" value="_donations">
                        <input type="hidden" name="business" value="st3phh@gmail.com">
                        <input type="hidden" name="return" value="http://mushraider.com/merci/">
                        <input type="hidden" name="item_name" value="Aider MushRaider a se developper">
                        <input type="hidden" name="amount" value="10">
                        <input type="hidden" name="rm" value="0">
                        <input type="hidden" name="currency_code" value="EUR">
                        <input type="image" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_donate_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online.">
                        <img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                    </div>
                </form>            
            </li>
        </ul>
    </div>
</div>

<!-- Twitter -->
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

<!-- G+ -->
<script type="text/javascript">
  window.___gcfg = {lang: 'fr'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
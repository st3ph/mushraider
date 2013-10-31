<div class="fuckie alert alert-block">
	<a class="close">&times;</a>
	<h4 class="alert-heading"><?php echo ___('fuckie-warning', 'Attention !');?></h4>
	<div><?php echo ___('fuckie-texte-1', 'Votre navigateur est obsolète ce qui peut gêner votre navigation sur un site moderne tel que HDgaming.tv et présente de sérieuses lacunes en terme de sécurité et de performances.');?></div>
	<div><?php echo ___('fuckie-texte-2', 'Pour une expérience optimale et sécurisée nous vous recommandons de mettre à jour votre navigateur (ou d\'en changer) via les liens suivants');?> :</div>
	<div class="row">		
		<div class="span1"><?php echo $this->Html->link($this->Html->image('/img/firefox.png', array('alt' => 'Firefox')), 'http://www.mozilla.org', array('escape' => false, 'title' => 'Firefox', 'target' => '_blank'));?></div>
		<div class="span1"><?php echo $this->Html->link($this->Html->image('/img/safari.png', array('alt' => 'Safari')), 'http://www.apple.com/fr/safari', array('escape' => false, 'title' => 'Safari', 'target' => '_blank'));?></div>
		<div class="span1"><?php echo $this->Html->link($this->Html->image('/img/chrome.png', array('alt' => 'Google Chrome')), 'https://www.google.com/chrome', array('escape' => false, 'title' => 'Google Chrome', 'target' => '_blank'));?></div>
		<div class="span1"><?php echo $this->Html->link($this->Html->image('/img/opera.png', array('alt' => 'Opera')), 'http://www.opera.com', array('escape' => false, 'title' => 'Opera', 'target' => '_blank'));?></div>
		<div class="span1"><?php echo $this->Html->link($this->Html->image('/img/ie.png', array('alt' => 'Internet Explorer')), 'http://windows.microsoft.com/fr-FR/internet-explorer/products/ie/home', array('escape' => false, 'title' => 'Internet Explorer', 'target' => '_blank'));?></div>
	</div>
</div>
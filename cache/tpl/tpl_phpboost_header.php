<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo isset($this->_var['L_XML_LANGUAGE']) ? $this->_var['L_XML_LANGUAGE'] : ''; ?>" >
	<head>
		<title><?php echo isset($this->_var['SITE_NAME']) ? $this->_var['SITE_NAME'] : ''; ?> :: <?php echo isset($this->_var['TITLE']) ? $this->_var['TITLE'] : ''; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="description" content="<?php echo isset($this->_var['SITE_DESCRIPTION']) ? $this->_var['SITE_DESCRIPTION'] : ''; ?>" />
		<meta name="keywords" content="<?php echo isset($this->_var['SITE_KEYWORD']) ? $this->_var['SITE_KEYWORD'] : ''; ?>" />
		<meta http-equiv="Content-Language" content="<?php echo isset($this->_var['L_XML_LANGUAGE']) ? $this->_var['L_XML_LANGUAGE'] : ''; ?>" />
		<meta name="Robots" content="index, follow, all" />
		<meta name="classification" content="tout public" />
		<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/generic.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/content.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/bbcode.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/links/links.css" type="text/css" media="screen, print, handheld" />
		<?php echo isset($this->_var['ALTERNATIVE_CSS']) ? $this->_var['ALTERNATIVE_CSS'] : ''; ?>
		<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
		<link rel="alternate" type="application/rss+xml" href="../index/rss.php" title="RSS <?php echo isset($this->_var['SITE_NAME']) ? $this->_var['SITE_NAME'] : ''; ?>" />
		<link rel="alternate" type="application/rss+xml" href="../forum/rss.php" title="RSS Forum" />
		<script type="text/javascript" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/global.js"></script>
	</head>
	<body>

	<div id="header">
		<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/header_boost.jpg" alt="PHPBoost" />
	</div>	
	
	<div id="sub_header">						
		<div id="sub_header_left">
			<h1 style="display:none;font-size:9px;"><?php echo isset($this->_var['SITE_NAME']) ? $this->_var['SITE_NAME'] : ''; ?></h1>
			<a href="http://www.phpboost.com" title="Accueil PHPBoost" class="button">Accueil</a>
			<a href="http://www.phpboost.com/forum/index.php" title="Forum PHPBoost" class="button">Forum</a>
			<a href="http://www.phpboost.com/wiki/wiki.php" title="Documentation PHPBoost" class="button">Documentation</a>
			<a href="http://www.phpboost.com/download/download-2-52+phpboost-2-0.php" title="Télécharger PHPBoost" class="button">Télécharger</a>
			<a href="http://themes.phpboost.com" title="Thèmes PHPBoost" class="button">Thèmes</a>
			<a href="http://www.phpboost.com/phpboost/modules.php" title="Modules PHPBoost" class="button">Modules</a>
			<a href="http://demo.phpboost.com" title="Démonstration PHPBoost" class="button">Démo</a>
		</div>
		<div id="sub_header_right"></div>
	</div>
	
	<?php if( isset($this->_var['C_COMPTEUR']) && $this->_var['C_COMPTEUR'] ) { ?>
	<div id="compteur">					
		<span class="text_strong"><?php echo isset($this->_var['L_VISIT']) ? $this->_var['L_VISIT'] : ''; ?>:</span> <?php echo isset($this->_var['COMPTEUR_TOTAL']) ? $this->_var['COMPTEUR_TOTAL'] : ''; ?>
		<br />
		<span class="text_strong"><?php echo isset($this->_var['L_TODAY']) ? $this->_var['L_TODAY'] : ''; ?>:</span> <?php echo isset($this->_var['COMPTEUR_DAY']) ? $this->_var['COMPTEUR_DAY'] : ''; ?>
	</div>
	<?php } ?>
	
	<?php if( isset($this->_var['C_ALERT_MAINTAIN']) && $this->_var['C_ALERT_MAINTAIN'] ) { ?>
	<div style="position:absolute;top:5px;width:99%;">					
		<div style="position:relative;width:400px;margin:auto;" class="warning">		
			<?php echo isset($this->_var['L_MAINTAIN_DELAY']) ? $this->_var['L_MAINTAIN_DELAY'] : ''; ?>
			<br /><br />	
			<script type="text/javascript">
				document.write('<div id="release"><?php echo isset($this->_var['L_LOADING']) ? $this->_var['L_LOADING'] : ''; ?>...</div>');
			</script>
			<noscript>				
				<strong><?php echo isset($this->_var['DELAY']) ? $this->_var['DELAY'] : ''; ?></strong>
			</noscript>
		</div>
	</div>
	<script type="text/javascript">
	<!--		
	function release(year, month, day, hour, minute, second)
	{
		if( document.getElementById('release') )
		{
			var sp_day = 86400;
			var sp_hour = 3600;
			var sp_minute = 60;
			
			now = new Date();
			end = new Date(year, month, day, hour, minute, second);
			
			release_time = (end.getTime() - now.getTime())/1000;
			if( release_time <= 0 )
			{
				document.location.reload();					
				release_time = '0';					
			}
			else
				timeout = setTimeout('release('+year+', '+month+', '+day+', '+hour+', '+minute+', '+second+')', 1000);
			
			release_days = Math.floor(release_time/sp_day);
			release_time -= (release_days * sp_day);
			
			release_hours = Math.floor(release_time/sp_hour);
			release_time -= (release_hours * sp_hour);

			release_minutes = Math.floor(release_time/sp_minute);
			release_time -= (release_minutes * sp_minute);

			release_seconds = Math.floor(release_time);
	
			document.getElementById('release').innerHTML = '<strong>' + release_days + '</strong> <?php echo isset($this->_var['L_DAYS']) ? $this->_var['L_DAYS'] : ''; ?> <strong>' + release_hours + '</strong> <?php echo isset($this->_var['L_HOURS']) ? $this->_var['L_HOURS'] : ''; ?> <strong>' + release_minutes + '</strong> <?php echo isset($this->_var['L_MIN']) ? $this->_var['L_MIN'] : ''; ?> <strong>' + release_seconds + '</strong> <?php echo isset($this->_var['L_SEC']) ? $this->_var['L_SEC'] : ''; ?>';
		}
	}
	release(<?php echo isset($this->_var['L_RELEASE_FORMAT']) ? $this->_var['L_RELEASE_FORMAT'] : ''; ?>);
	-->
	</script>
	<?php } ?>
	
	<?php if( isset($this->_var['C_START_LEFT']) && $this->_var['C_START_LEFT'] ) { ?>
	<div id="left_menu">
	<?php } ?>
	
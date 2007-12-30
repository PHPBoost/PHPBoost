<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo isset($this->_var['L_XML_LANGUAGE']) ? $this->_var['L_XML_LANGUAGE'] : ''; ?>" >
	<head>
		<title><?php echo isset($this->_var['SITE_NAME']) ? $this->_var['SITE_NAME'] : ''; ?> :: <?php echo isset($this->_var['TITLE']) ? $this->_var['TITLE'] : ''; ?></title>
		<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/generic.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/content.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/bbcode.css" type="text/css" media="screen, print, handheld" />
		<link rel="shortcut" href="../favicon.ico" />
		
		<style type="text/css">
			#content{
				height:750px;
			}
			html>body #content {
				height:auto;
				min-height:750px;
			}
			#maintain{
				text-align:center;
				padding:10px;
			}
			.delay{
				width:280px;				
				border:1px solid black;
				padding:15px;
				margin:auto;			
			}
		</style>					
	</head>
	<body>		
		<div id="content">
			<br />
			<hr />
			<div id="maintain">					
				<?php echo isset($this->_var['L_MAINTAIN']) ? $this->_var['L_MAINTAIN'] : ''; ?>
				
				<?php if( isset($this->_var['C_DISPLAY_DELAY']) && $this->_var['C_DISPLAY_DELAY'] ) { ?>
				<br /><br /><br /><br />				
				<div class="delay">
					<?php echo isset($this->_var['L_MAINTAIN_DELAY']) ? $this->_var['L_MAINTAIN_DELAY'] : ''; ?>
					<br /><br />	
					<script type="text/javascript">
						document.write('<div id="release"><?php echo isset($this->_var['L_LOADING']) ? $this->_var['L_LOADING'] : ''; ?>...</div>');
					</script>
					<noscript>				
						<strong><?php echo isset($_tmpb_delay['DELAY']) ? $_tmpb_delay['DELAY'] : ''; ?></strong>
					</noscript>
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
			</div>	
			<hr />
			
			<br /><br />			
			<p style="text-align:center;"><?php echo isset($this->_var['U_INDEX']) ? $this->_var['U_INDEX'] : ''; ?></p>	
		</div>	
		<div id="footer" style="position:relative;bottom:0px">
			<span class="footer">
				<!-- This mention must figured on the website ! -->
				<!-- Cette mention dois figurer sur le site ! -->
				<?php echo isset($this->_var['L_POWERED_BY']) ? $this->_var['L_POWERED_BY'] : ''; ?> <a href="http://www.phpboost.com" title="PHPBoost">PHPBoost <?php echo isset($this->_var['VERSION']) ? $this->_var['VERSION'] : ''; ?></a> &copy; <a href="http://www.phpboost.com" title="PHPBoost">PHPBoost</a>
				<?php echo isset($this->_var['L_PHPBOOST_RIGHT']) ? $this->_var['L_PHPBOOST_RIGHT'] : ''; ?>
				<br /><br />
			</span>		
				<a href="http://www.phpboost.com" title="PHPBoost"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/power_phpboost.jpg" alt="PHPBoost" title="PHPBoost" /></a>
				<a href="http://validator.w3.org/check?uri=referer" title="Xhtml 1.0 Strict"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/xhtml.jpg" alt="Xhtml 1.0 Strict" title="Xhtml 1.0 Strict" /></a>
				<a href="http://jigsaw.w3.org/css-validator/validator?uri=<?php echo isset($this->_var['HOST']) ? $this->_var['HOST'] : '';  echo isset($this->_var['DIR']) ? $this->_var['DIR'] : ''; ?>/templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>.css" title="Css3"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/css3.jpg" alt="Css3" title="Css3" /></a>
		</div>	
	</body>
</html>
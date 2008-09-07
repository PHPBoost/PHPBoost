	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{L_XML_LANGUAGE}" >
	<head>
		<title>{SITE_NAME} : {TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="description" content="{SITE_DESCRIPTION} {TITLE}" />
		<meta name="keywords" content="{SITE_KEYWORD}" />
		<meta http-equiv="Content-Language" content="{L_XML_LANGUAGE}" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/global.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/generic.css" type="text/css" media="screen, print, handheld" />
		<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/content.css" type="text/css" media="screen, print, handheld" />
        <link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/bbcode.css" type="text/css" media="screen, print, handheld" />
        <link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/framework/content/syndication/syndication.css" type="text/css" media="screen, print, handheld" />
		{ALTERNATIVE_CSS}
		<link rel="shortcut icon" href="{PATH_TO_ROOT}/favicon.ico" type="image/x-icon" />
		<link rel="alternate" href="{PATH_TO_ROOT}/news/syndication.php" type="application/rss+xml" title="RSS {SITE_NAME}" />
		
		<script type="text/javascript">
		<!--
			var PATH_TO_ROOT = "{PATH_TO_ROOT}";
		-->
		</script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/scriptaculous/prototype.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/scriptaculous/scriptaculous.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/framework/js/global.js"></script>
        <script type="text/javascript" src="{PATH_TO_ROOT}/templates/{THEME}/images/js/jquery.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/templates/{THEME}/images/js/interface.js"></script>
	</head>
	<body>

# IF C_ALERT_MAINTAIN #
<div style="position:absolute;top:5px;width:99%;">					
	<div style="position:relative;width:400px;margin:auto;" class="warning">		
		{L_MAINTAIN_DELAY}
		<br /><br />	
		<script type="text/javascript">
			document.write('<div id="release">{L_LOADING}{PATH_TO_ROOT}.</div>');
		</script>
		<noscript>				
			<strong>{DELAY}</strong>
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
		release_seconds = (release_seconds < 10) ? '0' + release_seconds : release_seconds;
		
		document.getElementById('release').innerHTML = '<strong>' + release_days + '</strong> {L_DAYS} <strong>' + release_hours + '</strong> {L_HOURS} <strong>' + release_minutes + '</strong> {L_MIN} <strong>' + release_seconds + '</strong> {L_SEC}';
	}
}
release({L_RELEASE_FORMAT});
-->
</script>
# ENDIF #

<span id="scroll_top_page"></span>
    <div id="wrapper">
	<div id="header">
	<div class="dock" id="dock">
  <div class="dock-container">
  <a class="dock-item" href="{PATH_TO_ROOT}/forum/index.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/f1.png" alt="Forum" /><span>Forum</span></a> 
  <a class="dock-item" href="{PATH_TO_ROOT}/contact/contact.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/c1.png" alt="contact" /><span>Contact</span></a> 
  <a class="dock-item" href="{PATH_TO_ROOT}/articles/articles.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/a1.png" alt="article" /><span>Article</span></a> 
  <a class="dock-item" href="{PATH_TO_ROOT}/download/download.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/t1.png" alt="telechargement," /><span>Telechargement</span></a> 

</div>
</div>
<script type="text/javascript">
	
	$(document).ready(
		function()
		{
			$('#dock').Fisheye(
				{
				maxWidth: 70,
					items: 'a',
					itemsText: 'span',
					container: '.dock-container',
					itemWidth: 60,
					proximity: 90,
					halign : 'center'
				}
			)
		}
	);

</script>

	</div>
    <div id="sub_header">						
		<div id="sub_header_left">
			<a href="{PATH_TO_ROOT}/news/news.php" title="Accueil du site" class="button">Accueil</a><a href="{PATH_TO_ROOT}/forum/index.php" title="Forum" class="button">Forum</a><a href="{PATH_TO_ROOT}/articles/articles.php" title="Articles" class="button">Articles</a><a href="{PATH_TO_ROOT}/gallery/gallery.php" title="Galerie" class="button">Galerie</a><a href="{PATH_TO_ROOT}/download/download.php" title="Téléchargements" class="button" style="border-right:none;">Téléchargements</a>
		</div>
		{MODULES_MINI_SUB_HEADER_CONTENT}
	</div>

    	<div id="conteneur">
		<div id="global">
	
	# IF C_COMPTEUR #
	<div id="compteur">					
		<span class="text_strong">{L_VISIT}:</span> {COMPTEUR_TOTAL}
		<br />
		<span class="text_strong">{L_TODAY}:</span> {COMPTEUR_DAY}
	</div>
	# ENDIF #
	
	# IF C_START_LEFT #
	<div id="left_menu">
		{MODULES_MINI_LEFT_CONTENT}
	</div>
	# ENDIF #
	
	# IF C_START_RIGHT #
	<div id="right_menu">
            <script type="text/javascript">
        <!--
        function check_search_mini_form_post()
        {
            var textSearched = document.getElementById('TxTMiniSearched').value;
            if ( (textSearched.length > 3) && (textSearched != escape('{L_SEARCH}...')) )
            {
                textSearched = escape_xmlhttprequest(textSearched);
                return true;
            }
            else
            {
                alert('{WARNING_LENGTH_STRING_SEARCH}');
                return false;
            }
        }
        -->
        </script>
        <div id="search">
			<form action="{U_FORM_VALID}" onsubmit="return check_search_mini_form_post();" method="post">
				<input type="text" size="14" id="TxTMiniSearched" name="search" value="{TEXT_SEARCHED}" class="text" style="background:#FFFFFF url({PATH_TO_ROOT}/templates/{THEME}/images/search.png) no-repeat;background-position:2px 1px;padding-left:22px;height:14px" onclick="if(this.value=='{L_SEARCH}...')this.value='';" onblur="if(this.value=='')this.value='{L_SEARCH}...';" />
				<input type="hidden" name="search_submit" id="search_submit" value="{SEARCH}" class="submit" />
				<input type="image" name="search_submit" style="margin-left:-4px;padding:0;border:none;background:none;" value="1" src="{PATH_TO_ROOT}/templates/{THEME}/modules/search/images/search_submit.png" />
	        </form>
		</div>
		
		{MODULES_MINI_RIGHT_CONTENT}
	</div>
	# ENDIF #
	
	<div id="main">
		<div id="links">
			&nbsp;&nbsp;<a class="small_link" href="{START_PAGE}" title="{L_INDEX}">{L_INDEX}</a>
			# START link_bread_crumb #
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/breadcrumb.png" alt="" class="valign_middle" /> <a class="small_link" href="{link_bread_crumb.URL}" title="{link_bread_crumb.TITLE}">{link_bread_crumb.TITLE}</a>
			# END link_bread_crumb #
		</div>	
		<div id="top_contents">
			{MODULES_MINI_TOPCENTRAL_CONTENT}
		</div>

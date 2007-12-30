	</div>
	<!--
	<div style="margin:auto;width:468px;margin-top:20px;margin-bottom:20px;">
		<script type="text/javascript">
		google_ad_client = "pub-9943645616388527";
		google_ad_slot = "5657830319";
		google_ad_width = 468;
		google_ad_height = 60;
		</script>
		<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	</div> 
	-->
	<div id="footer">
		<span>
			<!-- This mention must figured on the website ! -->
			<!-- Cette mention dois figurer sur le site ! -->
			{L_POWERED_BY} <a style="font-size:10px" href="http://www.phpboost.com" title="PHPBoost">PHPBoost 2</a> {L_PHPBOOST_RIGHT}
		</span>	
		# IF C_DISPLAY_BENCH #
		<br />
		<span>
			{L_ACHIEVED} {BENCH}{L_UNIT_SECOND} - {REQ} {L_REQ}
		</span>	
		# ENDIF #
		# IF C_DISPLAY_AUTHOR_THEME #
		<br />
		<span>
			{L_THEME} {L_THEME_NAME} {L_BY} <a href="{U_THEME_AUTHOR_LINK}" style="font-size:10px;">{L_THEME_AUTHOR}</a>
		</span>
		# ENDIF #
	</div>
	<!--<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
	<script type="text/javascript">_uacct = "UA-727662-1";urchinTracker();</script>-->
	</body>
</html>

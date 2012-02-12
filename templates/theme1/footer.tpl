		</div>
        		<div id="bottom_contents">
			{MENUS_BOTTOMCENTRAL_CONTENT}
		</div>
	</div>
	<div id="top_footer">
		{MENUS_TOP_FOOTER_CONTENT}
		<div class="spacer"></div>
	</div>
    
	<div id="footer" style="padding-right:55px;">
    		<div style=" float:left; width:300px; padding-left:60px; padding-top:20px;">
			<form action="{PATH_TO_ROOT}/newsletter/newsletter.php{SID}" method="post">
				<div class="newsletter_form">
					<span class="newsletter_title">{L_NEWSLETTER}</span> 
					<span style="float:right;">
						<input type="text" name="mail_newsletter" maxlength="50" size="16" class="text newsletter_text" value="{USER_MAIL}" />
						<input type="image" class="newsletter_img" value="1" src="{PATH_TO_ROOT}/templates/{THEME}/modules/newsletter/images/newsletter_submit.png" />
						<input type="hidden" name="subscribe" value="subscribe" />
					</span> 
				</div>
			</form>
		</div> 
		{MENUS_FOOTER_CONTENT}
		<span>
			{L_POWERED_BY} <a style="font-size:10px" href="http://www.phpboost.com/news/news.php" title="PHPBoost">PHPBoost 2.1</a> {L_PHPBOOST_RIGHT}
		</span>	
		# IF C_DISPLAY_BENCH #
		<br />
		<span>
			{L_ACHIEVED} {BENCH}{L_UNIT_SECOND} - {REQ} {L_REQ}
		</span>	
		# ENDIF #
		# IF C_DISPLAY_AUTHOR_THEME #
		<span>
			| {L_THEME} {L_THEME_NAME} {L_BY} <a href="{U_THEME_AUTHOR_LINK}" style="font-size:10px;">{L_THEME_AUTHOR}</a>
		</span>
		# ENDIF #
		</div>
	</div>
</div>
		<span id="scroll_bottom_page"></span>
	</body>
</html>

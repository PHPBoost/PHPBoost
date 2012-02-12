		</div>
        		<div id="bottom_contents">
			{MENUS_BOTTOMCENTRAL_CONTENT}
		</div>
	</div>
	<div id="top_footer">
		{MENUS_TOP_FOOTER_CONTENT}

		<div class="spacer"></div>
	</div></div>
        <div id="footer">
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
		<span id="scroll_bottom_page"></span>
	</body>
</html>

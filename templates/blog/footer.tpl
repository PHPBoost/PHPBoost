	</div>
	<div style="clear:both"></div>
</div>

<div id="fond_footer">	
	
	<div id="footer">
		# IF C_MENUS_FOOTER_CONTENT #
		{MENUS_FOOTER_CONTENT}
		# ENDIF #
		<span>
			{L_POWERED_BY} <a style="font-size:10px" href="http://www.phpboost.com" title="PHPBoost">PHPBoost {PHPBOOST_VERSION}</a> {L_PHPBOOST_RIGHT}
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
		
		<p style="margin-top:25px">
		
			# IF C_COMPTEUR #
				<div id="compteur">
					<span class="text_strong">{L_VISIT}:</span> {COMPTEUR_TOTAL}					
					<span class="text_strong">{L_TODAY}:</span> {COMPTEUR_DAY}
				</div>
			# ENDIF #
		
		</p>
	</div>
</div>

	</body>
</html>

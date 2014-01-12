			</div>
			# IF C_MENUS_BOTTOM_CENTRAL_CONTENT #
	        <div id="bottom-contents">
				{MENUS_BOTTOMCENTRAL_CONTENT}
			</div>
			# ENDIF #
		</div>
		# IF C_MENUS_TOP_FOOTER_CONTENT #
		<div id="top-footer">
			{MENUS_TOP_FOOTER_CONTENT}
			<div class="spacer"></div>
		</div>
		# ENDIF #
	</div>
	<footer id="footer">
		# IF C_MENUS_FOOTER_CONTENT #
		{MENUS_FOOTER_CONTENT}
		# ENDIF #
		<div class="spacer">
		<span>
			{L_POWERED_BY} <a href="http://phpboost.com" title="PHPBoost">PHPBoost {PHPBOOST_VERSION}</a> {L_PHPBOOST_RIGHT}
		</span>	
		# IF C_DISPLAY_BENCH #
		<span>
			&nbsp;|&nbsp;		
			{L_ACHIEVED} {BENCH}{L_UNIT_SECOND} - {REQ} {L_REQ} - {MEMORY_USED}
		</span>	
		# ENDIF #
		# IF C_DISPLAY_AUTHOR_THEME #
		<span>
			| {L_THEME} {L_THEME_NAME} {L_BY} <a href="{U_THEME_AUTHOR_LINK}">{L_THEME_AUTHOR}</a>
		</span>
		# ENDIF #
		
	</footer>
	</body>
	# INCLUDE JAVASCRIPT #
</html>

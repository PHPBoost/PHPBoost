    			<br /><br /><br />
    		</div></div>
    		<div id="footer">
    			<span>
					<!-- This mention must figured on the website ! -->
					<!-- Cette mention dois figurer sur le site ! -->
    				{L_POWERED_BY} <a style="font-size:10px" href="http://www.phpboost.com" title="PHPBoost">PHPBoost {PHPBOOST_VERSION}</a> {L_PHPBOOST_RIGHT}
    			</span>	
    			# IF C_DISPLAY_BENCH #
    			<br />
    			<span>
    				{L_ACHIEVED} {BENCH}{L_UNIT_SECOND} - {REQ} {L_REQ} - {MEMORY_USED}
    			</span>	
    			# ENDIF #
    			# IF C_DISPLAY_AUTHOR_THEME #
    			<span>
    				| {L_THEME} {L_THEME_NAME} {L_BY} <a href="{U_THEME_AUTHOR_LINK}" style="font-size:10px;">{L_THEME_AUTHOR}</a>
    			</span>
    			# ENDIF #
    		</div>
        </div>
	</body>
	<script type="text/javascript">
	<!-- 
		$$('.delete:not([data-confirmation=false]').each(function(a) {
			var message = ${escapejs(LangLoader::get_message('confirm.delete', 'errors-common'))};
			var data_message = a.readAttribute('data-message');
			if (data_message != null)
				var message = data_message;
			a.onclick = function () { return confirm(message); }
		}); 
	-->
	</script>
</html>

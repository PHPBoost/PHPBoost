    		</div></div>
    		<footer id="footer">
    			<span>
					<!-- This mention must figured on the website ! -->
					<!-- Cette mention dois figurer sur le site ! -->
    				{L_POWERED_BY} <a style="font-size:10px" href="http://www.phpboost.com" title="PHPBoost">PHPBoost {PHPBOOST_VERSION}</a> {L_PHPBOOST_RIGHT}
    			</span>	
    			# IF C_DISPLAY_BENCH #
    			<span>
    				{L_ACHIEVED} {BENCH}{L_UNIT_SECOND} - {REQ} {L_REQ} - {MEMORY_USED}
    			</span>	
    			# ENDIF #
    			# IF C_DISPLAY_AUTHOR_THEME #
    			<span>
    				| {L_THEME} {L_THEME_NAME} {L_BY} <a href="{U_THEME_AUTHOR_LINK}">{L_THEME_AUTHOR}</a>
    			</span>
    			# ENDIF #
    		</footer>
        </div>
	</body>
	<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/scriptaculous.js"></script>
	<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/global.js"></script>
	<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/lightbox/lightbox.js"></script>
	<!--[if lt IE 9]>
	<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/html5shiv/html5shiv.js"></script>
	<![endif]-->
	<script type="text/javascript">
	<!-- 
		$$('[data-confirmation]').each(function(a) {
			var data_confirmation = a.readAttribute('data-confirmation');
			
			if (data_confirmation == 'delete-element')
				var message = ${escapejs(LangLoader::get_message('confirm.delete', 'errors-common'))};
			else
				var message = data_confirmation;
	
			a.onclick = function () { return confirm(message); }
		});
	-->
	</script>
</html>

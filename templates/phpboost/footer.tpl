		</div>
		# IF C_MENUS_BOTTOM_CENTRAL_CONTENT #
        <div id="bottom_contents">
			{MENUS_BOTTOMCENTRAL_CONTENT}
		</div>
		# ENDIF #
	</div>
	# IF C_MENUS_TOP_FOOTER_CONTENT #
	<div id="top_footer">
		{MENUS_TOP_FOOTER_CONTENT}
		<div class="spacer"></div>
	</div>
	# ENDIF #
</div>
	<div id="footer">
		<div id="footer_columns_container">
			<div class="footer_columns">
				<div class="footer_columns_title"> Les parternaires </div>
				<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/nuxit.png" align="center" />
			</div>
			<div class="footer_columns phpboost">
				<div class="footer_columns_title"> Le projet PHPBoost </div>
				<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" align="center"  width="75px" /> 
				<ul>
					<li><a href="#">Fonctionnalités</a></li>
					<li><a href="#">Telecharger</a></li>
					<li><a href="#">Demonstration</a></li>
					<li><a href="#">Soutenir</a></li>
				</ul>
			</div>	
			<div class="footer_columns">
				<div class="footer_columns_title"> Participer au Projet </div>
				<ul>
					<li><a href="#">Créer un Thème</a></li>
					<li><a href="#">Créer un Module</a></li>
					<li><a href="#">API</a></li>
					<li><a href="#">La documentation</a></li>
				</ul>
			</div>	
			<div class="footer_columns">
				<div class="footer_columns_title"> Support PHPBoost </div>
				<ul>
					<li><a href="#">FAQ</a></li>
					<li><a href="#">Forum</a></li>
					<li><a href="#">news</a></li>
				</ul>
			</div>	
		</div>
		<div class="spacer"></div>
		<div id="footer_links">
			# IF C_MENUS_FOOTER_CONTENT #
			{MENUS_FOOTER_CONTENT}
			# ENDIF #
			<span>
				{L_POWERED_BY} <a style="font-size:10px" href="http://www.phpboost.com" title="PHPBoost">PHPBoost {PHPBOOST_VERSION}</a> {L_PHPBOOST_RIGHT}
			</span>	
			# IF C_DISPLAY_BENCH #
			<span>
				&nbsp;|&nbsp;		
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
	</body>
</html>

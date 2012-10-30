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
				<div class="footer_columns_title">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/partners.png" align="center"  width="25px" />
					Les parternaires
				</div>
				<div class="footer_columns_partners">
					<p style="font-size:9px;color:#dfa959;line-height:0px;margin-bottom: 0px;font-weight:bold;">Hebergement de site web</p>
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/nuxit.png" align="center" />
					<p style="font-size:9px;font-style:italic;color:#8bb9ff;">Qualité, fiabilité, Support</p>
				</div>
			</div>
			<div class="footer_columns">
				<div class="footer_columns_title"> 
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" align="center"  width="25px" />
					Le projet PHPBoost
				</div>
				<ul>
					<li><a href="#">Fonctionnalités</a></li>
					<li><a href="#">Télécharger</a></li>
					<li><a href="#">Demonstration</a></li>
					<li><a href="#">Soutenir le projet</a></li>
				</ul>
			</div>	
			<div class="footer_columns">
				<div class="footer_columns_title">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/community.png" align="center"  width="25px" />
					Participer au Projet
				</div>
				<ul>
					<li><a href="#">Créer un Thème</a></li>
					<li><a href="#">Créer un Module</a></li>
					<li><a href="#">A.P.I.</a></li>
					<li><a href="#">La documentation</a></li>
				</ul>
			</div>	
			<div class="footer_columns">
				<div class="footer_columns_title">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/support.png" align="center"  width="25px" />
					Support PHPBoost
				</div>
				<ul>
					<li><a href="#">Foire aux Questions</a></li>
					<li><a href="#">Le Forum</a></li>
					<li><a href="#">Les news</a></li>
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

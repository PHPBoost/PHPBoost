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
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/footer_partners.png" align="center" />
					Les partenaires
				</div>
				<div class="footer_columns_partners">
					<a href="http://www.nuxit.com/" style="text-decoration:none; ">
						<p style="font-size:9px;color:#dfa959;line-height:0px;margin-bottom: 0px;font-weight:bold;">Hébergement de site web</p>
						<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/nuxit.png" align="center" />
						<p style="font-size:9px;font-style:italic;color:#8bb9ff;">Qualité, fiabilité, Support</p>
					</a>
				</div>
			</div>
			<div class="footer_columns">
				<div class="footer_columns_title"> 
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/footer_phpboost.png" align="center" />
					Le projet PHPBoost
				</div>
				<ul>
					<li><a href="{PATH_TO_ROOT}/pages/fonctionnalites-de-phpboost">Fonctionnalités</a></li>
					<li><a href="{PATH_TO_ROOT}/download/">Télécharger</a></li>
					<li><a href="http://demo.phpboost.com">Démonstration</a></li>
					<li><a href="{PATH_TO_ROOT}/pages/aider-phpboost">Contribuer au projet</a></li>
				</ul>
			</div>	
			<div class="footer_columns">
				<div class="footer_columns_title">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/footer_community.png" align="center" />
					Participer au Projet
				</div>
				<ul>
					<li><a href="{PATH_TO_ROOT}/wiki/creer-un-theme">Créer un Thème</a></li>
					<li><a href="{PATH_TO_ROOT}/wiki/creer-un-module">Créer un Module</a></li>
					<li><a href="{PATH_TO_ROOT}/doc/">A.P.I.</a></li>
					<li><a href="{PATH_TO_ROOT}/bugtracker/">Rapport de bugs</a></li>
					
				</ul>
			</div>	
			<div class="footer_columns footer_columns_last">
				<div class="footer_columns_title">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/footer_support.png" align="center" />
					Support PHPBoost
				</div>
				<ul>
					<li><a href="{PATH_TO_ROOT}/faq/">Foire Aux Questions</a></li>
					<li><a href="{PATH_TO_ROOT}/forum/">Forum</a></li>
					<li><a href="{PATH_TO_ROOT}/news/">News</a></li>
					<li><a href="{PATH_TO_ROOT}/wiki/">Documentation</a></li>
				</ul>
			</div>	
			<div class="spacer"></div>
		</div>
		<div style="margin:auto;width:920px;">
			<div class="footer_social footer_social_guestbook">
				<a href="{PATH_TO_ROOT}/guestbook/" title="Livre d'Or"></a>
			</div>
			<div class="footer_social footer_social_gplus">			
				<a href="https://plus.google.com/103112963627704533252" rel="publisher" title="Google +"></a>
			</div>
			<div class="footer_social footer_social_twitter">			
				<a href="http://twitter.com/PHPBoostCMS" title="Twitter"></a>
			</div>
			<div class="footer_social footer_social_facebook">			
				<a href="http://www.facebook.com/pages/PHPBoost-CMS/229132847163144" title="Facebook"></a>
			</div>
		</div>	
		<div id="footer_links">
				# IF C_MENUS_FOOTER_CONTENT #
				{MENUS_FOOTER_CONTENT}
				# ENDIF #
				<span>
					{L_POWERED_BY} <a href="http://www.phpboost.com" title="PHPBoost">PHPBoost {PHPBOOST_VERSION}</a> {L_PHPBOOST_RIGHT}
				</span>	
				# IF C_DISPLAY_BENCH #
				<span>
					&nbsp;|&nbsp;		
					{L_ACHIEVED} {BENCH}{L_UNIT_SECOND} - {REQ} {L_REQ}
				</span>	
				# ENDIF #
				# IF C_DISPLAY_AUTHOR_THEME #
				<span>
					| {L_THEME} {L_THEME_NAME} {L_BY} <a href="{U_THEME_AUTHOR_LINK}" title="PHPBoost">{L_THEME_AUTHOR}</a>
				</span>
				# ENDIF #
				<span>
					| <a href="{PATH_TO_ROOT}/contact/">Contactez-nous</a>
				</span>
		</div>
	</div>
	
	</body>
</html>
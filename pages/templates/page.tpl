		<article>					
			<header>
				<h1>{TITLE}</h1>
			</header>
			<div class="content" style="padding-bottom:85px;">
				# START redirect #
					<div class="row3" style="width:auto; float:left;">
					{redirect.REDIRECTED_FROM} {redirect.DELETE_REDIRECTION}
					</div>
				# END redirect #
				<noscript>
					<div class="row2" style="text-align:right;">
						&nbsp;
						# IF C_TOOLS_AUTH #
							<a href="{U_EDIT}">{L_EDIT}</a>
							<a href="{U_RENAME}">{L_RENAME}</a>
							<a href="{U_DELETE}">{L_DELETE}</a>
							<a href="{U_REDIRECTIONS}">{L_REDIRECTIONS}</a>
							<a href="{U_CREATE}">{L_CREATE}</a>
							<a href="{U_EXPLORER}">{L_EXPLORER}</a>
							# IF C_PRINT #
							<a href="{U_PRINT}">{L_PRINT}</a>
							# ENDIF #
							&bull;
						# ENDIF #	
						&nbsp;
						<br />
					</div>
				</noscript>

				<div style="margin-bottom:10px;">
					<menu class="dynamic_menu right group">
						<ul>
						# IF C_ACTIV_COM #
							<li>
								<a href="{U_COM}"><i class="icon-comments"></i> {L_COM}</a>
							</li>
						# ENDIF #
							<li>
								<a><i class="icon-cog"></i> {L_PAGE_OUTILS}</a>
								<ul>
									# IF C_TOOLS_AUTH #
										<li><a href="{U_EDIT}" title="{L_EDIT}" class="pbt-icon-edit">&nbsp;&nbsp;&nbsp;{L_EDIT}</a></li>
										<li><a href="{U_RENAME}" title="{L_RENAME}"><img src="{PATH_TO_ROOT}/pages/templates/images/rename.png"/>{L_RENAME}</a></li>
										<li><a href="{U_DELETE}" title="{L_DELETE}" class="pbt-icon-delete">&nbsp;&nbsp;&nbsp;{L_DELETE}</a></li>
										<li><a href="{U_REDIRECTIONS}" title="{L_REDIRECTIONS}"><img src="{PATH_TO_ROOT}/pages/templates/images/redirect.png"/>{L_REDIRECTIONS}</a></li>
										<li><a href="{U_CREATE}" title="{L_CREATE}"><img src="{PATH_TO_ROOT}/pages/templates/images/create_page.png"/>{L_CREATE}</a></li>
										<li><a href="{U_EXPLORER}" title="{L_EXPLORER}"><img src="{PATH_TO_ROOT}/pages/templates/images/explorer.png"/>{L_EXPLORER}</a></li>
										# IF C_PRINT #
										<li><a href="{U_PRINT}" title="{U_PRINT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/print_mini.png"/>{L_PRINT}</a></li>
										# ENDIF #
									# ENDIF #
								</ul>
							</li>
						</ul>
					</menu>	
				</div>			
				<div class="spacer">&nbsp;</div>
					{CONTENTS}
				<div class="spacer">&nbsp;</div>
			</div>
			<footer style="text-align:center">{COUNT_HITS}</footer>
		</article>
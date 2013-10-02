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
						# START link #
							<a href="{link.U_LINK}">{link.L_LINK}</a>
							# START separation #
								&bull;
							# END separation #
						# END link #
						&nbsp;
						<br />
					</div>
				</noscript>

				<div style="margin-bottom:10px;">
					<menu class="tools_menu right group">
						<ul>
						# IF C_ACTIV_COM #
							<li>
								<a href="{U_COM}" ><i class="icon-comments"></i> {L_COM}</a>
							</li>
						# ENDIF #
							<li>
								<a><i class="icon-cog"></i> {L_PAGE_OUTILS}</a>
								<ul class="img_bg">
								# START links_list #
									<li><a href="{links_list.U_ACTION}" title="{links_list.L_ACTION}" onclick="{links_list.ONCLICK}" class="small" style="background-image:url({links_list.DM_A_CLASS});">{links_list.L_ACTION}</a></li>
								# END links_list #
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
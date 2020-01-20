		<div class="wiki-tools-container">
			<nav id="cssmenu-wikitools" class="cssmenu cssmenu-right cssmenu-actionslinks cssmenu-tools">
				<ul class="level-0 hidden">
					# IF C_ACTIV_COM #
						<li>
							<a href="{U_COM}" class="cssmenu-title">
								<i class="fa fa-fw fa-comments" aria-hidden="true"></i>
								<span>{L_COM}</span>
							</a>
						</li>
					# ENDIF #
					<li>
						<a href="{U_HISTORY}" class="cssmenu-title">
							<i class="fa fa-fw fa-reply" aria-hidden="true"></i>
							<span>{L_HISTORY}</span>
						</a>
					</li>
					# IF C_INDEX_PAGE #
						# IF IS_ADMIN #
							<li>
								<a href="{U_EDIT_INDEX}" class="cssmenu-title">
									<i class="far fa-fw fa-edit" aria-hidden="true"></i>
									<span>{L_EDIT_INDEX}</span>
								</a>
							</li>
						# ENDIF #
					# ENDIF #
					# IF NOT C_INDEX_PAGE #
						# IF C_EDIT #
						<li>
							<a href="{U_EDIT}" class="cssmenu-title">
								<i class="far fa-fw fa-edit" aria-hidden="true"></i>
								<span>{L_EDIT}</span>
							</a>
						</li>
						# ENDIF #
						# IF C_DELETE #
						<li>
							<a href="{U_DELETE}" data-confirmation="delete-element" class="cssmenu-title">
								<i class="far fa-fw fa-trash-alt" aria-hidden="true"></i>
								<span>{L_DELETE}</span>
							</a>
						</li>
						# ENDIF #
						# IF C_RENAME #
						<li>
							<a href="{U_RENAME}" class="cssmenu-title">
								<i class="fa fa-fw fa-magic" aria-hidden="true"></i>
								<span>{L_RENAME}</span>
							</a>
						</li>
						# ENDIF #
						# IF C_REDIRECT #
						<li>
							<a href="{U_REDIRECT}" class="cssmenu-title">
								<i class="fa fa-fw fa-fast-forward" aria-hidden="true"></i>
								<span>{L_REDIRECT}</span>
							</a>
						</li>
						# ENDIF #
						# IF C_MOVE #
						<li>
							<a href="{U_MOVE}" class="cssmenu-title">
								<i class="fa fa-fw fa-share" aria-hidden="true"></i>
								<span>{L_MOVE}</span>
							</a>
						</li>
						# ENDIF #
						# IF C_STATUS #
						<li>
							<a href="{U_STATUS}" class="cssmenu-title">
								<i class="fa fa-fw fa-tasks" aria-hidden="true"></i>
								<span>{L_STATUS}</span>
							</a>
						</li>
						# ENDIF #
						# IF C_RESTRICTION #
							<li>
								<a href="{U_RESTRICTION}" class="cssmenu-title">
									<i class="fa fa-fw fa-lock" aria-hidden="true"></i>
									<span>{L_RESTRICTION}</span>
								</a>
							</li>
						# ENDIF #
						# IF IS_USER_CONNECTED #
							<li>
								<a href="{U_WATCH}" class="cssmenu-title">
									<i class="fa fa-fw fa-heart" aria-hidden="true"></i>
									<span>{L_WATCH}</span>
								</a>
							</li>
						# ENDIF #
					# ENDIF #
					# IF C_INDEX_PAGE #
						<li>
							<a href="{U_RANDOM}" class="cssmenu-title">
								<i class="fa fa-fw fa-random" aria-hidden="true"></i>
								<span>{L_RANDOM}</span>
							</a>
						</li>
					# ENDIF #
					# IF NOT C_INDEX_PAGE #
						<li>
							<a href="{U_PRINT}" class="cssmenu-title">
								<i class="fa fa-fw fa-print" aria-hidden="true"></i>
								<span>{L_PRINT}</span>
							</a>
						</li>
					# ENDIF #
				</ul>
			</nav>
			<script>
				jQuery("#cssmenu-wikitools").menumaker({
					title: "{L_OTHER_TOOLS}",
					format: "multitoggle",
					breakpoint: 768
				});
				jQuery(document).ready(function() {
					jQuery("#cssmenu-wikitools ul").removeClass('hidden');
				});
			</script>
		</div>
		<div class="spacer"></div>

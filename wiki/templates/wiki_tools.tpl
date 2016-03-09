		<div class="wiki-tools-container">
			<menu id="cssmenu-wikitools" class="cssmenu cssmenu-right cssmenu-actionslinks cssmenu-tools">
				<ul class="level-0 hidden">
					# IF C_ACTIV_COM #
						<li>
							<a href="{U_COM}" class="cssmenu-title"><i class="fa fa-comments-o"></i> {L_COM}</a>
						</li>
					# ENDIF #
					<li><a href="{U_HISTORY}" title="{L_HISTORY}" class="cssmenu-title">
						<i class="fa fa-reply"></i> {L_HISTORY}
					</a></li>
					# IF C_INDEX_PAGE #
						# IF IS_ADMIN #
							<li><a href="{U_EDIT_INDEX}" title="{L_EDIT_INDEX}" class="cssmenu-title">
								<i class="fa fa-edit"></i> {L_EDIT_INDEX}
							</a></li>
						# ENDIF #
					# ENDIF #
					# IF NOT C_INDEX_PAGE #
						# IF C_EDIT #
						<li><a href="{U_EDIT}" title="{L_EDIT}" class="cssmenu-title">
							<i class="fa fa-edit"></i> {L_EDIT}
						</a></li>
						# ENDIF #
						# IF C_DELETE #
						<li><a href="{U_DELETE}" title="{L_DELETE}" data-confirmation="delete-element" class="cssmenu-title">
							<i class="fa fa-delete"></i> {L_DELETE}
						</a></li>
						# ENDIF #
						# IF C_RENAME #
						<li><a href="{U_RENAME}" title="{L_RENAME}" class="cssmenu-title">
							<i class="fa fa-magic"></i> {L_RENAME}
						</a></li>
						# ENDIF #
						# IF C_REDIRECT #
						<li><a href="{U_REDIRECT}" title="{L_REDIRECT}" class="cssmenu-title">
							<i class="fa fa-fast-forward"></i> {L_REDIRECT}
						</a></li>
						# ENDIF #
						# IF C_MOVE #
						<li><a href="{U_MOVE}" title="{L_MOVE}" class="cssmenu-title">
							<i class="fa fa-move"></i> {L_MOVE}
						</a></li>
						# ENDIF #
						# IF C_STATUS #
						<li><a href="{U_STATUS}" title="{L_STATUS}" class="cssmenu-title">
							<i class="fa fa-tasks"></i> {L_STATUS}
						</a></li>
						# ENDIF #
						# IF C_RESTRICTION #
						<li><a href="{U_RESTRICTION}" title="{L_RESTRICTION}" class="cssmenu-title">
							<i class="fa fa-lock"></i> {L_RESTRICTION}
						</a></li>
						# ENDIF #
						# IF IS_USER_CONNECTED #
							<li><a href="{U_WATCH}" title="{L_WATCH}" class="cssmenu-title">
								<i class="fa fa-heart"></i> {L_WATCH}
							</a></li>
						# ENDIF #
					# ENDIF #
					# IF C_INDEX_PAGE #
						<li><a href="{U_RANDOM}" title="{L_RANDOM}" class="cssmenu-title">
							<i class="fa fa-random"></i> {L_RANDOM}
						</a></li>
					# ENDIF #
					# IF NOT C_INDEX_PAGE #
						<li><a href="{U_PRINT}" title="{L_PRINT}" class="cssmenu-title">
							<i class="fa fa-print"></i> {L_PRINT}
						</a></li>
					# ENDIF #
				</ul>
			</menu>
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
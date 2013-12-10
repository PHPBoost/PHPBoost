		<div style="margin-bottom:10px;">
			<menu class="dynamic-menu right group">
				<ul>
				# IF C_ACTIV_COM #
					<li>
						<a href="{U_COM}" ><i class="icon-comments-o"></i> {L_COM}</a>
					</li>
				# ENDIF #
					<li>
						<a><i class="icon-cog"></i> {L_OTHER_TOOLS}</a>
						<ul>
							<li><a href="{U_HISTORY}" title="{L_HISTORY}">
								<i class="icon-reply"></i> {L_HISTORY}
							</a></li>
							# IF C_INDEX_PAGE #
								# IF IS_ADMIN #
									<li><a href="{U_EDIT_INDEX}" title="{L_EDIT_INDEX}">
										<i class="icon-edit"></i> {L_EDIT_INDEX}
									</a></li>
								# ENDIF #
							# ENDIF #
							# IF NOT C_INDEX_PAGE #
								# IF C_EDIT #
								<li><a href="{U_EDIT}" title="{L_EDIT}">
									<i class="icon-edit"></i> {L_EDIT}
								</a></li>
								# ENDIF #
								# IF C_DELETE #
								<li><a href="{U_DELETE}" title="{L_DELETE}" data-confirmation="delete-element">
									<i class="icon-delete"></i> {L_DELETE}
								</a></li>
								# ENDIF #
								# IF C_RENAME #
								<li><a href="{U_RENAME}" title="{L_RENAME}">
									<i class="icon-magic"></i> {L_RENAME}
								</a></li>
								# ENDIF #
								# IF C_REDIRECT #
								<li><a href="{U_REDIRECT}" title="{L_REDIRECT}">
									<i class="icon-fast-forward"></i> {L_REDIRECT}
								</a></li>
								# ENDIF #
								# IF C_MOVE #
								<li><a href="{U_MOVE}" title="{L_MOVE}">
									<i class="icon-move"></i> {L_MOVE}
								</a></li>
								# ENDIF #
								# IF C_STATUS #
								<li><a href="{U_STATUS}" title="{L_STATUS}">
									<i class="icon-tasks"></i> {L_STATUS}
								</a></li>
								# ENDIF #
								# IF C_RESTRICTION #
								<li><a href="{U_RESTRICTION}" title="{L_RESTRICTION}">
									<i class="icon-lock"></i> {L_RESTRICTION}
								</a></li>
								# ENDIF #
								# IF IS_USER_CONNECTED #
									<li><a href="{U_WATCH}" title="{L_WATCH}">
										<i class="icon-heart"></i> {L_WATCH}
									</a></li>
								# ENDIF #
							# ENDIF #
								<li><a href="{U_RANDOM}" title="{L_RANDOM}">
									<i class="icon-random"></i> {L_RANDOM}
								</a></li>
								<li><a href="{U_RSS}" title="{L_RSS}">
									<i class="icon-rss"></i> {L_RSS}
								</a></li>
							# IF NOT C_INDEX_PAGE #
								<li><a href="{U_PRINT}" title="{L_PRINT}">
									<i class="icon-print"></i> {L_PRINT}
								</a></li>
							# ENDIF #
						</ul>
					</li>
				</ul>
			</menu>
		</div>
		<div  class="spacer" style="margin-top:15px;">&nbsp;</div>
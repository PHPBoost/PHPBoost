<section id="module-{MODULE_ID}" class="several-items">
	<header class="section-header">
		<h1>{MODULE_NAME}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			# INCLUDE MESSAGE_HELPER #

			# INCLUDE FORM #

			# IF C_PAGINATION #
				<div class="align-center"># INCLUDE PAGINATION #</div>
			# ENDIF #
			# IF C_NO_MESSAGE #
				<div class="message-helper bgc notice message-helper-small align-center">{@items.no.element}</div>
			# ELSE #
				<form method="post" class="fieldset-content">
					# START messages #
						<article id="m{messages.ID}" class="{MODULE_ID}-item message-container message-small" itemscope="itemscope" itemtype="https://schema.org/Comment">
							<header class="message-header-container">
								# IF messages.C_AVATAR #
									<img class="message-user-avatar" src="{messages.U_AVATAR}" alt="{common.avatar}" />
								# ENDIF #
								<div class="message-header-infos">
									<div class="message-user">
										<h3 class="message-user-pseudo">
											# IF messages.C_AUTHOR_EXISTS #
												<a itemprop="author" href="{messages.U_AUTHOR_PROFILE}" class="{messages.USER_LEVEL_CLASS} offload" # IF messages.C_USER_GROUP_COLOR # style="color:{messages.USER_GROUP_COLOR}" # ENDIF #>{messages.PSEUDO}</a>
											# ELSE #
												{messages.PSEUDO}
											# ENDIF #
										</h3>
										<div class="message-actions">
											# IF messages.C_DELETE #
												<label for="multiple-checkbox-{messages.MESSAGE_NUMBER}" class="checkbox" aria-label="{@common.select.element}">
													<input type="checkbox" class="multiple-checkbox" id="multiple-checkbox-{messages.MESSAGE_NUMBER}" name="delete-checkbox-{messages.MESSAGE_NUMBER}" onclick="delete_button_display({MESSAGES_NUMBER});" />
													<span>&nbsp;</span>
												</label>
											# ENDIF #
											# IF messages.C_EDIT #
												<a class="offload" href="{messages.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
											# ENDIF #
											# IF messages.C_DELETE #
												<a href="{messages.U_DELETE}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
											# ENDIF #
										</div>
									</div>
									<div class="message-infos">
										<time datetime="{messages.DATE}">{@common.on.date} {messages.DATE}</time>
										<a href="{messages.U_ANCHOR}" aria-label="{@common.link.to.anchor}">\#{messages.ID}</a>
									</div>
								</div>
							</header>
							<div class="message-content# IF messages.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
								{messages.CONTENTS}
								# IF messages.C_ENABLED_UPDATE_DATE #
									<p class="message-edition">
										<span class="text-strong">{@common.last.update} : </span>
										<time datetime="{messages.UPDATE_DATE_ISO8601}# ENDIF #" itemprop="dateModified">
											{messages.UPDATE_DATE}
										</time>
									</p>
								# ENDIF #
							</div>
							# IF messages.C_USER_GROUPS #
								<footer class="message-footer-container# IF messages.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
									<div class="message-user-infos">
										# START messages.user_groups #
											<div class="message-group-level">
												# IF messages.user_groups.C_GROUP_PICTURE #
													<img src="{PATH_TO_ROOT}/images/group/{messages.user_groups.GROUP_PICTURE}" alt="{messages.user_groups.GROUP_NAME}" class="message-user-group" />
												# ELSE #
													{@user.group}: {messages.user_groups.GROUP_NAME}
												# ENDIF #
											</div>
										# END user_groups #
									</div>
								</footer>
							# ENDIF #
						</article>
					# END messages #
					# IF C_MULTIPLE_DELETE_DISPLAYED #
						<div class="mini-checkbox">
							<label for="delete-all-checkbox" class="checkbox" aria-label="{@common.select.all.elements}">
								<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {MESSAGES_NUMBER});">
								<span>&nbsp;</span>
							</label>
							<input type="hidden" name="token" value="{TOKEN}" />
							<button type="submit" id="delete-all-button" name="delete-selected-elements" value="true" class="button submit" data-confirmation="delete-element" disabled="disabled">{@common.delete}</button>
						</div>
					# ENDIF #
				</form>
			# ENDIF #
		</div>
	</div>
	<footer>
		# IF C_PAGINATION #
			<div class="sub-section">
				<div class="content-container">
					<div class="content align-center"># INCLUDE PAGINATION #</div>
				</div>
			</div>
		# ENDIF #
	</footer>
</section>

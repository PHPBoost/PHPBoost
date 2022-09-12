<section id="module-shoutbox" class="several-items">
	<header class="section-header">
		<h1>{@shoutbox.module.title}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			# IF C_PAGINATION #
				<div class="content align-center"># INCLUDE PAGINATION #</div>
			# ENDIF #
			# IF C_FORBIDDEN_TO_WRITE #
				# IF NOT C_WRITE #
					<div class="content"># INCLUDE MESSAGE_HELPER #</div>
				# ENDIF #
			# ENDIF #
			<div class="content tabs-container">
				<!-- <nav class="tabs-nav">
					<ul class="flex-between">
						<li><a data-tabs="" data-target="message-list">{@common.messages}</a></li>
						# IF C_WRITE #<li><a class="pinned question" data-tabs="" data-target="add-message">{@shoutbox.add.item}</a></li># ENDIF #
					</ul>
				</nav> -->
				# IF C_WRITE #
					<div id="add-message"> <!-- class="tabs tabs-animation" -->
						<div class="content-panel">
							<div id="comment-form">
								# INCLUDE FORM #
							</div>
						</div>
					</div>
				# ENDIF #
				<div id="message-list"> <!-- class="first-tab tabs tabs-animation" -->
					<div class="content-panel">
						# IF C_NO_MESSAGE #
							<div class="message-helper bgc notice message-helper-small align-center">{@common.no.item.now}</div>
						# ENDIF #
						# IF C_MULTIPLE_DELETE_DISPLAYED #<form method="post" class="fieldset-content"># ENDIF #
							# START messages #
								<article id="article-shoutbox-{messages.ID}" class="shoutbox-item message-container message-small# IF IS_USER_CONNECTED # message-offset# ENDIF #" itemscope="itemscope" itemtype="https://schema.org/Comment">
									<header class="message-header-container# IF messages.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
										# IF messages.C_AVATAR #<img src="{messages.U_AVATAR}" alt="{@common.avatar}" class="message-user-avatar" /># ENDIF #
										<div class="message-header-infos">
											<div class="message-user-container">
												<h3 class="message-user-pseudo">
													# IF messages.C_AUTHOR_EXISTS #
														<a itemprop="author" href="{messages.U_AUTHOR_PROFILE}" class="{messages.USER_LEVEL_CLASS} offload" # IF messages.C_USER_GROUP_COLOR # style="color:{messages.USER_GROUP_COLOR}" # ENDIF #>{messages.PSEUDO}</a>
													# ELSE #
														<span class="visitor">{messages.PSEUDO}</span>
													# ENDIF #
												</h3>
												<div class="controls message-user-infos-preview">
													# IF messages.C_USER_GROUPS #
														<div class="controls">
															# START messages.usergroups #
																{messages.usergroups.GROUP_NAME}
															# END usergroups #
														</div>
													# ENDIF #
												</div>
											</div>
											<div class="message-infos">
												<time datetime="{messages.DATE}" itemprop="datePublished">{@common.on.date} {messages.DATE}</time>
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
														<a href="{messages.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
													# ENDIF #
													<a href="{U_SITE}{messages.U_ANCHOR}" class="copy-link-to-clipboard" aria-label="{@common.copy.link.to.clipboard}">\#S{messages.ID}</a>
												</div>
											</div>
										</div>
									</header>
									<div class="message-content# IF messages.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
										{messages.CONTENT}
									</div>
								</article>
							# END messages #
						# IF C_MULTIPLE_DELETE_DISPLAYED #
								<div class="mini-checkbox">
									<label for="delete-all-checkbox" class="checkbox" aria-label="{@common.select.all.elements}">
										<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {MESSAGES_NUMBER});">
										<span aria-label="{@common.select.all.elements}">&nbsp;</span>
									</label>
									<input type="hidden" name="token" value="{TOKEN}" />
									<button type="submit" id="delete-all-button" name="delete-selected-elements" value="true" class="button submit" data-confirmation="delete-element" disabled="disabled">{@common.delete}</button>
								</div>
							</form>
						# ENDIF #
					</div>
				</div>
			</div>

		</div>
	</div>
	<footer># IF C_PAGINATION #<div class="sub-section"><div class="content-container"># INCLUDE PAGINATION #</div></div># ENDIF #</footer>
</section>

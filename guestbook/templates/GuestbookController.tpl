<section id="module-guestbook" class="several-items">
	<header class="section-header">
		<h1>{@guestbook.module.title}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				<div> <!-- class="tabs-container" -->
					# IF NOT C_WRITE #
						<div class="content"># INCLUDE MESSAGE_HELPER #</div>
					# ENDIF #

					# IF C_PAGINATION #
						<div class="align-center"># INCLUDE PAGINATION #</div>
					# ENDIF #
					<!-- <nav class="tabs-nav">
						<ul class="flex-between">
							<li><a href="#" data-tabs="" data-target="message-list">{@guestbook.items}</a></li>
							# IF C_WRITE #<li><a class="pinned question" href="#" data-tabs="" data-target="add-message">{@guestbook.add.item}</a></li># ENDIF #
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
							<form method="post" class="fieldset-content">
								# START messages #
									<article id="m{messages.ID}" class="guestbook-item message-container message-small" itemscope="itemscope" itemtype="https://schema.org/Comment">
										<header class="message-header-container">
											# IF messages.C_AVATAR #
												<img class="message-user-avatar" src="{messages.U_AVATAR}" alt="{@common.avatar}" />
											# ENDIF #
											<div class="message-header-infos">
												<div class="message-user-container">
													<h3 class="message-user-pseudo">
														# IF messages.C_AUTHOR_EXISTS #
															<a itemprop="author" href="{messages.U_AUTHOR_PROFILE}" class="{messages.AUTHOR_LEVEL_CLASS} offload" # IF messages.C_AUTHOR_GROUP_COLOR # style="color:{messages.AUTHOR_GROUP_COLOR}" # ENDIF #>{messages.AUTHOR_DISPLAY_NAME}</a>
														# ELSE #
															<span class="visitor">{messages.AUTHOR_DISPLAY_NAME}</span>
														# ENDIF #
													</h3>
													<div class="controls message-user-infos-preview">
														# IF messages.C_AUTHOR_GROUPS #
															# START messages.usergroups #
																{messages.usergroups.GROUP_NAME}
															# END usergroups #
														# ENDIF #
													</div>
												</div>
												<div class="message-infos">
													<time datetime="{messages.DATE}">{@common.on.date} {messages.DATE}</time>
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
														<a href="{U_SITE}{messages.U_ANCHOR}" class="copy-link-to-clipboard" aria-label="{@common.copy.link.to.clipboard}">\#{messages.ID}</a>
												<	/div>
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
											<span>&nbsp;</span>
										</label>
										<input type="hidden" name="token" value="{TOKEN}" />
										<button type="submit" id="delete-all-button" name="delete-selected-elements" value="true" class="button submit" data-confirmation="delete-element" disabled="disabled">{@common.delete}</button>
									</div>
								# ENDIF #
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer># IF C_PAGINATION #<div class="sub-section"><div class="content-container"># INCLUDE PAGINATION #</div></div># ENDIF #</footer>
</section>

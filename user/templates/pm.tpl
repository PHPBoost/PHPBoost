<script>
	function check_form_convers() {
		if(document.getElementById('login').value == "") {
			alert("{@warning.recipient}");
			return false;
		}
		if(document.getElementById('contents').value == "") {
			alert("{@warning.text}");
			return false;
		}
		if(document.getElementById('title').value == "") {
			alert("{@warning.title}");
			return false;
		}
		return true;
	}
	function check_form_pm() {
		if(document.getElementById('contents').value == "") {
			alert("{@warning.text}");
			return false;
		}
		return true;
	}
	function Confirm_pm() {
		return confirm("{@warning.delete.message}");
	}
</script>

# START convers #
	<script>
		function check_convers(status, id)
		{
			var i;
			for(i = 0; i < {convers.NBR_PM}; i++) {
				if( document.getElementById(id + i) )
					document.getElementById(id + i).checked = status;
			}
			document.getElementById('checkall').checked = status;
			document.getElementById('validc').checked = status;
		}
	</script>
	# INCLUDE MESSAGE_HELPER #

	<section id="module-user-convers">
		<header class="section-header">
			<div class="controls align-right">{@user.private.messages}: {convers.PM_POURCENT}</div>
			<h1>{@user.pm.box}</h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<form action="pm{convers.U_USER_ACTION_PM}" method="post" onsubmit="javascript:return Confirm_pm();">
						<nav id="cssmenu-pmactions" class="cssmenu cssmenu-group">
							<ul>
								<li>
									<a href="{convers.U_POST_NEW_CONVERS}" class="offload cssmenu-title"><i class="fa fa-plus" aria-hidden="true"></i> <span>{@user.post.new.conversation}</span></a>
								</li>
								<li>
									<a href="{convers.U_MARK_AS_READ}" class="offload cssmenu-title"><i class="fa fa-eraser" aria-hidden="true"></i> <span>{@user.mark.pm.as.read}</span></a>
								</li>
							</ul>
						</nav>
						<script>
							jQuery("#cssmenu-pmactions").menumaker({
								title: "{@form.options}",
								format: "multitoggle",
								breakpoint: 768
							});
						</script>
						<table class="table">
							<thead>
								<tr>
									<th>
										<span aria-label="{@common.select.elements}"><i class="far fa-square" aria-hidden="true"></i></span>
									</th>
									<th>
										<span aria-label="{@user.pm.status}"><i class="fa fa-eye" aria-hidden="true"></i></span>
									</th>
									<th>
										{@common.title}
									</th>
									<th>
										{@common.participants}
									</th>
									<th>
										{@user.messages}
									</th>
									<th>
										{@user.last.message}
									</th>
								</tr>
							</thead>
							<tbody>
								# START convers.list #
									<tr>
										<td>
											<label for="d{convers.list.INCR}" class="checkbox" aria-label="{@common.select.element}">
												<input type="checkbox" id="d{convers.list.INCR}" name="{convers.list.ID}" />
												<span>&nbsp;</span>
											</label>
										</td>
										<td class="convers-announce">
											<span><i class="fa fa-people-arrows {convers.list.ANNOUNCE}" aria-hidden="true"></i></span>
										</td>
										<td class="align-left">
											{convers.list.ANCRE} <a href="{convers.list.U_CONVERS}" class="offload">{convers.list.TITLE}</a> &nbsp;
											<span class="small">[{@common.by}
												# IF convers.list.C_AUTHOR_IS_ADMINISTRATOR #
												{@user.administrator}
												# ELSE #
													# IF convers.list.C_AUTHOR_EXIST #
														<a itemprop="author" href="{convers.list.U_AUTHOR_PROFILE}" class="{convers.list.AUTHOR_CSSCLASS} offload"# IF convers.list.C_AUTHOR_GROUP_COLOR #style="color:{convers.list.AUTHOR_GROUP_COLOR}"# ENDIF #>{convers.list.AUTHOR_NAME}</a>
													# ELSE #
														<del>{@user.guest}</del>
													# ENDIF #
												# ENDIF #
											]</span>
										</td>
										<td>
											# IF convers.list.C_PARTICIPANT_IS_ADMINISTRATOR #
												{@user.administrator}
											# ELSE #
												# IF convers.list.C_PARTICIPANT_EXIST #
													<a href="{convers.list.U_PARTICIPANT}" class="{convers.list.PARTICIPANT_CSSCLASS} offload"# IF convers.list.C_PARTICIPANT_GROUP_COLOR #style="color:{convers.list.PARTICIPANT_GROUP_COLOR}"# ENDIF #>
														# IF convers.list.C_PARTICIPANT_LEAVE #<del>{convers.list.PARTICIPANT_NAME}</del># ELSE #{convers.list.PARTICIPANT_NAME}# ENDIF #
													</a>
												# ELSE #
													<del>{@user.guest}</del>
												# ENDIF #
											# ENDIF #
										</td>
										<td>
											{convers.list.MSG}
										</td>
										<td class="small">
											<a class="offload" href="{convers.list.U_LAST_MSG}"><i class="far fa-hand-point-right"></i></a>
											{@common.on.date} {convers.list.LAST_MSG_DATE} <br />
											{@common.by}
											# IF convers.list.C_PARTICIPANT_IS_ADMINISTRATOR #
												{@user.administrator}
											# ELSE #
												<a href="{convers.list.U_LAST_USER}" class="{convers.list.LAST_USER_CSSCLASS} offload"# IF convers.list.C_LAST_USER_GROUP_COLOR #style="color:{convers.list.LAST_USER_GROUP_COLOR}"# ENDIF #>{convers.list.LAST_USER_NAME}</a>
											# ENDIF #
										</td>
									</tr>
								# END convers.list #

								# START convers.no_pm #
									<tr>
										<td colspan="6">
											<div class="message-helper bgc notice">{@common.no.item.now}</div>
										</td>
									</tr>
								# END convers.no_pm #
							</tbody>
							<tfoot>
								<tr>
									<td colspan="6">
										<div class="align-left">
											<label for="validc" class="checkbox" aria-label="{@common.select.all.elements}">
												<input type="checkbox" id="validc" onclick="check_convers(this.checked, 'd');" />
												<span>&nbsp;</span>
											</label>
											<input type="hidden" name="token" value="{TOKEN}" />
											<button type="submit" name="valid" value="true" class="button submit">{@common.delete}</button>
										</div>
										# IF convers.C_PAGINATION #<div class="float-right"># INCLUDE convers.PAGINATION #</div># ENDIF #
									</td>
								</tr>
							</tfoot>
						</table>

						<table class="table-no-header announce-legend">
							<tr>
								<td>
									<i class="fa fa-people-arrows message-announce-track" aria-hidden="true"></i> {@user.pm.track}
								</td>
								<td>
									<i class="fa fa-people-arrows message-announce-new" aria-hidden="true"></i> {@user.not.read}
								</td>
								<td>
									<i class="fa fa-people-arrows message-announce" aria-hidden="true"></i> {@user.read}
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# END convers #

# START pm #
	<section id="module-user-pm">
		<header class="section-header">
			<div class="controls align-right">{@user.private.messaging}</div>
			<h1><a class="offload" href="{pm.U_TITLE_CONVERS}">{pm.TITLE}</a></h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					# IF pm.C_PAGINATION #<div class="float-right"># INCLUDE pm.PAGINATION #</div># ENDIF #

					# START pm.msg #
						<article id="article-pm-{pm.msg.ID}" class="pm-item several-items message-container message-small message-offset" itemscope="itemscope" itemtype="https://schema.org/Comment">
							<span id="m{pm.msg.ID}"></span>
							<header class="message-header-container# IF pm.msg.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
								# IF pm.msg.C_AVATAR #<img src="{pm.msg.USER_AVATAR}" alt="{pm.msg.USER_PSEUDO}" class="message-user-avatar" /># ENDIF #
								<div class="message-header-infos">
									<div class="message-user-container">
										<h3 class="message-user-pseudo">
											# IF pm.msg.C_NOT_USER #
												<span class="{pm.msg.LEVEL_CLASS}">{pm.msg.PSEUDO}</span>
											# ELSE #
												<a href="{pm.msg.U_PROFILE}" class="offload {pm.msg.LEVEL_CLASS}" # IF pm.msg.C_GROUP_COLOR # style="color:{pm.msg.GROUP_COLOR}" # ENDIF #>
													{pm.msg.PSEUDO}
												</a>
											# ENDIF #
										</h3>
										<div class="controls message-user-infos-preview">
											# IF pm.msg.C_MODERATION_TOOLS #
												<a href="pm.php?edit={pm.msg.ID}" class="offload" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
												<a href="pm.php?del={pm.msg.ID}&amp;token={TOKEN}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
											# ENDIF #
											{pm.msg.WARNING_LEVEL}
										</div>
									</div>
									<div class="message-infos">
										<time datetime="Date" itemprop="{pm.msg.DATE_FULL}">{@common.on.date} {pm.msg.DATE_FULL}</time>
										<a href="#article-pm-{pm.msg.ID}" aria-label="{@common.link.to.anchor}">\#{pm.msg.ID}</a>
									</div>
								</div>
							</header>
							<div class="message-content# IF pm.msg.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
								{pm.msg.CONTENTS}
							</div>
						</article>
					# END pm.msg #
				</div>
			</div>
		</div>
		<footer>
			# IF pm.C_PAGINATION #<div class="float-right"># INCLUDE pm.PAGINATION #</div># ENDIF #
		</footer>
	</section>
# END pm #

# START post_pm #
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				# INCLUDE MESSAGE_HELPER #
				<span id="quote"></span>
				<form action="pm{post_pm.U_PM_ACTION_POST}" method="post" onsubmit="return check_form_msg();" class="post-pm">
					<fieldset id="pm_message">
						<legend>{@common.respond}</legend>
						<div class="fieldset-inset">
							<div class="form-element form-element-textarea">
								<div class="form-field-textarea bbcode-sidebar">
									{KERNEL_EDITOR}
									<textarea rows="15" cols="40" id="contents" name="contents">{post_pm.CONTENTS}</textarea>
								</div>
								<button type="button" class="button preview-button" name="prw" id="prw_pm" onclick="XMLHttpRequest_preview();">{@form.preview}</button>
							</div>
						</div>
					</fieldset>
					<fieldset id="pm_fbutton" class="fieldset-submit">
						<div class="fieldset-inset">
							<button type="submit" class="button submit" name="pm" value="true">{@form.submit}</button>
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
# END post_pm #


# START edit_pm #
	<section id="module-user-edit-pm">
		<header class="section-header">
			<div class="controls align-right">{@user.private.messaging}</div>
			<h1>{@common.edit}</h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<form action="pm{edit_pm.U_ACTION_EDIT}" method="post" onsubmit="return check_form_convers();" class="fieldset-content">
						<p class="align-center small text-italic">{@form.required.fields}</p>
						<fieldset>
							<legend class="sr-only">{@common.edit}</legend>
							<div class="fieldset-inset">
								# START edit_pm.title #
									<div class="form-element">
										<label for="title">* {@form.title}</label>
										<div class="form-field form-field-text"><input type="text" id="title" name="title" value="{edit_pm.title.TITLE}"></div>
									</div>
								# END edit_pm.title #
								<div class="form-element form-element-textarea">
									<label for="content">* {@user.message}</label>
									<div class="form-field form-field-textarea bbcode-sidebar">
										{KERNEL_EDITOR}
										<textarea rows="15" cols="40" id="contents" name="contents">{edit_pm.CONTENTS}</textarea>
									</div>
									<button type="button" class="button preview-button" name="prw" id="prw_pm" onclick="XMLHttpRequest_preview();">{@form.preview}</button>
								</div>
							</div>
						</fieldset>
						<fieldset class="fieldset-submit">
							<legend>{@form.submit}</legend>
							<button type="submit" class="button submit" name="{SUBMIT_NAME}" value="{@form.submit}">{@form.submit}</button>
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# END edit_pm #

# START post_convers #
	<section id="module-user-post-convers">
		<header class="section-header">
			<div class="controls align-right">{@user.private.messaging}</div>
			<h1>{@user.post.new.conversation}</h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					# INCLUDE MESSAGE_HELPER #
					<form action="pm.php" method="post" onsubmit="return check_form_convers();">
						<div class="fieldset-content">
						<p class="align-center small text-italic">{@form.required.fields}</p>
						<fieldset>
							<legend class="sr-only">{@user.post.new.conversation}</legend>
							<div class="fieldset-inset">
								# START post_convers.user_id_dest #
									<div class="form-element">
										<label for="login">* {@user.recipient}</label>
										<div class="form-field">
											<label for="" class="grouped-inputs">
												<input class="grouped-element" type="text" maxlength="25" id="login" name="login" value="{post_convers.LOGIN}">
												<button type="button" class="button" value="{@form.search}" onclick="XMLHttpRequest_search_members('', '{THEME}', 'insert_member', '{@warning.recipient}');">{@form.search}</button>
												<span id="search_img"></span>
											</label>
											<div id="xmlhttprequest-result-search" style="display: none;" class="pinned question"></div>
											# START post_convers.user_id_dest.search #
												{post_convers.user_id_dest.search.RESULT}
											# END post_convers.user_id_dest.search #
										</div>
									</div>
								# END post_convers.user_id_dest #
								<div class="form-element">
									<label for="title">* {@form.title}</label>
									<div class="form-field form-field-text"><input type="text" id="title" name="title" value="{post_convers.TITLE}"></div>
								</div>
								<div class="form-element form-element-textarea">
									<label for="contents">* {@user.message}</label>
									<div class="form-field form-field-textarea bbcode-sidebar">
										{KERNEL_EDITOR}
										<textarea rows="15" cols="40" id="contents" name="contents">{CONTENTS}</textarea>
									</div>
									<button type="button" class="button preview-button" name="prw_convers" id="prw_convers_pm" onclick="XMLHttpRequest_preview();">{@form.preview}</button>
								</div>

							</div>
						</fieldset>

						<fieldset class="fieldset-submit">
							<legend>{@form.submit}</legend>
							<button type="submit" class="button submit" name="convers" value="true">{@form.submit}</button>
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		<footer></footer>
	</section>
# END post_convers #

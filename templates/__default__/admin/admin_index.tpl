<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" aria-label="{L_QUICK_LINKS}">
		<i class="fa fa-bars" aria-hidden="true"></i> {@admin.quick.access}
	</a>
	<ul>
		<li>
			<a href="{PATH_TO_ROOT}/admin/admin_alerts.php" class="quick-link">{@admin.alerts}</a>
		</li>
		<li>
			<a href="${relative_url(AdminMembersUrlBuilder::management())}" class="quick-link">{@user.members}</a>
		</li>
		<li>
			<a href="{PATH_TO_ROOT}/admin/menus/menus.php" class="quick-link">{@menu.menus}</a>
		</li>
		<li>
			<a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}" class="quick-link">{@addon.modules}</a>
		</li>
		<li>
			<a href="${relative_url(AdminThemeUrlBuilder::list_installed_theme())}" class="quick-link">{@addon.themes}</a>
		</li>
		<li>
			<a href="${relative_url(AdminLangsUrlBuilder::list_installed_langs())}" class="quick-link">{@addon.langs}</a>
		</li>
		<li>
			<a href="{PATH_TO_ROOT}/admin/updates/updates.php" class="quick-link">{@admin.updates}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	<div id="admin-index">
		<div class="content cell-flex cell-columns-3 cell-tile">
			<div class="cell cell-2-3">
				<div class="cell-body">
					<div class="cell-content">
						<div class="index-logo hidden-small-screens" # IF C_HEADER_LOGO #style="background-image: url({HEADER_LOGO});"# ENDIF #></div>
						<div class="welcome-desc">
							<h2>{@admin.welcome.title}</h2>
							<p>{@H|admin.welcome.description}</p>
						</div>
					</div>
				</div>
			</div>
			<div class="cell">
				<div class="cell-header">
					<h3><i class="fa fa-bell" aria-hidden="true"></i> {@admin.alerts}</h3>
				</div>
				<div class="cell-body">
					<div class="cell-content">
						# IF C_UNREAD_ALERTS #
							<div class="message-helper bgc warning">{@admin.unread.alerts}</div>
							<p class="align-center"><a href="admin_alerts.php">{@admin.see.all.alerts}</a></p>
						# ELSE #
							<div class="message-helper bgc success">{@common.no.item.now}</div>
						# ENDIF #
					</div>
				</div>
			</div>
		</div>

		<div class="content quick-access hidden-small-screens">
			<h2><i class="fa fa-angle-double-right" aria-hidden="true"></i> {@admin.quick.access}</h2>
			<div class="cell-flex cell-columns-3 cell-tile">
				<div class="cell">
					<div class="cell-header">
						<h3><i class="fa fa-fw fa-cogs" aria-hidden="true"></i> {@admin.website.management}</h3>
					</div>
					<div class="cell-list">
						<ul>
							<li><a href="${relative_url(AdminConfigUrlBuilder::general_config())}">{@configuration.general}</a></li>
							<li><a href="${relative_url(AdminCacheUrlBuilder::clear_cache())}">{@admin.clear.cache}</a></li>
							# IF C_MODULE_DATABASE #
								<li><a href="{U_SAVE_DATABASE}">{@form.save}</a></li>
							# ENDIF #
						</ul>
					</div>
				</div>
				<div class="cell">
					<div class="cell-header">
						<h3><i class="fa fa-fw fa-image" aria-hidden="true"></i> {@admin.customize.website}</h3>
					</div>
					<div class="cell-list">
						<ul>
							<li><a href="${relative_url(AdminThemeUrlBuilder::add_theme())}">{@addon.themes.add}</a></li>
							<li><a href="{PATH_TO_ROOT}/admin/menus">{@menu.menus.management}</a></li>
							# IF C_MODULE_CUSTOMIZATION #
								<li><a href="{U_EDIT_CSS_FILES}">{@admin.customize.theme}</a></li>
							# ENDIF #
						</ul>
					</div>
				</div>
				<div class="cell">
					<div class="cell-header">
						<h3><i class="fa fa-fw fa-plus" aria-hidden="true"></i> {@admin.add.content}</h3>
					</div>
					<div class="cell-list">
						<ul>
							<li><a href="${relative_url(AdminModulesUrlBuilder::list_installed_modules())}">{@addon.modules.management}</a></li>
							# IF C_MODULE_ARTICLES #
								<li><a href="{U_ADD_ARTICLE}">{@admin.add.article}</a></li>
							# ENDIF #
							# IF C_MODULE_NEWS #
								<li><a href="{U_ADD_NEWS}">{@admin.add.news}</a></li>
							# ENDIF #
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="content cell-flex cell-columns-2 cell-tile">
			<div class="cell dashboard-user-online">
				<div class="cell-header">
					<h3><i class="fa fa-user" aria-hidden="true"></i> {@user.online.users}</h3>
				</div>
				<div class="cell-table">
					<table class="table">
						<thead>
							<tr>
								<th>{@user.online.users}</th>
								<th>{@user.ip.address}</th>
								<th>{@common.location}</th>
								<th>{@common.last.update}</th>
							</tr>
						</thead>
						<tbody>
							# START user #
								<tr>
									<td>
									# IF user.C_ROBOT #
										<span class="{user.LEVEL_CLASS}">{user.USER_DISPLAY_NAME}</span>
									# ELSE #
										# IF user.C_VISITOR #
											{user.USER_DISPLAY_NAME}
										# ELSE #
										<a href="{user.U_USER_PROFILE}" class="{user.USER_LEVEL_CLASS}" # IF user.C_USER_GROUP_COLOR # style="color:{user.USER_GROUP_COLOR}" # ENDIF #>{user.USER_DISPLAY_NAME}</a>
										# ENDIF #
									# ENDIF #
									</td>
									<td>{user.USER_IP}</td>
									<td># IF user.C_LOCATION #<a href="{user.U_LOCATION}">{user.WEBSITE_LOCATION}</a># ELSE #{@common.unknown}# ENDIF #</td>
									<td>{user.DATE}</td>
								</tr>
							# END user #
						</tbody>
					</table>
				</div>
			</div>

			<div class="cell dashboard-advices">
				<div class="cell-body">
					<div class="cell-content">
						# INCLUDE ADVICE #
					</div>
				</div>
			</div>

			<div class="cell dashboard-comments">
				<div class="cell-header">
					<h3><i class="fa fa-comment" aria-hidden="true"></i> {@admin.last.comments}</h3>
				</div>
				<div class="cell-list">
					<form method="post" class="fieldset-content mini-checkbox">
						<ul>
							# IF C_COMMENTS #
								# START comments_list #
									<li>
										<span class="controls# IF IS_MOBILE_DEVICE # flex-between# ENDIF #">
											<span>
												<label class="checkbox" for="multiple-checkbox-{comments_list.COMMENTS_NUMBER}" aria-label="{@common.select.element}">
													<input type="checkbox" class="multiple-checkbox" id="multiple-checkbox-{comments_list.COMMENTS_NUMBER}" name="delete-checkbox-{comments_list.COMMENTS_NUMBER}" onclick="delete_button_display({COMMENTS_NUMBER});" />
													<span>&nbsp;</span>
												</label>
												<a href="{comments_list.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
											</span>
											<a href="{comments_list.U_LINK}" aria-label="{@common.read.more}">
												<i class="far fa-fw fa-hand-point-right" aria-hidden="true"></i> {comments_list.MODULE_NAME}
											</a>
										</span>
										<span # IF IS_MOBILE_DEVICE #class="d-block flex-between"# ENDIF #>
											<span>{@common.by}
												# IF comments_list.C_VISITOR #
													# IF comments_list.C_VISITOR_EMAIL #
														<a class="visitor" href="mailto:{comments_list.VISITOR_EMAIL}"><i class="fa iboost fa-iboost-email" aria-hidden="true"></i> {comments_list.USER_DISPLAY_NAME}</a>
													# ELSE #
														<span class="visitor">{comments_list.USER_DISPLAY_NAME}</span>
													# ENDIF #
												# ELSE #
													<a href="{comments_list.U_USER_PROFILE}" class="{comments_list.USER_LEVEL_CLASS}" # IF comments_list.C_USER_GROUP_COLOR # style="color:{comments_list.USER_GROUP_COLOR}" # ENDIF #>{comments_list.USER_DISPLAY_NAME}</a>
												# ENDIF #,
											</span> <span class="pinned visitor small">{comments_list.DATE_DELAY}</span>
										</span>
										<p>{comments_list.CONTENT}</p>
									</li>
								# END comments_list #
								<li>
									<p class="align-center"><a class="d-inline-block button bgc-full link-color" href="${relative_url(UserUrlBuilder::comments())}">{@comment.see.all.comments}</a></p>
								</li>
								<li class="mini-checkbox">
									<label for="delete-all-checkbox" class="checkbox" aria-label="{@common.select.all.elements}">
										<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {COMMENTS_NUMBER});">
										<span>&nbsp;</span>
									</label>
									<input type="hidden" name="token" value="{TOKEN}" />
									<button type="submit" id="delete-all-button" name="delete-selected-comments" value="true" class="button submit" data-confirmation="delete-element" disabled="disabled">{@common.delete}</button>
								</li>
							# ELSE #
								<li>
									<p class="align-center"><em>{@common.no.item.now}</em></p>
								</li>
							# ENDIF #
						</ul>
					</form>
				</div>
			</div>

			<div class="cell dashboard-writting-pad">
				<div class="cell-header">
					<h3><i class="far fa-edit" aria-hidden="true"></i> {@admin.writing.pad}</h3>
				</div>
				<div class="cell-textarea">
					<form action="admin_index.php" class="form-content" method="post">
						<textarea id="writing_pad_content" name="writing_pad_content">{WRITING_PAD_CONTENT}</textarea>
						<fieldset class="fieldset-submit">
							<legend>{@form.submit}</legend>
							<button type="submit" class="button submit" name="writingpad" value="true">{@form.submit}</button>
							<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
							<input type="hidden" name="token" value="{TOKEN}">
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

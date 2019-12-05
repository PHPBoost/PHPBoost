		<nav id="admin-quick-menu">
			<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
				<i class="fa fa-bars" aria-hidden="true"></i> {L_PAGES_MANAGEMENT}
			</a>
			<ul>
				<li>
					<a href="${Url::to_rel('/pages')}" class="quick-link">${LangLoader::get_message('home', 'main')}</a>
				</li>
				<li>
					<a href="admin_pages.php" class="quick-link">{L_PAGES_CONGIG}</a>
				</li>
				<li>
					<a href="pages.php" class="quick-link">{L_PAGES_MANAGEMENT}</a>
				</li>
				<li>
					<a href="${relative_url(PagesUrlBuilder::documentation())}" class="quick-link">${LangLoader::get_message('module.documentation', 'admin-modules-common')}</a>
				</li>
			</ul>
		</nav>

		<div id="admin-contents">
			# INCLUDE MSG #
			<form action="admin_pages.php?token={TOKEN}" method="post" class="fieldset-content">
				<fieldset>
					<legend>{L_PAGES_CONGIG}</legend>
					<div class="fieldset-inset">
						<div class="form-element custom-checkbox">
							<label for="count_hits">
								{L_COUNT_HITS}
								<span class="field-description">({L_COUNT_HITS_EXPLAIN})</span>
							</label>
							<div class="form-field">
								<div class="form-field-checkbox">
									<label class="checkbox" for="hits-checked">
										<input id="hits-checked" type="checkbox" name="count_hits" {HITS_CHECKED} />
										<span>&nbsp;</span>
									</label>
								</div>
							</div>
						</div>
						<div class="form-element custom-checkbox">
							<label for="comments_activated">
								{L_COMMENTS_ACTIVATED}
							</label>
							<div class="form-field">
								<div class="form-field-checkbox">
									<label class="checkbox" for="com-checked">
										<input id="com-checked" type="checkbox" name="comments_activated" {COM_CHECKED} />
										<span>&nbsp;</span>
									</label>
								</div>
							</div>
						</div>
						<div class="form-element custom-checkbox">
							<label for="left_column_disabled">
								{L_HIDE_LEFT_COLUMN}
							</label>
							<div class="form-field">
								<div class="form-field-checkbox">
									<label class="checkbox" for="left-checked">
										<input id="left-checked" type="checkbox" name="left_column_disabled" {HIDE_LEFT_COLUMN_CHECKED} />
										<span>&nbsp;</span>
									</label>
								</div>
							</div>
						</div>
						<div class="form-element custom-checkbox">
							<label for="right_column_disabled">
								{L_HIDE_RIGHT_COLUMN}
							</label>
							<div class="form-field">
								<div class="form-field-checkbox">
									<label class="checkbox" for="right-checked">
										<input id="right-checked" type="checkbox" name="right_column_disabled" {HIDE_RIGHT_COLUMN_CHECKED} />
										<span>&nbsp;</span>
									</label>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>{L_AUTH}</legend>
					<div class="fieldset-inset">
						<div class="auth-setter">
							<div class="form-element form-element-auth">
								<label>{L_READ_PAGE}</label>
								<div class="form-field">
									{SELECT_READ_PAGE}
								</div>
							</div>
							<div class="form-element form-element-auth">
								<label>{L_EDIT_PAGE}</label>
								<div class="form-field">
									{SELECT_EDIT_PAGE}
								</div>
							</div>
							<div class="form-element form-element-auth">
								<label>{L_READ_COM}</label>
								<div class="form-field">
									{SELECT_READ_COM}
								</div>
							</div>
						</div>
					</div>
				</fieldset>

				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<div class="fieldset-inset">
						<button type="submit" name="update" value="true" class="button submit">{L_UPDATE}</button>
						<button type="reset" class="button reset" value="true">{L_RESET}</button>
						<input type="hidden" name="token" value="{TOKEN}">
					</div>
				</fieldset>
			</form>
		</div>

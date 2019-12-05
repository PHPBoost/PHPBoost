<nav id="admin-quick-menu">
		<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
			<i class="fa fa-bars" aria-hidden="true"></i> {L_WIKI_MANAGEMENT}
		</a>
		<ul>
			<li>
				<a href="${Url::to_rel('/wiki')}" class="quick-link">${LangLoader::get_message('home', 'main')}</a>
			</li>
			<li>
				<a href="admin_wiki.php" class="quick-link">{L_CONFIG_WIKI}</a>
			</li>
			<li>
				<a href="admin_wiki_groups.php" class="quick-link">{L_WIKI_GROUPS}</a>
			</li>
			<li>
			<a href="${relative_url(WikiUrlBuilder::documentation())}" class="quick-link">${LangLoader::get_message('module.documentation', 'admin-modules-common')}</a>
			</li>
		</ul>
</nav>

<div id="admin-contents">
	# INCLUDE MSG #
	<form action="admin_wiki_groups.php" method="post" class="fieldset-content">
		<fieldset>
			<legend>{L_WIKI_GROUPS}</legend>
			<div class="fieldset-inset">
				{EXPLAIN_WIKI_GROUPS}

				<div class="form-element half-field">
					<label>{L_READ}</label>

					<div class="form-field">
						{SELECT_READ}
					</div>
				</div>
				<div class="form-element half-field">
					<label>{L_CREATE_ARTICLE}</label>

					<div class="form-field">
						{SELECT_CREATE_ARTICLE}
					</div>
				</div>
				<div class="form-element half-field">
					<label>{L_CREATE_CAT}</label>

					<div class="form-field">
						{SELECT_CREATE_CAT}
					</div>
				</div>
				<div class="form-element half-field">
					<label>{L_RESTORE_ARCHIVE}</label>

					<div class="form-field">
						{SELECT_RESTORE_ARCHIVE}
					</div>
				</div>
				<div class="form-element half-field">
					<label>{L_DELETE_ARCHIVE}</label>

					<div class="form-field">
						{SELECT_DELETE_ARCHIVE}
					</div>
				</div>
				<div class="form-element half-field">
					<label>{L_EDIT}</label>

					<div class="form-field">
						{SELECT_EDIT}
					</div>
				</div>
				<div class="form-element half-field">
					<label>{L_DELETE}</label>

					<div class="form-field">
						{SELECT_DELETE}
					</div>
				</div>
				<div class="form-element half-field">
					<label>{L_RENAME}</label>

					<div class="form-field">
						{SELECT_RENAME}
					</div>
				</div>
				<div class="form-element half-field">
					<label>{L_REDIRECT}</label>

					<div class="form-field">
						{SELECT_REDIRECT}
					</div>
				</div>
				<div class="form-element half-field">
					<label>{L_MOVE}</label>

					<div class="form-field">
						{SELECT_MOVE}
					</div>
				</div>
				<div class="form-element half-field">
					<label>{L_STATUS}</label>

					<div class="form-field">
						{SELECT_STATUS}
					</div>
				</div>
				<div class="form-element half-field">
					<label>{L_COM}</label>

					<div class="form-field">
						{SELECT_COM}
					</div>
				</div>
				<div class="form-element half-field">
					<label>{L_RESTRICTION}</label>

					<div class="form-field">
						{SELECT_RESTRICTION}
					</div>
				</div>
			</div>
		</fieldset>

		<fieldset class="fieldset-submit">
			<legend>{L_UPDATE}</legend>
			<div class="fieldset-inset">
				<button type="submit" name="valid" value="true" class="button submit">{L_UPDATE}</button>
				<button type="reset" class="button reset" value="true">{L_RESET}</button>
				<input type="hidden" name="token" value="{TOKEN}">
			</div>
		</fieldset>
	</form>
</div>

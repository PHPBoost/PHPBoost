<nav id="admin-quick-menu">
		<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
			<i class="fa fa-bars" aria-hidden="true"></i> {@wiki.config.module.title}
		</a>
		<ul>
			<li>
				<a href="${Url::to_rel('/wiki')}" class="quick-link">{@form.home}</a>
			</li>
			<li>
				<a href="admin_wiki.php" class="quick-link">{@form.configuration}</a>
			</li>
			<li>
				<a href="admin_wiki_groups.php" class="quick-link">{@form.authorizations}</a>
			</li>
			<li>
				<a href="${relative_url(WikiUrlBuilder::documentation())}" class="quick-link">{@form.documentation}</a>
			</li>
		</ul>
</nav>

<div id="admin-contents">
	# INCLUDE MESSAGE_HELPER #
	<form action="admin_wiki_groups.php" method="post" class="fieldset-content">
		<fieldset>
			<legend>{@wiki.authorizations}</legend>
			<div class="fieldset-inset">
				{@wiki.authorizations.clue}

				<div class="form-element form-element-auth">
					<label>{@wiki.authorizations.read}</label>
					<div class="form-field">
						{SELECT_READ}
					</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@wiki.authorizations.write}</label>
					<div class="form-field">
						{SELECT_CREATE_ARTICLE}
					</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@wiki.authorizations.create.category}</label>
					<div class="form-field">
						{SELECT_CREATE_CAT}
					</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@wiki.authorizations.restore.archive}</label>
					<div class="form-field">
						{SELECT_RESTORE_ARCHIVE}
					</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@wiki.authorizations.delete.archive}</label>
					<div class="form-field">
						{SELECT_DELETE_ARCHIVE}
					</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@wiki.authorizations.edit}</label>
					<div class="form-field">
						{SELECT_EDIT}
					</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@wiki.authorizations.delete}</label>
					<div class="form-field">
						{SELECT_DELETE}
					</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@wiki.authorizations.rename}</label>
					<div class="form-field">
						{SELECT_RENAME}
					</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@wiki.authorizations.redirect}</label>
					<div class="form-field">
						{SELECT_REDIRECT}
					</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@wiki.authorizations.move}</label>
					<div class="form-field">
						{SELECT_MOVE}
					</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@wiki.authorizations.status}</label>
					<div class="form-field">
						{SELECT_STATUS}
					</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@wiki.authorizations.comment}</label>
					<div class="form-field">
						{SELECT_COM}
					</div>
				</div>
				<div class="form-element form-element-auth">
					<label>
						{@wiki.authorizations.restriction}
						<span class="field-description">{@wiki.authorizations.restriction.clue}</span>
					</label>
					<div class="form-field">
						{SELECT_RESTRICTION}
					</div>
				</div>
			</div>
		</fieldset>

		<fieldset class="fieldset-submit">
			<legend>{@form.submit}</legend>
			<div class="fieldset-inset">
				<button type="submit" name="valid" value="true" class="button submit">{@form.submit}</button>
				<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
				<input type="hidden" name="token" value="{TOKEN}">
			</div>
		</fieldset>
	</form>
</div>

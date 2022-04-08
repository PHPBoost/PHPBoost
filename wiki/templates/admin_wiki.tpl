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
	<form action="admin_wiki.php?token={TOKEN}" method="post" class="fieldset-content">
		<fieldset>
			<legend>{@wiki.config.module.title}</legend>
			<div class="fieldset-inset">
				<div class="form-element">
					<label for="wiki_name">{@wiki.config.name}</label>
					<div class="form-field form-field-text">
						<input type="text" maxlength="255" id="wiki_name" name="wiki_name" value="{WIKI_NAME}" />
					</div>
				</div>
				<div class="form-element custom-checkbox">
					<label for="sticky_menu">{@wiki.config.sticky.summary}</label>
					<div class="form-field">
						<div class="form-field-checkbox">
							<label class="checkbox" for="sticky_menu">
								<input type="checkbox" name="sticky_menu" id="sticky_menu" {STICKY_MENU_SELECTED} />
								<span>&nbsp;</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element custom-checkbox">
					<label for="hits_counter">{@wiki.config.enable.views.number}</label>
					<div class="form-field">
						<div class="form-field-checkbox">
							<label class="checkbox" for="hits_counter">
								<input type="checkbox" name="hits_counter" id="hits_counter" {HITS_SELECTED} />
								<span>&nbsp;</span>
							</label>
					</div>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>{@common.options}</legend>
			<div class="fieldset-inset">
				<div class="form-element inline-radio custom-radio">
					<label for="display_cats">{@wiki.config.display.categories}</label>
					<div class="form-field">
						<div class="form-field-radio">
							<label class="radio" for="display_cats">
								<input type="radio" {HIDE_CATEGORIES_ON_INDEX} name="display_categories_on_index" id="display_cats" value="0">
								<span>{@wiki.config.hide}</span>
							</label>
						</div>
						<div class="form-field-radio">
							<label class="radio" for="display_cats_visible">
								<input type="radio" {DISPLAY_CATEGORIES_ON_INDEX} id="display_cats_visible" name="display_categories_on_index" value="1" />
								<span>{@wiki.config.show}</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element">
					<label for="number_articles_on_index">{@wiki.config.last.items} <span class="field-description">{@wiki.config.last.items.clue}</span></label>
					<div class="form-field form-field-text">
						<input type="text" name="number_articles_on_index" id="number_articles_on_index" value="{ITEMS_NUMBER_ON_INDEX}">
					</div>
				</div>
				<div class="form-element form-element-textarea">
					<label for="contents">{@wiki.config.description}</label>
					<div id="loading-preview-contents"class="loading-preview-container" style="display: none;">
						<div class="loading-preview"><i class="fa fa-spinner fa-2x fa-spin"></i></div>
					</div>
					<div id="xmlhttprequest-preview-contents" class="xmlhttprequest-preview" style="display: none;"></div>
					<div class="form-field form-field-textarea bbcode-sidebar">
						{KERNEL_EDITOR}
						<textarea rows="10" cols="60" id="contents" name="contents">{DESCRIPTION}</textarea>
					</div>
					<button type="button" class="button preview-button" onclick="XMLHttpRequest_preview('contents');">{@form.preview}</button>
				</div>
			</div>
		</fieldset>

		<fieldset class="fieldset-submit">
			<legend>{@form.submit}</legend>
			<div class="fieldset-inset">
				<button type="submit" name="update" value="true" class="button submit">{@form.submit}</button>
				<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
				<input type="hidden" name="token" value="{TOKEN}">
			</div>
		</fieldset>
	</form>
</div>

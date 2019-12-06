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
	<form action="admin_wiki.php?token={TOKEN}" method="post" class="fieldset-content">
		<fieldset>
			<legend>{L_WHOLE_WIKI}</legend>
			<div class="fieldset-inset">
				<div class="form-element custom-checkbox">
					<label for="sticky_menu">{L_STICKY_MENU}</label>
					<div class="form-field">
						<div class="form-field-checkbox">
							<label class="checkbox" for="sticky_menu">
								<input type="checkbox" name="sticky_menu" id="sticky_menu" {STICKY_MENU_SELECTED} />
								<span>&nbsp;</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element">
					<label for="wiki_name">{L_WIKI_NAME}</label>
					<div class="form-field">
						<input type="text" maxlength="255" id="wiki_name" name="wiki_name" value="{WIKI_NAME}" />
					</div>
				</div>
				<div class="form-element custom-checkbox">
					<label for="hits_counter">{L_HITS_COUNTER}</label>
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
			<legend>{L_INDEX_WIKI}</legend>
			<div class="fieldset-inset">
				<div class="form-element inline-radio custom-radio">
					<label for="display_cats">{L_DISPLAY_CATEGORIES_ON_INDEX}</label>
					<div class="form-field">
						<div class="form-field-radio">
							<label class="radio" for="display_cats">
								<input type="radio" {HIDE_CATEGORIES_ON_INDEX} name="display_categories_on_index" id="display_cats" value="0">
								<span>{L_NOT_DISPLAY}</span>
							</label>
						</div>
						<div class="form-field-radio">
							<label class="radio" for="display_cats_visible">
								<input type="radio" {DISPLAY_CATEGORIES_ON_INDEX} id="display_cats_visible" name="display_categories_on_index" value="1" />
								<span>{L_DISPLAY}</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element">
					<label for="number_articles_on_index">{L_NUMBER_ARTICLES_ON_INDEX} <span class="field-description">{L_NUMBER_ARTICLES_ON_INDEX_EXPLAIN}</span></label>
					<div class="form-field"><label><input type="text" name="number_articles_on_index" id="number_articles_on_index" value="{NUMBER_ARTICLES_ON_INDEX}"></label></div>
				</div>
				<div class="form-element form-element-textarea">
					<label for="contents">{L_DESCRIPTION}</label>
					<div id="loading-preview-contents"class="loading-preview-container" style="display: none;">
						<div class="loading-preview"><i class="fa fa-spinner fa-2x fa-spin"></i></div>
					</div>
					<div id="xmlhttprequest-preview-contents" class="xmlhttprequest-preview" style="display: none;"></div>
					{KERNEL_EDITOR}
					<textarea rows="10" cols="60" id="contents" name="contents">{DESCRIPTION}</textarea>
					<div class="align-center"><button type="button" class="button small" onclick="XMLHttpRequest_preview('contents');">{L_PREVIEW}</button></div>
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

<script>
	function check_form_conf()
	{
		if (document.getElementById('nb_results_p').value == "") {
			alert("{@warning.integer}");
			return false;
		}
		if (document.getElementById('cache_time').value == "") {
			alert("{@warning.integer}");
			return false;
		}
		if (document.getElementById('max_use').value == "") {
			alert("{@warning.integer}");
			return false;
		}
		return true;
	}
</script>
<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars"></i> {@search.management}
	</a>
	<ul>
		<li>
			<a href="${Url::to_rel('/search')}" class="quick-link">{@form.home}</a>
		</li>
		<li>
			<a href="admin_search.php" class="quick-link">{@form.configuration}</a>
		</li>
		<li>
			<a href="admin_search.php?weighting=true" class="quick-link">{@search.config.weighting}</a>
		</li>
		<li>
			<a href="${relative_url(SearchUrlBuilder::documentation())}" class="quick-link">{@form.documentation}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	# INCLUDE MESSAGE_HELPER #
	# IF NOT C_WEIGHTING #
		<form action="admin_search.php" method="post" onsubmit="return check_form_conf();" class="fieldset-content">
			<p class="align-center small text-italic">{@form.required.fields}</p>
			<fieldset>
				<legend>{@search.config.module.title}</legend>
				<div class="fieldset-inset">
					<div class="form-element top-field">
						<label for="nb_results_p">* {@form.items.per.page}</label>
						<div class="form-field form-field-number">
							<input id="nb_results_p" type="number" min="1" max="200" name="nb_results_p" value="{ITEMS_PER_PAGE}">
						</div>
					</div>
					<div class="form-element">
						<label for="authorized_modules[]">* {@form.forbidden.module} <span class="field-description">{@search.forbidden.module.clue}</span></label>
						<div class="form-field form-field-select">
							<select id="authorized_modules[]" name="authorized_modules[]" size="10" multiple="multiple" class="list-modules">
								# START authorized_modules #
									<option id="{authorized_modules.MODULE}" value="{authorized_modules.MODULE}"{authorized_modules.SELECTED}>{authorized_modules.MODULE_NAME}</option>
								# END authorized_modules #
							</select>
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>{@search.cache}</legend>
				<div class="fieldset-inset">
					<div class="form-element">
						<label for="cache_time">
							* {@search.cache.time}
							<span class="field-description">{@search.cache.time.clue}</span>
						</label>
						<div class="form-field form-field-number">
							<input id="cache_time" type="number" min="0" name="cache_time" value="{CACHE_TIME}">
						</div>
					</div>
					<div class="form-element">
						<label for="max_use">
							* {@search.max.use}
							<span class="field-description">{@search.max.use.clue}</span>
						</label>
						<div class="form-field form-field-number">
							<input id="max_use" type="number" min="0" name="max_use" value="{MAX_USE}">
						</div>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>{@form.authorizations}</legend>
				<div class="fieldset-inset">
					<div class="auth-setter">
						<div class="form-element form-element-auth">
							<label>
								{@form.authorizations.read}
							</label>
							<div class="form-field">
								{READ_AUTHORIZATION}
							</div>
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
		<form action="admin_search.php?clear=1" name="form" method="post" class="fieldset-content">
			<fieldset>
				<legend>{@search.clear.cache}</legend>
			</fieldset>
			<fieldset class="fieldset-submit">
				<legend>{@form.empty}</legend>
				<div class="fieldset-inset">
					<input type="hidden" name="token" value="{TOKEN}">
					<button type="submit" name="gallery_cache" value="true" class="button alt-submit">{@form.empty}</button>
				</div>
			</fieldset>
		</form>
	# ELSE #
		<form action="admin_search.php?weighting=true&amp;token={TOKEN}" method="post" class="fieldset-content">
			<fieldset>
				<legend>{@search.config.weighting}</legend>
				<div class="fieldset-inset">
					<p>{@search.config.weighting.clue}</p>
					<table class="table">
						<thead>
							<tr>
								<th>{@form.module}</th>
								<th>{@search.weights}</th>
							</tr>
						</thead>
						<tbody>
							# START weights #
								<tr>
									<td><label for="{weights.MODULE}">{weights.MODULE_NAME}</label></td>
									<td><input type="number" id="{weights.MODULE}" name="{weights.MODULE}" value="{weights.WEIGHT}" min="1" max="100"></td>
								</tr>
							# END weights #
						</tbody>
					</table>
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
	# ENDIF #
</div>

<script>
	function check_form(){
		if(document.getElementById('elements_number').value == "" || document.getElementById('elements_number').value < 1) {
			alert("{@stats.require.items.number}");
			return false;
		}
		return true;
	}
</script>
<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars"></i> {@stats.module.title}
	</a>
	<ul>
		<li>
			<a href="${Url::to_rel('/stats')}" class="quick-link">{@form.home}</a>
		</li>
		<li>
			<a href="admin_stats.php" class="quick-link">{@form.configuration}</a>
		</li>
		<li>
			<a href="${relative_url(StatsUrlBuilder::documentation())}" class="quick-link">{@form.documentation}</a>
		</li>
	</ul>
</nav>
<div id="admin-contents">
	<form action="admin_stats.php" method="post" class="fieldset-content" onsubmit="return check_form();">
		<fieldset>
			<legend>{@stats.config.module.title}</legend>
			<div class="fieldset-inset">
				<div id="AdminStatsElementNumberPerPage" class="form-element">
					<label for="">{@form.items.per.page}<span class="field-description">{@stats.items.per.page.clue}</span></label>
					<div class="form-field form-field-number">
						<input id="elements_number" name="elements_number" value="{ELEMENTS_NUMBER_PER_PAGE}" type="number" min="1" max="100" patern="[0-9]*">
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset>
			<legend>{@form.authorizations}</legend>
			<div class="fieldset-inset">
				<div class="auth-setter">
					<div class="form-element form-element-auth">
						<label>{@form.authorizations.read}</label>
						<div class="form-field">{READ_AUTHORIZATION}</div>
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

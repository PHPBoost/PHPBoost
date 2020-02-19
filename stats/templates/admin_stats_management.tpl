<script>
	function check_form(){
		if(document.getElementById('elements_number').value == "" || document.getElementById('elements_number').value < 1) {
			alert("{L_REQUIRE_ELEMENTS_NUMBER}");
			return false;
		}
		return true;
	}
</script>
<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars"></i> {L_STATS}
	</a>
	<ul>
		<li>
			<a href="${Url::to_rel('/stats')}" class="quick-link">${LangLoader::get_message('home', 'main')}</a>
		</li>
		<li>
			<a href="admin_stats.php" class="quick-link">${LangLoader::get_message('configuration', 'admin')}</a>
		</li>
		<li>
			<a href="${relative_url(StatsUrlBuilder::documentation())}" class="quick-link">${LangLoader::get_message('module.documentation', 'admin-modules-common')}</a>
		</li>
	</ul>
</nav>
<div id="admin-contents">
	<form action="admin_stats.php" method="post" class="fieldset-content" onsubmit="return check_form();">
		<fieldset>
			<legend>${LangLoader::get_message('configuration', 'admin')}: {L_STATS}</legend>
			<div class="fieldset-inset">
				<div id="AdminStatsElementNumberPerPage" class="form-element">
					<label for="">{L_ELEMENTS_NUMBER_PER_PAGE}<span class="field-description">{L_ELEMENTS_NUMBER_EXPLAIN}</span></label>
					<div class="form-field form-field-number">
						<input id="elements_number" name="elements_number" value="{ELEMENTS_NUMBER_PER_PAGE}" type="number" min="1" max="100" patern="[0-9]*">
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset>
			<legend>{L_AUTHORIZATIONS}</legend>
			<div class="fieldset-inset">
				<div class="auth-setter">
					<div class="form-element form-element-auth">
						<label>{L_READ_AUTHORIZATION}</label>
						<div class="form-field">{READ_AUTHORIZATION}</div>
					</div>
				</div>
			</div>
		</fieldset>

		<fieldset class="fieldset-submit">
			<div class="fieldset-inset">
				<button type="submit" name="valid" value="true" class="button submit">{L_UPDATE}</button>
				<button type="reset" class="button reset-button" value="true">{L_RESET}</button>
				<input type="hidden" name="token" value="{TOKEN}">
			</div>
		</fieldset>
	</form>
</div>

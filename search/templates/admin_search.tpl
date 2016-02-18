		<script>
		<!--
			function check_form_conf()
			{
				if (document.getElementById('nb_results_p').value == "") {
					alert("{L_REQUIRE_INTEGER}");
					return false;
				}
				if (document.getElementById('cache_time').value == "") {
					alert("{L_REQUIRE_INTEGER}");
					return false;
				}
				if (document.getElementById('max_use').value == "") {
					alert("{L_REQUIRE_INTEGER}");
					return false;
				}
				return true;
			}
		-->
		</script>
		<nav id="admin-quick-menu">
			<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" title="{L_SEARCH_MANAGEMENT}">
				<i class="fa fa-bars"></i> {L_SEARCH_MANAGEMENT}
			</a>
			<ul>
				<li>
					<a href="admin_search.php" class="quick-link">{L_SEARCH_CONFIG}</a>
				</li>
				<li>
					<a href="admin_search.php?weighting=true" class="quick-link">{L_SEARCH_CONFIG_WEIGHTING}</a>
				</li>
			</ul>
		</nav>

		<div id="admin-contents">
			# IF NOT C_WEIGHTING #
			<form action="admin_search.php" method="post" onsubmit="return check_form_conf();" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_SEARCH_CONFIG}</legend>
					<div class="fieldset-inset">
						<div class="form-element">
							<label for="nb_results_p">* {L_NB_RESULTS_P}</label>
							<div class="form-field"><input type="number" min="1" max="200" id="nb_results_p" name="nb_results_p" value="{NB_RESULTS_P}"></div>
						</div>
						<div class="form-element">
							<label for="authorized_modules[]">* {L_AUTHORIZED_MODULES} <span class="field-description">{L_AUTHORIZED_MODULES_EXPLAIN}</span></label>
							<div class="form-field"><label>
								<select id="authorized_modules[]" name="authorized_modules[]" size="10" multiple="multiple" class="list-modules">
									# START authorized_modules #
									<option value="{authorized_modules.MODULE}" id="{authorized_modules.MODULE}"{authorized_modules.SELECTED}>{authorized_modules.L_MODULE_NAME}</option>
									# END authorized_modules #
								</select>
							</label></div>
						</div>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>{L_SEARCH_CACHE}</legend>
					<div class="fieldset-inset">
						<div class="form-element">
							<label for="cache_time">* {L_CACHE_TIME} <span class="field-description">{L_CACHE_TIME_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="0" id="cache_time" name="cache_time" value="{CACHE_TIME}"></div>
						</div>
						<div class="form-element">
							<label for="max_use">* {L_MAX_USE} <span class="field-description">{L_MAX_USE_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="0" id="max_use" name="max_use" value="{MAX_USE}"></div>
						</div>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>{L_AUTHORIZATIONS}</legend>
					<div class="fieldset-inset">
						<div class="form-element">
							<label>
								{L_READ_AUTHORIZATION}
							</label>
							<div class="form-field">
								{READ_AUTHORIZATION}
							</div>
						</div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<div class="fieldset-inset">
						<button type="submit" name="valid" value="true" class="submit">{L_UPDATE}</button>
						<button type="reset" value="true">{L_RESET}</button>
						<input type="hidden" name="token" value="{TOKEN}">
					</div>
				</fieldset>
			</form>
			<form action="admin_search.php?clear=1&amp;token={TOKEN}" name="form" method="post" class="fieldset-content">
				<fieldset>
					<legend>{L_CLEAR_OUT_CACHE}</legend>
					<div class="fieldset-inset fieldset-refresh">
						<a href="admin_search.php?clear=1" title="{L_CLEAR_OUT_CACHE}">
							<i class="fa fa-refresh fa-2x"></i>
							<span>{L_CLEAR_OUT_CACHE}</span>
						</a>
					</div>
				</fieldset>
			</form>
			# ELSE #
			<form action="admin_search.php?weighting=true&amp;token={TOKEN}" method="post" class="fieldset-content">
				<fieldset>
					<legend>{L_SEARCH_CONFIG_WEIGHTING}</legend>
					<div class="fieldset-inset">
						<p>{L_SEARCH_CONFIG_WEIGHTING_EXPLAIN}</p>
						<table id="table">
							<thead>
								<tr>
									<th>{L_MODULES}</th>
									<th>{L_WEIGHTS}</th>
								</tr>
							</thead>
							<tbody>
								# START weights #
								<tr>
									<td><label for="{weights.MODULE}">{weights.L_MODULE_NAME}</label></td>
									<td><input type="number" id="{weights.MODULE}" name="{weights.MODULE}" value="{weights.WEIGHT}" min="1" max="100"></td>
								</tr>
								# END weights #
							</tbody>
						</table>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<div class="fieldset-inset">
						<button type="submit" name="valid" value="true" class="submit">{L_UPDATE}</button> 
						<button type="reset" value="true">{L_RESET}</button>
						<input type="hidden" name="token" value="{TOKEN}">
					</div>
				</fieldset>
			</form>
			# ENDIF #
		</div>
		

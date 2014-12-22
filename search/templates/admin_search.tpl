		<script>
		<!--
			function check_form_conf()
			{
				if(!isInteger(document.getElementById('nb_results_p').value)) {
					alert("{L_REQUIRE_INTEGER}");
					return false;
				}
				if(!isInteger(document.getElementById('cache_time').value)) {
					alert("{L_REQUIRE_INTEGER}");
					return false;
				}
				if(!isInteger(document.getElementById('max_use').value)) {
					alert("{L_REQUIRE_INTEGER}");
					return false;
				}
				return true;
			}
		-->
		</script>
		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_SEARCH_MANAGEMENT}</li>
				<li>
					<a href="admin_search.php"><img src="search.png" alt="" /></a>
					<br />
					<a href="admin_search.php" class="quick-link">{L_SEARCH_CONFIG}</a>
				</li>
				<li>
					<a href="admin_search.php?weighting=true"><img src="search.png" alt="" /></a>
					<br />
					<a href="admin_search.php?weighting=true" class="quick-link">{L_SEARCH_CONFIG_WEIGHTING}</a>
				</li>
			</ul>
		</div>

		<div id="admin-contents">
			# IF NOT C_WEIGHTING #
			<form action="admin_search.php?token={TOKEN}" method="post" onsubmit="return check_form_conf();" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_SEARCH_CONFIG}</legend>
					<div class="form-element">
						<label for="nb_results_p">* {L_NB_RESULTS_P}</label>
						<div class="form-field"><input type="text" maxlength="2" size="4" id="nb_results_p" name="nb_results_p" value="{NB_RESULTS_P}"></div>
					</div>
					<div class="form-element">
						<label for="authorized_modules[]">* {L_AUTHORIZED_MODULES} <span class="field-description">{L_AUTHORIZED_MODULES_EXPLAIN}</span></label>
						<div class="form-field"><label>
							<select id="authorized_modules[]" name="authorized_modules[]" size="5" multiple="multiple" class="list-modules">
								# START authorized_modules #
								<option value="{authorized_modules.MODULE}" id="{authorized_modules.MODULE}"{authorized_modules.SELECTED}>{authorized_modules.L_MODULE_NAME}</option>
								# END authorized_modules #
							</select>
						</label></div>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>{L_SEARCH_CACHE}</legend>
					<div class="form-element">
						<label for="cache_time">* {L_CACHE_TIME} <span class="field-description">{L_CACHE_TIME_EXPLAIN}</span></label>
						<div class="form-field"><input type="text" maxlength="4" size="4" id="cache_time" name="cache_time" value="{CACHE_TIME}"></div>
					</div>
					<div class="form-element">
						<label for="max_use">* {L_MAX_USE} <span class="field-description">{L_MAX_USE_EXPLAIN}</span></label>
						<div class="form-field"><input type="text" maxlength="3" size="4" id="max_use" name="max_use" value="{MAX_USE}"></div>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>
						{L_AUTHORIZATIONS}
					</legend>
					<div class="form-element">
						<label>
							{L_READ_AUTHORIZATION}
						</label>
						<div class="form-field">
							{READ_AUTHORIZATION}
						</div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
				<legend>{L_UPDATE}</legend>
					<button type="submit" name="valid" value="true" class="submit">{L_UPDATE}</button>
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
			<form action="admin_search.php?clear=1&amp;token={TOKEN}" name="form" method="post" class="fieldset-content">
				<fieldset>
					<legend>{L_CLEAR_OUT_CACHE}</legend>
					<p class="center">
						<a href="admin_search.php?clear=1" title="{L_CLEAR_OUT_CACHE}">
							<i class="fa fa-refresh fa-2x"></i>
						</a>
						<br />
						<a href="admin_search.php?clear=1">{L_CLEAR_OUT_CACHE}</a>
					</p>
				</fieldset>
			</form>
			# ELSE #
			<form action="admin_search.php?weighting=true&amp;token={TOKEN}" method="post" class="fieldset-content">
				<fieldset>
					<legend>{L_SEARCH_CONFIG_WEIGHTING}</legend>
					<p>{L_SEARCH_CONFIG_WEIGHTING_EXPLAIN}</p>
						<table>
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
									<td><input type="text" id="{weights.MODULE}" name="{weights.MODULE}" value="{weights.WEIGHT}" size="2" maxlength="3"></td>
								</tr>
								# END weights #
							</tbody>
						</table>
				</fieldset>
				
				<fieldset class="fieldset-submit">
				<legend>{L_UPDATE}</legend>
					<button type="submit" name="valid" value="true" class="submit">{L_UPDATE}</button> 
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
			# ENDIF #
		</div>
		
# INCLUDE UPLOAD_FORM #

# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
<script>
<!--
	function select_all(status)
	{
		var i;
		for(i = 1; i <= {MODULES_NUMBER}; i++)
		{
			if(document.getElementById('upgrade-checkbox-' + i))
				document.getElementById('upgrade-checkbox-' + i).checked = status;
		}
		document.getElementById('check-all-top').checked = status;
		document.getElementById('check-all-bottom').checked = status;
	}
-->
</script>
# ENDIF #

<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	# INCLUDE MSG #
	<fieldset>
		<legend>{@modules.updates_available}</legend>
		<div class="fieldset-inset">
		# IF C_UPDATES #
			<table id="table">
				<caption>{@modules.updates_available}</caption>
				<thead>
					<tr>
						# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
						<th>
							<div class="form-field-checkbox">
								<input type="checkbox" id="check-all-top" onclick="select_all(this.checked);" title="${LangLoader::get_message('select_all', 'main')}" />
								<label for="check-all-top"></label>
							</div>
						</th>
						# ENDIF #
						<th>{@modules.name}</th>
						<th>{@modules.description}</th>
						<th>{@modules.upgrade_module}</th>
					</tr>
				</thead>
				# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
				<tfoot>
					<tr>
						<td colspan="4">
							<div class="left">
								<div class="form-field-checkbox">
									<input type="checkbox" id="check-all-bottom" onclick="select_all(this.checked);" title="${LangLoader::get_message('select_all', 'main')}" />
									<label for="check-all-bottom"></label>
								</div>
								<button type="submit" name="upgrade-selected-modules" value="true" class="submit">{@modules.upgrade_all_selected_modules}</button>
							</div>
						</td>
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START modules_upgradable #
					<tr>
						# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
						<td>
							<div class="form-field-checkbox">
								<input type="checkbox" id="upgrade-checkbox-{modules_upgradable.MODULE_NUMBER}" name="upgrade-checkbox-{modules_upgradable.MODULE_NUMBER}" />
								<label for="upgrade-checkbox-{modules_upgradable.MODULE_NUMBER}"></label>
							</div>
						</td>
						# ENDIF #
						<td>
							<img src="{PATH_TO_ROOT}/{modules_upgradable.ICON}/{modules_upgradable.ICON}.png" alt="{modules_upgradable.NAME}" /> <span class="text-strong">{modules_upgradable.NAME}</span> <span class="text-italic">({modules_upgradable.VERSION})</span>
						</td>
						<td class="left">
							<span class="text-strong">{@modules.author} :</span> # IF modules_upgradable.C_AUTHOR_EMAIL #<a href="mailto:{modules_upgradable.AUTHOR_EMAIL}">{modules_upgradable.AUTHOR}</a># ELSE #{modules_upgradable.AUTHOR}# ENDIF # # IF modules_upgradable.C_AUTHOR_WEBSITE #<a href="{modules_upgradable.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
							<span class="text-strong">{@modules.description} :</span> {modules_upgradable.DESCRIPTION}<br />
							<span class="text-strong">{@modules.compatibility} :</span> PHPBoost {modules_upgradable.COMPATIBILITY}<br />
							<span class="text-strong">{@modules.php_version} :</span> {modules_upgradable.PHP_VERSION}
						</td>
						<td>
							<button type="submit" class="submit" name="upgrade-{modules_upgradable.ID}" value="true">{@modules.upgrade_module}</button>
						</td>
					</tr>
					# END modules_upgradable #
				</tbody>
			</table>
		# ELSE #
			<div class="success message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
		# ENDIF #
		</div>
	</fieldset>
</form>

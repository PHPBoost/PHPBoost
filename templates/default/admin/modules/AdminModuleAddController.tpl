# INCLUDE UPLOAD_FORM #

# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
<script>
<!--
	function select_all(status)
	{
		var i;
		for(i = 1; i <= {MODULES_NUMBER}; i++)
		{
			if(document.getElementById('add-checkbox-' + i))
				document.getElementById('add-checkbox-' + i).checked = status;
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
		<legend>{@modules.modules_available}</legend>
		<div class="fieldset-inset">
		# IF C_MODULES_AVAILABLE #
			<table id="table">
				<caption>{@modules.modules_available}</caption>
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
						<th>${LangLoader::get_message('enable', 'common')}</th>
						<th>{@modules.install_module}</th>
					</tr>
				</thead>
				# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
				<tfoot>
					<tr>
						<td colspan="5">
							<div class="left">
								<div class="form-field-checkbox">
									<input type="checkbox" id="check-all-bottom" onclick="select_all(this.checked);" title="${LangLoader::get_message('select_all', 'main')}" />
									<label for="check-all-bottom"></label>
								</div>
								<button type="submit" name="add-selected-modules" value="true" class="submit">{@modules.install_all_selected_modules}</button>
							</div>
						</td>
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START available #
					<tr>
						# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
						<td>
							<div class="form-field-checkbox">
								<input type="checkbox" id="add-checkbox-{available.MODULE_NUMBER}" name="add-checkbox-{available.MODULE_NUMBER}" />
								<label for="add-checkbox-{available.MODULE_NUMBER}"></label>
							</div>
						</td>
						# ENDIF #
						<td>
							<img src="{PATH_TO_ROOT}/{available.ICON}/{available.ICON}.png" alt="{available.NAME}" />
							<span class="text-strong">{available.NAME}</span>
							<em>({available.VERSION})</em>
						</td>
						<td class="left">
							<span class="text-strong">{@modules.author} :</span> # IF available.C_AUTHOR_EMAIL #<a href="mailto:{available.AUTHOR_EMAIL}">{available.AUTHOR}</a># ELSE #{available.AUTHOR}# ENDIF # # IF available.C_AUTHOR_WEBSITE #<a href="{available.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
							<span class="text-strong">{@modules.description} :</span> {available.DESCRIPTION}<br />
							<span class="text-strong">{@modules.compatibility} :</span> PHPBoost {available.COMPATIBILITY}<br />
						</td>
						<td class="input-radio">
							<div class="form-field-radio">
								<input id="activated-{available.ID}" type="radio" name="activated-{available.ID}" value="1" checked="checked" />
								<label for="activated-{available.ID}"></label>
							</div>
							<span class="form-field-radio-span">${LangLoader::get_message('yes', 'common')}</span>
							<br />
							<div class="form-field-radio">
								<input id="activated-{available.ID}2" type="radio" name="activated-{available.ID}" value="0" />
								<label for="activated-{available.ID}2"></label>
							</div>
							<span class="form-field-radio-span">${LangLoader::get_message('no', 'common')}</span>
						</td>
						<td>
							<button type="submit" class="submit" name="add-{available.ID}" value="true">{@modules.install_module}</button>
						</td>
					</tr>
					# END available #
				</tbody>
			</table>
		# ELSE #
		<div class="notice message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
		# ENDIF #
		</div>
	</fieldset>
</form>

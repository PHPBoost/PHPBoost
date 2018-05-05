<script>
<!--
	function display_description(id){
		var SHOW = ${escapejs(LangLoader::get_message('display', 'common'))};
		var HIDE = ${escapejs(LangLoader::get_message('hide', 'common'))};

		jQuery('#desc-explain-' + id).toggle(300, function(){
			if (jQuery(this).css('display') == 'block'){
				jQuery('#picture-desc-' + id).attr('title', HIDE);
			}
			else{
				jQuery('#picture-desc-' + id).attr('title', SHOW);
			}
			jQuery('#picture-desc-' + id).children().toggleClass('fa-minus');
			jQuery('#picture-desc-' + id).children().toggleClass('fa-plus');
		});
	}
	# IF C_MORE_THAN_ONE_MODULE_INSTALLED #
	function select_all(status)
	{
		var i;
		for(i = 1; i <= {MODULES_NUMBER}; i++)
		{
			if(document.getElementById('delete-checkbox-' + i))
				document.getElementById('delete-checkbox-' + i).checked = status;
		}
		document.getElementById('check-all-top').checked = status;
		document.getElementById('check-all-bottom').checked = status;
	}
	# ENDIF #
-->
</script>

# START errors #
	# INCLUDE errors.MSG #
# END errors #

<form action="{REWRITED_SCRIPT}" method="post">
	${LangLoader::get_message('modules.warning_before_install', 'admin-modules-common')}
	<table id="table">
		<caption>{@modules.installed_modules}</caption>
		<thead>
			<tr> 
				# IF C_MORE_THAN_ONE_MODULE_INSTALLED #
				<th>
					<div class="form-field-checkbox">
						<input type="checkbox" id="check-all-top" onclick="select_all(this.checked);" title="${LangLoader::get_message('select_all', 'main')}" />
						<label for="check-all-top"></label>
					</div>
				</th>
				# ENDIF #
				<th>{@modules.name}</th>
				<th>{@modules.description}</th>
				<th>${LangLoader::get_message('enabled', 'common')}</th>
				<th>${LangLoader::get_message('uninstall', 'admin-common')}</th>
			</tr>
		</thead>
		# IF C_MORE_THAN_ONE_MODULE_INSTALLED #
		<tfoot>
			<tr>
				<td colspan="5">
					<div class="left">
						<div class="form-field-checkbox">
							<input type="checkbox" id="check-all-bottom" onclick="select_all(this.checked);" title="${LangLoader::get_message('select_all', 'main')}" />
							<label for="check-all-bottom"></label>
						</div>
						<button type="submit" name="delete-selected-modules" value="true" class="submit">{@modules.uninstall_all_selected_modules}</button>
					</div>
				</td>
			</tr>
		</tfoot>
		# ENDIF #
		<tbody>
			# START modules_installed #
			<tr>
				# IF C_MORE_THAN_ONE_MODULE_INSTALLED #
				<td>
					<div class="form-field-checkbox">
						<input type="checkbox" id="delete-checkbox-{modules_installed.MODULE_NUMBER}" name="delete-checkbox-{modules_installed.MODULE_NUMBER}" />
						<label for="delete-checkbox-{modules_installed.MODULE_NUMBER}"></label>
					</div>
				</td>
				# ENDIF #
				<td>
					<span id="module-{modules_installed.ID}"></span>
					<img class="valign-middle" src="{PATH_TO_ROOT}/{modules_installed.ICON}/{modules_installed.ICON}.png" alt="{modules_installed.NAME}" /><br />
					<span class="text-strong">{modules_installed.NAME}</span> <em>({modules_installed.VERSION})</em>
				</td>
				<td>
					<div id="desc-explain-{modules_installed.ID}" class="left" style="display: none;">
						<span class="text-strong">{@modules.name} :</span> # IF modules_installed.C_AUTHOR_EMAIL #<a href="mailto:{modules_installed.AUTHOR_EMAIL}">{modules_installed.AUTHOR}</a># ELSE #{modules_installed.AUTHOR}# ENDIF # # IF modules_installed.C_AUTHOR_WEBSITE #<a href="{modules_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
						<span class="text-strong">{@modules.description} :</span> {modules_installed.DESCRIPTION}<br />
						<span class="text-strong">{@modules.compatibility} :</span> PHPBoost {modules_installed.COMPATIBILITY}<br />
						<span class="text-strong">{@modules.php_version} :</span> {modules_installed.PHP_VERSION}<br />
						# IF modules_installed.C_DOCUMENTATION #<a class="basic-button smaller" href="{modules_installed.L_DOCUMENTATION}">{@module.documentation}</a># ENDIF #
					</div>
					<div class="center"><a href="" onclick="javascript:display_description('{modules_installed.ID}'); return false;" id="picture-desc-{modules_installed.ID}" class="description-displayed" title="${LangLoader::get_message('display', 'common')}"><i class="fa fa-plus"></i></a></div>
				</td>
				<td class="input-radio">
					<div class="form-field-radio">
						<input id="activated-{modules_installed.ID}" type="radio" name="activated-{modules_installed.ID}" value="1" # IF modules_installed.C_IS_ACTIVATED # checked="checked" # ENDIF #>
						<label for="activated-{modules_installed.ID}"></label>
					</div>
					<span class="form-field-radio-span">${LangLoader::get_message('yes', 'common')}</span>
					<br />
					<div class="form-field-radio">
						<input id="activated-{modules_installed.ID}2" type="radio" name="activated-{modules_installed.ID}" value="0" # IF NOT modules_installed.C_IS_ACTIVATED # checked="checked" # ENDIF #>
						<label for="activated-{modules_installed.ID}2"></label>
					</div>
					<span class="form-field-radio-span">${LangLoader::get_message('no', 'common')}</span>
				</td>
				<td>
					<button type="submit" class="submit" name="delete-{modules_installed.ID}" value="true">${LangLoader::get_message('uninstall', 'admin-common')}</button>
				</td>
			</tr>
			# END modules_installed #
		</tbody>
	</table>
	
	<fieldset class="fieldset-submit">
		<legend>{L_SUBMIT}</legend>
		<button type="submit" class="submit" name="update_modules_configuration" value="true">${LangLoader::get_message('update', 'main')}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="update" value="true">
		<button type="reset" value="true">${LangLoader::get_message('reset', 'main')}</button>
	</fieldset>
</form>
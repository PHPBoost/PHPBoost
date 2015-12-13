<script>
<!--
function display_description(id){
	jQuery('#desc_explain' + id).toggle(300, function(){
		if (jQuery(this).css('display') == 'block'){
			jQuery('#picture_desc' + id)[0].className = 'fa fa-minus';
		}
		else{
			jQuery('#picture_desc' + id)[0].className = 'fa fa-plus';
			
		}
	});
}
-->
</script>

# START errors #
	# INCLUDE errors.MSG #
# END errors #

<form action="{REWRITED_SCRIPT}" method="post">
	<table id="table">
		<caption>{@modules.installed_not_activated_modules}</caption>
		# IF C_MODULES_NOT_ACTIVATED #
		<thead>
			<tr>
				<th>{@modules.name}</th>
				<th>{@modules.description}</th>
				<th>${LangLoader::get_message('enabled', 'common')}</th>
				<th>${LangLoader::get_message('delete', 'common')}</th>
			</tr>
		</thead>
		<tbody>
			# START modules_not_activated #
			<tr>
				<td>
					<span id="m{modules_not_activated.ID}"></span>
					<img src="{PATH_TO_ROOT}/{modules_not_activated.ICON}/{modules_not_activated.ICON}.png" alt="{modules_not_activated.NAME}" /><br />
					<span class="text-strong">{modules_not_activated.NAME}</span> <em>({modules_not_activated.VERSION})</em>
				</td>
				<td>
					<div id="desc_explain{modules_not_activated.ID}" class="left" style="display: none;">
						<span class="text-strong">{@modules.author} :</span> # IF modules_not_activated.C_AUTHOR #<a href="mailto:{modules_not_activated.AUTHOR_EMAIL}">{modules_not_activated.AUTHOR}</a># ELSE #{modules_not_activated.AUTHOR}# ENDIF # # IF modules_not_activated.C_AUTHOR_WEBSITE #<a href="{modules_not_activated.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
						<span class="text-strong">{@modules.description} :</span> {modules_not_activated.DESCRIPTION}<br />
						<span class="text-strong">{@modules.compatibility} :</span> PHPBoost {modules_not_activated.COMPATIBILITY}<br />
						<span class="text-strong">{@modules.php_version} :</span> {modules_not_activated.PHP_VERSION}
					</div>
					<div class="center"><a href="" onclick="javascript:display_description('{modules_not_activated.ID}'); return false;" class="fa fa-plus" id="picture_desc{modules_not_activated.ID}"></a></div>
				</td>
				<td class="input-radio">
					<div class="form-field-radio">
						<input id="activated-{modules_not_activated.ID}" type="radio" name="activated-{modules_not_activated.ID}" value="1" # IF modules_not_activated.C_MODULE_ACTIVE # checked="checked" # ENDIF #>
						<label for="activated-{modules_not_activated.ID}"></label>
					</div>
					<span class="form-field-radio-span">${LangLoader::get_message('yes', 'common')}</span>
					<br />
					<div class="form-field-radio">
						<input id="activated-{modules_not_activated.ID}2" type="radio" name="activated-{modules_not_activated.ID}" value="0" # IF NOT modules_not_activated.C_MODULE_ACTIVE # checked="checked" # ENDIF # />
						<label for="activated-{modules_not_activated.ID}2"></label>
					</div>
					<span class="form-field-radio-span">${LangLoader::get_message('no', 'common')}</span>
				</td>
				<td>
					<button type="submit" class="submit" name="delete-{modules_not_activated.ID}" value="true" />${LangLoader::get_message('delete', 'common')}</button>
				</td>
			</tr>
			# END modules_not_activated #
		</tbody>

		# ELSE #
	</table>
	<div class="notice message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
		# ENDIF #
		
	<table id="table2">
		<caption>{@modules.installed_activated_modules}</caption>
		# IF C_MODULES_ACTIVATED #
		<thead>
			<tr> 
				<th>{@modules.name}</th>
				<th>{@modules.description}</th>
				<th>${LangLoader::get_message('enabled', 'common')}</th>
				<th>${LangLoader::get_message('delete', 'common')}</th>
			</tr>
		</thead>
		<tbody>
			# START modules_activated #
			<tr>
				<td>
					<span id="m{modules_activated.ID}"></span>
					<img class="valign-middle" src="{PATH_TO_ROOT}/{modules_activated.ICON}/{modules_activated.ICON}.png" alt="{modules_activated.NAME}" /><br />
					<span class="text-strong">{modules_activated.NAME}</span> <em>({modules_activated.VERSION})</em>
				</td>
				<td>
					<div id="desc_explain{modules_activated.ID}" class="left" style="display:none;">
						<span class="text-strong">{@modules.name} :</span> # IF modules_activated.C_AUTHOR #<a href="mailto:{modules_activated.AUTHOR_EMAIL}">{modules_activated.AUTHOR}</a># ELSE #{modules_activated.AUTHOR}# ENDIF # # IF modules_activated.C_AUTHOR_WEBSITE #<a href="{modules_activated.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
						<span class="text-strong">{@modules.description} :</span> {modules_activated.DESCRIPTION}<br />
						<span class="text-strong">{@modules.compatibility} :</span> PHPBoost {modules_activated.COMPATIBILITY}<br />
						<span class="text-strong">{@modules.php_version} :</span> {modules_activated.PHP_VERSION}
					</div>
					<div class="center"><a href="" onclick="javascript:display_description('{modules_activated.ID}'); return false;" class="fa fa-plus" id="picture_desc{modules_activated.ID}"></a></div>
				</td>
				<td class="input-radio">
					<div class="form-field-radio">
						<input id="activated-{modules_activated.ID}" type="radio" name="activated-{modules_activated.ID}" value="1" # IF modules_activated.C_MODULE_ACTIVE # checked="checked" # ENDIF #>
						<label for="activated-{modules_activated.ID}"></label>
					</div>
					<span class="form-field-radio-span">${LangLoader::get_message('yes', 'common')}</span>
					<br />
					<div class="form-field-radio">
						<input id="activated-{modules_activated.ID}2" type="radio" name="activated-{modules_activated.ID}" value="0" # IF NOT modules_activated.C_MODULE_ACTIVE # checked="checked" # ENDIF #>
						<label for="activated-{modules_activated.ID}2"></label>
					</div>
					<span class="form-field-radio-span">${LangLoader::get_message('no', 'common')}</span>
				</td>
				<td>
					<button type="submit" class="submit" name="delete-{modules_activated.ID}" value="true">${LangLoader::get_message('delete', 'common')}</button>
				</td>
			</tr>
			# END modules_activated #
		</tbody>
	</table>
		# ELSE #
	</table>
	<div class="notice">${LangLoader::get_message('no_item_now', 'common')}</div>
		# ENDIF #
		
	<fieldset class="fieldset-submit">
		<legend>{L_SUBMIT}</legend>
		<button type="submit" class="submit" name="update_modules_configuration" value="true">${LangLoader::get_message('update', 'main')}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="update" value="true">
	</fieldset>
</form>
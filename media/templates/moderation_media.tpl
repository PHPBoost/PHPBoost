		# IF C_DISPLAY #
		<form action="moderation_media.php" method="post">
			<fieldset class="fieldset-submit">
				<legend>{L_FILTER}</legend>
				<div id="form" class="align-center fieldset-content">
					{L_DISPLAY_FILE}&nbsp;
					<select name="state" id="state" class="nav" onchange="change_order()">
						<option value="all"{SELECTED_ALL}>{L_ALL}</option>
						<option value="visible"{SELECTED_VISIBLE}>{L_FVISIBLE}</option>
						<option value="unvisible"{SELECTED_UNVISIBLE}>{L_FUNVISIBLE}</option>
						<option value="unaprobed"{SELECTED_UNAPROBED}>{L_FUNAPROBED}</option>
					</select>
					<label for="show_sub_cats" class="checkbox">
						<span>{L_INCLUDE_SUB_CATS}</span>
						<input type="checkbox" id="show_sub_cats" name="sub_cats" value="1"{SUB_CATS}>
					</label>
				</div>
				<div class="spacer">&nbsp;</div>
				<div class="fieldset-inset">
					<input type="hidden" name="token" value="{TOKEN}">
					<button type="submit" name="filter" value="true" class="button submit">{L_SUBMIT}</button>
					<button type="reset" class="button reset-button" value="true">{L_RESET}</button>
				</div>
			</fieldset>
		</form>

		<script>
			<!--
			function check_all (type)
			{
				var item = new Array({JS_ARRAY});

				if (type == "delete")
				{
					if (confirm ('{L_CONFIRM_DELETE_ALL}'))
					{
						for (var i=0; i < item.length; i++)
							document.getElementById(type + item[i]).checked = 'checked';
					}
				}
				else
				{
					for (var i=0; i < item.length; i++)
						document.getElementById(type + item[i]).checked = 'checked';
				}
			}

			function pointer (id)
			{
				document.getElementById(id).style.cursor = 'pointer';
			}
			-->
		</script>
		<form action="moderation_media.php" method="post" class="fieldset-content">
			<fieldset>
				<legend><h1>{L_MODO_PANEL}</h1></legend>
				<table class="table">
					<thead>
						<tr>
							<th>
								{L_NAME}
							</th>
							<th>
								${LangLoader::get_message('category', 'categories-common')}
							</th>
							<th onclick="check_all('visible');" onmouseover="pointer('visible');" id="visible">
								{L_VISIBLE}
							</th>
							<th onclick="check_all('unvisible');" onmouseover="pointer('unvisible');" id="unvisible">
								{L_UNVISIBLE}
							</th>
							<th>
								{L_UNAPROBED}
							</th>
							<th onclick="check_all('delete');" onmouseover="pointer('delete');" id="delete">
								${LangLoader::get_message('delete', 'common')}
							</th>
						</tr>
					</thead>
					<tbody>
						# IF C_NO_MODERATION #
						<tr>
							<td colspan="6">{L_NO_MODERATION}</td>
						</tr>
						# ELSE #
						# START files #
						<tr>
							<td class="{files.COLOR}">
								<a href="{files.U_FILE}">{files.NAME}</a>
								<a href="{files.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
							</td>
							<td class="{files.COLOR}">
								<a href="{files.U_CAT}">{files.CAT}</a>
							</td>
							<td class="{files.COLOR}">
								<input type="radio" id="visible{files.ID}" name="action[{files.ID}]" value="visible"{files.SHOW}>
							</td>
							<td class="{files.COLOR}">
								<input type="radio" id="unvisible{files.ID}" name="action[{files.ID}]" value="unvisible"{files.HIDE}>
							</td>
							<td class="{files.COLOR}">
								<input type="radio" name="action[{files.ID}]" value="unaprobed"{files.UNAPROBED} # IF NOT files.UNAPROBED #disabled="disabled" # ENDIF #/>
							</td>
							<td class="{files.COLOR}">
								<input type="radio" id="delete{files.ID}" name="action[{files.ID}]" value="delete" data-confirmation="delete-element">
							</td>
						</tr>
						# END files #
						# ENDIF #
					</tbody>
					# IF C_PAGINATION #
					<tfoot>
						<tr>
							<td colspan="6">
								# INCLUDE PAGINATION #
							</td>
						</tr>
					</tfoot>
					# ENDIF #
				</table>
				<table class="table">
					<thead>
						<tr>
							<th colspan="3">{L_LEGEND}</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="bgc error">
								{L_FILE_UNAPROBED}
							</td>
							<td class="bgc warning">
								{L_FILE_UNVISIBLE}
							</td>
							<td class="bgc success">
								{L_FILE_VISIBLE}
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset>
			<fieldset class="fieldset-submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" name="submit" value="true" class="button submit">{L_SUBMIT}</button>
				<button type="reset" class="button reset-button" value="true">{L_RESET}</button>
			</fieldset>
		</form>
		# ENDIF #

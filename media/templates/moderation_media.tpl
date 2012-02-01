		# IF C_DISPLAY #
		<form action="moderation_media.php?token={TOKEN}" method="post" class="fieldset_content">
			<fieldset style="padding:15px 10px;">
				<legend>{L_FILTER}</legend>
				<div id="form" style="text-align:center;">
					{L_DISPLAY_FILE}&nbsp;
					<select name="state" id="state" class="nav" onchange="change_order()">
							<option value="all"{SELECTED_ALL}>{L_ALL}</option>
							<option value="visible"{SELECTED_VISIBLE}>{L_FVISIBLE}</option>
							<option value="unvisible"{SELECTED_UNVISIBLE}>{L_FUNVISIBLE}</option>
							<option value="unaprobed"{SELECTED_UNAPROBED}>{L_FUNAPROBED}</option>
					</select>
					&nbsp;{L_CATEGORIES}&nbsp;
						{CATEGORIES_TREE}
					&nbsp;{L_INCLUDE_SUB_CATS}&nbsp;
					<input type="checkbox" name="sub_cats" value="1"{SUB_CATS} />
				</div>
				<div style="margin-top:20px;text-align:center;">
					<input type="submit" name="filter" value="{L_SUBMIT}" class="submit" />
					&nbsp;&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />
				</div>
			</fieldset>
		</form>

		<script type="text/javascript">
			<!--
			function check_all (type)
			{
				var item = new Array({JS_ARRAY});
				
				if (type == "delete")
					confirm ('{L_CONFIRM_DELETE_ALL}');

				for (var i=0; i < item.length; i++)
					document.getElementById(type + item[i]).checked = 'checked';
			}
			
			function pointer (id)
			{
				document.getElementById(id).style.cursor = 'pointer';
			}
			-->
		</script>
		<form action="moderation_media.php?token={TOKEN}" method="post" class="fieldset_content">
			<fieldset>
				<legend>{L_MODO_PANEL}</legend>
				<p style="text-align:center;margin:10px auto;">{PAGINATION}</p>
				<table class="module_table">
					<tr>
						<th style="width:40%">
							{L_NAME}
						</th>
						<th style="width:20%">
							{L_CATEGORY}
						</th>
						<th style="width:10%" onclick="check_all('visible');" onmouseover="pointer('visible');" id="visible">
							{L_VISIBLE}
						</th>
						<th style="width:10%" onclick="check_all('unvisible');" onmouseover="pointer('unvisible');" id="unvisible">
							{L_UNVISIBLE}
						</th>
						<th style="width:10%">
							{L_UNAPROBED}
						</th>
						<th style="width:10%" onclick="check_all('delete');" onmouseover="pointer('delete');" id="delete">
							{L_DELETE}
						</th>
					</tr>
					# IF C_NO_MODERATION #
					<tr style="text-align:center;">
						<td class="row1" colspan="6">{L_NO_MODERATION}</td>
					</tr>
					# ELSE #
					# START files #
					<tr style="text-align:center;">
						<td class="row2" style="background:{files.COLOR};padding:5px 0;">
							<a href="{files.U_FILE}">{files.NAME}</a>
							<a href="{files.U_EDIT}">
								<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="">
							</a>
						</td>
						<td class="row2" style="background:{files.COLOR};padding:5px 0;">
							<a href="{files.U_CAT}">{files.CAT}</a>
						</td>
						<td class="row2" style="background:{files.COLOR};padding:5px 0;">
							<input type="radio" id="visible{files.ID}" name="action[{files.ID}]" value="visible"{files.SHOW} />
						</td>
						<td class="row2" style="background:{files.COLOR};padding:5px 0;">
							<input type="radio" id="unvisible{files.ID}" name="action[{files.ID}]" value="unvisible"{files.HIDE} />
						</td>
						<td class="row2" style="background:{files.COLOR};padding:5px 0;">
							<input type="radio" name="action[{files.ID}]" value="unaprobed"{files.UNAPROBED} # IF NOT files.UNAPROBED #disabled="disabled" # ENDIF #/>
						</td>
						<td class="row2" style="background:{files.COLOR};padding:5px 0;">
							<input type="radio" id="delete{files.ID}" name="action[{files.ID}]" value="delete" onclick="return confirm('{L_CONFIRM_DELETE}');" />
						</td>
					</tr>
					# END files #
					# ENDIF #
				</table>
				<p style="text-align: center;margin:10px auto;">{PAGINATION}</p>
				<table class="module_table" style="margin-bottom: 15px;">
					<tr>
						<th colspan="3" style="text-align:center;">{L_LEGEND}</th>
					</tr>
					<tr style="text-align:center;">
						<td class="row2" style="width:33.3%;background:#FFCCCC;padding:3px 0;">
							{L_FILE_UNAPROBED}
						</td>
						<td class="row2" style="width:33.3%;background:#FFEE99;padding:3px 0;">
							{L_FILE_UNVISIBLE}
						</td>
						<td class="row2" style="width:33.3%;background:#CCFFCC;padding:3px 0;">
							{L_FILE_VISIBLE}
						</td>
					</tr>
				</table>
			</fieldset>
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
				&nbsp;&nbsp;
				<input type="reset" value="{L_RESET}" class="reset" />
			</fieldset>
		</form>
		# IF C_ADMIN #
		<div style="text-align:center; margin:20px 20px;" class="row1">
			<a href="moderation_media.php?recount=1">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/refresh.png" alt="{L_RECOUNT_MEDIA}" />
			</a>
			<br />
			<a href="moderation_media.php?recount=1">{L_RECOUNT_MEDIA}</a>
		</div>
		# ENDIF #
		# ENDIF #
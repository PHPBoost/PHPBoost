		# IF C_DISPLAY #
		<form action="moderation_media.php" method="post" onsubmit="return check_form();" class="fieldset_content">
			<fieldset>
				<legend>{L_MODO_PANEL}</legend>
				<table class="module_table">
					<tr>
						<th style="width:40%">
							{L_NAME}
						</th>
						<th style="width:16%">
							{L_CATEGORY}
						</th>
						<th style="width:11%">
							{L_VISIBLE}
						</th>
						<th style="width:11%">
							{L_UNVISIBLE}
						</th>
						<th style="width:11%">
							{L_DELETE}
						</th>
						<th style="width:11%">
							{L_WAIT}
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
							<a href="{files.U_EDIT}">{files.NAME}</a>
						</td>
						<td class="row2" style="background:{files.COLOR};padding:5px 0;">
							<a href="{files.U_CAT}">{files.CAT}</a>
						</td>
						<td class="row2" style="background:{files.COLOR};padding:5px 0;">
							<input type="radio" name="action[{files.ID}]" value="visible" />
						</td>
						<td class="row2" style="background:{files.COLOR};padding:5px 0;">
							<input type="radio" name="action[{files.ID}]" value="unvisible" />
						</td>
						<td class="row2" style="background:{files.COLOR};padding:5px 0;">
							<input type="radio" name="action[{files.ID}]" value="delete" onclick="return confirm('{L_CONFIRM_DELETE}');" />
						</td>
						<td class="row2" style="background:{files.COLOR};padding:5px 0;">
							<input type="radio" name="action[{files.ID}]" value="wait" checked="checked" />
						</td>
					</tr>
					# END files #
					# ENDIF #
				</table>
				<table class="module_table">
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
			<br /><p style="text-align: center;">{PAGINATION}</p>
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
				&nbsp;&nbsp;
				<input type="reset" value="{L_RESET}" class="reset" />
			</fieldset>
		</form>
		# IF C_ADMIN #
		<div style="text-align:center; margin:30px 20px;" class="row1">
			<a href="admin_media_cats.php?recount=1">
				<img src="../templates/{THEME}/images/admin/refresh.png" alt="{L_RECOUNT_MEDIA}" />
			</a>
			<br />
			<a href="admin_media_cats.php?recount=1">{L_RECOUNT_MEDIA}</a>
		</div>
		# ENDIF #
		# ENDIF #
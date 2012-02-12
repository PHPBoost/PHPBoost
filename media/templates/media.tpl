		# IF C_CATEGORIES #
		<div class="module_position">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">
				<a href="../syndication.php?m=media" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a>
				{TITLE}
				# IF C_ADMIN #
				<a href="{U_ADMIN_CAT}">
					<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="">
				</a>
				# END IF #
				# IF C_MODO #
				<a href="moderation_media.php">
					<img class="valign_middle" src="../templates/{THEME}/images/moderation_panel.png" style="width:16px;height:16px;" alt="">
				</a>
				# END IF #
			</div>
			<div class="module_contents">
				# IF C_ADD_FILE #
					<div style="text-align:center;">
						<a href="{U_ADD_FILE}" title="{L_ADD_FILE}">
							<img src="../templates/{THEME}/images/french/add.png" alt="{L_ADD_FILE}" />
						</a>
					</div>
					<hr style="margin-top:25px; margin-bottom:25px;" />
				# ENDIF #
				# IF C_DESCRIPTION #
					{DESCRIPTION}
					<hr style="margin-top:25px;" />
				# ENDIF #

				# IF C_SUB_CATS #
					# START row #
						# START row.list_cats #
							<div style="float:left;width:{row.list_cats.WIDTH}%;text-align:center;margin:20px 0px;">
								<a href="{row.list_cats.U_CAT}" title="{row.list_cats.IMG_NAME}">
									<img src="{row.list_cats.SRC}" alt="{row.list_cats.IMG_NAME}" />
								</a>
								<br />
								<a href="{row.list_cats.U_CAT}">{row.list_cats.NAME}</a>
								# IF C_ADMIN #
								<a href="{row.list_cats.U_ADMIN_CAT}">
									<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="">
								</a>
								# ENDIF #
								# IF row.list_cats.NUM_MEDIA #
								<div class="text_small">
									{row.list_cats.NUM_MEDIA}
								</div>
								# ENDIF #
							</div>
						# END row.list_cats #
						<div class="spacer">&nbsp;</div>
					# END row #
					<hr />
				# ENDIF #

				# IF C_FILES #
					<div style="float:right;" class="row3" id="form">
						<script type="text/javascript">
						<!--
						function change_order()
						{
							window.location = "{TARGET_ON_CHANGE_ORDER}sort=" + document.getElementById("sort").value + "&mode=" + document.getElementById("mode").value;
						}
						-->
						</script>
						{L_ORDER_BY}
						<select name="sort" id="sort" class="nav" onchange="change_order()">
							<option value="alpha"{SELECTED_ALPHA}>{L_ALPHA}</option>
							<option value="date"{SELECTED_DATE}>{L_DATE}</option>
							<option value="nbr"{SELECTED_NBR}>{L_NBR}</option>
							<option value="note"{SELECTED_NOTE}>{L_NOTE}</option>
							<option value="com"{SELECTED_COM}>{L_COM}</option>
						</select>
						<select name="mode" id="mode" class="nav" onchange="change_order()">
							<option value="asc"{SELECTED_ASC}>{L_ASC}</option>
							<option value="desc"{SELECTED_DESC}>{L_DESC}</option>
						</select>
					</div>
					<div class="spacer">&nbsp;</div>

					# START file #
						<div class="block_container">
							<div class="block_contents">
								<div>
									# IF C_MODO #
									<div style="float:right;">
										<a href="{file.U_ADMIN_UNVISIBLE_MEDIA}">
											<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/visible.png" alt="">
										</a>
										<a href="{file.U_ADMIN_EDIT_MEDIA}">
											<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="">
										</a>
										<a href="{file.U_ADMIN_DELETE_MEDIA}" onclick="return confirm('{L_CONFIRM_DELETE_FILE}');">
											<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/delete.png" alt="">
										</a>
									</div>
									# ENDIF #
										<a href="{file.U_MEDIA_LINK}" class="big_link">{file.NAME}</a>
								</div>
								# IF A_DESC #
								# IF file.C_DESCRIPTION #
									<p style="margin-top:10px">
									{file.DESCRIPTION}
									<br />
									</p>
								# ENDIF #
								# ELSE #
									<br />
								# ENDIF #
								# IF A_BLOCK #
								<div class="text_small">
									# IF A_DATE #
									{file.DATE}
									<br />
									# ENDIF #
									# IF A_USER #
									{file.POSTER}
									<br />
									# ENDIF #
									# IF A_COUNTER #
									{file.COUNT}
									<br />
									# ENDIF #
									# IF A_COM #
									{file.U_COM_LINK}
									<br />
									# ENDIF #
									# IF A_NOTE #
									{L_NOTE} {file.NOTE}
									# ENDIF #
								</div>
								# ENDIF #
								<div class="spacer"></div>
							</div>
						</div>
					# END file #
					<div style="text-align:center;">{PAGINATION}</div>
				# ENDIF #

				# IF C_NO_FILE #
					<div class="notice">
						{L_NO_FILE_THIS_CATEGORY}
					</div>
				# ENDIF #
				<div class="spacer"></div>
			</div>
			<div class="module_bottom_l"></div>
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		# ENDIF #

		# IF C_DISPLAY_MEDIA #
		<div class="module_position">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					{NAME}
					# IF C_MODO #
					<a href="moderation_media.php">
						<img class="valign_middle" src="../templates/{THEME}/images/moderation_panel.png" style="width:16px;height:16px;" alt="">
					</a>
					# END IF #
				</div>
				<div style="float:right">
					# IF A_COM #{U_COM}# ENDIF #
					# IF C_MODO #
						<a href="{U_UNVISIBLE_MEDIA}">
							<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/visible.png" alt="">
						</a>
						<a href="{U_EDIT_MEDIA}">
							<img src="../templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" alt="" />
						</a>
						<a href="{U_DELETE_MEDIA}" onclick="return confirm('{L_CONFIRM_DELETE_FILE}');">
							<img src="../templates/{THEME}/images/{LANG}/delete.png" class="valign_middle" alt="" />
						</a>
					# ENDIF #
				</div>
			</div>
			<div class="module_contents">
				# IF A_DESC #
				<p class="text_justify" style="margin-top:15px">
					{CONTENTS}
				</p>
				# ENDIF #
				<p class="text_center" style="margin-top:25px;margin-bottom:25px;">
					# INCLUDE media_format #
				</p>
				# IF C_DISPLAY #
				<table style="width:430px;float:right;margin-top:40px;" class="module_table text_small">
					<tr>
						<th colspan="2">
							{L_MEDIA_INFOS}
						</th>
					</tr>
					# IF A_DATE #
					<tr>
						<td class="row1" style="padding:3px">
							{L_DATE}
						</td>
						<td class="row2" style="padding:3px">
							{DATE}
						</td>
					</tr>
					# ENDIF #
					# IF A_USER #
					<tr>
						<td class="row1" style="padding:3px">
							{L_BY}
						</td>
						<td class="row2" style="padding:3px">
							{BY}
						</td>
					</tr>
					# ENDIF #
					# IF A_COUNTER #
					<tr>
						<td class="row1" style="padding:3px">
							{L_VIEWED}
						</td>
						<td class="row2" style="padding:3px">
							{HITS}
						</td>
					</tr>
					# ENDIF #
					# IF A_NOTE #
					<tr>
						<td class="row1" style="padding:3px">
							{L_NOTE} <em><span id="nbrnote{ID_MEDIA}">({NUM_NOTES})</span></em>
						</td>
						<td class="row2" style="padding:1px">
							{KERNEL_NOTATION}
						</td>
					</tr>
					# ENDIF #
				</table>
				<div class="spacer"></div>
				# ENDIF #
			</div>
			<div class="module_bottom_l"></div>
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
			</div>
		</div>
		<br /><br />
		{COMMENTS}
		# ENDIF #
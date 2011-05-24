 # IF C_DOWNLOAD_CAT #
<div class="module_position">
	<div class="module_top_l"></div>
	<div class="module_top_r"></div>
	<div class="module_top">
		<a href="{PATH_TO_ROOT}/syndication.php?m=download&amp;cat={IDCAT}"
			title="Rss"><img style="vertical-align: middle; margin-top: -2px;"
			src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss"
			title="Rss" /> </a> {TITLE} # IF C_ADMIN # <a href="{U_ADMIN_CAT}"> <img
			class="valign_middle"
			src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" />
		</a> # END IF #
	</div>
	<div class="module_contents">
		# IF C_ADD_FILE #
		<div style="text-align: center;">
			<a href="{U_ADD_FILE}" title="{L_ADD_FILE}"> <img
				src="{PATH_TO_ROOT}/templates/{THEME}/images/french/add.png"
				alt="{L_ADD_FILE}" /> </a>
		</div>
		<hr style="margin-top: 25px; margin-bottom: 25px;" />
		# ENDIF # # IF C_DESCRIPTION # {DESCRIPTION}
		<hr style="margin-top: 25px;" />
		# ENDIF # # IF C_SUB_CATS # # START row # # START row.list_cats #
		<div style="float: left; width: { row.list_cats.WIDTH">
			# IF row.list_cats.C_CAT_IMG # <a href="{row.list_cats.U_CAT}"
				title="{row.list_cats.IMG_NAME}"><img src="{row.list_cats.SRC}"
				alt="{row.list_cats.IMG_NAME}" /> </a> <br /> # ENDIF # <a
				href="{row.list_cats.U_CAT}">{row.list_cats.NAME}</a> # IF C_ADMIN #
			<a href="{row.list_cats.U_ADMIN_CAT}"> <img class="valign_middle"
				src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" />
			</a> # ENDIF #
			<div class="text_small">{row.list_cats.NUM_FILES}</div>
		</div>
		# END row.list_cats #
		<div class="spacer">&nbsp;</div>
		# END row #
		<hr style="margin-bottom: 25px;" />
		# ENDIF # # IF C_FILES #

		<div style="float: right;" class="row3" id="form">
			<script type="text/javascript">
						<!--
						function change_order()
						{
							window.location = "{TARGET_ON_CHANGE_ORDER}sort=" + document.getElementById("sort").value + "&mode=" + document.getElementById("mode").value;
						}
						-->
						</script>
			{L_ORDER_BY} <select name="sort" id="sort" class="nav"
				onchange="change_order()">
				<option value="alpha"{SELECTED_ALPHA}>{L_ALPHA}</option>
				<option value="size"{SELECTED_SIZE}>{L_SIZE}</option>
				<option value="date"{SELECTED_DATE}>{L_DATE}</option>
				<option value="hits"{SELECTED_HITS}>{L_POPULARITY}</option>
				<option value="note"{SELECTED_NOTE}>{L_NOTE}</option>
			</select> <select name="mode" id="mode" class="nav"
				onchange="change_order()">
				<option value="asc"{SELECTED_ASC}>{L_ASC}</option>
				<option value="desc"{SELECTED_DESC}>{L_DESC}</option>
			</select>
		</div>
		<div class="spacer">&nbsp;</div>

		# START file #
		<div class="block_container" style="margin-bottom: 20px;">
			<div class="block_contents">
				# IF file.C_IMG #
				<div class="float_right">
					<a href="{file.U_DOWNLOAD_LINK}"> <img src="{file.IMG}"
						alt="{file.IMG_NAME}" /> </a>
				</div>
				# ENDIF #
				<p style="margin-bottom: 10px">
					<a href="{file.U_DOWNLOAD_LINK}" class="big_link">{file.NAME}</a> #
					IF C_ADMIN # <a href="{file.U_ADMIN_EDIT_FILE}"> <img
						class="valign_middle"
						src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png"
						alt="" /> </a> <a href="{file.U_ADMIN_DELETE_FILE}"
						onclick="return confirm('{L_CONFIRM_DELETE_FILE}');"> <img
						class="valign_middle"
						src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png"
						alt="" /> </a> # ENDIF #
				</p>
				# IF file.C_DESCRIPTION #
				<p>{file.DESCRIPTION}</p>
				# ENDIF #
				<div class="text_small">
					{file.DATE} <br /> {file.COUNT_DL} <br /> {file.U_COM_LINK} <br />
					{L_NOTE} {file.NOTE}
				</div>
				<div class="spacer"></div>
			</div>
		</div>
		# END file #
		<div style="text-align: center;">{PAGINATION}</div>
		# ENDIF # # IF C_NO_FILE #
		<div class="notice">{L_NO_FILE_THIS_CATEGORY}</div>
		# ENDIF #
		<div class="spacer"></div>
	</div>
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>
	<div class="module_bottom"></div>
</div>
# ENDIF # # IF C_DISPLAY_DOWNLOAD #
<div class="module_position">
	<div class="module_top_l"></div>
	<div class="module_top_r"></div>
	<div class="module_top">
		<div style="float: left">{NAME}</div>
		<div style="float: right">
			{U_COM} # IF C_EDIT_AUTH # <a href="{U_EDIT_FILE}"> <img
				src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png"
				class="valign_middle" alt="{L_EDIT_FILE}" /> </a> <a
				href="{U_DELETE_FILE}"
				onclick="return confirm('{L_CONFIRM_DELETE_FILE}');"> <img
				src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png"
				class="valign_middle" alt="{L_DELETE_FILE}" /> </a> # ENDIF #
		</div>
	</div>
	<div class="module_contents">
		<table>
			<tr>
				<td style="text-align: center; padding-right: 20px;"># IF C_IMG # <img
					src="{U_IMG}" alt="{IMAGE_ALT}" /> <br /> <br /> # ENDIF # <a
					href="{U_DOWNLOAD_FILE}"> <img
						src="{PICTURES_DATA_PATH}/images/download_file.png" alt="" /> </a>
					<p>
						<a href="{U_DOWNLOAD_FILE}">{L_DOWNLOAD_FILE}</a>
					</p> # IF IS_USER_CONNECTED # <a href="{U_DEADLINK}"> <img
						src="{PATH_TO_ROOT}/templates/{THEME}/images/notice.png" alt="" />
				</a>
					<p>
						<a href="{U_DEADLINK}">{L_DEADLINK}</a>
					</p> # ENDIF #</td>
				<td>
					<p class="text_justify">{CONTENTS}</p>
				</td>
			</tr>
		</table>
		<br />
		<table style="width: 430px; margin-right: 0;"
			class="module_table text_small">
			<tr>
				<th colspan="2">{L_FILE_INFOS}</th>
			</tr>
			<tr>
				<td class="row1" style="padding: 3px">{L_SIZE}</td>
				<td class="row2" style="padding: 3px">{SIZE}</td>
			</tr>
			<tr>
				<td class="row1" style="padding: 3px">{L_INSERTION_DATE}</td>
				<td class="row2" style="padding: 3px">{CREATION_DATE}</td>
			</tr>
			<tr>
				<td class="row1" style="padding: 3px">{L_RELEASE_DATE}</td>
				<td class="row2" style="padding: 3px">{RELEASE_DATE}</td>
			</tr>
			<tr>
				<td class="row1" style="padding: 3px">{L_DOWNLOADED}</td>
				<td class="row2" style="padding: 3px">{HITS}</td>
			</tr>
			<tr>
				<td class="row1" style="padding: 3px">{L_NOTE} <em><span
						id="nbrnote{ID_FILE}">({NUM_NOTES})</span> </em>
				</td>
				<td class="row2" style="padding: 1px">{KERNEL_NOTATION}</td>
			</tr>
		</table>
	</div>
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>
	<div class="module_bottom"></div>
</div>
<br />
<br />
{COMMENTS} # ENDIF #

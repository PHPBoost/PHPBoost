		{JAVA}

		# IF C_DOWNLOAD_CAT #
		<div class="module_position">			
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				{TITLE}
				# IF C_ADMIN #
				<a href="{U_ADMIN_CAT}">
					<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="">
				</a>
				# END IF #
			</div>
			<div class="module_contents">
			
				# IF C_DESCRIPTION #
					{DESCRIPTION}
					<hr style="margin-top:25px;" />
				# ENDIF #
				
				# IF C_SUB_CATS #
					# START row #
						# START row.list_cats #
							<div style="float:left;width:{row.list_cats.WIDTH}%;text-align:center;margin:20px 0px;">
								# IF row.list_cats.C_CAT_IMG #
									<a href="{row.list_cats.U_CAT}" title="{row.list_cats.IMG_NAME}"><img src="{row.list_cats.SRC}" alt="{row.list_cats.IMG_NAME}" /></a>
									<br />
								# ENDIF #
								<a href="{row.list_cats.U_CAT}">{row.list_cats.NAME}</a>
								
								# IF C_ADMIN #
								<a href="{row.list_cats.U_ADMIN_CAT}">
									<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="">
								</a>
								# ENDIF #
								<div class="text_small">
									{row.list_cats.NUM_FILES}
								</div>
							</div>
						# END row.list_cats #
						<div class="spacer">&nbsp;</div>
					# END row #
					<hr style="margin-bottom:25px;" />
				# ENDIF #
				
				# IF C_FILES #
					# START file #
						<div class="block_position" style="margin-bottom:20px;">
							<div class="row1 block_contents">
								# IF file.C_IMG #
									<div class="float_right">
										<img src="{file.IMG}" alt="{file.IMG_NAME}" />
									</div>
								# ENDIF #
								<p style="margin-bottom:10px">
									<a href="{file.U_DOWNLOAD_LINK}" class="big_link">{file.NAME}</a>
									# IF C_ADMIN #
										<a href="{file.U_ADMIN_EDIT_FILE}">
											<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="">
										</a>
										<a href="{file.U_ADMIN_DELETE_FILE}">
											<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/delete.png" alt="">
										</a>
									# ENDIF #
								</p>
								# IF file.C_DESCRIPTION #
									<p>
									{file.DESCRIPTION}
									</p>
								# ENDIF #
								<div class="text_small">
									{file.DATE}
									<br />
									{file.COUNT_DL}
									<br />
									{file.COMS}
									<br />
									{L_NOTE}  {file.NOTE}
								</div>
								<div class="spacer"></div>								
							</div>
						</div>						
					# END file #
					<div style="align:center;">{PAGINATION}</div>
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
		
		# IF C_DISPLAY_DOWNLOAD #			
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					{NAME}
				</div>
				<div style="float:right">
					{COMMENTARIES}
					# IF C_EDIT_AUTH #
						<a href="{U_EDIT_FILE}">
							<img src="../templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" alt="{L_EDIT_FILE}" />
						</a>
						<a href="{U_DELETE_FILE}">
							<img src="../templates/{THEME}/images/{LANG}/delete.png" class="valign_middle" alt="{L_DELETE_FILE}" />
						</a>
					# ENDIF #
				</div>
			</div>
			<div class="module_contents">
				<table>
					<tr>
						<td style="float:left;text-align:center;padding-right:20px;">
							# IF C_IMG #
								<a href="{U_DOWNLOAD_FILE}"><img src="{U_IMG}" alt="{IMAGE_ALT}" /></a>
								<br /><br />
							# ENDIF #
							<a href="{U_DOWNLOAD_FILE}">
								<img src="{MODULE_DATA_PATH}/images/download_file.png" alt="" />
							</a>
							<p style="margin-top:-15px;"><a href="{U_DOWNLOAD_FILE}">{L_DOWNLOAD_FILE}</a></p>
						</td>
						<td>
							<p class="text_justify" style="margin-top:-20px">
								{CONTENTS}
							</p>
						</td>
					</tr>
				</table>
				<br />
				<table style="width:400px;margin-right:0;" class="module_table text_small">
					<tr>
						<th colspan="2">
							{L_FILE_INFOS}
						</th>
					</tr>
					<tr>
						<td class="row1" style="padding:3px">
							{L_SIZE}
						</td>
						<td class="row2" style="padding:3px">
							{SIZE}
						</td>
					</tr>
					<tr>
						<td class="row1" style="padding:3px">
							{L_INSERTION_DATE}
						</td>
						<td class="row2" style="padding:3px">
							{INSERTION_DATE}
						</td>
					</tr>
					<tr>
						<td class="row1" style="padding:3px">
							{L_LAST_UPDATE_DATE}
						</td>
						<td class="row2" style="padding:3px">
							{LAST_UPDATE_DATE}
						</td>
					</tr>
					<tr>
						<td class="row1" style="padding:3px">
							{L_DOWNLOADED}
						</td>
						<td class="row2" style="padding:3px">
							{HITS}
						</td>
					</tr>
					<tr>
						<td class="row1" style="padding:3px">
							{L_NOTE} <em>({NUM_NOTES})</em>
						</td>
						<td class="row2" style="padding:1px">
							# INCLUDE handle_note #
						</td>
					</tr>
				</table>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
			</div>
		</div>		
		<br /><br />
		# INCLUDE handle_com #
		# ENDIF #
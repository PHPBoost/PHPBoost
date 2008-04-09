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
			
				# START description #
					{description.DESCRIPTION}
					<hr style="margin-top:25px;" />
				# END description #
				
				# IF C_SUB_CATS #
					# START row #
						# START row.list_cats #
							<div style="float:left;width:{row.list_cats.WIDTH}%;text-align:center;margin:20px 0px;">
								# IF C_CAT_IMG #
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
					<div class="text_strong" style="text-align:center;">
						{L_FILES_IN_THIS_CATEGORY}
					</div>
					# START file #
						<div class="block_position" style="margin-bottom:20px;">
							<div class="row1 block_contents">
								# IF file.C_IMG #
									<div class="float_right">
										<img src="{file.IMG}" alt="" />
									</div>
								# ENDIF #
								<strong><a href="{file.U_DOWNLOAD_LINK}">{file.NAME}</a></strong>
								# IF file.C_DESCRIPTION #
									<p>
									{file.DESCRIPTION}
									</p>
								# ENDIF #
								<div class="text_small">
									{file.DATE} &bull; {file.COUNT_DL} &bull; {file.COMS} &bull; {file.NOTE}
								</div>								
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
					{COM} {EDIT}{DEL}
				</div>
			</div>
			<div class="module_contents">
				<p>					
					<strong>{L_DESC}:</strong> {CONTENTS}						
					<br /><br />						
					<strong>{L_CAT}:</strong> 
					<a href="../download/download{U_DOWNLOAD_CAT}" title="{CAT}">{CAT}</a><br />						
					<strong>{L_DATE}:</strong> {DATE}<br />						
					<strong>{L_SIZE}:</strong> {SIZE}<br />						
					<strong>{L_DOWNLOAD}:</strong> {COUNT} {L_TIMES}
					<div class="spacer">&nbsp;</div>
				</p>
				<p style="text-align: center;">					
					<a href="../download/count.php?id={IDURL}"><img src="{MODULE_DATA_PATH}/images/{LANG}/bouton_dl.gif" alt="" /></a>
				</p>
				&nbsp;
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				# INCLUDE handle_note #
			</div>
		</div>		
		<br /><br />
		# INCLUDE handle_com #
		# ENDIF #


		# IF C_DISPLAY_DOWNLOAD_NOTE #
		<form action="../download/download{U_DOWNLOAD_ACTION_NOTE}" method="post" class="fieldset_content">
			<span id="note"></span>
			<fieldset>
				<legend>{L_NOTE}</legend>
				<dl>
					<dt><label for="note_select">{L_NOTE}</label></dt>
					<dd>
						<span class="text_small">{L_ACTUAL_NOTE}: {NOTE}</span>	
						<label>
							<select id="note_select" name="note">
								{SELECT}
							</select>
						</label>
					</dd>					
				</dl>
			</fieldset>
			<fieldset class="fieldset_submit">
				<legend>{L_VOTE}</legend>
				<input type="submit" name="valid_note" value="{L_VOTE}" class="submit" />
			</fieldset>
		</form>
		# ENDIF #

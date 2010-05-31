	{HEADER}
	<script type="text/javascript">
	<!--
	function close_popup()
	{
		opener=self;
		self.close();
	}
	-->
	</script>
	<br />		
	<table class="module_table">
		<tr> 
			<th>
				{L_FILES_MANAGEMENT}
			</th>
		</tr>							
		<tr> 
			<td class="row2">
				<a href="upload.php?root=1{POPUP}"><img src="../templates/{THEME}/images/upload/home.png" class="valign_middle" alt="" /></a>
				<a href="upload.php?root=1{POPUP}">{L_ROOT}</a>
			</td>
		</tr>							
		<tr> 
			<td class="row3" style="margin:0px;padding:0px">
				<div style="float:left;padding:2px;padding-left:8px;">
					{L_URL}
				</div>
				<div style="float:right;width:90%;padding:2px;background:#f3f3ee;padding-left:6px;color:black;border:1px solid #7f9db9;">
						<img src="../templates/{THEME}/images/upload/folder_mini.png" class="valign_middle" alt="" /> {U_ROOT}{URL}
				</div>
			</td>
		</tr>			
		<tr>	
			<td class="row2" style="padding:20px;">							
				<br />
				<form action="{TARGET}" method="post">
				<table class="module_table">
					# IF C_ERROR_HANDLER #
					<tr>
						<td class="row1" colspan="3">
							<span id="errorh"></span>
							<div class="{ERRORH_CLASS}">
								<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
							</div>
						</td>
					</tr>
					# ENDIF #
					<tr> 
						<td class="row1" style="width:210px;">
							# START folder #	
							<table style="border:0;width:210px;">
								<tr>
									<td style="width:34px;vertical-align:top;">
										<img src="../templates/{THEME}/images/upload/folder_max.png" alt="" />
									</td>
									<td style="padding-top:8px;">						
										{folder.NAME}										
									</td>
								</tr>						
							</table>
				
							# END folder #
							
							# START file #							
							<table style="border:0;width:210px;">
								<tr>
									<td style="width:100px;vertical-align:top;">
										# IF file.C_DISPLAY_REAL_IMG #
										<img src="{PATH_TO_ROOT}/upload/{file.FILE_ICON}" alt="" style="width:100px;height:auto;" />
										# ELSE #
										<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/{file.FILE_ICON}" alt="" />
										# ENDIF #
									</td>
									<td style="padding-top:8px;">						
										{file.NAME}	
										<br />
										<span class="text_small">{file.FILETYPE}</span><br />
										<span class="text_small">{file.SIZE}</span><br />								
									</td>
								</tr>						
							</table>
							# END file #
						</td>
						<td class="row1" style="text-align:center;width:100px;">
							<strong>{L_MOVE_TO}</strong>
							<br />
							<img src="../templates/{THEME}/images/right.png" alt="" />
						</td>
						<td class="row1">
							<script type="text/javascript" src="../kernel/lib/js/upload.js">
							</script>
							<script type="text/javascript">
							<!--
								var path = '../templates/{THEME}';
								var selected_cat = {SELECTED_CAT};
							-->
							</script>
							<span style="padding-left:17px;"><a href="javascript:select_cat(0);"><img src="../templates/{THEME}/images/upload/cat_root.png" alt="" /> <span id="class_0" class="{CAT_0}">{L_ROOT}</span></a></span>
							<br />
							{FOLDERS}
						</td>
					</tr>	
				</table>
				<br />
				<input type="hidden" name="new_cat" id="id_cat" value="{SELECTED_CAT}" />
				<p style="text-align:center;"><input type="submit" class="submit" value="{L_SUBMIT}" name="valid" /></p>
				</form>
			</td>
		</tr>		
		<tr> 
			<th>
				&nbsp;{CLOSE}				
			</th>
		</tr>
	</table>
	{FOOTER}
	
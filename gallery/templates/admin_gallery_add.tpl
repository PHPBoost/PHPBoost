		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_GALLERY_MANAGEMENT}</li>
				<li>
					<a href="admin_gallery.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery.php" class="quick_link">{L_GALLERY_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_gallery_add.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_add.php" class="quick_link">{L_GALLERY_PICS_ADD}</a>
				</li>
				<li>
					<a href="admin_gallery_cat.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_cat.php" class="quick_link">{L_GALLERY_CAT_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_gallery_cat_add.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_cat_add.php" class="quick_link">{L_GALLERY_CAT_ADD}</a>
				</li>
				<li>
					<a href="admin_gallery_config.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_config.php" class="quick_link">{L_GALLERY_CONFIG}</a>
				</li>
			</ul>
		</div>
						 
		<div id="admin_contents">
					
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
			</div>
			# ENDIF #
				
			<form method="post" enctype="multipart/form-data" class="fieldset_content">
				<fieldset>
					<legend>{L_ADD_IMG}</legend>
					# START image_up #				
						<div style="text-align:center;">
							<strong>{image_up.L_SUCCESS_UPLOAD}</strong>
							<br /><br />
							<strong>{image_up.NAME}</strong>
							<br />
							{image_up.IMG}
							<br />
							{image_up.U_CAT}
						</div>
					# END image_up #
					{L_AUTH_EXTENSION}: <strong>{AUTH_EXTENSION} </strong>
					<br /><br />
					{L_WIDTH_MAX}: {WIDTH_MAX} {L_UNIT_PX}
					<br />
					{L_HEIGHT_MAX}: {HEIGHT_MAX} {L_UNIT_PX}
					<br />
					{L_WEIGHT_MAX}: {WEIGHT_MAX} {L_UNIT_KO}

					<br /><br />
					
					<dl>
						<dt><label for="category">{L_CATEGORY}</label></dt>
						<dd><label>
							<select name="idcat_post" id="category">
								{CATEGORIES}
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="name">{L_NAME}</label></dt>
						<dd><label><input type="text" size="40" maxlength="50" name="name" id="name" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="gallery">{L_UPLOAD_IMG}</label></dt>
						<dd><label><input type="file" name="gallery" id="gallery" size="30" class="file" /></label></dd>
					</dl>
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPLOAD_IMG}</legend>
					<input type="hidden" name="max_file_size" value="2000000" />
					<input type="submit" value="{L_UPLOAD_IMG}" class="submit" />
				</fieldset>	
			</form>
			
			<form action="admin_gallery_add.php?token={TOKEN}" method="post">
				<table class="module_table">
					<tr> 
						<th colspan="2">
							{L_IMG_DISPO_GALLERY}
						</th>
					</tr>
					<tr> 
						<td class="row1" colspan="2">
							{L_REQUIRE}
						</td>
					</tr>
					<tr> 
						<td class="row1" colspan="2">
							{L_SELECT_IMG_ADD}
						</td>
					</tr>
					<tr> 	
						<td>
							# START list #
								<div style="text-align:center;width:{COLUMN_WIDTH_PICS}%;float:left;">
									<table class="module_table" style="width:100%;">
										<tr>
											<td class="row2">												
												<input type="checkbox" checked="checked" name="{list.ID}activ" value="1" class="valign_middle" /> <span class="text_small">{L_SELECT}</span>
											</td>
										</tr>
										<tr>
											<td class="row2" style="height:{IMG_HEIGHT_MAX}px;padding:3px;text-align:center;">
												{list.THUMNAILS}
											</td>
										</tr>
										<tr>
											<td class="row2">
												<span class="text_small">{L_NAME}: </span>
												<input type="text" size="25" name="{list.ID}name" value="{list.NAME}" class="text valign_middle" />
												
												<input type="hidden" name="{list.ID}uniq" value="{list.UNIQ_NAME}" />
											</td>
										</tr>
										<tr>
											<td class="row2">
												<span class="text_small">{L_CAT}:</span> 
												<select name="{list.ID}cat" class="valign_middle">
													{list.CATEGORIES}
												</select>
											</td>
										</tr>
										<tr>
											<td class="row2">												
												<input type="checkbox" name="{list.ID}del" value="1" class="valign_middle" /> <span class="text_small">{L_DELETE}</span> 
											</td>
										</tr>
									</table>	
							</div>
							# END list #
														
							# START no_img #						
							<p style="text-align:center" class="row1">
								<strong>{no_img.L_NO_IMG}</strong>
							</p>
							# END no_img #
						</td>
					</tr>
				</table>
				
				<br /><br />
				
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="nbr_pics" value="{NBR_PICS}" />
					<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
				</fieldset>			
			</form>
		</div>	
		
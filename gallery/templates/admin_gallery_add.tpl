		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_GALLERY_MANAGEMENT}</li>
				<li>
					<a href="admin_gallery_cat.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_cat.php" class="quick-link">{L_GALLERY_CAT_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_gallery_cat_add.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_cat_add.php" class="quick-link">{L_GALLERY_CAT_ADD}</a>
				</li>
				<li>
					<a href="admin_gallery.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery.php" class="quick-link">{L_GALLERY_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_gallery_add.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_add.php" class="quick-link">{L_GALLERY_PICS_ADD}</a>
				</li>
				<li>
					<a href="admin_gallery_config.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_config.php" class="quick-link">{L_GALLERY_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<div id="admin-contents">
			
			# INCLUDE message_helper #
			
			<form method="post" enctype="multipart/form-data" class="fieldset-content">
				<fieldset>
					<legend>{L_ADD_IMG}</legend>
					# START image_up #
						<div class="center">
							<strong>{image_up.L_SUCCESS_UPLOAD}</strong>
							
							<div class="spacer">&nbsp;</div>
							
							<strong>{image_up.NAME}</strong>
							<div class="spacer"></div>
							{image_up.IMG}
							<div class="spacer"></div>
							{image_up.U_CAT}
						</div>
					# END image_up #
					<span>{L_AUTH_EXTENSION}: <strong>{AUTH_EXTENSION} </strong></span>
					
					<div class="spacer">&nbsp;</div>
					
					<span>{L_WIDTH_MAX}: {WIDTH_MAX} {L_UNIT_PX}</span>
					<div class="spacer"></div>
					<span>{L_HEIGHT_MAX}: {HEIGHT_MAX} {L_UNIT_PX}</span>
					<div class="spacer"></div>
					<span>{L_WEIGHT_MAX}: {WEIGHT_MAX} {L_UNIT_KO}</span>

					<div class="spacer">&nbsp;</div>
					
					<div class="form-element">
						<label for="category">{L_CATEGORY}</label>
						<div class="form-field">
							<select name="idcat_post" id="category">
								{CATEGORIES}
							</select>
						</div>
					</div>
					<div class="form-element">
						<label for="name">{L_NAME}</label>
						<div class="form-field"><label><input type="text" size="40" maxlength="50" name="name" id="name"></label></div>
					</div>
					<div class="form-element">
						<label for="gallery">{L_UPLOAD_IMG}</label>
						<div class="form-field"><label><input type="file" name="gallery" id="gallery" size="30" class="file"></label></div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_UPLOAD_IMG}</legend>
					<input type="hidden" name="max_file_size" value="2000000">
					<button type="submit" name="" value="true" class="submit">{L_UPLOAD_IMG}</button>
				</fieldset>
			</form>
			
			<form action="admin_gallery_add.php?token={TOKEN}" method="post">
				<fieldset>
					<legend>{L_IMG_DISPO_GALLERY}</legend>
					
					# IF C_IMG #
					<table style="table-layout:fixed">
						<tbody>
							# START list #
							{list.TR_START}
								<td style="vertical-align:bottom;width:{COLUMN_WIDTH_PICS}%;">
									<div class="smaller">
										<div style="padding:0 5px;">
										{list.THUMNAILS}
										</div>
										<div class="spacer">&nbsp;</div>
										<div>
											{L_NAME}
											<div class="spacer"></div>
											<input type="text" size="25" name="{list.ID}name" value="{list.NAME}">
											<input type="hidden" name="{list.ID}uniq" value="{list.UNIQ_NAME}">
										</div>
										<div class="spacer">&nbsp;</div>
										<div>
											{L_CAT}
											<div class="spacer"></div>
												<select name="{list.ID}cat" id="{list.ID}cat" style="max-width:100%;">
													{list.CATEGORIES}
												</select>
										</div>
										<div class="spacer">&nbsp;</div>
										<div class="right">
											{L_SELECT} <input type="checkbox" checked="checked" name="{list.ID}activ" value="1">
											<div class="spacer"></div>
											{L_DELETE} <input type="checkbox" name="{list.ID}del" value="1">
										</div>
									</div>
								</td>
							{list.TR_END}
							# END list #
							
							# START end_td_pics #
								{end_td_pics.TD_END}
								
							{end_td_pics.TR_END}
							# END end_td_pics #
						</tbody>
					</table>
					
					<div class="spacer">&nbsp;</div>
					
					<div class="form-element">
						<label for="root_cat">{L_GLOBAL_CAT_SELECTION} <spa class="field-description">{L_GLOBAL_CAT_SELECTION_EXPLAIN}</span></span></label>
						<div class="form-field">
							<select name="root_cat" id="root_cat">
								{CATEGORIES}
							</select>
							<script>
							$('root_cat').observe('change', function() {
								root_value = $('root_cat').value;
								# START list #
								$('{list.ID}cat').value = root_value;
								# END list #
							});
							</script>
						</div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="nbr_pics" value="{NBR_PICS}">
					<button type="submit" name="valid" value="true" class="submit">{L_SUBMIT}</button>
				# ELSE #
					<div class="notice">{L_NO_IMG}</div>
				# ENDIF #
				</fieldset>
			</form>
		</div>
		
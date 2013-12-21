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
					
			# INCLUDE message_helper #
				
			<form method="post" enctype="multipart/form-data" class="fieldset-content">
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
					<button type="submit" name="" value="true">{L_UPLOAD_IMG}</button>
				</fieldset>	
			</form>
			
			<form action="admin_gallery_add.php?token={TOKEN}" method="post">
				<fieldset>
					<legend>{L_IMG_DISPO_GALLERY}</legend>
					
					<div class="message-helper notice">
						<i class="icon-notice"></i>
						<div class="message-helper-content">{L_REQUIRE}<br />{L_SELECT_IMG_ADD}</div>
					</div>

					<div class="form-element">
						<label for="category" style="text-align:right;vertical-align: middle;">{L_CAT}</label>
						<div class="form-field">
							<select name="root_cat" id="root_cat" class="valign-middle">
								{CATEGORIES}
							</select>
							<script type="text/javascript">
							$('root_cat').observe('change', function() {
								root_value = $('root_cat').value;
								# START list #
								$('{list.ID}cat').value = root_value;
								# END list #
							});
							</script>
						</div>
					</div>

					# START list #
						<div style="text-align:center;width:{COLUMN_WIDTH_PICS}%;float:left;">
							<table>
								<thead>
									<tr>
										<th>
											<div class="form-element" style="margin:0px">
												<label for="category" style="text-align:right;vertical-align: middle; width:auto; padding-right:0xp;">{L_NAME}</label>
												<div class="form-field" style="width:auto;">
													<input type="text" size="25" name="{list.ID}name" value="{list.NAME}" class="text valign-middle">
													<input type="hidden" name="{list.ID}uniq" value="{list.UNIQ_NAME}">
												</div>
											</div>
										</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>												
											<div class="form-element" style="margin:0px">
												<label for="category" style="text-align:right;vertical-align: middle; width:25%; padding-right:0xp;">{L_SELECT}</label>
												<div class="form-field" style="width:auto;">
													<input type="checkbox" checked="checked" name="{list.ID}activ" value="1" class="valign-middle">
												</div>
											</div>
											<div class="form-element" style="margin:0px">
												<label for="category" style="text-align:right;vertical-align: middle; width:25%; padding-right:0xp;">{L_DELETE}</label>
												<div class="form-field" style="width:auto;">
													<input type="checkbox" name="{list.ID}del" value="1" class="valign-middle">
												</div>
											</div>
										</th>
									</tr>
								</tfoot>
								<tbody>
									<tr>
										<td style="height:{IMG_HEIGHT_MAX}px;padding:3px;text-align:center;">
											{list.THUMNAILS}
										</td>
									</tr>
									<tr>
										<td>
											<div class="form-element" style="margin:0px">
												<label for="category" style="text-align:right;vertical-align: middle; width:auto; padding-right:0xp;">{L_CAT}</label>
												<div class="form-field">
													<select name="{list.ID}cat" id="{list.ID}cat" class="valign-middle" style="width:{SELECTBOX_WIDTH}%">
														{list.CATEGORIES}
													</select>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>	
						</div>
					# END list #
														
					# START no_img #						
					<div class="message-helper notice">
						<i class="icon-notice"></i>
						<div class="message-helper-content">{no_img.L_NO_IMG}</div>
					</div>
					# END no_img #
				
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="nbr_pics" value="{NBR_PICS}">
					<button type="submit" name="valid" value="true">{L_SUBMIT}</button>
				</fieldset>			
			</form>
		</div>	
		
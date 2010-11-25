		<form action="gallery{U_GALLERY_ACTION_ADD}" method="post" enctype="multipart/form-data">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top">
					<div style="float:left">
						<a href="gallery.php{SID}">{L_GALLERY}</a> &raquo; {U_GALLERY_CAT_LINKS} {ADD_PICS}
					</div>
					<div style="float:right">
						{PAGINATION}
					</div>
				</div>
				<div class="module_contents">
					<div style="text-align:center">
						# IF C_ERROR_HANDLER #
							<span id="errorh"></span>
							<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
								<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
								<br />	
							</div>
							<br />	
						# ENDIF #
		
						# START image_up #								
						<strong>{image_up.L_SUCCESS_UPLOAD}</strong>
						<br />
						<strong>{image_up.NAME}</strong>
						<br />
						{image_up.IMG}
						<br />
						{image_up.U_CAT}
						<br /><br />
						# END image_up #
						
						# START image_quota #	
						<div class="row3" style="width:50%;margin:auto">
							<strong>{image_quota.L_IMAGE_QUOTA}</strong>
						</div>
						# END image_quota #
						
						<br />
						{L_IMG_FORMAT}: {IMG_FORMAT} 
						<br />
						{L_WIDTH_MAX}: {WIDTH_MAX} {L_UNIT_PX}
						<br />
						{L_HEIGHT_MAX}: {HEIGHT_MAX} {L_UNIT_PX}
						<br />
						{L_WEIGHT_MAX}: {WEIGHT_MAX} {L_UNIT_KO}
			
						<br /><br />
						<label>{L_CATEGORIES}: 
						<select name="cat">
							{CATEGORIES}
						</select></label>
						<br /><br />
						<label>{L_NAME}: <input type="text" size="40" maxlength="50" name="name" class="text" /></label>
						<br /><br />
						  					
						* <input type="file" name="gallery" size="30" class="file" /><br /><br />
					</div>
					<br />
					<fieldset class="fieldset_submit">
					<legend></legend><input type="hidden" name="max_file_size" value="2000000" />
					<input type="submit" name="vupload" value="{L_UPLOAD}" class="submit" />
					</fieldset>
				</div>
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom">
					<div style="float:left" class="text_strong">
						<a href="gallery.php{SID}">{L_GALLERY}</a> &raquo; {U_GALLERY_CAT_LINKS} {ADD_PICS}
					</div>
					<div style="float:right">
						{PAGINATION}
					</div>
				</div>
			</div>
		</form>

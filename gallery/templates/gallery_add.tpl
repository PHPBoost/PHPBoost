		<form action="{U_GALLERY_ACTION_ADD}" method="post" enctype="multipart/form-data">
			<section id="module-gallery-add">
				<header>
					<h1>{L_GALLERY} - {L_ADD_IMG}</h1>
					<div class="right">
						{PAGINATION}
					</div>
				</header>
				<div class="content">
					<div class="center">
						# INCLUDE message_helper #
		
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
						<div class="image-quota-container">
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
						{CATEGORIES_TREE}
						<br /><br />
						<label>{L_NAME}: <input type="text" maxlength="50" name="name"></label>
						<br /><br />
						
						* <input type="file" name="gallery" class="file" /><br /><br />
					</div>
					<br />
					<fieldset class="fieldset-submit">
						<legend></legend><input type="hidden" name="max_file_size" value="2000000">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="vupload" value="true" class="submit">{L_UPLOAD}</button>
					</fieldset>
				</div>
				<footer>
					<div class="right">
						{PAGINATION}
					</div>
				</footer>
			</section>
		</form>

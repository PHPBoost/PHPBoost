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
						<span class="spacer"></span>
						<strong>{image_up.NAME}</strong>
						<span class="spacer"></span>
						<a href="gallery.php?cat={image_up.ID_CAT}&amp;id={image_up.ID}#pics_max"><img src="pics/{image_up.PATH}" alt="{image_up.NAME}" /></a>
						<span class="spacer"></span>
						<a href="gallery.php?cat={image_up.ID_CAT}">{image_up.CAT_NAME}</a>
						# END image_up #

						# START image_quota #
						<div class="image-quota-container">
							<strong>{image_quota.L_IMAGE_QUOTA}</strong>
						</div>
						# END image_quota #

						<span class="spacer"></span>
						{L_IMG_FORMAT}: {IMG_FORMAT}
						<span class="spacer"></span>
						{L_WIDTH_MAX}: {WIDTH_MAX} {L_UNIT_PX}
						<span class="spacer"></span>
						{L_HEIGHT_MAX}: {HEIGHT_MAX} {L_UNIT_PX}
						<span class="spacer"></span>
						{L_WEIGHT_MAX}: {WEIGHT_MAX} {L_UNIT_KO}

						{CATEGORIES_TREE}
						<label>{L_NAME} plop: <input type="text" maxlength="50" name="name"></label>
						* <input type="file" name="gallery" class="file" />
					</div>
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

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
			<form action="admin_gallery_cat.php?del={IDCAT}&amp;token={TOKEN}" method="post" class="fieldset-content">
				# START pics #
				<fieldset>
					<legend>{pics.L_KEEP}</legend>
					<div class="notice">
						{pics.L_EXPLAIN_CAT}
					</div>
					<br />
					<div class="form-element">
						<label for="t_to">{pics.L_MOVE_PICS}</label>
						<div class="form-field"><label>
							<select id="t_to" name="t_to">
								{pics.GALLERIES}
							</select>
						</label></div>
					</div>
				</fieldset>
				# END pics #
				
				# START subgalleries #
				<fieldset>
					<legend>{subgalleries.L_KEEP}</legend>
					<div class="warning">{subgalleries.L_EXPLAIN_CAT}</div>

					<div class="form-element">
						<label for="f_to">{subgalleries.L_MOVE_GALLERIES}</label>
						<div class="form-field"><label>
							<select id="f_to" name="f_to">
								{subgalleries.GALLERIES}
							</select>
						</label></div>
					</div>
				</fieldset>
				# END subgalleries #
				
				<fieldset>
					<legend>{L_DEL_ALL}</legend>
					<div class="form-element">
						<label for="del_conf">{L_DEL_GALLERY_CONTENTS}</label>
						<div class="form-field"><label><input type="checkbox" name="del_conf" id="del_conf"></label></div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<button type="submit" name="del_cat" value="true">{L_SUBMIT}</button>
				</fieldset>
			</form>
		</div>
		
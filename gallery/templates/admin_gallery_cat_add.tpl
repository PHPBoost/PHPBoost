		<script>
		<!--
			function check_form_list()
			{
				if(document.getElementById('name').value == "") {
					alert("{L_REQUIRE}");
					return false;
				}
				return true;
			}
		-->
		</script>
		
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
			
			<form action="admin_gallery_cat_add.php?token={TOKEN}" method="post" onsubmit="return check_form_list();" class="fieldset-content">
				<fieldset>
					<legend>{L_GALLERY_CAT_ADD}</legend>
					<p class="center">{L_REQUIRE}</p>
					<div class="form-element">
						<label for="category">{L_PARENT_CATEGORY}</label>
						<div class="form-field"><label>
							<select name="category" id="category" onchange="change_parent_cat(this.options[this.selectedIndex].value)">
								{CATEGORIES}
							</select>
						</label></div>
					</div>
					<div class="form-element">
						<label for="name">* {L_NAME}</label>
						<div class="form-field"><label><input type="text" maxlength="100" size="35" id="name" name="name"></label></div>
					</div>
					<div class="form-element">
						<label for="desc">{L_DESC}</label>
						<div class="form-field"><label><input type="text" maxlength="150" size="35" name="desc" id="desc"></label></div>
					</div>
					<div class="form-element">
						<label for="aprob">{L_APROB}</label>
						<div class="form-field"><label>
							<label><input type="radio" name="aprob" id="aprob" checked="checked" value="1"> {L_YES}</label>
							<label><input type="radio" name="aprob" value="0"> {L_NO}</label>
						</label></div>
					</div>
					<div class="form-element">
						<label for="status">{L_STATUS}</label>
						<div class="form-field"><label>
							<label><input type="radio" name="status" id="status" checked="checked" value="1"> {L_UNLOCK}</label>
							<label><input type="radio" name="status" value="0"> {L_LOCK}</label>
						</label></div>
					</div>
					<div class="form-element">
						<label>{L_AUTH_READ}</label>
						<div class="form-field">{AUTH_READ}</div>
					</div>
					<div class="form-element">
						<label>{L_AUTH_WRITE}</label>
						<div class="form-field"><label>{AUTH_WRITE}</div>
					</div>
					<div class="form-element">
						<label>{L_AUTH_EDIT}</label>
						<div class="form-field">{AUTH_EDIT}</div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_ADD}</legend>
					<button type="submit" name="add" value="true">{L_ADD}</button>
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
		</div>
		
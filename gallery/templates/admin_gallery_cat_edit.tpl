		<script type="text/javascript">
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
				
			<form action="admin_gallery_cat.php?id={ID}&amp;token={TOKEN}" method="post" onsubmit="return check_form_list();" class="fieldset_content">
				<fieldset>
					<legend>{L_EDIT_CAT}</legend>
					<p>{L_REQUIRE}</p>
					<div class="form-element">
						<label for="category">* {L_PARENT_CATEGORY}</label>
						<div class="form-field"><label>
							<select name="category" id="category" onchange="change_parent_cat(this.options[this.selectedIndex].value)">
								{CATEGORIES}
							</select>
						</label></div>
					</div>
					<div class="form-element">
						<label for="name">* {L_NAME}</label>
						<div class="form-field"><label><input type="text" maxlength="100" size="35" id="name" name="name" value="{NAME}"></label></div>
					</div>
					<div class="form-element">
						<label for="desc">{L_DESC}</label>
						<div class="form-field"><label><input type="text" maxlength="150" size="35" name="desc" value="{DESC}" id="desc"></label></div>
					</div>
					<div class="form-element">
						<label for="aprob">{L_APROB}</label>
						<div class="form-field"><label>
							<label><input type="radio" name="aprob" id="aprob" {CHECKED_APROB} value="1"> {L_YES}</label>
							<label><input type="radio" name="aprob" {UNCHECKED_APROB} value="0"> {L_NO}</label>
						</label></div>
					</div>
					<div class="form-element">
						<label for="status">{L_STATUS}</label>
						<div class="form-field"><label>
							<label><input type="radio" name="status" {CHECKED_STATUS}  id="status" checked="checked" value="1"> {L_UNLOCK}</label>
							<label><input type="radio" name="status" {UNCHECKED_STATUS}  value="0"> {L_LOCK}</label>
						</label></div>
					</div>
					<div class="form-element">
						<label>{L_AUTH_READ}</label>
						<div class="form-field">{AUTH_READ}</div>
					</div>
					<div class="form-element">
						<label>{L_AUTH_WRITE}</label>
						<div class="form-field">{AUTH_WRITE}</div>
					</div>
					<div class="form-element">
						<label>{L_AUTH_EDIT}</label>
						<div class="form-field">{AUTH_EDIT}</div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<button type="submit" name="valid" value="true">{L_UPDATE}</button>
					&nbsp;&nbsp; 
					<button type="reset" value="true">{L_RESET}</button>	
				</fieldset>		
			</form>
		</div>
			
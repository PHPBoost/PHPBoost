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
				
			<form action="admin_gallery_cat_add.php?token={TOKEN}" method="post" onsubmit="return check_form_list();" class="fieldset_content">
				<fieldset>
					<legend>{L_GALLERY_CAT_ADD}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="category">* {L_PARENT_CATEGORY}</label></dt>
						<dd><label>
							<select name="category" id="category" onchange="change_parent_cat(this.options[this.selectedIndex].value)">
								{CATEGORIES}
							</select>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="name">* {L_NAME}</label></dt>
						<dd><label><input type="text" maxlength="100" size="35" id="name" name="name" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="desc">{L_DESC}</label></dt>
						<dd><label><input type="text" maxlength="150" size="35" name="desc" id="desc" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="aprob">{L_APROB}</label></dt>
						<dd><label>
							<label><input type="radio" name="aprob" id="aprob" checked="checked" value="1" /> {L_YES}</label>
							<label><input type="radio" name="aprob" value="0" /> {L_NO}</label>
						</label></dd>
					</dl>
					<dl>
						<dt><label for="status">{L_STATUS}</label></dt>
						<dd><label>
							<label><input type="radio" name="status" id="status" checked="checked" value="1" /> {L_UNLOCK}</label>
							<label><input type="radio" name="status" value="0" /> {L_LOCK}</label>
						</label></dd>
					</dl>
					<dl>
						<dt><label>{L_AUTH_READ}</label></dt>
						<dd><label>{AUTH_READ}</label></dd>
					</dl>
					<dl>
						<dt><label>{L_AUTH_WRITE}</label></dt>
						<dd><label>{AUTH_WRITE}</label></dd>
					</dl>
					<dl>
						<dt><label>{L_AUTH_EDIT}</label></dt>
						<dd><label>{AUTH_EDIT}</label></dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_ADD}</legend>
					<input type="submit" name="add" value="{L_ADD}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />	
				</fieldset>	
			</form>
		</div>
		
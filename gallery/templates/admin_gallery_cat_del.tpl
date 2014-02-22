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
			<form method="post" action="admin_gallery_cat.php?del={IDCAT}&amp;token={TOKEN}" onsubmit="javascript:return check_form_select();" class="fieldset_content">
				# START pics #
				<fieldset>
					<legend>{pics.L_KEEP}</legend>
					<div class="error_warning" style="width:500px;margin:auto;padding:15px;">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/important.png" alt="" style="float:left;padding-right:6px;" /> &nbsp;{pics.L_EXPLAIN_CAT}
						<br />	
					</div>
					<br />	
					<dl>
						<dt><label for="t_to">{pics.L_MOVE_PICS}</label></dt>
						<dd><label>
							<select id="t_to" name="t_to">
								{pics.GALLERIES}
							</select>
						</label></dd>
					</dl>
				</fieldset>			
				# END pics #
				
				# START subgalleries #
				<fieldset>
					<legend>{subgalleries.L_KEEP}</legend>
					<div class="error_warning" style="width:500px;margin:auto;padding:15px;">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/important.png" alt="" style="float:left;padding-right:6px;" /> &nbsp;{subgalleries.L_EXPLAIN_CAT}
						<br />	
					</div>
					<br />	
					<dl>
						<dt><label for="f_to">{subgalleries.L_MOVE_GALLERIES}</label></dt>
						<dd><label>
							<select id="f_to" name="f_to">
								{subgalleries.GALLERIES}
							</select>
						</label></dd>
					</dl>
				</fieldset>			
				# END subgalleries #
				
				<fieldset>
					<legend>{L_DEL_ALL}</legend>
					<dl>
						<dt><label for="del_conf">{L_DEL_GALLERY_CONTENTS}</label></dt>
						<dd><label><input type="checkbox" name="del_conf" id="del_conf" /></label></dd>
					</dl>
				</fieldset>	
				
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="submit" name="del_cat" value="{L_SUBMIT}" class="submit" />
				</fieldset>
			</form>
		</div>
		
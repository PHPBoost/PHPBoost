		<script type="text/javascript">
		<!--
		function change_upload_level( level ) 
		{
			if( level == -1 )
			{
				document.getElementById('limit_member').innerHTML = '<?php echo isset($this->_var['L_UNLIMITED']) ? $this->_var['L_UNLIMITED'] : ''; ?>';
				document.getElementById('limit_modo').innerHTML = '<?php echo isset($this->_var['L_UNLIMITED']) ? $this->_var['L_UNLIMITED'] : ''; ?>';			
			}
			else if( level == 1 )
			{
				document.getElementById('limit_member').innerHTML = '<?php echo isset($this->_var['L_UNAUTH']) ? $this->_var['L_UNAUTH'] : ''; ?>';
				document.getElementById('limit_modo').innerHTML = '<input type="text" size="5" name="limit_modo" value="<?php echo isset($this->_var['LIMIT_MODO']) ? $this->_var['LIMIT_MODO'] : ''; ?>" class="text" />';
			}
			else if( level == 2 )
			{
				document.getElementById('limit_member').innerHTML = '<?php echo isset($this->_var['L_UNAUTH']) ? $this->_var['L_UNAUTH'] : ''; ?>';
				document.getElementById('limit_modo').innerHTML = '<?php echo isset($this->_var['L_UNAUTH']) ? $this->_var['L_UNAUTH'] : ''; ?>';			
			}
			else
			{
				document.getElementById('limit_member').innerHTML = '<input type="text" size="5" name="limit_member" value="<?php echo isset($this->_var['LIMIT_MEMBER']) ? $this->_var['LIMIT_MEMBER'] : ''; ?>" class="text" />';
				document.getElementById('limit_modo').innerHTML = '<input type="text" size="5" name="limit_modo" value="<?php echo isset($this->_var['LIMIT_MODO']) ? $this->_var['LIMIT_MODO'] : ''; ?>" class="text" />';		
			}
		}
		
		function check_form(){
			if(document.getElementById('height_max').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_HEIGHT_MAX']) ? $this->_var['L_REQUIRE_HEIGHT_MAX'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('width_max').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_WIDTH_MAX']) ? $this->_var['L_REQUIRE_WIDTH_MAX'] : ''; ?>");
				return false;
		    }
				if(document.getElementById('height').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_HEIGHT']) ? $this->_var['L_REQUIRE_HEIGHT'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('width').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_WIDTH']) ? $this->_var['L_REQUIRE_WIDTH'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('nbr_column').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_ROW']) ? $this->_var['L_REQUIRE_ROW'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('nbr_pics_max').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_IMG_P']) ? $this->_var['L_REQUIRE_IMG_P'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('quality').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_QUALITY']) ? $this->_var['L_REQUIRE_QUALITY'] : ''; ?>V");
				return false;
		    }
			if(document.getElementById('weight_max').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_WEIGHT_MAX']) ? $this->_var['L_REQUIRE_WEIGHT_MAX'] : ''; ?>");
				return false;
		    }
			
			
			return true;
		}

		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_GALLERY_MANAGEMENT']) ? $this->_var['L_GALLERY_MANAGEMENT'] : ''; ?></li>
				<li>
					<a href="admin_gallery.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery.php" class="quick_link"><?php echo isset($this->_var['L_GALLERY_MANAGEMENT']) ? $this->_var['L_GALLERY_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_gallery_add.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_add.php" class="quick_link"><?php echo isset($this->_var['L_GALLERY_PICS_ADD']) ? $this->_var['L_GALLERY_PICS_ADD'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_gallery_cat.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_cat.php" class="quick_link"><?php echo isset($this->_var['L_GALLERY_CAT_MANAGEMENT']) ? $this->_var['L_GALLERY_CAT_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_gallery_cat_add.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_cat_add.php" class="quick_link"><?php echo isset($this->_var['L_GALLERY_CAT_ADD']) ? $this->_var['L_GALLERY_CAT_ADD'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_gallery_config.php"><img src="gallery.png" alt="" /></a>
					<br />
					<a href="admin_gallery_config.php" class="quick_link"><?php echo isset($this->_var['L_GALLERY_CONFIG']) ? $this->_var['L_GALLERY_CONFIG'] : ''; ?></a>
				</li>
			</ul>
		</div>
						 
		<div id="admin_contents">
			<form action="admin_gallery_config.php" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_CONFIG_CONFIG']) ? $this->_var['L_CONFIG_CONFIG'] : ''; ?></legend>
					<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
					<dl>
						<dt><label for="width_max">* <?php echo isset($this->_var['L_HEIGHT_MAX']) ? $this->_var['L_HEIGHT_MAX'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_WIDTH_MAX_EXPLAIN']) ? $this->_var['L_WIDTH_MAX_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="5" id="width_max" name="width_max" value="<?php echo isset($this->_var['WIDTH_MAX']) ? $this->_var['WIDTH_MAX'] : ''; ?>" class="text" /> <?php echo isset($this->_var['L_UNIT_PX']) ? $this->_var['L_UNIT_PX'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label for="height_max">* <?php echo isset($this->_var['L_WIDTH_MAX']) ? $this->_var['L_WIDTH_MAX'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_HEIGHT_MAX_EXPLAIN']) ? $this->_var['L_HEIGHT_MAX_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="5" id="height_max" name="height_max" value="<?php echo isset($this->_var['HEIGHT_MAX']) ? $this->_var['HEIGHT_MAX'] : ''; ?>" class="text" /> <?php echo isset($this->_var['L_UNIT_PX']) ? $this->_var['L_UNIT_PX'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label for="height">* <?php echo isset($this->_var['L_HEIGHT_MAX_THUMB']) ? $this->_var['L_HEIGHT_MAX_THUMB'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_HEIGHT_MAX_THUMB_EXPLAIN']) ? $this->_var['L_HEIGHT_MAX_THUMB_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="5" id="height" name="height" value="<?php echo isset($this->_var['HEIGHT']) ? $this->_var['HEIGHT'] : ''; ?>" class="text" /> <?php echo isset($this->_var['L_UNIT_PX']) ? $this->_var['L_UNIT_PX'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label for="width">* <?php echo isset($this->_var['L_WIDTH_MAX_THUMB']) ? $this->_var['L_WIDTH_MAX_THUMB'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_WIDTH_MAX_THUMB_EXPLAIN']) ? $this->_var['L_WIDTH_MAX_THUMB_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="5" id="width" name="width" value="<?php echo isset($this->_var['WIDTH']) ? $this->_var['WIDTH'] : ''; ?>" class="text" /> <?php echo isset($this->_var['L_UNIT_PX']) ? $this->_var['L_UNIT_PX'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label for="weight_max">* <?php echo isset($this->_var['L_WEIGHT_MAX']) ? $this->_var['L_WEIGHT_MAX'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_WEIGHT_MAX_EXPLAIN']) ? $this->_var['L_WEIGHT_MAX_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="6" id="weight_max" name="weight_max" value="<?php echo isset($this->_var['WEIGHT_MAX']) ? $this->_var['WEIGHT_MAX'] : ''; ?>" class="text" /> <?php echo isset($this->_var['L_UNIT_KO']) ? $this->_var['L_UNIT_KO'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label for="quality">* <?php echo isset($this->_var['L_QUALITY_THUMB']) ? $this->_var['L_QUALITY_THUMB'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_QUALITY_THUMB_EXPLAIN']) ? $this->_var['L_QUALITY_THUMB_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="3" id="quality" name="quality" value="<?php echo isset($this->_var['QUALITY']) ? $this->_var['QUALITY'] : ''; ?>" class="text" /> %</label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_column">* <?php echo isset($this->_var['L_NBR_COLUMN']) ? $this->_var['L_NBR_COLUMN'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_NBR_COLUMN_EXPLAIN']) ? $this->_var['L_NBR_COLUMN_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="1" maxlength="1" id="nbr_column" name="nbr_column" value="<?php echo isset($this->_var['NBR_COLUMN']) ? $this->_var['NBR_COLUMN'] : ''; ?>" class="text" /> <?php echo isset($this->_var['L_COLUMN']) ? $this->_var['L_COLUMN'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_pics_max">* <?php echo isset($this->_var['L_NBR_PICS_MAX']) ? $this->_var['L_NBR_PICS_MAX'] : ''; ?></label></dt>
						<dd><label><input type="text" size="6" maxlength="6" id="nbr_pics_max" name="nbr_pics_max" value="<?php echo isset($this->_var['NBR_PICS_MAX']) ? $this->_var['NBR_PICS_MAX'] : ''; ?>" class="text" /></label></dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend><?php echo isset($this->_var['L_DISPLAY_OPTION']) ? $this->_var['L_DISPLAY_OPTION'] : ''; ?></legend>
					<dl>
						<dt><label for="display_pics"><?php echo isset($this->_var['L_DISPLAY_MODE']) ? $this->_var['L_DISPLAY_MODE'] : ''; ?></label></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['DISPLAY_PICS_NEW_PAGE']) ? $this->_var['DISPLAY_PICS_NEW_PAGE'] : ''; ?> name="display_pics" id="display_pics" value="0" /> <?php echo isset($this->_var['L_NEW_PAGE']) ? $this->_var['L_NEW_PAGE'] : ''; ?></label><br />
							<label><input type="radio" <?php echo isset($this->_var['DISPLAY_PICS']) ? $this->_var['DISPLAY_PICS'] : ''; ?> name="display_pics" value="1" /> <?php echo isset($this->_var['L_RESIZE']) ? $this->_var['L_RESIZE'] : ''; ?></label><br />
							<label><input type="radio" <?php echo isset($this->_var['DISPLAY_PICS_POPUP']) ? $this->_var['DISPLAY_PICS_POPUP'] : ''; ?> name="display_pics" value="2" /> <?php echo isset($this->_var['L_POPUP']) ? $this->_var['L_POPUP'] : ''; ?></label><br />
							<label><input type="radio" <?php echo isset($this->_var['DISPLAY_PICS_POPUP_FULL']) ? $this->_var['DISPLAY_PICS_POPUP_FULL'] : ''; ?> name="display_pics" value="3" /> <?php echo isset($this->_var['L_POPUP_FULL']) ? $this->_var['L_POPUP_FULL'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_title"><?php echo isset($this->_var['L_TITLE_ENABLED']) ? $this->_var['L_TITLE_ENABLED'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_TITLE_ENABLED_EXPLAIN']) ? $this->_var['L_TITLE_ENABLED_EXPLAIN'] : ''; ?></span></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['TITLE_ENABLED']) ? $this->_var['TITLE_ENABLED'] : ''; ?> name="activ_title" id="activ_title" value="1" /> <?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?></label>
							&nbsp;&nbsp; 
							<label><input type="radio" <?php echo isset($this->_var['TITLE_DISABLED']) ? $this->_var['TITLE_DISABLED'] : ''; ?> name="activ_title" value="0" /> <?php echo isset($this->_var['L_UNACTIV']) ? $this->_var['L_UNACTIV'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_user"><?php echo isset($this->_var['L_IMG_POSTER']) ? $this->_var['L_IMG_POSTER'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_IMG_POSTER_EXPLAIN']) ? $this->_var['L_IMG_POSTER_EXPLAIN'] : ''; ?></span></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['USER_ENABLED']) ? $this->_var['USER_ENABLED'] : ''; ?> name="activ_user" id="activ_user" value="1" /> <?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?></label>
							&nbsp;&nbsp; 
							<label><input type="radio" <?php echo isset($this->_var['USER_DISABLED']) ? $this->_var['USER_DISABLED'] : ''; ?> name="activ_user" value="0" /> <?php echo isset($this->_var['L_UNACTIV']) ? $this->_var['L_UNACTIV'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_view"><?php echo isset($this->_var['L_COMPT_VIEWS']) ? $this->_var['L_COMPT_VIEWS'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_COMPT_VIEWS_EXPLAIN']) ? $this->_var['L_COMPT_VIEWS_EXPLAIN'] : ''; ?></span></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['VIEW_ENABLED']) ? $this->_var['VIEW_ENABLED'] : ''; ?> name="activ_view" id="activ_view" value="1" /> <?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?></label>
							&nbsp;&nbsp; 
							<label><input type="radio" <?php echo isset($this->_var['VIEW_DISABLED']) ? $this->_var['VIEW_DISABLED'] : ''; ?> name="activ_view" value="0" /> <?php echo isset($this->_var['L_UNACTIV']) ? $this->_var['L_UNACTIV'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_com"><?php echo isset($this->_var['L_ACTIV_COM']) ? $this->_var['L_ACTIV_COM'] : ''; ?></label></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['COM_ENABLED']) ? $this->_var['COM_ENABLED'] : ''; ?> name="activ_com" id="activ_com" value="1" /> <?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?></label>
							&nbsp;&nbsp; 
							<label><input type="radio" <?php echo isset($this->_var['COM_DISABLED']) ? $this->_var['COM_DISABLED'] : ''; ?> name="activ_com" value="0" /> <?php echo isset($this->_var['L_UNACTIV']) ? $this->_var['L_UNACTIV'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_note"><?php echo isset($this->_var['L_ACTIV_NOTE']) ? $this->_var['L_ACTIV_NOTE'] : ''; ?></label></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['NOTE_ENABLED']) ? $this->_var['NOTE_ENABLED'] : ''; ?> name="activ_note" id="activ_note" value="1" /> <?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?></label>
							&nbsp;&nbsp; 
							<label><input type="radio" <?php echo isset($this->_var['NOTE_DISABLED']) ? $this->_var['NOTE_DISABLED'] : ''; ?> name="activ_note" value="0" /> <?php echo isset($this->_var['L_UNACTIV']) ? $this->_var['L_UNACTIV'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="display_nbrnote"><?php echo isset($this->_var['L_DISPLAY_NBRNOTE']) ? $this->_var['L_DISPLAY_NBRNOTE'] : ''; ?></label></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['NBRNOTE_ENABLED']) ? $this->_var['NBRNOTE_ENABLED'] : ''; ?> name="display_nbrnote" id="display_nbrnote" value="1" /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?></label>
							&nbsp;&nbsp; 
							<label><input type="radio" <?php echo isset($this->_var['NBRNOTE_DISABLED']) ? $this->_var['NBRNOTE_DISABLED'] : ''; ?> name="display_nbrnote" value="0" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="note_max"><?php echo isset($this->_var['L_NOTE_MAX']) ? $this->_var['L_NOTE_MAX'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_NOTE_MAX_EXPLAIN']) ? $this->_var['L_NOTE_MAX_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="3" name="note_max" id="note_max" value="<?php echo isset($this->_var['NOTE_MAX']) ? $this->_var['NOTE_MAX'] : ''; ?>" class="text" /></label></dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend><?php echo isset($this->_var['L_THUMBNAILS_SCROLLING']) ? $this->_var['L_THUMBNAILS_SCROLLING'] : ''; ?></legend>
					<dl>
						<dt><label for="scroll_type"><?php echo isset($this->_var['L_SCROLL_TYPE']) ? $this->_var['L_SCROLL_TYPE'] : ''; ?></label></dt>
						<dd><label>
								<select name="scroll_type" id="scroll_type">
									<?php echo isset($this->_var['SCROLL_TYPES']) ? $this->_var['SCROLL_TYPES'] : ''; ?>
								</select>
							</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="nbr_pics_mini"><?php echo isset($this->_var['L_NBR_PICS_MINI']) ? $this->_var['L_NBR_PICS_MINI'] : ''; ?></label></dt>
						<dd><label><input type="text" size="3" name="nbr_pics_mini" id="nbr_pics_mini" value="<?php echo isset($this->_var['NBR_PICS_MINI']) ? $this->_var['NBR_PICS_MINI'] : ''; ?>" class="text" /> </label></dd>
					</dl>
					<dl>
						<dt><label for="speed_mini_pics"><?php echo isset($this->_var['L_SPEED_MINI_PICS']) ? $this->_var['L_SPEED_MINI_PICS'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_SPEED_MINI_PICS_EXPLAIN']) ? $this->_var['L_SPEED_MINI_PICS_EXPLAIN'] : ''; ?></span></dt>
						<dd>
							<label>
								<select name="speed_mini_pics" id="speed_mini_pics">
								<?php echo isset($this->_var['SPEED_MINI_PICS']) ? $this->_var['SPEED_MINI_PICS'] : ''; ?>
								</select>
							</label>
						</dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend><?php echo isset($this->_var['L_IMG_PROTECT']) ? $this->_var['L_IMG_PROTECT'] : ''; ?></legend>
					<dl>
						<dt><label for="activ_logo"><?php echo isset($this->_var['L_ACTIV_LOGO']) ? $this->_var['L_ACTIV_LOGO'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_ACTIV_LOGO_EXPLAIN']) ? $this->_var['L_ACTIV_LOGO_EXPLAIN'] : ''; ?></span></dt>
						<dd>
							<label><input type="radio" <?php echo isset($this->_var['LOGO_ENABLED']) ? $this->_var['LOGO_ENABLED'] : ''; ?> name="activ_logo" id="activ_logo" value="1" /> <?php echo isset($this->_var['L_ACTIV']) ? $this->_var['L_ACTIV'] : ''; ?></label>
							&nbsp;&nbsp; 
							<label><input type="radio" <?php echo isset($this->_var['LOGO_DISABLED']) ? $this->_var['LOGO_DISABLED'] : ''; ?> name="activ_logo" value="0" /> <?php echo isset($this->_var['L_UNACTIV']) ? $this->_var['L_UNACTIV'] : ''; ?></label>
						</dd>
					</dl>
					<dl>
						<dt><label for="logo"><?php echo isset($this->_var['L_LOGO_URL']) ? $this->_var['L_LOGO_URL'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_LOGO_URL_EXPLAIN']) ? $this->_var['L_LOGO_URL_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="25" name="logo" id="logo" value="<?php echo isset($this->_var['LOGO']) ? $this->_var['LOGO'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="trans"><?php echo isset($this->_var['L_LOGO_TRANS']) ? $this->_var['L_LOGO_TRANS'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_LOGO_TRANS_EXPLAIN']) ? $this->_var['L_LOGO_TRANS_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="3" name="trans" id="trans" value="<?php echo isset($this->_var['TRANS']) ? $this->_var['TRANS'] : ''; ?>" class="text" /> %</label></dd>
					</dl>
					<dl>
						<dt><label for="d_width"><?php echo isset($this->_var['L_WIDTH_BOTTOM_RIGHT']) ? $this->_var['L_WIDTH_BOTTOM_RIGHT'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_WIDTH_BOTTOM_RIGHT_EXPLAIN']) ? $this->_var['L_WIDTH_BOTTOM_RIGHT_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="5" name="d_width" id="d_width" value="<?php echo isset($this->_var['D_WIDTH']) ? $this->_var['D_WIDTH'] : ''; ?>" class="text" /> <?php echo isset($this->_var['L_UNIT_PX']) ? $this->_var['L_UNIT_PX'] : ''; ?></label></dd>
					</dl>
					<dl>
						<dt><label for="d_height"><?php echo isset($this->_var['L_HEIGHT_BOTTOM_RIGHT']) ? $this->_var['L_HEIGHT_BOTTOM_RIGHT'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_HEIGHT_BOTTOM_RIGHT_EXPLAIN']) ? $this->_var['L_HEIGHT_BOTTOM_RIGHT_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="5" name="d_height" id="d_height" value="<?php echo isset($this->_var['D_HEIGHT']) ? $this->_var['D_HEIGHT'] : ''; ?>" class="text" /> <?php echo isset($this->_var['L_UNIT_PX']) ? $this->_var['L_UNIT_PX'] : ''; ?></label></dd>
					</dl>
				</fieldset>	
					
				<fieldset>
					<legend><?php echo isset($this->_var['L_UPLOAD_PICS']) ? $this->_var['L_UPLOAD_PICS'] : ''; ?></legend>
					<dl>
						<dt><label for="limit_member"><?php echo isset($this->_var['L_NUMBER_IMG']) ? $this->_var['L_NUMBER_IMG'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_NUMBER_IMG_EXPLAIN']) ? $this->_var['L_NUMBER_IMG_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="5" name="limit_member" id="limit_member" value="<?php echo isset($this->_var['LIMIT_MEMBER']) ? $this->_var['LIMIT_MEMBER'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="limit_modo"><?php echo isset($this->_var['L_NUMBER_IMG_MODO']) ? $this->_var['L_NUMBER_IMG_MODO'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_NUMBER_IMG_MODO_EXPLAIN']) ? $this->_var['L_NUMBER_IMG_MODO_EXPLAIN'] : ''; ?></span></dt>
						<dd><label><input type="text" size="5" name="limit_modo" id="limit_modo" value="<?php echo isset($this->_var['LIMIT_MODO']) ? $this->_var['LIMIT_MODO'] : ''; ?>" class="text" /></label></dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?></legend>
					<input type="submit" name="valid" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />
							&nbsp;&nbsp; 
							<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />
				</fieldset>
			</form>

			<form action="admin_gallery_config.php" name="form" method="post" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_CACHE']) ? $this->_var['L_CACHE'] : ''; ?></legend>
					<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/admin/cache.png" alt="" style="float:left;padding:6px;" />
					<?php echo isset($this->_var['L_EXPLAIN_GALLERY_CACHE']) ? $this->_var['L_EXPLAIN_GALLERY_CACHE'] : ''; ?>
					<br /><br />
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_EMPTY']) ? $this->_var['L_EMPTY'] : ''; ?></legend>
					<input type="submit" name="gallery_cache" value="<?php echo isset($this->_var['L_EMPTY']) ? $this->_var['L_EMPTY'] : ''; ?>" class="submit" /> 		
				</fieldset>
			</form>
		</div>
		
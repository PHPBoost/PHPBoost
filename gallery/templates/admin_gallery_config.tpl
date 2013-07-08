		<script type="text/javascript">
		<!--
		function change_upload_level( level ) 
		{
			if( level == -1 )
			{
				document.getElementById('limit_member').innerHTML = '{L_UNLIMITED}';
				document.getElementById('limit_modo').innerHTML = '{L_UNLIMITED}';			
			}
			else if( level == 1 )
			{
				document.getElementById('limit_member').innerHTML = '{L_UNAUTH}';
				document.getElementById('limit_modo').innerHTML = '<input type="text" size="5" name="limit_modo" value="{LIMIT_MODO}" class="text" />';
			}
			else if( level == 2 )
			{
				document.getElementById('limit_member').innerHTML = '{L_UNAUTH}';
				document.getElementById('limit_modo').innerHTML = '{L_UNAUTH}';			
			}
			else
			{
				document.getElementById('limit_member').innerHTML = '<input type="text" size="5" name="limit_member" value="{LIMIT_USER}" class="text" />';
				document.getElementById('limit_modo').innerHTML = '<input type="text" size="5" name="limit_modo" value="{LIMIT_MODO}" class="text" />';		
			}
		}
		
		function check_form(){
			if(document.getElementById('height_max').value == "") {
				alert("{L_REQUIRE_MAX_HEIGHT}");
				return false;
		    }
			if(document.getElementById('width_max').value == "") {
				alert("{L_REQUIRE_MAX_WIDTH}");
				return false;
		    }
				if(document.getElementById('mini_max_height').value == "") {
				alert("{L_REQUIRE_MINI_MAX_HEIGHT}");
				return false;
		    }
			if(document.getElementById('mini_max_width').value == "") {
				alert("{L_REQUIRE_MINI_MAX_WIDTH}");
				return false;
		    }
			if(document.getElementById('nbr_column').value == "") {
				alert("{L_REQUIRE_ROW}");
				return false;
		    }
			if(document.getElementById('nbr_pics_max').value == "") {
				alert("{L_REQUIRE_IMG_P}");
				return false;
		    }
			if(document.getElementById('quality').value == "") {
				alert("{L_REQUIRE_QUALITY}V");
				return false;
		    }
			if(document.getElementById('weight_max').value == "") {
				alert("{L_REQUIRE_MAX_WEIGHT}");
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
			<form action="admin_gallery_config.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_CONFIG_CONFIG}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="max_width">* {L_MAX_WIDTH}</label><br /><span>{L_MAX_WIDTH_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" id="max_width" name="max_width" value="{MAX_WIDTH}" class="text" /> {L_UNIT_PX}</label></dd>
					</dl>
					<dl>
						<dt><label for="max_height">* {L_MAX_HEIGHT}</label><br /><span>{L_MAX_HEIGHT_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" id="max_height" name="max_height" value="{MAX_HEIGHT}" class="text" /> {L_UNIT_PX}</label></dd>
					</dl>
					<dl>
						<dt><label for="mini_max_height">* {L_MINI_MAX_HEIGHT}</label><br /><span>{L_MINI_MAX_HEIGHT_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" id="mini_max_height" name="mini_max_height" value="{MINI_MAX_HEIGHT}" class="text" /> {L_UNIT_PX}</label></dd>
					</dl>
					<dl>
						<dt><label for="mini_max_width">* {L_MINI_MAX_WIDTH}</label><br /><span>{L_MINI_MAX_WIDTH_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" id="mini_max_width" name="mini_max_width" value="{MINI_MAX_WIDTH}" class="text" /> {L_UNIT_PX}</label></dd>
					</dl>
					<dl>
						<dt><label for="max_weight">* {L_MAX_WEIGHT}</label><br /><span>{L_MAX_WEIGHT_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="6" id="max_weight" name="max_weight" value="{MAX_WEIGHT}" class="text" /> {L_UNIT_KO}</label></dd>
					</dl>
					<dl>
						<dt><label for="quality">* {L_QUALITY_THUMB}</label><br /><span>{L_QUALITY_THUMB_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" id="quality" name="quality" value="{QUALITY}" class="text" /> %</label></dd>
					</dl>
					<dl>
						<dt><label for="columns_number">* {L_COLUMNS_NUMBER}</label><br /><span>{L_COLUMNS_NUMBER_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="1" maxlength="1" id="columns_number" name="columns_number" value="{COLUMNS_NUMBER}" class="text" /> {L_COLUMN}</label></dd>
					</dl>
					<dl>
						<dt><label for="pics_number_per_page">* {L_PICS_NUMBER_PER_PAGE}</label></dt>
						<dd><label><input type="text" size="6" maxlength="6" id="pics_number_per_page" name="pics_number_per_page" value="{PICS_NUMBER_PER_PAGE}" class="text" /></label></dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_DISPLAY_OPTION}</legend>
					<dl>
						<dt><label for="pics_enlargement_mode">{L_DISPLAY_MODE}</label></dt>
						<dd>
							<label><input type="radio" # IF C_DISPLAY_PICS_NEW_PAGE #checked="checked" # ENDIF #name="pics_enlargement_mode" id="pics_enlargement_mode" value="{NEW_PAGE}" /> {L_NEW_PAGE}</label><br />
							<label><input type="radio" # IF C_DISPLAY_PICS_RESIZE #checked="checked" # ENDIF #name="pics_enlargement_mode" value="{RESIZE}" /> {L_RESIZE}</label><br />
							<label><input type="radio" # IF C_DISPLAY_PICS_POPUP #checked="checked" # ENDIF #name="pics_enlargement_mode" value="{POPUP}" /> {L_POPUP}</label><br />
							<label><input type="radio" # IF C_DISPLAY_PICS_FULL_SCREEN #checked="checked" # ENDIF #name="pics_enlargement_mode" value="{FULL_SCREEN}" /> {L_POPUP_FULL}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="title_enabled">{L_TITLE_ENABLED}</label><br /><span>{L_TITLE_ENABLED_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" # IF C_TITLE_ENABLED #checked="checked" # ENDIF #name="title_enabled" id="activ_title" value="1" /> {L_ENABLED}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" # IF NOT C_TITLE_ENABLED #checked="checked" # ENDIF #name="title_enabled" value="0" /> {L_DISABLED}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="author_displayed">{L_AUTHOR_DISPLAYED}</label><br /><span>{L_AUTHOR_DISPLAYED_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" # IF C_AUTHOR_DISPLAYED #checked="checked" # ENDIF #name="author_displayed" id="author_displayed" value="1" /> {L_ENABLED}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" # IF NOT C_AUTHOR_DISPLAYED #checked="checked" # ENDIF #name="author_displayed" value="0" /> {L_DISABLED}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="views_counter_enabled">{L_VIEWS_COUNTER_ENABLED}</label><br /><span>{L_VIEWS_COUNTER_ENABLED_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" # IF C_VIEWS_COUNTER_ENABLED #checked="checked" # ENDIF #name="views_counter_enabled" id="views_counter_enabled" value="1" /> {L_ENABLED}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" # IF NOT C_VIEWS_COUNTER_ENABLED #checked="checked" # ENDIF #name="views_counter_enabled" value="0" /> {L_DISABLED}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="comments_enabled">{L_COMMENTS_ENABLED}</label></dt>
						<dd>
							<label><input type="radio" # IF C_COMMENTS_ENABLED #checked="checked" # ENDIF #name="comments_enabled" id="comments_enabled" value="1" /> {L_ENABLED}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" # IF NOT C_COMMENTS_ENABLED #checked="checked" # ENDIF #name="comments_enabled" value="0" /> {L_DISABLED}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="notation_enabled">{L_NOTATION_ENABLED}</label></dt>
						<dd>
							<label><input type="radio" # IF C_NOTATION_ENABLED #checked="checked" # ENDIF #name="notation_enabled" id="notation_enabled" value="1" /> {L_ENABLED}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" # IF NOT C_NOTATION_ENABLED #checked="checked" # ENDIF #name="notation_enabled" value="0" /> {L_DISABLED}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="notes_number_displayed">{L_NOTES_NUMBER_DISPLAYED}</label></dt>
						<dd>
							<label><input type="radio" # IF C_NOTES_NUMBER_DISPLAYED #checked="checked" # ENDIF #name="notes_number_displayed" id="notes_number_displayed" value="1" /> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" # IF NOT C_NOTES_NUMBER_DISPLAYED #checked="checked" # ENDIF #name="notes_number_displayed" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="notation_scale">{L_NOTATION_SCALE}</label><br /><span>{L_NOTATION_SCALE_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" name="notation_scale" id="notation_scale" value="{NOTATION_SCALE}" class="text" /></label></dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_THUMBNAILS_SCROLLING}</legend>
					<dl>
						<dt><label for="scroll_type">{L_SCROLL_TYPE}</label></dt>
						<dd><label>
								<select name="scroll_type" id="scroll_type">
									{SCROLL_TYPES}
								</select>
							</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="pics_number_in_mini">{L_PICS_NUMBER_IN_MINI}</label></dt>
						<dd><label><input type="text" size="3" name="pics_number_in_mini" id="pics_number_in_mini" value="{PICS_NUMBER_IN_MINI}" class="text" /> </label></dd>
					</dl>
					<dl>
						<dt><label for="mini_pics_speed">{L_MINI_PICS_SPEED}</label><br /><span>{L_MINI_PICS_SPEED_EXPLAIN}</span></dt>
						<dd>
							<label>
								<select name="mini_pics_speed" id="mini_pics_speed">
								{MINI_PICS_SPEED}
								</select>
							</label>
						</dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_IMG_PROTECT}</legend>
					<dl>
						<dt><label for="logo_enabled">{L_LOGO_ENABLED}</label><br /><span>{L_LOGO_ENABLED_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" # IF C_LOGO_ENABLED #checked="checked" # ENDIF #name="logo_enabled" id="logo_ENABLED" value="1" /> {L_ENABLED}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" # IF NOT C_LOGO_ENABLED #checked="checked" # ENDIF #name="logo_enabled" value="0" /> {L_DISABLED}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="logo">{L_LOGO_URL}</label><br /><span>{L_LOGO_URL_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="25" name="logo" id="logo" value="{LOGO}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="logo_transparency">{L_LOGO_TRANSPARENCY}</label><br /><span>{L_LOGO_TRANSPARENCY_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" name="logo_transparency" id="logo_transparency" value="{LOGO_TRANSPARENCY}" class="text" /> %</label></dd>
					</dl>
					<dl>
						<dt><label for="logo_horizontal_distance">{L_WIDTH_BOTTOM_RIGHT}</label><br /><span>{L_WIDTH_BOTTOM_RIGHT_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" name="logo_horizontal_distance" id="logo_horizontal_distance" value="{LOGO_HORIZONTAL_DISTANCE}" class="text" /> {L_UNIT_PX}</label></dd>
					</dl>
					<dl>
						<dt><label for="logo_vertical_distance">{L_HEIGHT_BOTTOM_RIGHT}</label><br /><span>{L_HEIGHT_BOTTOM_RIGHT_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" name="logo_vertical_distance" id="logo_vertical_distance" value="{LOGO_VERTICAL_DISTANCE}" class="text" /> {L_UNIT_PX}</label></dd>
					</dl>
				</fieldset>	
					
				<fieldset>
					<legend>{L_UPLOAD_PICS}</legend>
					<dl>
						<dt><label for="member_max_pics_number">{L_MEMBER_MAX_PICS_NUMBER}</label><br /><span>{L_MEMBER_MAX_PICS_NUMBER_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" name="member_max_pics_number" id="member_max_pics_number" value="{MEMBER_MAX_PICS_NUMBER}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="moderator_max_pics_number">{L_MODERATOR_MAX_PICS_NUMBER}</label><br /><span>{L_MODERATOR_MAX_PICS_NUMBER_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" name="moderator_max_pics_number" id="moderator_max_pics_number" value="{MODERATOR_MAX_PICS_NUMBER}" class="text" /></label></dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
				<legend>{L_UPDATE}</legend>
					<input type="submit" name="valid" value="{L_UPDATE}" class="submit" />
							&nbsp;&nbsp; 
							<input type="reset" value="{L_RESET}" class="reset" />
				</fieldset>
			</form>

			<form action="admin_gallery_config.php?token={TOKEN}" name="form" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_CACHE}</legend>
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/cache.png" alt="" style="float:left;padding:6px;" />
					{L_EXPLAIN_GALLERY_CACHE}
					<br /><br />
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_EMPTY}</legend>
					<input type="submit" name="gallery_cache" value="{L_EMPTY}" class="submit" /> 		
				</fieldset>
			</form>
		</div>
		
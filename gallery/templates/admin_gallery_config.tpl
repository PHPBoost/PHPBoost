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
				alert("{L_REQUIRE_HEIGHT_MAX}");
				return false;
		    }
			if(document.getElementById('width_max').value == "") {
				alert("{L_REQUIRE_WIDTH_MAX}");
				return false;
		    }
				if(document.getElementById('height').value == "") {
				alert("{L_REQUIRE_HEIGHT}");
				return false;
		    }
			if(document.getElementById('width').value == "") {
				alert("{L_REQUIRE_WIDTH}");
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
				alert("{L_REQUIRE_WEIGHT_MAX}");
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
						<dt><label for="width_max">* {L_WIDTH_MAX}</label><br /><span>{L_WIDTH_MAX_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" id="width_max" name="width_max" value="{WIDTH_MAX}" class="text" /> {L_UNIT_PX}</label></dd>
					</dl>
					<dl>
						<dt><label for="height_max">* {L_HEIGHT_MAX}</label><br /><span>{L_HEIGHT_MAX_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" id="height_max" name="height_max" value="{HEIGHT_MAX}" class="text" /> {L_UNIT_PX}</label></dd>
					</dl>
					<dl>
						<dt><label for="height">* {L_HEIGHT_MAX_THUMB}</label><br /><span>{L_HEIGHT_MAX_THUMB_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" id="height" name="height" value="{HEIGHT}" class="text" /> {L_UNIT_PX}</label></dd>
					</dl>
					<dl>
						<dt><label for="width">* {L_WIDTH_MAX_THUMB}</label><br /><span>{L_WIDTH_MAX_THUMB_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" id="width" name="width" value="{WIDTH}" class="text" /> {L_UNIT_PX}</label></dd>
					</dl>
					<dl>
						<dt><label for="weight_max">* {L_WEIGHT_MAX}</label><br /><span>{L_WEIGHT_MAX_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="6" id="weight_max" name="weight_max" value="{WEIGHT_MAX}" class="text" /> {L_UNIT_KO}</label></dd>
					</dl>
					<dl>
						<dt><label for="quality">* {L_QUALITY_THUMB}</label><br /><span>{L_QUALITY_THUMB_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" id="quality" name="quality" value="{QUALITY}" class="text" /> %</label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_column">* {L_NBR_COLUMN}</label><br /><span>{L_NBR_COLUMN_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="1" maxlength="1" id="nbr_column" name="nbr_column" value="{NBR_COLUMN}" class="text" /> {L_COLUMN}</label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_pics_max">* {L_NBR_PICS_MAX}</label></dt>
						<dd><label><input type="text" size="6" maxlength="6" id="nbr_pics_max" name="nbr_pics_max" value="{NBR_PICS_MAX}" class="text" /></label></dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_DISPLAY_OPTION}</legend>
					<dl>
						<dt><label for="display_pics">{L_DISPLAY_MODE}</label></dt>
						<dd>
							<label><input type="radio" {DISPLAY_PICS_NEW_PAGE} name="display_pics" id="display_pics" value="0" /> {L_NEW_PAGE}</label><br />
							<label><input type="radio" {DISPLAY_PICS} name="display_pics" value="1" /> {L_RESIZE}</label><br />
							<label><input type="radio" {DISPLAY_PICS_POPUP} name="display_pics" value="2" /> {L_POPUP}</label><br />
							<label><input type="radio" {DISPLAY_PICS_POPUP_FULL} name="display_pics" value="3" /> {L_POPUP_FULL}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_title">{L_TITLE_ENABLED}</label><br /><span>{L_TITLE_ENABLED_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" {TITLE_ENABLED} name="activ_title" id="activ_title" value="1" /> {L_ACTIV}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {TITLE_DISABLED} name="activ_title" value="0" /> {L_UNACTIV}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_user">{L_IMG_POSTER}</label><br /><span>{L_IMG_POSTER_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" {USER_ENABLED} name="activ_user" id="activ_user" value="1" /> {L_ACTIV}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {USER_DISABLED} name="activ_user" value="0" /> {L_UNACTIV}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_view">{L_COMPT_VIEWS}</label><br /><span>{L_COMPT_VIEWS_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" {VIEW_ENABLED} name="activ_view" id="activ_view" value="1" /> {L_ACTIV}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {VIEW_DISABLED} name="activ_view" value="0" /> {L_UNACTIV}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_com">{L_ACTIV_COM}</label></dt>
						<dd>
							<label><input type="radio" {COM_ENABLED} name="activ_com" id="activ_com" value="1" /> {L_ACTIV}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {COM_DISABLED} name="activ_com" value="0" /> {L_UNACTIV}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="activ_note">{L_ACTIV_NOTE}</label></dt>
						<dd>
							<label><input type="radio" {NOTE_ENABLED} name="activ_note" id="activ_note" value="1" /> {L_ACTIV}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {NOTE_DISABLED} name="activ_note" value="0" /> {L_UNACTIV}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="display_nbrnote">{L_DISPLAY_NBRNOTE}</label></dt>
						<dd>
							<label><input type="radio" {NBRNOTE_ENABLED} name="display_nbrnote" id="display_nbrnote" value="1" /> {L_YES}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {NBRNOTE_DISABLED} name="display_nbrnote" value="0" /> {L_NO}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="note_max">{L_NOTE_MAX}</label><br /><span>{L_NOTE_MAX_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" name="note_max" id="note_max" value="{NOTE_MAX}" class="text" /></label></dd>
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
						<dt><label for="nbr_pics_mini">{L_NBR_PICS_MINI}</label></dt>
						<dd><label><input type="text" size="3" name="nbr_pics_mini" id="nbr_pics_mini" value="{NBR_PICS_MINI}" class="text" /> </label></dd>
					</dl>
					<dl>
						<dt><label for="speed_mini_pics">{L_SPEED_MINI_PICS}</label><br /><span>{L_SPEED_MINI_PICS_EXPLAIN}</span></dt>
						<dd>
							<label>
								<select name="speed_mini_pics" id="speed_mini_pics">
								{SPEED_MINI_PICS}
								</select>
							</label>
						</dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_IMG_PROTECT}</legend>
					<dl>
						<dt><label for="activ_logo">{L_ACTIV_LOGO}</label><br /><span>{L_ACTIV_LOGO_EXPLAIN}</span></dt>
						<dd>
							<label><input type="radio" {LOGO_ENABLED} name="activ_logo" id="activ_logo" value="1" /> {L_ACTIV}</label>
							&nbsp;&nbsp; 
							<label><input type="radio" {LOGO_DISABLED} name="activ_logo" value="0" /> {L_UNACTIV}</label>
						</dd>
					</dl>
					<dl>
						<dt><label for="logo">{L_LOGO_URL}</label><br /><span>{L_LOGO_URL_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="25" name="logo" id="logo" value="{LOGO}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="trans">{L_LOGO_TRANS}</label><br /><span>{L_LOGO_TRANS_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="3" name="trans" id="trans" value="{TRANS}" class="text" /> %</label></dd>
					</dl>
					<dl>
						<dt><label for="d_width">{L_WIDTH_BOTTOM_RIGHT}</label><br /><span>{L_WIDTH_BOTTOM_RIGHT_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" name="d_width" id="d_width" value="{D_WIDTH}" class="text" /> {L_UNIT_PX}</label></dd>
					</dl>
					<dl>
						<dt><label for="d_height">{L_HEIGHT_BOTTOM_RIGHT}</label><br /><span>{L_HEIGHT_BOTTOM_RIGHT_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" name="d_height" id="d_height" value="{D_HEIGHT}" class="text" /> {L_UNIT_PX}</label></dd>
					</dl>
				</fieldset>	
					
				<fieldset>
					<legend>{L_UPLOAD_PICS}</legend>
					<dl>
						<dt><label for="limit_member">{L_NUMBER_IMG}</label><br /><span>{L_NUMBER_IMG_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" name="limit_member" id="limit_member" value="{LIMIT_USER}" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="limit_modo">{L_NUMBER_IMG_MODO}</label><br /><span>{L_NUMBER_IMG_MODO_EXPLAIN}</span></dt>
						<dd><label><input type="text" size="5" name="limit_modo" id="limit_modo" value="{LIMIT_MODO}" class="text" /></label></dd>
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
					<img src="../templates/{THEME}/images/admin/cache.png" alt="" style="float:left;padding:6px;" />
					{L_EXPLAIN_GALLERY_CACHE}
					<br /><br />
				</fieldset>			
				<fieldset class="fieldset_submit">
					<legend>{L_EMPTY}</legend>
					<input type="submit" name="gallery_cache" value="{L_EMPTY}" class="submit" /> 		
				</fieldset>
			</form>
		</div>
		
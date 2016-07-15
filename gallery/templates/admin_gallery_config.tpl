		<script>
		<!--
		function check_form(){
			if(document.getElementById('max_height').value == "") {
				alert("{L_REQUIRE_MAX_HEIGHT}");
				return false;
			}
			if(document.getElementById('max_width').value == "") {
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
			if(document.getElementById('max_weight').value == "") {
				alert("{L_REQUIRE_MAX_WEIGHT}");
				return false;
			}
			if(document.getElementById('quality').value == "") {
				alert("{L_REQUIRE_QUALITY}");
				return false;
			}
			if(document.getElementById('categories_number_per_page').value == "") {
				alert("{L_REQUIRE_CAT_P}");
				return false;
			}
			if(document.getElementById('columns_number').value == "") {
				alert("{L_REQUIRE_ROW}");
				return false;
			}
			if(document.getElementById('pics_number_per_page').value == "") {
				alert("{L_REQUIRE_IMG_P}");
				return false;
			}
			if(document.getElementById('notation_scale').value == "" || document.getElementById('notation_scale').value < 3 || document.getElementById('notation_scale').value > 20) {
				alert("{L_REQUIRE_NOTATION_SCALE}");
				return false;
			}
			
			return true;
		}
		-->
		</script>

		<nav id="admin-quick-menu">
			<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" title="{L_GALLERY_MANAGEMENT}">
				<i class="fa fa-bars"></i> {L_GALLERY_MANAGEMENT}
			</a>
			<ul>
				<li>
					<a href="admin_gallery.php" class="quick-link">{L_GALLERY_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_gallery_add.php" class="quick-link">{L_GALLERY_PICS_ADD}</a>
				</li>
				<li>
					<a href="admin_gallery_config.php" class="quick-link">{L_GALLERY_CONFIG}</a>
				</li>
			</ul>
		</nav>
		
		<div id="admin-contents">
			<form action="admin_gallery_config.php" method="post" onsubmit="return check_form();" class="fieldset-content">
				<p class="center">{L_REQUIRE}</p>
				<fieldset>
					<legend>{L_CONFIG_CONFIG}</legend>
					<div class="fieldset-inset">
						<div class="form-element">
							<label for="max_width">* {L_MAX_WIDTH} <span class="field-description">{L_MAX_WIDTH_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="1" id="max_width" name="max_width" value="{MAX_WIDTH}" /> {L_UNIT_PX}</div>
						</div>
						<div class="form-element">
							<label for="max_height">* {L_MAX_HEIGHT} <span class="field-description">{L_MAX_HEIGHT_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="1" id="max_height" name="max_height" value="{MAX_HEIGHT}" /> {L_UNIT_PX}</div>
						</div>
						<div class="form-element">
							<label for="mini_max_height">* {L_MINI_MAX_HEIGHT} <span class="field-description">{L_MINI_MAX_HEIGHT_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="1" id="mini_max_height" name="mini_max_height" value="{MINI_MAX_HEIGHT}" /> {L_UNIT_PX}</div>
						</div>
						<div class="form-element">
							<label for="mini_max_width">* {L_MINI_MAX_WIDTH} <span class="field-description">{L_MINI_MAX_WIDTH_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="1" id="mini_max_width" name="mini_max_width" value="{MINI_MAX_WIDTH}" /> {L_UNIT_PX}</div>
						</div>
						<div class="form-element">
							<label for="max_weight">* {L_MAX_WEIGHT} <span class="field-description">{L_MAX_WEIGHT_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="1" id="max_weight" name="max_weight" value="{MAX_WEIGHT}" /> {L_UNIT_KO}</div>
						</div>
						<div class="form-element">
							<label for="quality">* {L_QUALITY_THUMB} <span class="field-description">{L_QUALITY_THUMB_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="1" max="100" id="quality" name="quality" value="{QUALITY}" /> %</div>
						</div>
						<div class="form-element">
							<label for="categories_number_per_page">* ${LangLoader::get_message('config.categories_number_per_page', 'admin-common')}</label>
							<div class="form-field"><input type="number" min="1" max="50" id="categories_number_per_page" name="categories_number_per_page" value="{CATEGORIES_NUMBER_PER_PAGE}" /></div>
						</div>
						<div class="form-element">
							<label for="columns_number">* {L_COLUMNS_NUMBER} <span class="field-description">{L_COLUMNS_NUMBER_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="1" max="4" id="columns_number" name="columns_number" value="{COLUMNS_NUMBER}" /> {L_COLUMN}</div>
						</div>
						<div class="form-element">
							<label for="pics_number_per_page">* {L_PICS_NUMBER_PER_PAGE}</label>
							<div class="form-field"><input type="number" min="1" max="100" id="pics_number_per_page" name="pics_number_per_page" value="{PICS_NUMBER_PER_PAGE}" /></div>
						</div>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>{L_DISPLAY_OPTION}</legend>
					<div class="fieldset-inset">
						<div class="form-element">
							<label for="pics_enlargement_mode">{L_DISPLAY_MODE}</label>
							<div class="form-field">
								<div class="form-field-radio">
									<input type="radio" # IF C_DISPLAY_PICS_NEW_PAGE #checked="checked" # ENDIF #name="pics_enlargement_mode" id="pics_enlargement_mode" value="{NEW_PAGE}" />
									<label for="pics_enlargement_mode"></label>
								</div>
								<span class="form-field-radio-span">{L_NEW_PAGE}</span>
								<br />
								<div class="form-field-radio">
									<input type="radio" # IF C_DISPLAY_PICS_RESIZE #checked="checked" # ENDIF #name="pics_enlargement_mode" id="pics_enlargement_mode_2" value="{RESIZE}" />
									<label for="pics_enlargement_mode_2"></label>
								</div>
								<span class="form-field-radio-span">{L_RESIZE}</span>
								<br />
								<div class="form-field-radio">
									<input type="radio" # IF C_DISPLAY_PICS_POPUP #checked="checked" # ENDIF #name="pics_enlargement_mode" id="pics_enlargement_mode_3" value="{POPUP}" />
									<label for="pics_enlargement_mode_3"></label>
								</div>
								<span class="form-field-radio-span">{L_POPUP}</span>
								<br />
								<div class="form-field-radio">
									<input type="radio" # IF C_DISPLAY_PICS_FULL_SCREEN #checked="checked" # ENDIF #name="pics_enlargement_mode" id="pics_enlargement_mode_4" value="{FULL_SCREEN}">
									<label for="pics_enlargement_mode_4"></label>
								</div>
								<span class="form-field-radio-span">{L_POPUP_FULL}</span>
							</div>
						</div>
						<div class="form-element">
							<label for="title_enabled">{L_TITLE_ENABLED} <span class="field-description">{L_TITLE_ENABLED_EXPLAIN}</span></label>
							<div class="form-field">
								<div class="form-field-radio">
									<input type="radio" # IF C_TITLE_ENABLED #checked="checked" # ENDIF #name="title_enabled" id="activ_title" value="1">
									<label for="activ_title"></label>
								</div>
								<span class="form-field-radio-span">{L_ENABLED}</span>
								<div class="form-field-radio">
									<input type="radio" # IF NOT C_TITLE_ENABLED #checked="checked" # ENDIF #name="title_enabled" id="activ_title_2" value="0">
									<label for="activ_title_2"></label>
								</div>
								<span class="form-field-radio-span">{L_DISABLED}</span>
							</div>
						</div>
						<div class="form-element">
							<label for="author_displayed">{L_AUTHOR_DISPLAYED} <span class="field-description">{L_AUTHOR_DISPLAYED_EXPLAIN}</span></label>
							<div class="form-field">
								<div class="form-field-radio">
									<input type="radio" # IF C_AUTHOR_DISPLAYED #checked="checked" # ENDIF #name="author_displayed" id="author_displayed" value="1">
									<label for="author_displayed"></label>
								</div>
								<span class="form-field-radio-span">{L_ENABLED}</span>
								<div class="form-field-radio">
									<input type="radio" # IF NOT C_AUTHOR_DISPLAYED #checked="checked" # ENDIF #name="author_displayed" id="author_displayed_2" value="0">
									<label for="author_displayed_2"></label>
								</div>
								<span class="form-field-radio-span">{L_DISABLED}</span>
							</div>
						</div>
						<div class="form-element">
							<label for="views_counter_enabled">{L_VIEWS_COUNTER_ENABLED} <span class="field-description">{L_VIEWS_COUNTER_ENABLED_EXPLAIN}</span></label>
							<div class="form-field">
								<div class="form-field-radio">
									<input type="radio" # IF C_VIEWS_COUNTER_ENABLED #checked="checked" # ENDIF #name="views_counter_enabled" id="views_counter_enabled" value="1">
									<label for="views_counter_enabled"></label>
								</div>
								<span class="form-field-radio-span">{L_ENABLED}</span>
								<div class="form-field-radio">
									<input type="radio" # IF NOT C_VIEWS_COUNTER_ENABLED #checked="checked" # ENDIF #name="views_counter_enabled" id="views_counter_enabled_2" value="0">
									<label for="views_counter_enabled_2"></label>
								</div>
								<span class="form-field-radio-span">{L_DISABLED}</span>
							</div>
						</div>
						<div class="form-element">
							<label for="comments_enabled">{L_COMMENTS_ENABLED}</label>
							<div class="form-field">
								<div class="form-field-radio">
									<input type="radio" # IF C_COMMENTS_ENABLED #checked="checked" # ENDIF #name="comments_enabled" id="comments_enabled" value="1"> 
									<label for="comments_enabled"></label>
								</div>
								<span class="form-field-radio-span">{L_ENABLED}</span>
								<div class="form-field-radio">
									<input type="radio" # IF NOT C_COMMENTS_ENABLED #checked="checked" # ENDIF #name="comments_enabled" id="comments_enabled_2" value="0">
									<label for="comments_enabled_2"></label>
								</div>
								<span class="form-field-radio-span">{L_DISABLED}</span><label> </label>
							</div>
						</div>
						<div class="form-element">
							<label for="notation_enabled">{L_NOTATION_ENABLED}</label>
							<div class="form-field">
								<div class="form-field-radio">
									<input type="radio" # IF C_NOTATION_ENABLED #checked="checked" # ENDIF #name="notation_enabled" id="notation_enabled" value="1">
									<label for="notation_enabled"></label>
								</div>
								<span class="form-field-radio-span">{L_ENABLED}</span>
								<div class="form-field-radio">
									<input type="radio" # IF NOT C_NOTATION_ENABLED #checked="checked" # ENDIF #name="notation_enabled" id="notation_enabled_2" value="0" />
									<label for="notation_enabled_2"></label>
								</div>
								<span class="form-field-radio-span">{L_DISABLED}</span>
							</div>
						</div>
						<div class="form-element">
							<label for="notes_number_displayed">{L_NOTES_NUMBER_DISPLAYED}</label>
							<div class="form-field">
								<div class="form-field-radio">
									<input type="radio" # IF C_NOTES_NUMBER_DISPLAYED #checked="checked" # ENDIF #name="notes_number_displayed" id="notes_number_displayed" value="1" />
									<label for="notes_number_displayed"></label>
								</div>
								<span class="form-field-radio-span">{L_YES}</span>
								<div class="form-field-radio">
									<input type="radio" # IF NOT C_NOTES_NUMBER_DISPLAYED #checked="checked" # ENDIF #name="notes_number_displayed" id="notes_number_displayed_2" value="0" />
									<label for="notes_number_displayed_2"></label>
								</div>
								<span class="form-field-radio-span">{L_NO}</span>
							</div>
						</div>
						<div class="form-element">
							<label for="notation_scale">* {L_NOTATION_SCALE} <span class="field-description">{L_NOTATION_SCALE_EXPLAIN}</span></label>
							<div class="form-field">
								<input type="number" min="3" max="20" name="notation_scale" id="notation_scale" value="{NOTATION_SCALE}" />
							</div>
						</div>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>{L_THUMBNAILS_SCROLLING}</legend>
					<div class="fieldset-inset">
						<div class="form-element">
							<label for="scroll_type">{L_SCROLL_TYPE}</label>
							<div class="form-field"><label>
									<select name="scroll_type" id="scroll_type">
										{SCROLL_TYPES}
									</select>
								</label>
							</div>
						</div>
						<div class="form-element">
							<label for="pics_number_in_mini">{L_PICS_NUMBER_IN_MINI}</label>
							<div class="form-field"><label><input type="number" min="1" name="pics_number_in_mini" id="pics_number_in_mini" value="{PICS_NUMBER_IN_MINI}"> </label></div>
						</div>
						<div class="form-element">
							<label for="mini_pics_speed">{L_MINI_PICS_SPEED} <span class="field-description">{L_MINI_PICS_SPEED_EXPLAIN}</span></label>
							<div class="form-field">
								<label>
									<select name="mini_pics_speed" id="mini_pics_speed">
									{MINI_PICS_SPEED}
									</select>
								</label>
							</div>
						</div>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>{L_IMG_PROTECT}</legend>
					<div class="fieldset-inset">
						<div class="form-element">
							<label for="logo_enabled">{L_LOGO_ENABLED} <span class="field-description">{L_LOGO_ENABLED_EXPLAIN}</span></label>
							<div class="form-field">
								<div class="form-field-radio">
									<input type="radio" # IF C_LOGO_ENABLED #checked="checked" # ENDIF #name="logo_enabled" id="logo_ENABLED" value="1" />
									<label for="logo_ENABLED"></label>
								</div>
								<span class="form-field-radio-span">{L_ENABLED}</span>
								<div class="form-field-radio">
									<input type="radio" # IF NOT C_LOGO_ENABLED #checked="checked" # ENDIF #name="logo_enabled" id="logo_ENABLED_2" value="0" />
									<label for="logo_ENABLED_2"></label>
								</div>
								<span class="form-field-radio-span">{L_DISABLED}</span>
							</div>
						</div>
						<div class="form-element">
							<label for="logo">{L_LOGO_URL} <span class="field-description">{L_LOGO_URL_EXPLAIN}</span></label>
							<div class="form-field"><input type="text" name="logo" id="logo" value="{LOGO}"></div>
						</div>
						<div class="form-element">
							<label for="logo_transparency">{L_LOGO_TRANSPARENCY} <span class="field-description">{L_LOGO_TRANSPARENCY_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="1" max="100" name="logo_transparency" id="logo_transparency" value="{LOGO_TRANSPARENCY}"> %</div>
						</div>
						<div class="form-element">
							<label for="logo_horizontal_distance">{L_WIDTH_BOTTOM_RIGHT} <span class="field-description">{L_WIDTH_BOTTOM_RIGHT_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="1" name="logo_horizontal_distance" id="logo_horizontal_distance" value="{LOGO_HORIZONTAL_DISTANCE}"> {L_UNIT_PX}</div>
						</div>
						<div class="form-element">
							<label for="logo_vertical_distance">{L_HEIGHT_BOTTOM_RIGHT} <span class="field-description">{L_HEIGHT_BOTTOM_RIGHT_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="1" name="logo_vertical_distance" id="logo_vertical_distance" value="{LOGO_VERTICAL_DISTANCE}"> {L_UNIT_PX}</div>
						</div>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>{L_UPLOAD_PICS}</legend>
					<div class="fieldset-inset">
						<div class="form-element">
							<label for="member_max_pics_number">{L_MEMBER_MAX_PICS_NUMBER} <span class="field-description">{L_MEMBER_MAX_PICS_NUMBER_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="0" name="member_max_pics_number" id="member_max_pics_number" value="{MEMBER_MAX_PICS_NUMBER}"></div>
						</div>
						<div class="form-element">
							<label for="moderator_max_pics_number">{L_MODERATOR_MAX_PICS_NUMBER} <span class="field-description">{L_MODERATOR_MAX_PICS_NUMBER_EXPLAIN}</span></label>
							<div class="form-field"><input type="number" min="0" name="moderator_max_pics_number" id="moderator_max_pics_number" value="{MODERATOR_MAX_PICS_NUMBER}"></div>
						</div>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>${LangLoader::get_message('authorizations', 'common')}</legend>
					<div class="fieldset-inset">
						<p class="fieldset-description">${LangLoader::get_message('config.authorizations.explain', 'admin-common')}</p>
						<div class="form-element">
							<label>{L_AUTH_READ}</label>
							<div class="form-field">{AUTH_READ}</div>
						</div>
						<div class="form-element">
							<label>{L_AUTH_WRITE}</label>
							<div class="form-field">{AUTH_WRITE}</div>
						</div>
						<div class="form-element">
							<label>{L_AUTH_MODERATION}</label>
							<div class="form-field">{AUTH_MODERATION}</div>
						</div>
						<div class="form-element">
							<label>${LangLoader::get_message('authorizations.categories_management', 'common')}</label>
							<div class="form-field">{AUTH_MANAGE_CATEGORIES}</div>
						</div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<div class="fieldset-inset">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="valid" value="true" class="submit">{L_UPDATE}</button>
						<button type="reset" value="true">{L_RESET}</button>
					</div>
				</fieldset>
			</form>
			<form action="admin_gallery_config.php" name="form" method="post" class="fieldset-content">
				<fieldset>
					<legend>{L_CACHE}</legend>
					<div class="fieldset-inset">
						<p class="center"><i class="fa fa-2x fa-refresh"></i></p>
						{L_EXPLAIN_GALLERY_CACHE}
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_EMPTY}</legend>
					<div class="fieldset-inset">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="gallery_cache" value="true" class="submit">{L_EMPTY}</button>
					</div>
				</fieldset>
			</form>
		</div>

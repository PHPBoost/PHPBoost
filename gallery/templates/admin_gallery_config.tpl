<script>
	function check_form(){
		if(document.getElementById('max_height').value == "") {
			alert("{@gallery.warning.height.max}");
			return false;
		}
		if(document.getElementById('max_width').value == "") {
			alert("{@gallery.warning.width.max}");
			return false;
		}
		if(document.getElementById('mini_max_height').value == "") {
			alert("{@gallery.warning.height}");
			return false;
		}
		if(document.getElementById('mini_max_width').value == "") {
			alert("{@gallery.warning.width}");
			return false;
		}
		if(document.getElementById('max_weight').value == "") {
			alert("{@gallery.warning.weight.max}");
			return false;
		}
		if(document.getElementById('quality').value == "") {
			alert("{@gallery.warning.quality}");
			return false;
		}
		if(document.getElementById('categories_number_per_page').value == "") {
			alert("{@gallery.warning.categories.per.page}");
			return false;
		}
		if(document.getElementById('columns_number').value == "") {
			alert("{@gallery.warning.row}");
			return false;
		}
		if(document.getElementById('pics_number_per_page').value == "") {
			alert("{@gallery.warning.items.per.page}");
			return false;
		}

		return true;
	}
</script>

<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true"></i> {@form.configuration}
	</a>
	<ul>
		<li>
			<a href="${Url::to_rel('/gallery')}" class="quick-link">{@form.home}</a>
		</li>
		<li>
			<a href="admin_gallery.php" class="quick-link">{@gallery.management}</a>
		</li>
		<li>
			<a href="admin_gallery_add.php" class="quick-link">{@gallery.add.items}</a>
		</li>
		<li>
			<a href="admin_gallery_config.php" class="quick-link">{@form.configuration}</a>
		</li>
		<li>
			<a href="${relative_url(GalleryUrlBuilder::documentation())}" class="quick-link">{@form.documentation}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	# INCLUDE MESSAGE_HELPER #
	<form action="admin_gallery_config.php" method="post" onsubmit="return check_form();" class="fieldset-content">
		<p class="align-center small text-italic">{@form.required.fields}</p>
		<fieldset>
			<legend>{@gallery.config.module.title}</legend>
			<div class="fieldset-inset">
				<div class="form-element">
					<label for="max_width">* {@gallery.max.width} ({@common.unit.pixels})<span class="field-description">{@gallery.max.width.clue}</span></label>
					<div class="form-field form-field-number"><input type="number" min="1" id="max_width" name="max_width" value="{MAX_WIDTH}" /> </div>
				</div>
				<div class="form-element">
					<label for="max_height">* {@gallery.max.height} ({@common.unit.pixels})<span class="field-description">{@gallery.max.height.clue}</span></label>
					<div class="form-field form-field-number"><input type="number" min="1" id="max_height" name="max_height" value="{MAX_HEIGHT}" /> </div>
				</div>
				<div class="form-element">
					<label for="mini_max_height">* {@gallery.thumbnails.max.height} ({@common.unit.pixels})<span class="field-description">{@gallery.thumbnails.max.size.clue}</span></label>
					<div class="form-field form-field-number"><input type="number" min="1" id="mini_max_height" name="mini_max_height" value="{THUMBNAIL_MAX_HEIGHT}" /> </div>
				</div>
				<div class="form-element">
					<label for="mini_max_width">* {@gallery.thumbnails.max.width} ({@common.unit.pixels})<span class="field-description">{@gallery.thumbnails.max.size.clue}</span></label>
					<div class="form-field form-field-number"><input type="number" min="1" id="mini_max_width" name="mini_max_width" value="{THUMBNAIL_MAX_WIDTH}" /> </div>
				</div>
				<div class="form-element">
					<label for="max_weight">* {@gallery.weight.max} ({@common.unit.kilobytes})<span class="field-description">{@gallery.weight.max.clue}</span></label>
					<div class="form-field form-field-number"><input type="number" min="1" id="max_weight" name="max_weight" value="{MAX_WEIGHT}" /> </div>
				</div>
				<div class="form-element">
					<label for="quality">* {@gallery.thumbnails.quality} (%)<span class="field-description">{@gallery.thumbnails.quality.clue}</span></label>
					<div class="form-field form-field-number"><input type="number" min="1" max="100" id="quality" name="quality" value="{QUALITY}" /> </div>
				</div>
				<div class="form-element">
					<label for="categories_number_per_page">* {@form.categories.per.page}</label>
					<div class="form-field form-field-number"><input type="number" min="1" max="50" id="categories_number_per_page" name="categories_number_per_page" value="{CATEGORIES_PER_PAGE}" /></div>
				</div>
				<div class="form-element">
					<label for="columns_number">* {@form.items.per.row} <span class="field-description">{@gallery.items.per.row.clue}</span></label>
					<div class="form-field form-field-number"><input type="number" min="1" max="4" id="columns_number" name="columns_number" value="{COLUMNS_NUMBER}" /> </div>
				</div>
				<div class="form-element">
					<label for="pics_number_per_page">* {@gallery.items.per.page}</label>
					<div class="form-field form-field-number"><input type="number" min="1" max="100" id="pics_number_per_page" name="pics_number_per_page" value="{ITEMS_PER_PAGE}" /></div>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>{@form.options}</legend>
			<div class="fieldset-inset">
				<div class="form-element custom-radio">
					<label for="pics_enlargement_mode">{@gallery.item.resizing.mode}</label>
					<div class="form-field form-field-radio-button">
						<div class="form-field-radio">
							<label class="radio" for="pics_enlargement_mode">
								<input type="radio" # IF C_DISPLAY_NEW_PAGE #checked="checked" # ENDIF #name="pics_enlargement_mode" id="pics_enlargement_mode" value="{NEW_PAGE}" />
								<span>{@gallery.new.page}</span>
							</label>
						</div>
						<div class="form-field-radio">
							<label class="radio" for="pics_enlargement_mode_2">
								<input type="radio" # IF C_DISPLAY_RESIZING #checked="checked" # ENDIF #name="pics_enlargement_mode" id="pics_enlargement_mode_2" value="{RESIZE}" />
								<span>{@gallery.resizing}</span>
							</label>
						</div>
						<div class="form-field-radio">
							<label class="radio" for="pics_enlargement_mode_3">
								<input type="radio" # IF C_DISPLAY_POPUP #checked="checked" # ENDIF #name="pics_enlargement_mode" id="pics_enlargement_mode_3" value="{POPUP}" />
								<span>{@gallery.popup}</span>
							</label>
						</div>
						<div class="form-field-radio">
							<label class="radio" for="pics_enlargement_mode_4">
								<input type="radio" # IF C_DISPLAY_FULL_SCREEN #checked="checked" # ENDIF #name="pics_enlargement_mode" id="pics_enlargement_mode_4" value="{FULL_SCREEN}">
								<span>{@gallery.popup.full.screen}</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element top-field inline-radio custom-radio">
					<label for="title_enabled">{@gallery.enable.title} <span class="field-description">{@gallery.enable.title.clue}</span></label>
					<div class="form-field form-field-radio-button">
						<div class="form-field-radio">
							<label class="radio" for="activ_title">
								<input type="radio" # IF C_TITLE_ENABLED #checked="checked" # ENDIF #name="title_enabled" id="activ_title" value="1">
								<span>{@common.enable}</span>
							</label>
						</div>
						<div class="form-field-radio">
							<label class="radio" for="activ_title_2">
								<input type="radio" # IF NOT C_TITLE_ENABLED #checked="checked" # ENDIF #name="title_enabled" id="activ_title_2" value="0">
								<span>{@common.disable}</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element top-field inline-radio custom-radio">
					<label for="author_displayed">{@gallery.enable.contributor} <span class="field-description">{@gallery.enable.contributor.clue}</span></label>
					<div class="form-field form-field-radio-button">
						<div class="form-field-radio">
							<label class="radio" for="author_displayed">
								<input type="radio" # IF C_AUTHOR_DISPLAYED #checked="checked" # ENDIF #name="author_displayed" id="author_displayed" value="1">
								<span>{@common.enable}</span>
							</label>
						</div>
						<div class="form-field-radio">
							<label class="radio" for="author_displayed_2">
								<input type="radio" # IF NOT C_AUTHOR_DISPLAYED #checked="checked" # ENDIF #name="author_displayed" id="author_displayed_2" value="0">
								<span>{@common.disable}</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element top-field inline-radio custom-radio">
					<label for="views_counter_enabled">{@gallery.enable.views.counter} <span class="field-description">{@gallery.enable.views.counter.clue}</span></label>
					<div class="form-field form-field-radio-button">
						<div class="form-field-radio">
							<label class="radio" for="views_counter_enabled">
								<input type="radio" # IF C_VIEWS_COUNTER_ENABLED #checked="checked" # ENDIF #name="views_counter_enabled" id="views_counter_enabled" value="1">
								<span>{@common.enable}</span>
							</label>
						</div>
						<div class="form-field-radio">
							<label class="radio" for="views_counter_enabled_2">
								<input type="radio" # IF NOT C_VIEWS_COUNTER_ENABLED #checked="checked" # ENDIF #name="views_counter_enabled" id="views_counter_enabled_2" value="0">
								<span>{@common.disable}</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element top-field inline-radio custom-radio">
					<label for="notes_number_displayed">{@gallery.enable.notes.number}</label>
					<div class="form-field form-field-radio-button">
						<div class="form-field-radio">
							<label class="radio" for="notes_number_displayed">
								<input type="radio" # IF C_NOTES_NUMBER_DISPLAYED #checked="checked" # ENDIF #name="notes_number_displayed" id="notes_number_displayed" value="1" />
								<span>{@common.enable}</span>
							</label>
						</div>
						<div class="form-field-radio">
							<label class="radio" for="notes_number_displayed_2">
								<input type="radio" # IF NOT C_NOTES_NUMBER_DISPLAYED #checked="checked" # ENDIF #name="notes_number_displayed" id="notes_number_displayed_2" value="0" />
								<span>{@common.disable}</span>
							</label>
						</div>
					</div>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>{@gallery.items.protection}</legend>
			<div class="fieldset-inset">
				<div class="form-element top-field inline-radio custom-radio">
					<label for="logo_enabled">{@gallery.enable.logo} <span class="field-description">{@gallery.enable.logo.clue}</span></label>
					<div class="form-field">
						<div class="form-field-radio">
							<label class="radio" for="logo_ENABLED">
								<input type="radio" # IF C_LOGO_ENABLED #checked="checked" # ENDIF #name="logo_enabled" id="logo_ENABLED" value="1" />
								<span>{@common.enable}</span>
							</label>
						</div>
						<div class="form-field-radio">
							<label class="radio" for="logo_ENABLED_2">
								<input type="radio" # IF NOT C_LOGO_ENABLED #checked="checked" # ENDIF #name="logo_enabled" id="logo_ENABLED_2" value="0" />
								<span>{@common.disable}</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-element">
					<label for="logo">{@gallery.logo.url} <span class="field-description">{@gallery.logo.url.clue}</span></label>
					<div class="form-field form-field-text"><input type="text" name="logo" id="logo" value="{LOGO}"></div>
				</div>
				<div class="form-element">
					<label for="logo_horizontal_distance">{@gallery.horizontal.distance} ({@common.unit.pixels})<span class="field-description">{@gallery.from.bottom.right.clue}</span></label>
					<div class="form-field form-field-number"><input type="number" min="1" name="logo_horizontal_distance" id="logo_horizontal_distance" value="{LOGO_HORIZONTAL_DISTANCE}"> </div>
				</div>
				<div class="form-element">
					<label for="logo_vertical_distance">{@gallery.vertical.distance} ({@common.unit.pixels})<span class="field-description">{@gallery.from.bottom.right.clue}</span></label>
					<div class="form-field form-field-number"><input type="number" min="1" name="logo_vertical_distance" id="logo_vertical_distance" value="{LOGO_VERTICAL_DISTANCE}"> </div>
				</div>
				<div class="form-element">
					<label for="logo_transparency">{@gallery.logo.trans} (%)<span class="field-description">{@gallery.logo.trans.clue}</span></label>
					<div class="form-field form-field-number"><input type="number" min="1" max="100" name="logo_transparency" id="logo_transparency" value="{LOGO_TRANSPARENCY}"> </div>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>{@gallery.items.upload}</legend>
			<div class="fieldset-inset">
				<div class="form-element">
					<label for="member_max_pics_number">{@gallery.members.items.number} <span class="field-description">{@gallery.members.items.number.clue}</span></label>
					<div class="form-field form-field-number"><input type="number" min="0" name="member_max_pics_number" id="member_max_pics_number" value="{MEMBER_ITEMS_NUMBER}"></div>
				</div>
				<div class="form-element">
					<label for="moderator_max_pics_number">{@gallery.moderators.items.number} <span class="field-description">{@gallery.moderators.items.number.clue}</span></label>
					<div class="form-field form-field-number"><input type="number" min="0" name="moderator_max_pics_number" id="moderator_max_pics_number" value="{MODERATOR_ITEMS_NUMBER}"></div>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend>{@gallery.mini.module}</legend>
			<div class="fieldset-inset">
				<div class="form-element">
					<label for="scroll_type">{@gallery.scroll.type}</label>
					<div class="form-field form-field-select">
						<select name="scroll_type" id="scroll_type">
							{SCROLLING_TYPES}
						</select>
					</div>
				</div>
				<div class="form-element" id="pics_number">
					<label for="pics_number_in_mini">{@gallery.thumbnails.number}</label>
					<div class="form-field form-field-number"><input type="number" min="1" name="pics_number_in_mini" id="pics_number_in_mini" value="{THUMBNAILS_NUMBER}"></div>
				</div>
				<div class="form-element" id="pics_speed">
					<label for="mini_pics_speed">{@gallery.scroll.speed} <span class="field-description">{@gallery.scroll.speed.clue}</span></label>
					<div class="form-field form-field-select">
						<select name="mini_pics_speed" id="mini_pics_speed">
							{SCROLLING_SPEED}
						</select>
					</div>
				</div>
			</div>
            <script>
                jQuery('#scroll_type').on('change', function(){
                    if(jQuery(this).val() == 'no_scroll')
                    {
                        jQuery('#pics_number').hide();
                        jQuery('#pics_speed').hide();
                    }
                    else
                    {
                        jQuery('#pics_number').show();
                        jQuery('#pics_speed').show();
                    }
                }).trigger('change')
            </script>
		</fieldset>

		<fieldset>
			<legend>{@form.authorizations}</legend>
			<div class="fieldset-inset">
				<p class="fieldset-description form-element full-field">{@form.authorizations.clue}</p>
				<div class="form-element form-element-auth">
					<label>{@form.authorizations.read}</label>
					<div class="form-field">{AUTH_READ}</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@form.authorizations.write}</label>
					<div class="form-field">{AUTH_WRITE}</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@form.authorizations.moderation}</label>
					<div class="form-field">{AUTH_MODERATION}</div>
				</div>
				<div class="form-element form-element-auth">
					<label>{@form.authorizations.categories}</label>
					<div class="form-field">{AUTH_MANAGE_CATEGORIES}</div>
				</div>
			</div>
		</fieldset>

		<fieldset class="fieldset-submit">
			<legend>{@form.submit}</legend>
			<div class="fieldset-inset">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" name="valid" value="true" class="button submit">{@form.submit}</button>
				<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
			</div>
		</fieldset>
	</form>
	<form action="admin_gallery_config.php" name="form" method="post" class="fieldset-content">
		<fieldset>
			<legend>{@gallery.cache}</legend>
			<div class="fieldset-inset">
				<i class="fa fa-2x fa-sync mr5" aria-hidden="true"></i>
				{@H|gallery.cache.clue}
			</div>
		</fieldset>
		<fieldset class="fieldset-submit">
			<legend>{@form.empty}</legend>
			<div class="fieldset-inset">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" name="gallery_cache" value="true" class="button alt-submit">{@form.empty}</button>
			</div>
		</fieldset>
	</form>
</div>

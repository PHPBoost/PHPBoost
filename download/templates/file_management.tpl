		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('contents').value == "") {
				alert("{L_REQUIRE_DESC}");
				return false;
		    }
			if(document.getElementById('cat').value == "") {
				alert("{L_REQUIRE_CAT}");
				return false;
			}
			if(document.getElementById('title').value == "") {
				alert("{L_REQUIRE_TITLE}");
				return false;
		    }
			if(document.getElementById('url').value == "") {
				alert("{L_REQUIRE_URL}");
				return false;
		    }
				if(document.getElementById('cat').value == "") {
				alert("{L_REQUIRE_CAT}");
				return false;
		    }
			return true;
		}
		-->
		</script>

		# INCLUDE admin_download_menu #
		
		<div class="module_position">			
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				{L_PAGE_TITLE}
			</div>
			<div class="module_contents">
			# IF C_ERROR_HANDLER #
			<div class="error_handler_position">
					<span id="errorh"></span>
					<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
						<br />	
					</div>
			</div>
			# ENDIF #
			
			# IF C_PREVIEW #
				<div class="notice">
					{L_WARNING_PREVIEWING}
				</div>
				# INCLUDE download #
				<hr />
				<br />
				<div class="module_position">			
					<div class="module_top_l"></div>		
					<div class="module_top_r"></div>
					<div class="module_top">
						{L_SHORT_CONTENTS}
					</div>
					<div class="module_contents">
						{SHORT_DESCRIPTION_PREVIEW}
					</div>
					<div class="module_bottom_l"></div>		
					<div class="module_bottom_r"></div>
					<div class="module_bottom"></div>
				</div>
			# ENDIF #

			<form action="{U_TARGET}" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_PAGE_TITLE}</legend>
					<p>{L_REQUIRE}</p>
					<dl>
						<dt><label for="title">* {L_TITLE}</label></dt>
						<dd><input type="text" size="65" maxlength="100" id="title" name="title" value="{TITLE}" class="text" /></dd>
					</dl>
					<dl>
						<dt><label for="idcat">* {L_CATEGORY}</label></dt>
						<dd><label>
							{CATEGORIES_TREE}
						</label></dd>
					</dl>
					<dl>
						<dt><label for="url">* {L_URL}</label></dt>
						<dd><input type="text" size="65" id="url" name="url" id="url" value="{URL}" class="text" /></dd>
					</dl>
					<dl>
						<dt><label for="size">{L_SIZE}</label></dt>
						<dd><input type="text" size="10" maxlength="10" name="size" id="size" value="{SIZE_FORM}" class="text" /> {L_UNIT_SIZE}</dd>
					</dl>
					<dl>
						<dt><label for="count">{L_DOWNLOAD}</label></dt>
						<dd><input type="text" size="10" maxlength="10" name="count" id="count" value="{COUNT}" class="text" /></dd>
					</dl>
					<br />
					
					<label for="contents">* {L_CONTENTS}</label>
					{BBCODE_CONTENTS}
					<textarea type="text" rows="20" cols="90" id="contents" name="contents">{DESCRIPTION}</textarea>
					
					<br />
					<label for="short_contents">* {L_SHORT_CONTENTS}</label>
					{BBCODE_CONTENTS_SHORT}
					<textarea type="text" rows="20" cols="90" id="short_contents" name="short_contents">{SHORT_DESCRIPTION}</textarea>
					<br /><br />
					<dl>
						<dt>
							<label for="image">
								{L_FILE_IMAGE}
							</label>
						</dt>
						<dd>
							<input type="text" size="50" name="image" class="text" value="{FILE_IMAGE}" />
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="release_date">* {L_RELEASE_DATE}</label></dt>
						<dd>
							<label><input type="radio" value="2" name="visible" {VISIBLE_WAITING} /> 
						<input type="text" size="8" maxlength="8" id="start" name="creation" value="{START}" class="text" /> 
						<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
							<div id="start_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
							</div>
						</div>
						<a onClick="xmlhttprequest_calendar('start_date', '?input_field=start&amp;field=start_date&amp;d={DAY_RELEASE_S}&amp;m={MONTH_RELEASE_S}&amp;y={YEAR_RELEASE_S}');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img class="valign_middle" src="../templates/{THEME}/images/calendar.png" alt="" /></a>
						
						{L_UNTIL}&nbsp;
						
						<input type="text" size="8" maxlength="8" id="end" name="last_update" value="{END}" class="text" />					
						<div style="position:relative;z-index:100;top:6px;margin-left:155px;float:left;display:none;" id="calendar2">
							<div id="end_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);">							
							</div>
						</div>
						<a onClick="xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date&amp;d={DAY_RELEASE_E}&amp;m={MONTH_RELEASE_E}&amp;y={YEAR_RELEASE_E}');display_calendar(2);" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;"><img class="valign_middle" src="../templates/{THEME}/images/calendar.png" alt="" /></a></label>
						<br />
						<label><input type="radio" value="1" name="visible" {VISIBLE_ENABLED} id="release_date" /> {L_IMMEDIATE}</label>
						<br />
						<label><input type="radio" value="0" name="visible" {VISIBLE_UNAPROB} /> {L_UNAPROB}</label>
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="current_date">* {L_DOWNLOAD_DATE}</label></dt>
						<dd>
							{DATE_CALENDAR_CREATION}
						</dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
					&nbsp;&nbsp; 
					<input type="submit" name="preview" value="{L_PREVIEW}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>
			</form>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
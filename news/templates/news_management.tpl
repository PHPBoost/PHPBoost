		<script type="text/javascript">
		<!--
		function check_form()
		{
			# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
			# ENDIF #
			
			if( document.getElementById('contents').value == "" )
			{
				alert("{L_REQUIRE_DESCRIPTION}");
				return false;
		    }
			if( document.getElementById('title').value == "" )
			{
				alert("{L_REQUIRE_TITLE}");
				return false;
		    }
			reg_exp = new RegExp(".*", "g");
			if( !document.getElementById('url').match(reg_exp) )
			{
				alert("{L_REQUIRE_URL}");
				return false;
		    }
			if( !check_mini_calendar_form('creation') )
			{
				alert("{L_REQUIRE_CREATION_DATE}");
				return false;
			}
			if( document.getElementById("ignore_release_date").checked == false && !check_mini_calendar_form('release_date') )
			{
				alert("{L_REQUIRE_RELEASE_DATE}");
				return false;
			}
			return true;
		}
		-->
		</script>
		
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
			
			<script type="text/javascript">
			<!--
				var enabled = {BOOL_IGNORE_RELEASE_DATE};
				function show_hide_release_date()
				{
					if( enabled )
						document.getElementById("release_date_form").style.display = 'block';
					else
						document.getElementById("release_date_form").style.display = 'none';
					
					enabled = !enabled;
				}
			-->
			</script>
			
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
						<dd><input type="text" size="50" maxlength="100" id="title" name="title" value="{TITLE}" class="text" /></dd>
					</dl>
					<dl>
						<dt><label for="idcat">* {L_CATEGORY}</label></dt>
						<dd><label>
							{CATEGORIES_TREE}
						</label></dd>
					</dl>
					<dl>
						<dt><label for="url">* {L_URL}</label></dt>
						<dd><input type="text" size="50" id="url" name="url" id="url" value="{URL}" class="text" /></dd>
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
					{KERNEL_EDITOR}
					<textarea rows="20" cols="90" id="contents" name="contents">{DESCRIPTION}</textarea>					
					<br />
					<label for="short_contents">{L_SHORT_CONTENTS}</label>
					{KERNEL_EDITOR_SHORT}
					<textarea rows="20" cols="90" id="short_contents" name="short_contents">{SHORT_DESCRIPTION}</textarea>
					<br />
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
						<dt><label for="creation">* {L_DOWNLOAD_DATE}</label></dt>
						<dd>
							{DATE_CALENDAR_CREATION}
						</dd>
					</dl>
					<dl>
						<dt>
							<label>{L_IGNORE_RELEASE_DATE}</label>
						</dt>
						<dd>
							<input type="checkbox" id="ignore_release_date" name="ignore_release_date" onclick="show_hide_release_date();" {IGNORE_RELEASE_DATE_CHECKED} />
						</dd>
					</dl>
					<dl id="release_date_form" style="display:{STYLE_FIELD_RELEASE_DATE};" class="overflow_visible">
						<dt><label for="release">* {L_RELEASE_DATE}</label></dt>
						<dd>
							{DATE_CALENDAR_RELEASE}
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="visible">* {L_FILE_VISIBILITY}</label></dt>
						<dd>
							<input type="radio" value="2" name="visibility" {VISIBLE_WAITING} /> 
						{L_FROM_DATE}&nbsp;
						{BEGINING_CALENDAR}
						&nbsp;{L_TO_DATE}&nbsp;
						{END_CALENDAR}
						<br />
						<label>
							<input type="radio" value="1" name="visibility" {VISIBLE_ENABLED} id="release_date" /> {L_NOW}
						</label>
						<br />
						<label>
							<input type="radio" value="0" name="visibility" {VISIBLE_UNAPROVED} /> {L_UNAPPROVED}
						</label>
						</dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
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
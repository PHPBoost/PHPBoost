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
			if( document.getElementById('url').value == "" )
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
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
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
				<h1>{L_SHORT_CONTENTS}</h1>
				{SHORT_DESCRIPTION_PREVIEW}
				# IF C_CONTRIBUTION #
				<h1>{L_CONTRIBUTION_COUNTERPART}</h1>
				{CONTRIBUTION_COUNTERPART_PREVIEW}
				# ENDIF #
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
						<dd>
							<input type="text" size="50" id="url" name="url" value="{URL}" class="text" />
							<a title="{L_FILE_ADD}" href="#" onclick="window.open('{PATH_TO_ROOT}/member/upload.php?popup=1&amp;fd=url&amp;parse=true', '', 'height=500,width=720,resizable=yes,scrollbars=yes');return false;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/files_add.png" alt="" /></a>
						</dd>
					</dl>
					<dl>
						<dt><label for="size">{L_SIZE}</label></dt>
						<dd><input type="text" size="10" maxlength="10" name="size" id="size" value="{SIZE_FORM}" class="text" /> {L_UNIT_SIZE}</dd>
					</dl>
					<dl>
						<dt><label for="count">{L_NUMBER_OF_HITS}</label></dt>
						<dd><input type="text" size="10" maxlength="10" name="count" id="count" value="{COUNT}" class="text" /></dd>
					</dl>
					<dl>					
						<dt>
							<label for="download_method">{L_DOWNLOAD_METHOD}</label>
							<br /><span>{L_DOWNLOAD_METHOD_EXPLAIN}</span>
						</dt>
						<dd>
							<select name="download_method" id="download_method">
								<option value="force_download" {FORCE_DOWNLOAD_SELECTED}>{L_FORCE_DOWNLOAD}</option>
								<option value="redirect" {REDIRECTION_SELECTED}>{L_REDIRECTION}</option>
							</select>
						</dd>
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
							<input type="text" size="50" name="image" id="image" class="text" value="{FILE_IMAGE}" />
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label for="calendar_1">* {L_DOWNLOAD_DATE}</label></dt>
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
						<dt><label for="calendar_2">* {L_RELEASE_DATE}</label></dt>
						<dd>
							{DATE_CALENDAR_RELEASE}
						</dd>
					</dl>
					<dl class="overflow_visible">
						<dt><label>* {L_FILE_VISIBILITY}</label></dt>
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
							<input type="radio" value="0" name="visibility" {VISIBLE_HIDDEN} /> {L_HIDDEN}
						</label>
						</dd>
					</dl>
					# IF NOT C_CONTRIBUTION #
					<dl>
						<dt><label>{L_APPROVED}</label></dt>
						<dd>
							<input type="checkbox" name="approved" id="approved" {APPROVED} />
						</dd>
					</dl>
					# ENDIF #
				</fieldset>
				# IF C_CONTRIBUTION #
				<fieldset>
					<legend>{L_CONTRIBUTION_LEGEND}</legend>
					<div class="notice">
						{L_NOTICE_CONTRIBUTION}
					</div>
					<p><label>{L_CONTRIBUTION_COUNTERPART}</label></p>
					<p class="text_small">{L_CONTRIBUTION_COUNTERPART_EXPLAIN}</p>
					{CONTRIBUTION_COUNTERPART_EDITOR}
					<textarea rows="20" cols="40" id="counterpart" name="counterpart">{CONTRIBUTION_COUNTERPART}</textarea>
				</fieldset>
				# ENDIF #	
				
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
					&nbsp;&nbsp; 
					<input type="submit" name="preview" value="{L_PREVIEW}" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="{L_RESET}" class="reset" />
					<input type="hidden" name="token" value="{TOKEN}" />
				</fieldset>
			</form>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		
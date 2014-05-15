		<script>
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
		
		<section>			
			<header>
				<h1>{L_PAGE_TITLE}</h1>
			</header>
			<div class="content">
			
			<script>
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
				<div class="notice">{L_WARNING_PREVIEWING}</div>
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

			<form action="{U_TARGET}" method="post" onsubmit="return check_form();" class="fieldset-content">
				<fieldset>
					<legend>{L_PAGE_TITLE}</legend>
					<p>{L_REQUIRE}</p>
					<div class="form-element">
						<label for="title">* {L_TITLE}</label>
						<div class="form-field">
							<input type="text" size="50" maxlength="100" id="title" name="title" value="{TITLE}">
						</div>
					</div>
					<div class="form-element">
						<label for="idcat">* {L_CATEGORY}</label>
						<div class="form-field">
							{CATEGORIES_TREE}
						</div>
					</div>
					<div class="form-element">
						<label for="url">* {L_URL}</label>
						<div class="form-field">
							<input type="text" size="50" id="url" name="url" value="{URL}">
							<a title="{L_FILE_ADD}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=url&amp;parse=true', '', 'height=500,width=720,resizable=yes,scrollbars=yes');return false;"><i class="fa fa-cloud-upload fa-2x"></i></a>
						</div>
					</div>
					<div class="form-element">
						<label for="size">{L_SIZE}</label>
						<div class="form-field">
							<input type="text" size="10" maxlength="10" name="size" id="size" value="{SIZE_FORM}">
							{L_UNIT_SIZE}
						</div>
					</div>
					<div class="form-element">
						<label for="count">{L_NUMBER_OF_HITS}</label>
						<div class="form-field">
							<input type="text" size="10" maxlength="10" name="count" id="count" value="{COUNT}">
						</div>
					</div>
					<div class="form-element">					
						<label for="download_method">
							{L_DOWNLOAD_METHOD}
							<span class="field-description">{L_DOWNLOAD_METHOD_EXPLAIN}</span>
						</label>
						<div class="form-field">
							<select name="download_method" id="download_method">
								<option value="force_download" {FORCE_DOWNLOAD_SELECTED}>{L_FORCE_DOWNLOAD}</option>
								<option value="redirect" {REDIRECTION_SELECTED}>{L_REDIRECTION}</option>
							</select>
						</div>
					</div>
					<div class="form-element-textarea">
						<label for="contents">* {L_CONTENTS}</label>
						{KERNEL_EDITOR}
						<textarea rows="20" cols="90" id="contents" name="contents">{DESCRIPTION}</textarea>					
					</div>
					<div class="form-element-textarea">
						<label for="short_contents">{L_SHORT_CONTENTS}</label>
						{KERNEL_EDITOR_SHORT}
						<textarea rows="20" cols="90" id="short_contents" name="short_contents">{SHORT_DESCRIPTION}</textarea>
					</div>
					<div class="form-element">
						<label for="image">{L_FILE_IMAGE}</label>
						<div class="form-field">
							<input type="text" size="50" name="image" id="image" value="{FILE_IMAGE}">
						</div>
					</div>
					<div class="form-element overflow_visible">
						<label for="calendar_1">* {L_DOWNLOAD_DATE}</label>
						<div class="form-field">
							{DATE_CALENDAR_CREATION}
						</div>
					</div>
					<div class="form-element">
						<label>{L_IGNORE_RELEASE_DATE}</label>
						<div class="form-field">
							<input type="checkbox" id="ignore_release_date" name="ignore_release_date" onclick="show_hide_release_date();" {IGNORE_RELEASE_DATE_CHECKED}>
						</div>
					</div>
					<div class="form-element overflow_visible" id="release_date_form" style="display:{STYLE_FIELD_RELEASE_DATE};">
						<label for="calendar_2">* {L_RELEASE_DATE}</label>
						<div class="form-field">
							{DATE_CALENDAR_RELEASE}
						</div>
					</div>
					<div class="form-element overflow_visible">
						<label>* {L_FILE_VISIBILITY}</label>
						<div class="form-field">
							<input type="radio" value="2" name="visibility" {VISIBLE_WAITING}> 
							{L_FROM_DATE}&nbsp;
							{BEGINING_CALENDAR}
							&nbsp;{L_TO_DATE}&nbsp;
							{END_CALENDAR}
							<label>
								<input type="radio" value="1" name="visibility" {VISIBLE_ENABLED} id="release_date"> {L_NOW}
							</label>
							<label>
								<input type="radio" value="0" name="visibility" {VISIBLE_HIDDEN}> {L_HIDDEN}
							</label>
						</div>
					</div>
					# IF NOT C_CONTRIBUTION #
					<div class="form-element">
						<label>{L_APPROVED}</label>
						<div class="form-field">
							<input type="checkbox" name="approved" id="approved" {APPROVED}>
						</div>
					</div>
					# ENDIF #
				</fieldset>
				# IF C_CONTRIBUTION #
				<fieldset>
					<legend>{L_CONTRIBUTION_LEGEND}</legend>
					<div class="notice">{L_NOTICE_CONTRIBUTION}</div>
					<p><label>{L_CONTRIBUTION_COUNTERPART}</label></p>
					<p class="smaller">{L_CONTRIBUTION_COUNTERPART_EXPLAIN}</p>
					{CONTRIBUTION_COUNTERPART_EDITOR}
					<textarea rows="20" cols="40" id="counterpart" name="counterpart">{CONTRIBUTION_COUNTERPART}</textarea>
				</fieldset>
				# ENDIF #	
				
				<fieldset class="fieldset-submit">
					<legend>{L_SUBMIT}</legend>
					<button type="submit" name="submit" value="true" class="submit">{L_SUBMIT}</button>
					<button type="submit" name="preview" value="true">{L_PREVIEW}</button>
					<button type="reset" value="true">{L_RESET}</button>
					<input type="hidden" name="token" value="{TOKEN}">
				</fieldset>
			</form>
		</div>
	<footer></footer>
</section>
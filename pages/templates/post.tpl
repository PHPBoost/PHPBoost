		<script type="text/javascript">
		<!--
			var path = '{PAGES_PATH}';
			var selected_cat = {SELECTED_CAT};
			function check_form_post(){
				if(document.getElementById('title').value == "") {
					alert("{L_ALERT_TITLE}");
					return false;
				}
				if(document.getElementById('contents').value == "") {
					alert("{L_ALERT_CONTENTS}");
					return false;
				}
				return true;
			}
			var disabled = {OWN_AUTH_DISABLED};
			function disable_own_auth()
			{
				if( disabled )
				{
					disabled = false;
					document.getElementById("own_auth_display").style.display = 'block';
				}
				else
				{
					document.getElementById("own_auth_display").style.display = 'none';
					disabled = true;
				}
			}
			function check_select_multiple(id, status)
			{
				var i;		
				for(i = -1; i <= 2; i++)
				{
					if( document.getElementById(id + 'r' + i) )
						document.getElementById(id + 'r' + i).selected = status;
				}				
				document.getElementById(id + 'r3').selected = true;
				
				for(i = 0; i < {NBR_GROUP}; i++)
				{	
					if( document.getElementById(id + 'g' + i) )
						document.getElementById(id + 'g' + i).selected = status;		
				}	
			}	
			function check_select_multiple_ranks(id, start)
			{
				var i;				
				for(i = start; i <= 3; i++)
				{	
					if( document.getElementById(id + i) )
						document.getElementById(id + i).selected = true;			
				}
			}		
		-->
		</script>

		<script type="text/javascript" src="{PAGES_PATH}/images/pages.js"></script>
	
		# START error_handler #
			<div class="{error_handler.CLASS}">
				<img src="../templates/{THEME}/images/{error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {error_handler.L_ERROR}
				<br />
			</div>
			<br />
		# END error_handler #
		
		# START previewing #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{L_PREVIEWING}: {previewing.TITLE}</div>
			<div class="module_contents">{previewing.PREVIEWING}</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		# END previewing #
			
		<form action="{TARGET}" method="post"  onsubmit="return check_form_post();" class="fieldset_content">					
			<fieldset>
				<legend>{L_TITLE_POST}</legend>
				# START create #
				<dl>
					<dt><label for="title">{L_TITLE_FIELD}</label></dt>
					<dd><label><input type="text" class="text" id="title" name="title" size="70" maxlength="250" value="{PAGE_TITLE}" /></label></dd>					
				</dl>
				# END create #
				<br />
				<label for="contents">{L_CONTENTS}</label>
				# INCLUDE handle_bbcode #
				<label><textarea rows="25" cols="66" id="contents" name="contents">{CONTENTS}</textarea></label>
				<br />
			</fieldset>	
			
			<fieldset>
				<legend>{L_PATH}</legend>
				<dl>
					<dt><label for="is_cat">{L_IS_CAT}</label></dt>
					<dd><label><input type="checkbox" name="is_cat" id="is_cat" {CHECK_IS_CAT} /></label></dd>					
				</dl>
				<dl>
					<dt><label>{L_CAT}</label></dt>
					<dd>
						<input type="hidden" name="id_cat" id="id_cat" value="{ID_CAT}"/>
						<span style="padding-left:17px;"><a href="javascript:select_cat(0);"><img src="{PAGES_PATH}/images/cat_root.png" alt="" /> <span id="class_0" class="{CAT_0}">{L_ROOT}</span></a></span>
						<br />
						<ul style="margin:0;padding:0;list-style-type:none;line-height:normal;">
						{CAT_LIST}
						</ul>
					</dd>					
				</dl>
			</fieldset>
			
			<fieldset>
				<legend>{L_PROPERTIES}</legend>
				<dl>
					<dt><label for="count_hits">{L_COUNT_HITS}</label></dt>
					<dd><label><input type="checkbox" id="count_hits" name="count_hits" {COUNT_HITS_CHECKED} /></label></dd>					
				</dl>
				<dl>
					<dt><label for="activ_com">{L_ACTIV_COM}</label></dt>
					<dd><label><input type="checkbox" id="activ_com" name="activ_com" {ACTIV_COM_CHECKED} /></label></dd>					
				</dl>
			</fieldset>
			
			<fieldset>
				<legend>{L_AUTH}</legend>
				<dl>
					<dt><label for="own_auth">{L_OWN_AUTH}</label></dt>
					<dd><label><input type="checkbox" name="own_auth" id="own_auth" onclick="disable_own_auth();" {OWN_AUTH_CHECKED} /></label></dd>					
				</dl>
				<span id="own_auth_display" style="{DISPLAY}">
					<dl>
						<dt><label>{L_READ_PAGE}</label></dt>
						<dd>
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_READ_PAGE}
							<br />
							<a href="javascript:check_select_multiple('1', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('1', false);">{L_SELECT_NONE}</a>
						</dd>					
					</dl>
					<dl>
						<dt><label>{L_EDIT_PAGE}</label></dt>
						<dd>
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_EDIT_PAGE}
							<br />
							<a href="javascript:check_select_multiple('2', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('2', false);">{L_SELECT_NONE}</a>
						</dd>					
					</dl>
					<dl>
						<dt><label>{L_READ_COM}</label></dt>
						<dd>
							<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
							<br />
							{SELECT_READ_COM}
							<br />
							<a href="javascript:check_select_multiple('3', true);">{L_SELECT_ALL}</a>
							&nbsp;/&nbsp;
							<a href="javascript:check_select_multiple('3', false);">{L_SELECT_NONE}</a>
						</dd>					
					</dl>
				</span>
			</fieldset>
			
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" name="id_edit" value="{ID_EDIT}" />
				<input type="submit" class="submit" value="{L_SUBMIT}" />
				<input type="submit" class="submit" value="{L_PREVIEW}" name="preview" />
				<input value="{L_RESET}" class="reset" type="reset" />
			</fieldset>
		</form>
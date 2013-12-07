		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_CONTENT_CONFIG}</li>
				<li>
					<a href="admin_content_config.php"><img src="{PATH_TO_ROOT}/templates/default/images/admin/content.png" alt="" /></a>
					<br />
					<a href="admin_content_config.php" class="quick_link">{L_CONTENT_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<script type="text/javascript">
		<!--
			function check_select_multiple(id, status)
			{
				var i;
				
				for(i = 0; i < {NBR_TAGS}; i++)
				{	
					if( document.getElementById(id + i) )
						document.getElementById(id + i).selected = status;			
				}
			}	
		-->
		</script>
		
		<div id="admin_contents">
			<form action="admin_content_config.php" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_LANGUAGE_CONFIG}</legend>
					<div class="form-element"> 
						<label for="formatting_language">{L_DEFAULT_LANGUAGE}</label>
						<div class="form-field">
							<select name="formatting_language" id="formatting_language">
								# START formatting_language #
									<option value="{formatting_language.ID}" # IF formatting_language.C_SELECTED # selected="selected" # ENDIF # >{formatting_language.NAME}</option>
								# END formatting_language #
							</select>
						</div>
					</div>
					<div class="form-element"> 
						<label for="forbidden_tags">{L_FORBIDDEN_TAGS}</label>
						<div class="form-field">
								<select id="forbidden_tags" name="forbidden_tags[]" size="10" multiple="multiple">
								# START tag #
									# IF tag.C_ENABLED #
									<option id="tag{tag.IDENTIFIER}" selected="selected" value="{tag.CODE}">{tag.TAG_NAME}</option>
									# ELSE #
									<option id="tag{tag.IDENTIFIER}" value="{tag.CODE}">{tag.TAG_NAME}</option>
									# ENDIF #
								# END tags #
								</select>
								<br />
								<span class="smaller">({L_EXPLAIN_SELECT_MULTIPLE})</span>
								<br />
								<a class="small" href="javascript:check_select_multiple('tag', true);">{L_SELECT_ALL}</a>/<a class="small" href="javascript:check_select_multiple('tag', false);">{L_SELECT_NONE}</a>
						</div>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>{L_HTML_LANGUAGE}</legend>
					<div class="form-element"> 
						<label for="groups_auth1">{L_AUTH_USE_HTML}</label>
						<div class="form-field">
							{SELECT_AUTH_USE_HTML}
						</div>
					</div>
				</fieldset>
				
				<fieldset>  
					<legend>{L_POST_MANAGEMENT}</legend> 
					<div class="form-element">
						<label for="pm_max">{L_PM_MAX}</label><br /><span>{L_PM_MAX_EXPLAIN}</span>
						<div class="form-field"><label><input type="text" size="2" name="pm_max" id="pm_max" value="{PM_MAX}"></label></div>
					</div>
					<div class="form-element">
						<label for="anti_flood">{L_ANTI_FLOOD}</label><br /><span>{L_ANTI_FLOOD_EXPLAIN}</span>
						<div class="form-field">
							<label><input type="radio" {FLOOD_ENABLED} name="anti_flood" id="anti_flood" value="1"> {L_ACTIV}&nbsp;&nbsp;</label>
							<label><input type="radio" {FLOOD_DISABLED} name="anti_flood" value="0"> {L_UNACTIVE}</label>
						</div>
					</div>
					<div class="form-element">
						<label for="delay_flood">{L_INT_FLOOD}</label><br /><span>{L_INT_FLOOD_EXPLAIN}</span>
						<div class="form-field"><label><input type="text" size="3" maxlength="9" name="delay_flood" id="delay_flood" value="{DELAY_FLOOD}"> {L_SECONDS}</label></div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<button type="submit" name="submit" value="true">{L_SUBMIT}</button>
					<button type="reset" value="true">{L_RESET}</button>
					<input type="hidden" name="token" value="{TOKEN}">					
				</fieldset>	
			</form>
		</div>
		

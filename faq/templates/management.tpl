		# START category #
		<div class="module_position">			
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{TITLE}</div>
			<div class="module_contents">
				<p style="text-align:center;">
					<a href="{U_GO_BACK_TO_CAT}">{L_GO_BACK_TO_CAT}</a>
				</p>
				
				<script type="text/javascript">
				<!--
					var global_auth = {JS_GLOBAL};
					function change_status_global_auth()
					{
						if( global_auth )
							hide_div("hide_global_auth");
						else
							show_div("hide_global_auth");
						global_auth = !global_auth;
					}
					
					function check_form_faq()
					{
						if( document.getElementById("id_faq").value > 0 && document.getElementById("cat_name").value == "" )
						{
							alert("{L_REQUIRE_CAT_NAME}");
							return false;
						}
						else
							return true;
					}
					
					# IF C_DISPLAY_ANSWERS #
					var display_answers = false;
					
					function show_hide_questions ( )
					{
						display_answers = !display_answers;
						var string_display = "";
						if( display_answers )
						{
							string_display = "block";
							document.getElementById("l_change_answers_status").innerHTML = "{L_HIDE_ANSWERS}";
						}
						else
						{
							string_display = "none";
							document.getElementById("l_change_answers_status").innerHTML = "{L_DISPLAY_ANSWERS}";
						}
						
						for( var i = 1; i <= {NUM_QUESTIONS}; i++ )
						{
							document.getElementById("a" + i).style.display = string_display;
						}
					}
					# ENDIF #
				-->
				</script>
				
				<form action="{TARGET}" method="post" onsubmit="javascript:return check_form_faq();">
					<fieldset>
						<legend>{L_CAT_PROPERTIES}</legend>
						# START category.not_root_name #
						<dl>
							<dt>
								<label for="cat_title">{L_CAT_NAME}</label>
							</dt>
							<dd>
								<input type="text" name="cat_name" id="cat_name" value="{category.not_root_name.CAT_TITLE}" />
							</dd>
						</dl>
						# END category.not_root_name #
						<label for="contents">{L_DESCRIPTION}</label>
						{KERNEL_EDITOR}
						<textarea id="contents" rows="15" cols="40" name="description">{DESCRIPTION}</textarea>
						<br />
						<div style="text-align:center;">
							<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" class="submit" type="button" />
						</div>
						<br />
						<dl>
							<dt>
								<label for="display_mode">{L_DISPLAY_MODE}</label>
								<br />
								<span class="text_small">{L_DISPLAY_EXPLAIN}</span>
							</dt>
							<dd>
								<select name="display_mode" id="display_mode">
									<option value="0" {AUTO_SELECTED}>{L_DISPLAY_AUTO}</option>
									<option value="1" {INLINE_SELECTED}>{L_DISPLAY_INLINE}</option>
									<option value="2" {BLOCK_SELECTED}>{L_DISPLAY_BLOCK}</option>
								</select>
							</dd>					
						</dl>
						# START category.not_root_auth #
						<dl>
							<dt><label for="global_auth">{L_GLOBAL_AUTH}</label>
							<br />
							<span class="text_small">{L_GLOBAL_AUTH_EXPLAIN}</span></dt>
							<dd>
								<input type="checkbox" name="global_auth" id="global_auth" onclick="javascript: change_status_global_auth();" {GLOBAL_CHECKED} />
							</dd>					
						</dl>
						<div id="hide_global_auth" style="display:{DISPLAY_GLOBAL};">
							<dl>
								<dt>
									<label>
										{L_READ_AUTH}
									</label>
								</dt>
								<dd>
									{category.READ_AUTH}
								</dd>					
							</dl>
							<dl>
								<dt>
									<label>
										{L_WRITE_AUTH}
									</label>
								</dt>
								<dd>
									{category.WRITE_AUTH}
								</dd>					
							</dl>
						</div>
						# END category.not_root_auth #
					</fieldset>
					<fieldset class="fieldset_submit">
						<legend>{L_SUBMIT}</legend>
						<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
						&nbsp;&nbsp; 
						<input type="reset" value="{L_RESET}" class="reset" />
						<input type="hidden" id="id_faq" name="id_faq" value="{category.ID_FAQ}" />
					</fieldset>
				</form>
				
				<fieldset>
					<legend>{L_QUESTIONS_LIST}</legend>
					# IF C_DISPLAY_ANSWERS #
						<script type="text/javascript">
							document.write('<div style="text-align:center;"><a href="javascript:show_hide_questions();" id="l_change_answers_status">{L_DISPLAY_ANSWERS}</a></div><br />');
						</script>
					# ENDIF #
					<p style="text-align:center;">
						<a href="{category.U_CREATE_BEFORE}" title="{L_INSERT_QUESTION_BEFORE}"><img src="../templates/{THEME}/images/{LANG}/add.png" alt="{L_INSERT_QUESTION_BEFORE}" /></a>
					</p>
					<br />
					# START category.questions #
						<div class="row1" id="q{category.questions.ID}">
							<span style="float:left;">
								<img src="{PICTURES_DATA_PATH}/images/line.png" alt="arrow" class="image_left" style="vertical-align:middle;" />
								{category.questions.QUESTION}
							</span>
							<span class="row2" style="float:right;">
								<a href="{category.questions.U_MOVE}" title="{L_MOVE}">
									<img src="../templates/{THEME}/images/upload/move.png" alt="{L_MOVE}" />
								</a>
								# START category.questions.up #
									<a href="{category.questions.U_UP}" title="{L_UP}">
										<img src="{PICTURES_DATA_PATH}/images/up.png" alt="{L_UP}" />
									</a>
								# END category.questions.up #
								# START category.questions.down #
									<a href="{category.questions.U_DOWN}" title="{L_DOWN}">
										<img src="{PICTURES_DATA_PATH}/images/down.png" alt="{L_DOWN}" />
									</a>
								# END category.questions.down #
								<a href="{category.questions.U_EDIT}" title="{L_EDIT}"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" /></a>
								<a href="{category.questions.U_DEL}" onclick="return confirm('{L_CONFIRM_DELETE}');" title="{L_DELETE}"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" /></a>
							</span>
							<div style="clear:both"></div>
						</div>
						<br />
						<div id="a{category.questions.ID}" class="blockquote" style="display:none;">
							{category.questions.ANSWER}
						</div>	
						<br />
						<div style="text-align:center;">
							<a href="{category.questions.U_CREATE_AFTER}" title="{L_INSERT_QUESTION}"><img src="../templates/{THEME}/images/{LANG}/add.png" alt="{L_INSERT_QUESTION}" /></a>
						</div>
						<br />
					# END category.questions #
				</fieldset>&nbsp;
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>

		# END category #

		# START edit_question #
		<script type="text/javascript">
		<!--
		function check_form_faq()
		{
			# IF C_BBCODE_TINYMCE_MODE #
				tinyMCE.triggerSave();
			# ENDIF #
			
			if( document.getElementById("entitled").value == '' )
			{
				alert("{L_REQUIRE_ENTITLED}");
				return false;
			}
			else if( document.getElementById("contents").value == '' )
			{
				alert("{L_REQUIRE_ANSWER}");
				return false;
			}
			else
				return true;
		}
		-->
		</script>
		<div class="module_position">			
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{TITLE}</div>
			<div class="module_contents">
				<form action="{edit_question.TARGET}" method="post" onsubmit="return check_form_faq()">
					<fieldset>
						<legend>{L_QUESTION}</legend>
						<dl>
							<dt>
								<label for="entitled">{L_ENTITLED}</label>
							</dt>
							<dd>
								<input type="text" value="{edit_question.ENTITLED}" maxlength="255" size="50" id="entitled" name="entitled" />
							</dd>
						</dl>
						<label for="contents">{L_ANSWER}</label>
						{KERNEL_EDITOR}
						<textarea id="contents" rows="15" cols="66" name="answer">{edit_question.ANSWER}</textarea>
						<br />
					</fieldset>
					<fieldset class="fieldset_submit">
						<legend>{L_SUBMIT}</legend>
						<input type="submit" name="valid" value="{L_SUBMIT}" class="submit" />
						&nbsp;&nbsp;
						<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" class="submit" type="button" />
						&nbsp;&nbsp;
						<input type="reset" value="{L_RESET}" class="reset" />
						<input type="hidden" name="id_question" value="{edit_question.ID_QUESTION}" />
						<input type="hidden" name="id_cat" value="{edit_question.ID_CAT}" />
						<input type="hidden" name="after" value="{edit_question.ID_AFTER}" />
					</fieldset>					
				</form>
				&nbsp;
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		# END edit_question #

		
		# START move_question #
		<div class="module_position">			
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">{TITLE}</div>
			<div class="module_contents">
				<form action="{U_FORM_TARGET}" method="post">
					<fieldset>
						<legend>{L_TARGET}</legend>
						<dl>
							<dt>
								<label for="target">{L_TARGET}</label>
							</dt>
							<dd>
								{move_question.CATEGORIES_TREE}
							</dd>
						</dl>
					</fieldset>
					<fieldset class="fieldset_submit">
						<legend>{L_MOVE}</legend>
						<input type="submit" name="submit" value="{L_MOVE}" class="submit" />
						<input type="hidden" name="id_question" value="{move_question.ID_QUESTION}" />
						<input type="hidden" name="move_question" value="true" />
					</fieldset>					
				</form>
				&nbsp;
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		# END move_question #

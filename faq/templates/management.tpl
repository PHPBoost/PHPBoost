		# START category #
		<section>			
			<header>
				<h1>{TITLE}</h1>
			</header>
			<div class="content">
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
						<div class="form-element">
							
								<label for="cat_title">{L_CAT_NAME}</label>
							
							<div class="form-field">
								<input type="text" name="cat_name" id="cat_name" value="{category.not_root_name.CAT_TITLE}">
							</div>
						</div>
						# END category.not_root_name #
						<label for="contents">{L_DESCRIPTION}</label>
						{KERNEL_EDITOR}
						<textarea id="contents" rows="15" cols="40" name="description">{DESCRIPTION}</textarea>
						<br />
						<div style="text-align:center;">
							<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" class="submit" type="button">
						</div>
						<br />
						<div class="form-element">
							
								<label for="display_mode">{L_DISPLAY_MODE}</label>
								<br />
								<span class="smaller">{L_DISPLAY_EXPLAIN}</span>
							
							<div class="form-field">
								<select name="display_mode" id="display_mode">
									<option value="0" {AUTO_SELECTED}>{L_DISPLAY_AUTO}</option>
									<option value="1" {INLINE_SELECTED}>{L_DISPLAY_INLINE}</option>
									<option value="2" {BLOCK_SELECTED}>{L_DISPLAY_BLOCK}</option>
								</select>
							</div>					
						</div>
						# START category.not_root_auth #
						<div class="form-element">
							<label for="global_auth">{L_GLOBAL_AUTH}</label>
							<br />
							<span class="smaller">{L_GLOBAL_AUTH_EXPLAIN}</span>
							<div class="form-field">
								<input type="checkbox" name="global_auth" id="global_auth" onclick="javascript: change_status_global_auth();" {GLOBAL_CHECKED}>
							</div>					
						</div>
						<div id="hide_global_auth" style="display:{DISPLAY_GLOBAL};">
							<div class="form-element">
								
									<label>
										{L_READ_AUTH}
									</label>
								
								<div class="form-field">
									{category.READ_AUTH}
								</div>					
							</div>
							<div class="form-element">
								
									<label>
										{L_WRITE_AUTH}
									</label>
								
								<div class="form-field">
									{category.WRITE_AUTH}
								</div>					
							</div>
						</div>
						# END category.not_root_auth #
					</fieldset>
					<fieldset class="fieldset_submit">
						<legend>{L_SUBMIT}</legend>
						<button type="submit" name="valid" value="true">{L_SUBMIT}</button>
						&nbsp;&nbsp; 
						<button type="reset" value="true">{L_RESET}</button>
						<input type="hidden" id="id_faq" name="id_faq" value="{category.ID_FAQ}">
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
						<a href="{category.U_CREATE_BEFORE}" title="{L_INSERT_QUESTION_BEFORE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" alt="{L_INSERT_QUESTION_BEFORE}" /></a>
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
									<img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/move.png" alt="{L_MOVE}" />
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
								<a href="{category.questions.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="pbt-icon-edit"></a>
								<a href="{category.questions.U_DEL}" title="${LangLoader::get_message('delete', 'main')}" class="pbt-icon-delete" data-confirmation="delete-element"></a>
							</span>
							<div style="clear:both"></div>
						</div>
						<br />
						<div id="a{category.questions.ID}" class="blockquote" style="display:none;">
							{category.questions.ANSWER}
						</div>	
						<br />
						<div style="text-align:center;">
							<a href="{category.questions.U_CREATE_AFTER}" title="{L_INSERT_QUESTION}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" alt="{L_INSERT_QUESTION}" /></a>
						</div>
						<br />
					# END category.questions #
				</fieldset>&nbsp;
			</div>
			<footer></footer>
		</section>

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
		<section>			
			<header>
				<h1>{TITLE}</h1>
			</header>
			<div class="content">
				<form action="{edit_question.TARGET}" method="post" onsubmit="return check_form_faq()">
					<fieldset>
						<legend>{L_QUESTION}</legend>
						<div class="form-element">
							
								<label for="entitled">{L_ENTITLED}</label>
							
							<div class="form-field">
								<input type="text" value="{edit_question.ENTITLED}" maxlength="255" size="50" id="entitled" name="entitled">
							</div>
						</div>
						<label for="contents">{L_ANSWER}</label>
						{KERNEL_EDITOR}
						<textarea id="contents" rows="15" cols="66" name="answer">{edit_question.ANSWER}</textarea>
						<br />
					</fieldset>
					<fieldset class="fieldset_submit">
						<legend>{L_SUBMIT}</legend>
						<button type="submit" name="valid" value="true">{L_SUBMIT}</button>
						&nbsp;&nbsp;
						<input value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" class="submit" type="button">
						&nbsp;&nbsp;
						<button type="reset" value="true">{L_RESET}</button>
						<input type="hidden" name="id_question" value="{edit_question.ID_QUESTION}">
						<input type="hidden" name="id_cat" value="{edit_question.ID_CAT}">
						<input type="hidden" name="after" value="{edit_question.ID_AFTER}">
					</fieldset>					
				</form>
				&nbsp;
			</div>
			<footer></footer>
		</section>
		# END edit_question #

		
		# START move_question #
		<section>			
			<header>
				<h1>{TITLE}</h1>
			</header>
			<div class="content">
				<form action="{U_FORM_TARGET}" method="post">
					<fieldset>
						<legend>{L_TARGET}</legend>
						<div class="form-element">
							
								<label for="target">{L_TARGET}</label>
							
							<div class="form-field">
								{move_question.CATEGORIES_TREE}
							</div>
						</div>
					</fieldset>
					<fieldset class="fieldset_submit">
						<legend>{L_MOVE}</legend>
						<button type="submit" name="submit" value="true">{L_MOVE}</button>
						<input type="hidden" name="id_question" value="{move_question.ID_QUESTION}">
						<input type="hidden" name="move_question" value="true">
					</fieldset>					
				</form>
				&nbsp;
			</div>
			<footer></footer>
		</section>
		# END move_question #

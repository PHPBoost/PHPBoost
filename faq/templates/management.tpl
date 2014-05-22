		# START edit_question #
		<script>
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
				<form action="{PATH_TO_ROOT}/faq/action.php?token={TOKEN}" method="post" onsubmit="return check_form_faq()">
					<p class="center">{L_REQUIRED_FIELDS}</p>
					<fieldset>
						<legend>{L_QUESTION}</legend>
						<div class="form-element">
							<label for="idcat">{L_CATEGORY}</label>
							<div class="form-field">
								{edit_question.CATEGORIES_TREE}
							</div>
						</div>
						<div class="form-element">
							<label for="entitled">* {L_ENTITLED}</label>
							<div class="form-field">
								<input type="text" value="{edit_question.ENTITLED}" maxlength="255" size="50" id="entitled" name="entitled">
							</div>
						</div>
						<div class="form-element-textarea">
							<label for="contents">* {L_ANSWER}</label>
							{KERNEL_EDITOR}
							<textarea id="contents" rows="15" cols="66" name="answer">{edit_question.ANSWER}</textarea>
							<div class="center">
								<button type="button" value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" class="small">{L_PREVIEW}</button>
							</div>
						</div>
					</fieldset>
					<fieldset class="fieldset-submit">
						<legend>{L_SUBMIT}</legend>
						<button type="submit" name="valid" value="true" class="submit">{L_SUBMIT}</button>
						<button type="reset" value="true">{L_RESET}</button>
						<input type="hidden" name="id_question" value="{edit_question.ID_QUESTION}">
					</fieldset>
				</form>
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
				<form action="{PATH_TO_ROOT}/faq/action.php?token={TOKEN}" method="post">
					<fieldset>
						<legend>{L_TARGET}</legend>
						<div class="form-element">
							<label for="target">{L_TARGET}</label>
							<div class="form-field">
								{move_question.CATEGORIES_TREE}
							</div>
						</div>
					</fieldset>
					<fieldset class="fieldset-submit">
						<legend>{L_MOVE}</legend>
						<button type="submit" name="submit" value="true" class="submit">{L_MOVE}</button>
						<input type="hidden" name="id_question" value="{move_question.ID_QUESTION}">
						<input type="hidden" name="move_question" value="true">
					</fieldset>
				</form>
			</div>
			<footer></footer>
		</section>
		# END move_question #
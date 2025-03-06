# INCLUDE FORUM_TOP #

<script>
	function check_form_post(){
		if(document.getElementById('content').value == "") {
			alert("{@warning.text}");
			return false;
	    }
		if(document.getElementById('title').value == "") {
			alert("{@warning.title}");
			return false;
	    }
		if(!poll_hidded && document.getElementById('question').value == "") {
			alert("{@forum.require.poll.title}");
			return false;
		}
		return true;
	}
	var poll_hidded = true;
	function hide_poll(divID)
	{
		if( document.getElementById(divID) )
		{
			document.getElementById(divID).style.display = 'block';
			if( document.getElementById('hidepoll_link') )
			{
				document.getElementById('hidepoll_link').style.display = 'none';
				poll_hidded = false;
			}
		}
	}
	function add_poll_field(nbr_field)
	{
		if ( typeof this.max_field_p == 'undefined' )
			this.max_field_p = (nbr_field == 0) ? 5 : nbr_field;
		else
			this.max_field_p++;

		if( this.max_field_p < 20 )
		{
			if( this.max_field_p == 19 )
			{
				if( document.getElementById('add_poll_field_link') )
					document.getElementById('add_poll_field_link').innerHTML = '';
			}
			document.getElementById('add_poll_field' + this.max_field_p).insertAdjacentHTML('afterend', '<label class="d-block"><input type="text" name="a' + this.max_field_p + '" value="" /></label><span id="add_poll_field' + (this.max_field_p + 1) + '"></span>');
		}
	}
	function XMLHttpRequest_change_statut()
	{
		var idtopic = {IDTOPIC};
		if( document.getElementById('forum_change_img') )
			document.getElementById('forum_change_img').innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

		var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&msg_d=' + idtopic);
		xhr_object.onreadystatechange = function()
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 )
			{
				if( document.getElementById('forum_change_img') )
					document.getElementById('forum_change_img').innerHTML = xhr_object.responseText == '1' ? '<i class="fa fa-times error"></i>' : '<i class="fa fa-check success"></i>';
				if( document.getElementById('forum_change_msg') )
					document.getElementById('forum_change_msg').innerHTML = xhr_object.responseText == '1' ? "{L_SOLVED_TOPIC}" : "{L_UNSOLVED_TOPIC}";
			}
		}
		xmlhttprequest_sender(xhr_object, null);
	}
</script>

<article itemscope="itemscope" itemtype="https://schema.org/Creativework" id="article-forum-post" class="forum-content">
	<header>
		<h2>
			<a class="offload" href="{U_CATEGORY}">{CATEGORY_NAME}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a class="offload" href="{U_TITLE_T}">{L_NEW_SUBJECT}</a> <span class="small"><em>{DESCRIPTION}</em></span>
		</h2>
	</header>

	<div class="content">
		<form action="{U_ACTION}" method="post" onsubmit="return check_form_post();">
			# INCLUDE MESSAGE_HELPER #

			<div class="fieldset-content">
				<p class="align-center small text-italic">{@form.required.fields}</p>
				<fieldset>
					<legend>{L_ACTION}</legend>
					# IF C_FORUM_CUT_CAT #
						<div class="form-element">
							<label for="to">* {L_CAT}</label>
							<div class="form-field">
								<select id="to" name="to">
									{CATEGORIES}
								</select>
							</div>
						</div>
					# ENDIF #
					<div class="form-element">
						<label for="title">* {@form.title}</label>
						<div class="form-field"><input type="text" id="title" name="title" value="{TITLE}"></div>
					</div>
					<div class="form-element">
						<label for="desc">{@form.description}</label>
						<div class="form-field"><input type="text" id="desc" name="desc" value="{DESCRIPTION}"></div>
					</div>
					<div class="form-element form-element-textarea">
						<label for="content">* {@common.message}</label>
						<div class="form-field form-field-textarea bbcode-sidebar">
							{KERNEL_EDITOR}
							<textarea rows="15" cols="40" id="content" name="content">{CONTENT}</textarea>
						</div>
						<button type="button" class="button preview-button" onclick="XMLHttpRequest_preview();">{@form.preview}</button>
					</div>
					# IF C_FORUM_POST_TYPE #
					<div class="form-element inline-radio">
						<label for="type">* {@common.type}</label>
						<div class="form-field">
							<label class="radio">
								<input type="radio" name="type" id="type" value="0"# IF C_NORMAL_TYPE_SELECTED # checked="checked"# ENDIF #>
								<span>{@common.default}</span>
							</label>
							<label class="radio">
								<input type="radio" name="type" value="1"# IF C_PINNED_TYPE_SELECTED # checked="checked"# ENDIF #>
								<span>{@forum.pinned}</span>
							</label>
							<label class="radio">
								<input type="radio" name="type" value="2"# IF C_ANNOUNCE_TYPE_SELECTED # checked="checked"# ENDIF #>
								<span>{@forum.announce}</span>
							</label>
						</div>
					</div>
					# ENDIF #
				</fieldset>

				<fieldset>
					<legend>{@forum.poll}</legend>
					<p id="hidepoll_link" class="align-center"><a href="#" onclick="hide_poll('hidepoll');return false;">{@forum.open.poll.menu}</a></p>
					<div id="hidepoll">
						<div class="form-element">
							<label for="question">* {@forum.question}</label>
							<div class="form-field"><input type="text" name="question" id="question" value="{POLL_QUESTION}"></div>
						</div>
						<div class="form-element inline-radio">
							<label for="poll_type">{@forum.poll.type}</label>
							<div class="form-field">
								<label class="radio">
									<input type="radio" name="poll_type" id="poll_type" value="0"# IF C_SIMPLE_POLL_SELECTED # checked="checked"# ENDIF #>
									<span>{@forum.simple.answer}</span>
								</label>
								<label class="radio">
									<input type="radio" name="poll_type" value="1"# IF C_MULTIPLE_POLL_SELECTED # checked="checked"# ENDIF #>
									<span>{@forum.multiple.answer}</span>
								</label>
							</div>
						</div>
						# IF C_DELETE_POLL #
							<div class="form-element">
								<label for="del_poll">{@forum.delete.poll}</label>
								<div class="form-field">
									<label class="checkbox">
										<input type="checkbox" name="del_poll" id="del_poll" value="true">
										<span></span>
									</label>
								</div>
							</div>
						# ENDIF #
						<div class="form-element">
							<label>{@forum.answers}</label>
							<div class="form-field">
								# START answers_poll #
									<label class="d-block"><input type="text" name="a{answers_poll.ID}" value="{answers_poll.ANSWER}" /> <em>{answers_poll.NBR_VOTES} {answers_poll.L_VOTES}</em></label>
								# END answers_poll #
								<span id="add_poll_field{NBR_POLL_FIELD}"></span>

								<p class="align-center" id="add_poll_field_link">
									# IF C_ADD_POLL_FIELD #
										<a aria-label="{@common.add}" href="#" onclick="add_poll_field({NBR_POLL_FIELD});return false;"><i class="fa fa-plus" aria-hidden="true"></i></a>
									# ENDIF #
								</p>
							</div>
						</div>
						<p class="align-center">
							<a href="#" onclick="
								document.getElementById('hidepoll').style.display = 'none';
								document.getElementById('hidepoll_link').style.display = 'block';
								poll_hidded = true;
								return false;">
								{@forum.close.poll.menu}
							</a>
						</p>
					</div>
					<script>
						document.getElementById('hidepoll# IF C_DISPLAY_POLL # _link# ENDIF #').style.display = 'none';
					</script>
				</fieldset>

				<fieldset class="fieldset-submit">
				<legend>{@form.submit}</legend>
					<button type="submit" class="button submit" name="post_topic" value="true">{@form.submit}</button>
					<input type="hidden" name="idm" value="{IDM}">
					<input type="hidden" name="token" value="{TOKEN}">
					<button type="reset" class="button reset-button" value="true">{@form.reset}</button>

					# IF C_DISPLAY_ISSUE_STATUS #
						<p>
							<a href="#" onclick="XMLHttpRequest_change_statut();return false;" id="forum_change_img"># IF C_DISPLAY_ISSUE_ICON #<i class="{ISSUE_ICON}" aria-hidden="true"></i># ENDIF #</a> <a href="#" onclick="XMLHttpRequest_change_statut();return false;"><span id="forum_change_msg">{L_DEFAULT_ISSUE_STATUS}</span></a>
						</p>
					# ENDIF #
				</fieldset>
			</div>
		</form>
	</div>
	<footer class="footer-forum">
		<a class="offload" href="{U_CATEGORY}">{CATEGORY_NAME}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a class="offload" href="{U_TITLE_T}">{L_NEW_SUBJECT}</a> <span class="small"><em>{DESCRIPTION}</em></span>
	</footer>
</article>

# INCLUDE FORUM_BOTTOM #

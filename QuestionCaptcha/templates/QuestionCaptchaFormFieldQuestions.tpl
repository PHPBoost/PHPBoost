<script>
	var QuestionCaptchaFormFieldQuestions = function(){
		this.integer = {ITEMS_NUMBER};
		this.id_input = ${escapejs(HTML_ID)};
		this.max_input = {MAX_INPUT};
	};

	QuestionCaptchaFormFieldQuestions.prototype = {
		add_question : function () {
			if (this.integer <= this.max_input) {
				var id = this.id_input + '_' + this.integer;

				jQuery('<div/>', {'id' : id}).appendTo('#input_questions_' + this.id_input);

				jQuery('<textarea/> ', {id : 'field_label_' + id, name : 'field_label_' + id, placeholder : '{@questioncaptcha.config.label.placeholder}'}).appendTo('#' + id);

				jQuery('<textarea/> ', {id : 'field_answers_' + id, name : 'field_answers_' + id, class : 'answers', placeholder : '{@questioncaptcha.config.answers.placeholder}'}).appendTo('#' + id);

				jQuery('<a/> ', {'aria-label' : ${escapejs(@questioncaptcha.config.delete)}, href : 'javascript:QuestionCaptchaFormFieldQuestions.delete_question('+ this.integer +');', id : 'delete_' + id}).html('<i class="far fa-trash-alt" aria-hidden="true"></i>').appendTo('#' + id);

				jQuery('<div/> ', {class : 'spacer'}).appendTo('#' + id);

				this.integer++;
			}
			if (this.integer == this.max_input) {
				jQuery('#add_' + this.id_input).hide();
			}
		},
		delete_question : function (id) {
			var id = this.id_input + '_' + id;
			jQuery('#' + id).remove();
			this.integer--;
			jQuery('#add_' + this.id_input).show();
		},
	};
	var QuestionCaptchaFormFieldQuestions = new QuestionCaptchaFormFieldQuestions();
</script>

<div id="input_questions_${escape(HTML_ID)}">
	# START fieldelements #
		<div id="${escape(HTML_ID)}_{fieldelements.ID}">
			<textarea name="field_label_${escape(HTML_ID)}_{fieldelements.ID}" id="field_label_${escape(ID)}_{fieldelements.ID}" placeholder="{@questioncaptcha.config.label.placeholder}">{fieldelements.LABEL}</textarea>
			<textarea name="field_answers_${escape(HTML_ID)}_{fieldelements.ID}" id="field_answers_${escape(ID)}_{fieldelements.ID}" placeholder="{@questioncaptcha.config.answers.placeholder}" class="answers">{fieldelements.ANSWERS}</textarea>

			# IF fieldelements.C_DELETE #
				<a href="javascript:QuestionCaptchaFormFieldQuestions.delete_question({fieldelements.ID});" id="delete_${escape(HTML_ID)}_{fieldelements.ID}" aria-label="{@questioncaptcha.config.delete}" data-confirmation="delete-element"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
			# ENDIF #
			<div class="spacer"></div>
		</div>
	# END fieldelements #
</div>
<a href="javascript:QuestionCaptchaFormFieldQuestions.add_question();" id="add_${escape(HTML_ID)}" class="field-question-more-value" aria-label="{@questioncaptcha.config.add}"><i class="fa fa-plus" aria-hidden="true"></i></a>

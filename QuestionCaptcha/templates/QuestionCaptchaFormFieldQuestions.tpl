<script>
<!--
var QuestionCaptchaFormFieldQuestions = Class.create({
	integer : ${escapejs(NBR_QUESTIONS)},
	id_input : ${escapejs(HTML_ID)},
	max_input : ${escapejs(MAX_INPUT)},
	add_question : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			jQuery('<div/>', {'id' : id}).appendTo('#input_questions_' + this.id_input);

			jQuery('<textarea/> ', {id : 'field_label_' + id, name : 'field_label_' + id, placeholder : '{@form.question}'}).appendTo('#' + id);
			
			jQuery('<textarea/> ', {id : 'field_answers_' + id, name : 'field_answers_' + id, class : 'answers', placeholder : '{@form.answers}'}).appendTo('#' + id);
			
			jQuery('<a/> ', {href : 'javascript:QuestionCaptchaFormFieldQuestions.delete_question('+ this.integer +');', id : 'delete_' + id, class : 'fa fa-delete'}).appendTo('#' + id);
			
			jQuery('<div/> ', {class : 'spacer'}).appendTo('#' + id);

			this.integer++;
		}
		if (this.integer == this.max_input) {
			$('add_' + this.id_input).hide();
		}
	},
	delete_question : function (id) {
		var id = this.id_input + '_' + id;
		$(id).remove();
		this.integer--;
		$('add_' + this.id_input).show();
	},
});

var QuestionCaptchaFormFieldQuestions = new QuestionCaptchaFormFieldQuestions();
-->
</script>

<div id="input_questions_${escape(HTML_ID)}">
# START fieldelements #
	<div id="${escape(HTML_ID)}_{fieldelements.ID}">
		<textarea name="field_label_${escape(HTML_ID)}_{fieldelements.ID}" id="field_label_${escape(ID)}_{fieldelements.ID}" placeholder="{@form.question}">{fieldelements.LABEL}</textarea>
		<textarea name="field_answers_${escape(HTML_ID)}_{fieldelements.ID}" id="field_answers_${escape(ID)}_{fieldelements.ID}" placeholder="{@form.answers}" class="answers">{fieldelements.ANSWERS}</textarea>
		
		# IF fieldelements.C_DELETE #<a href="javascript:QuestionCaptchaFormFieldQuestions.delete_question({fieldelements.ID});" id="delete_${escape(HTML_ID)}_{fieldelements.ID}" class="fa fa-delete" data-confirmation="delete-element"></a># ENDIF #
		<div class="spacer"></div>
	</div>
# END fieldelements #
</div>
<a href="javascript:QuestionCaptchaFormFieldQuestions.add_question();" class="fa fa-plus" id="add_${escape(HTML_ID)}"></a> 
# IF C_QUESTIONS #
<script>
<!--
var FaqQuestions = Class.create({
	id : '',
	questions_number : {QUESTIONS_NUMBER},
	initialize : function(id) {
		this.id = id;
	},
	create_sortable : function() {
		Sortable.create(this.id, {
			tag:'li',
			only:'sortable-element'
		});
	},
	destroy_sortable : function() {
		Sortable.destroy(this.id); 
	},
	serialize_sortable : function() {
		$('position').value = Sortable.serialize(this.id);
	},
	get_sortable_sequence : function() {
		return Sortable.sequence(this.id);
	},
	set_sortable_sequence : function(sequence) {
		Sortable.setSequence(this.id, sequence);
	},
	change_reposition_pictures : function() {
		sequence = Sortable.sequence(this.id);
		
		$('move_up_' + sequence[0]).style.display = "none";
		$('move_down_' + sequence[0]).style.display = "inline";
		
		for (var j = 1 ; j < sequence.length - 1 ; j++) {
			$('move_up_' + sequence[j]).style.display = "inline";
			$('move_down_' + sequence[j]).style.display = "inline";
		}
		
		$('move_up_' + sequence[sequence.length - 1]).style.display = "inline";
		$('move_down_' + sequence[sequence.length - 1]).style.display = "none";
	},
	hide_first_reposition_picture : function() {
		sequence = Sortable.sequence(this.id);
		
		$('move_up_' + sequence[0]).style.display = "none";
		$('move_down_' + sequence[0]).style.display = "none";
	}
});

var FaqQuestion = Class.create({
	id : '',
	FaqQuestions: null,
	initialize : function(id, faq_questions) {
		this.id = id;
		this.FaqQuestions = faq_questions;
		
		if (FaqQuestions.questions_number > 1)
			FaqQuestions.change_reposition_pictures();
	},
	delete_question : function() {
		if (confirm(${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))}))
		{
			new Ajax.Request('${relative_url(FaqUrlBuilder::delete())}', {
				method:'post',
				parameters: {'id' : this.id, 'token' : '{TOKEN}'},
				onComplete: function(response) {
					if(response.readyState == 4 && response.status == 200 && response.responseText > 0) {
						var elementToDelete = $('list_' + response.responseText);
						elementToDelete.parentNode.removeChild(elementToDelete);
						# IF NOT C_DISPLAY_TYPE_INLINE #
						var elementToDelete = $('title_question_' + response.responseText);
						elementToDelete.parentNode.removeChild(elementToDelete);
						# ENDIF #
						
						FaqQuestions.destroy_sortable();
						FaqQuestions.create_sortable();
						FaqQuestions.questions_number--;
						
						if (FaqQuestions.questions_number > 1)
							FaqQuestions.change_reposition_pictures();
						else {
							if (FaqQuestions.questions_number == 1) {
								FaqQuestions.hide_first_reposition_picture();
								$('position_update_button').style.display = "none";
							} else {
								$('position_update_form').style.display = "none";
								# IF NOT C_DISPLAY_TYPE_INLINE #
								$('questions_titles_list').style.display = "none";
								# ENDIF #
								$('no_item_message').style.display = "inline";
							}
						}
					}
				}
			});
		}
	},
	move_up : function() {
		var sequence = FaqQuestions.get_sortable_sequence();
		var reordered = false;
		
		if (sequence.length > 1)
			for (var j = 1 ; j < sequence.length ; j++) {
				if (sequence[j].length > 0 && sequence[j] == this.id) {
					var temp = sequence[j-1];
					sequence[j-1] = this.id;
					sequence[j] = temp;
					reordered = true;
				}
			}
		
		if (reordered) {
			FaqQuestions.set_sortable_sequence(sequence);
			FaqQuestions.change_reposition_pictures();
		}
	},
	move_down : function() {
		var sequence = FaqQuestions.get_sortable_sequence();
		var reordered = false;
		
		if (sequence.length > 1)
			for (var j = 0 ; j < sequence.length - 1 ; j++) {
				if (sequence[j].length > 0 && sequence[j] == this.id) {
					var temp = sequence[j+1];
					sequence[j+1] = this.id;
					sequence[j] = temp;
					reordered = true;
				}
			}
		
		if (reordered) {
			FaqQuestions.set_sortable_sequence(sequence);
			FaqQuestions.change_reposition_pictures();
		}
	},
});

var FaqQuestions = new FaqQuestions('questions_list');
Event.observe(window, 'load', function() {
	FaqQuestions.destroy_sortable();
	FaqQuestions.create_sortable();
});
-->
</script>
# ENDIF #
# INCLUDE MSG #
<section>
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('faq', ID_CAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'common')}"></a>
			# IF C_PENDING #{@faq.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF #
		</h1>
		# IF C_CATEGORY_DESCRIPTION #
			<div class="spacer">&nbsp;</div>
			{CATEGORY_DESCRIPTION}
		# ENDIF #
		# IF C_SUB_CATEGORIES #
		<div class="spacer">&nbsp;</div>
		<hr />
		<div class="spacer">&nbsp;</div>
		<div class="cat">
			<div class="subcat">
				# START sub_categories_list #
				<div class="sub-category" style="width:{CATS_COLUMNS_WIDTH}%;">
					<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="" /></a><br />
					<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a><br />
					<span class="small">{sub_categories_list.QUESTIONS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_QUESTION #${TextHelper::lowercase_first(LangLoader::get_message('faq.questions', 'common', 'faq'))}# ELSE #${TextHelper::lowercase_first(LangLoader::get_message('faq.form.question', 'common', 'faq'))}# ENDIF #</span>
				</div>
				# END sub_categories_list #
			</div>
		</div>
		<div class="spacer">&nbsp;</div>
		<hr />
		# ELSE #
		<div class="spacer">&nbsp;</div>
		# ENDIF #
	</header>
	<div class="content">
	# IF C_QUESTIONS #
		# IF C_DISPLAY_TYPE_INLINE #
		<script>
		<!--
			function show_answer(id_question)
			{
				if ($('q' + id_question)) {
					if( $('a' + id_question).style.display == "none" )
					{
						Effect.Appear("a" + id_question);
						$('q' + id_question).className="fa fa-caret-down"
					}
					else
					{
						Effect.Fade("a" + id_question);
						$('q' + id_question).className="fa fa-caret-right"
					}
				}
			}
			
			Event.observe(window, 'load', function() {
				var anchor = window.location.hash;
				var id_question;
				 
				id_question = anchor.substring(2,anchor.length);
				if (anchor.substring(0,2) == "#q" && id_question.match(/^[0-9]+$/))
					show_answer(id_question);
			});
		-->
		</script>
		# ELSE #
		<div id="questions_titles_list">
			<ol>
			# START questions #
				<li id="title_question_{questions.ID}">
					<a href="#q{questions.ID}">{questions.QUESTION}</a>
				</li>
			# END questions #
			</ol>
			<hr style="margin:20px 0;" />
		</div>
		# ENDIF #
		
		<form action="{REWRITED_SCRIPT}" method="post" id="position_update_form" onsubmit="FaqQuestions.serialize_sortable();">
			<fieldset id="questions_management">
				<ul id="questions_list" class="sortable-block">
					# START questions #
					<li class="sortable-element" id="list_{questions.ID}">
						<div class="sortable-title">
							<a title="${LangLoader::get_message('move', 'admin')}" class="fa fa-arrows"></a>
							<span>
								# IF C_DISPLAY_TYPE_INLINE #
								<a href="" id="q{questions.ID}" onclick="show_answer({questions.ID});return false;" title="" class="fa fa-caret-right"></a>
								<a href="" onclick="show_answer({questions.ID});return false;" title="">{questions.QUESTION}</a>
								# ELSE #
								<i class="fa fa-caret-right"></i>
								<span id="q{questions.ID}">{questions.QUESTION}</span>
								# ENDIF #
							</span>
							<div class="sortable-actions">
								# IF C_MORE_THAN_ONE_QUESTION #
								<div class="sortable-options">
									<a href="" title="${LangLoader::get_message('position.move_up', 'common')}" id="move_up_{questions.ID}" onclick="return false;" class="fa fa-arrow-up"></a>
								</div>
								<div class="sortable-options">
									<a href="" title="${LangLoader::get_message('position.move_down', 'common')}" id="move_down_{questions.ID}" onclick="return false;" class="fa fa-arrow-down"></a>
								</div>
								# ENDIF #
								<div class="sortable-options">
									<a href="{questions.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
								</div>
								<div class="sortable-options">
									<a href="" onclick="return false;" title="${LangLoader::get_message('delete', 'main')}" id="delete_{questions.ID}" class="fa fa-delete"></a>
								</div>
							</div>
						</div>
						<div>
							<div id="a{questions.ID}" class="blockquote"# IF C_DISPLAY_TYPE_INLINE # style="display: none;"# ENDIF #>
								<div itemprop="text">{questions.ANSWER}</div>
							</div>
						</div>
						<div class="spacer"></div>
					</li>
					<script>
					<!--
					Event.observe(window, 'load', function() {
						var faq_question = new FaqQuestion({questions.ID}, FaqQuestions);
						
						$('delete_{questions.ID}').observe('click',function(){
							faq_question.delete_question();
						});
						
						if (FaqQuestions.questions_number > 1) {
							$('list_{questions.ID}').observe('mouseup',function(){
								FaqQuestions.change_reposition_pictures();
							});
							
							$('move_up_{questions.ID}').observe('click',function(){
								faq_question.move_up();
							});
							
							$('move_down_{questions.ID}').observe('click',function(){
								faq_question.move_down();
							});
						}
					});
					-->
					</script>
					# END questions #
				</ul>
			</fieldset>
			# IF C_MORE_THAN_ONE_QUESTION #
			<fieldset class="fieldset-submit" id="position_update_button">
				<button type="submit" name="submit" value="true" class="submit">${LangLoader::get_message('position.update', 'common')}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="position" id="position" value="">
			</fieldset>
			# ENDIF #
		</form>
	# ENDIF #
	# IF NOT C_ROOT_CATEGORY #
		<div id="no_item_message"# IF C_QUESTIONS # style="display: none;"# ENDIF #>
			<div class="center">
				${LangLoader::get_message('no_item_now', 'common')}
			</div>
		</div>
	# ENDIF #
	</div>
	<footer></footer>
</section>
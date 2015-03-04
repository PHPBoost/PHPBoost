# IF C_QUESTIONS #
<script>
<!--
var FaqQuestions = function(id){
	this.id = id;
	this.questions_number = {QUESTIONS_NUMBER};
};

FaqQuestions.prototype = {
	init_sortable : function() {
		jQuery("ul#questions_list").sortable({handle: '.fa-arrows'});
	},
	serialize_sortable : function() {
		jQuery('#tree').val(JSON.stringify(this.get_sortable_sequence()));
	},
	get_sortable_sequence : function() {
		var sequence = jQuery("ul#questions_list").sortable("serialize").get();
		return sequence[0];
	},
	change_reposition_pictures : function() {
		sequence = this.get_sortable_sequence();
		
		jQuery("#move_up_" + sequence[0].id).hide();
		jQuery("#move_down_" + sequence[0].id).show();
		
		for (var j = 1 ; j < sequence.length - 1 ; j++) {
			jQuery("#move_up_" + sequence[j].id).show();
			jQuery("#move_down_" + sequence[j].id).show();
		}
		
		jQuery("#move_up_" + sequence[sequence.length - 1].id).show();
		jQuery("#move_down_" + sequence[sequence.length - 1].id).hide();
	},
	hide_first_reposition_picture : function() {
		sequence = this.get_sortable_sequence();
		
		jQuery("#move_up_" + sequence[0].id).hide();
		jQuery("#move_down_" + sequence[0].id).hide();
	}
};

var FaqQuestion = function(id, faq_questions){
	this.id = id;
	this.FaqQuestions = faq_questions;
	
	if (FaqQuestions.questions_number > 1)
		FaqQuestions.change_reposition_pictures();
};

FaqQuestion.prototype = {
	delete : function() {
		if (confirm(${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))}))
		{
			new Ajax.Request('${relative_url(FaqUrlBuilder::ajax_delete())}', {
				method:'post',
				parameters: {'id' : this.id, 'token' : '{TOKEN}'},
				onComplete: function(response) {
					if(response.readyState == 4 && response.status == 200 && response.responseJSON.code > 0) {
						var elementToDelete = jQuery("#list_" + response.responseJSON.code);
						elementToDelete.remove();
						# IF NOT C_DISPLAY_TYPE_ANSWERS_HIDDEN #
						var elementToDelete = jQuery("#title_question_" + response.responseJSON.code);
						elementToDelete.remove();
						# ENDIF #
						
						FaqQuestions.init_sortable();
						FaqQuestions.questions_number--;
						
						if (FaqQuestions.questions_number > 1)
							FaqQuestions.change_reposition_pictures();
						else {
							if (FaqQuestions.questions_number == 1) {
								FaqQuestions.hide_first_reposition_picture();
								jQuery("#position_update_button").hide();
							} else {
								jQuery("#position_update_form").hide();
								# IF NOT C_DISPLAY_TYPE_ANSWERS_HIDDEN #
								jQuery("#questions_titles_list").hide();
								# ENDIF #
								jQuery("#no_item_message").show();
							}
						}
					}
				},
				error: function(e){
					alert(e);
				}
			});
		}
	}
};

var FaqQuestions = new FaqQuestions('questions_list');
jQuery(document).ready(function() {
	FaqQuestions.init_sortable();
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
					# IF sub_categories_list.C_CATEGORY_IMAGE #<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="" /></a># ENDIF #
					<br />
					<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
					<br />
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
		# IF C_DISPLAY_TYPE_ANSWERS_HIDDEN #
		<script>
		<!--
			function show_answer(id_question)
			{
				if (jQuery("#question" + id_question)) {
					if(jQuery("#answer" + id_question).css('display') == 'none')
					{
						jQuery("#answer" + id_question).fadeIn();
						jQuery("#question" + id_question).class = "fa fa-caret-down";
					}
					else
					{
						jQuery("#answer" + id_question).fadeOut();
						jQuery("#question" + id_question).class = "fa fa-caret-right";
					}
				}
			}
			
			jQuery(document).ready(function() {
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
					<li class="sortable-element" id="list_{questions.ID}" data-id="{questions.ID}">
						<div class="sortable-title">
							<a title="${LangLoader::get_message('move', 'admin')}" class="fa fa-arrows"></a>
							<span>
								# IF C_DISPLAY_TYPE_ANSWERS_HIDDEN #
								<a href="" id="question{questions.ID}" onclick="show_answer({questions.ID});return false;" title="" class="fa fa-caret-right"></a>
								<a href="" onclick="show_answer({questions.ID});return false;" title="">{questions.QUESTION}</a>
								# ELSE #
								<i class="fa fa-caret-right"></i>
								<span id="question{questions.ID}">{questions.QUESTION}</span>
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
									<a href="{questions.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
								</div>
								<div class="sortable-options">
									<a href="" onclick="return false;" title="${LangLoader::get_message('delete', 'common')}" id="delete_{questions.ID}" class="fa fa-delete"></a>
								</div>
							</div>
						</div>
						<div>
							<div id="answer{questions.ID}" class="blockquote"# IF C_DISPLAY_TYPE_ANSWERS_HIDDEN # style="display: none;"# ENDIF #>
								<div itemprop="text">{questions.ANSWER}</div>
							</div>
						</div>
						<div class="spacer"></div>
						<script>
						<!--
						jQuery(document).ready(function() {
							var faq_question = new FaqQuestion({questions.ID}, FaqQuestions);
							
							jQuery('#delete_{questions.ID}').on('click',function(){
								faq_question.delete();
							});
							
							if (FaqQuestions.questions_number > 1) {
								jQuery('#list_{questions.ID}').on('mouseout',function(){
									FaqQuestions.change_reposition_pictures();
								});
								jQuery('#move_up_{questions.ID}').on('click',function(){
									var li = jQuery(this).closest('li');
									li.insertBefore( li.prev() );
									FaqQuestions.change_reposition_pictures();
								});
								jQuery('#move_down_{questions.ID}').on('click',function(){
									var li = jQuery(this).closest('li');
									li.insertAfter( li.next() );
									FaqQuestions.change_reposition_pictures();
								});
							}
						});
						-->
						</script>
					</li>
					# END questions #
				</ul>
			</fieldset>
			# IF C_MORE_THAN_ONE_QUESTION #
			<fieldset class="fieldset-submit" id="position_update_button">
				<button type="submit" name="submit" value="true" class="submit">${LangLoader::get_message('position.update', 'common')}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="tree" id="tree" value="">
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
# IF C_QUESTIONS #
<script>
<!--
# IF C_MODERATION #
var FaqQuestions = function(id){
	this.id = id;
	this.questions_number = {QUESTIONS_NUMBER};
};

FaqQuestions.prototype = {
	init_sortable : function() {
		jQuery("ul#questions-list").sortable({
			handle: '.sortable-selector',
			placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>'
		});
	},
	serialize_sortable : function() {
		jQuery('#tree').val(JSON.stringify(this.get_sortable_sequence()));
	},
	get_sortable_sequence : function() {
		var sequence = jQuery("ul#questions-list").sortable("serialize").get();
		return sequence[0];
	},
	change_reposition_pictures : function() {
		sequence = this.get_sortable_sequence();
		var length = sequence.length;
		for(var i = 0; i < length; i++)
		{
			if (jQuery('#list-' + sequence[i].id).is(':first-child'))
				jQuery("#move-up-" + sequence[i].id).hide();
			else
				jQuery("#move-up-" + sequence[i].id).show();
			
			if (jQuery('#list-' + sequence[i].id).is(':last-child'))
				jQuery("#move-down-" + sequence[i].id).hide();
			else
				jQuery("#move-down-" + sequence[i].id).show();
		}
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
			jQuery.ajax({
				url: '${relative_url(FaqUrlBuilder::ajax_delete())}',
				type: "post",
				dataType: "json",
				data: {'id' : this.id, 'token' : '{TOKEN}'},
				success: function(returnData) {
					if(returnData.code > 0) {
						jQuery("#list-" + returnData.code).remove();
						# IF NOT C_DISPLAY_TYPE_ANSWERS_HIDDEN #
						jQuery("#title-question-" + returnData.code).remove();
						# ENDIF #
						
						FaqQuestions.init_sortable();
						FaqQuestions.questions_number--;
						
						FaqQuestions.change_reposition_pictures();
						if (FaqQuestions.questions_number == 1) {
							jQuery("#position-update-button").hide();
						} else if (FaqQuestions.questions_number == 0) {
							jQuery("#position-update-form").hide();
							# IF NOT C_DISPLAY_TYPE_ANSWERS_HIDDEN #
							jQuery("#questions-titles-list").hide();
							# ENDIF #
							jQuery("#no-item-message").show();
						}
					}
				}
			});
		}
	}
};

var FaqQuestions = new FaqQuestions('questions-list');
jQuery(document).ready(function() {
	FaqQuestions.init_sortable();
	jQuery('li.sortable-element').on('mouseout',function(){
		FaqQuestions.change_reposition_pictures();
	});
});
# ELSE #
var questions_number = {QUESTIONS_NUMBER};

function delete_question(id_question)
{
	if (confirm(${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))}))
	{
		jQuery.ajax({
			url: '${relative_url(FaqUrlBuilder::ajax_delete())}',
			type: "post",
			dataType: "json",
			data: {'id' : id_question, 'token' : '{TOKEN}'},
			success: function(returnData) {
				if(returnData.code > 0) {
					jQuery("#question-" + returnData.code).remove();
					# IF NOT C_DISPLAY_TYPE_ANSWERS_HIDDEN #
					jQuery("#title-question-" + returnData.code).remove();
					# ENDIF #
					
					if (returnData.questions_number == 0) {
						# IF NOT C_DISPLAY_TYPE_ANSWERS_HIDDEN #
						jQuery("#questions-titles-list").hide();
						# ENDIF #
						jQuery("#no-item-message").show();
					}
				}
			}
		});
	}
}
# ENDIF #

# IF C_DISPLAY_TYPE_ANSWERS_HIDDEN #
function show_answer(id_question)
{
	if (jQuery("#question" + id_question)) {
		if(jQuery("#answer" + id_question).css('display') == 'none')
		{
			jQuery("#answer" + id_question).fadeIn();
			jQuery("#question" + id_question).removeClass('fa-caret-right');
			jQuery("#question" + id_question).addClass('fa-caret-down');
		}
		else
		{
			jQuery("#answer" + id_question).fadeOut();
			jQuery("#question" + id_question).removeClass('fa-caret-down');
			jQuery("#question" + id_question).addClass('fa-caret-right');
		}
	}
}

jQuery(document).ready(function() {
	var anchor = window.location.hash;
	var id_question;
	 
	id_question = anchor.substring(9,anchor.length);
	if (anchor.substring(0,9) == "#question" && id_question.match(/^[0-9]+$/))
		show_answer(id_question);
});
# ENDIF #
-->
</script>
# ENDIF #
# INCLUDE MSG #
<section id="module-faq">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('faq', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@faq.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
		</h1>
		# IF C_CATEGORY_DESCRIPTION #
			<div class="cat-description">
				{CATEGORY_DESCRIPTION}
			</div>
		# ENDIF #
	</header>
	
	# IF C_SUB_CATEGORIES #
	<div class="subcat-container">
		# START sub_categories_list #
		<div class="subcat-element" style="width:{CATS_COLUMNS_WIDTH}%;">
			<div class="subcat-content">
				# IF sub_categories_list.C_CATEGORY_IMAGE #<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" /></a># ENDIF #
				<br />
				<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
				<br />
				<span class="small">{sub_categories_list.QUESTIONS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_QUESTION #${TextHelper::lowercase_first(LangLoader::get_message('faq.questions', 'common', 'faq'))}# ELSE #${TextHelper::lowercase_first(LangLoader::get_message('faq.form.question', 'common', 'faq'))}# ENDIF #</span>
			</div>
		</div>
		# END sub_categories_list #
		<div class="spacer"></div>
	</div>
	# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ELSE #
	<div class="spacer"></div>
	# ENDIF #
	
	<div class="content">
	# IF C_QUESTIONS #
		# IF C_PENDING #
			# IF C_MORE_THAN_ONE_QUESTION #
			# INCLUDE SORT_FORM #
			<div class="spacer"></div>
			# ENDIF #
		# ENDIF #
		# IF NOT C_DISPLAY_TYPE_ANSWERS_HIDDEN #
		<div id="questions-titles-list">
			<ol>
			# START questions #
				<li id="title-question-{questions.ID}">
					<a href="#question{questions.ID}">{questions.QUESTION}</a>
				</li>
			# END questions #
			</ol>

			<hr>

		</div>
		# ENDIF #
		
		# IF C_MODERATION #
		<form action="{REWRITED_SCRIPT}" method="post" id="position-update-form" onsubmit="FaqQuestions.serialize_sortable();">
			<fieldset id="questions-management">
				<ul id="questions-list" class="sortable-block">
					# START questions #
					<li class="sortable-element" id="list-{questions.ID}" data-id="{questions.ID}">
						<div class="sortable-selector" title="${LangLoader::get_message('position.move', 'common')}"></div>
						<div class="sortable-title">
							<h3 class="question-title">
								# IF C_DISPLAY_TYPE_ANSWERS_HIDDEN #
								<a href="" onclick="show_answer({questions.ID});return false;" title=""><i id="question{questions.ID}" class="fa fa-caret-right question-anchor"></i></a>
								<a href="" onclick="show_answer({questions.ID});return false;" title="">{questions.QUESTION}</a>
								# ELSE #
								<i id="question{questions.ID}" class="fa fa-caret-right question-anchor"></i>
								<span>{questions.QUESTION}</span>
								# ENDIF #
							</h3>
							<div class="sortable-actions">
								# IF C_MORE_THAN_ONE_QUESTION #
								<a href="" title="${LangLoader::get_message('position.move_up', 'common')}" id="move-up-{questions.ID}" onclick="return false;"><i class="fa fa-arrow-up"></i></a>
								<a href="" title="${LangLoader::get_message('position.move_down', 'common')}" id="move-down-{questions.ID}" onclick="return false;"><i class="fa fa-arrow-down"></i></a>
								# ENDIF #
								<a href="{questions.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
								<a href="" onclick="return false;" title="${LangLoader::get_message('delete', 'common')}" id="delete-{questions.ID}"><i class="fa fa-delete"></i></a>
							</div>
							<div id="answer{questions.ID}" class="faq-answer-container"# IF C_DISPLAY_TYPE_ANSWERS_HIDDEN # style="display: none;"# ENDIF #>
								<div itemprop="text">{questions.ANSWER}</div>
							</div>
						</div>
						<div class="spacer"></div>
						<script>
						<!--
						jQuery(document).ready(function() {
							var faq_question = new FaqQuestion({questions.ID}, FaqQuestions);
							
							jQuery('#delete-{questions.ID}').on('click',function(){
								faq_question.delete();
							});
							
							if (FaqQuestions.questions_number > 1) {
								jQuery('#move-up-{questions.ID}').on('click',function(){
									var li = jQuery(this).closest('li');
									li.insertBefore( li.prev() );
									FaqQuestions.change_reposition_pictures();
								});
								jQuery('#move-down-{questions.ID}').on('click',function(){
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
			<fieldset class="fieldset-submit" id="position-update-button">
				<button type="submit" name="submit" value="true" class="submit">${LangLoader::get_message('position.update', 'common')}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="tree" id="tree" value="">
			</fieldset>
			# ENDIF #
		</form>
		# ELSE #
			# START questions #
			<article id="article-faq-{questions.ID}" itemscope="itemscope" itemtype="http://schema.org/CreativeWork" class="article-faq article-several">
				<header class="faq-question-element">
					<h3 class="question-title">
						# IF C_DISPLAY_TYPE_ANSWERS_HIDDEN #
						<a href="" onclick="show_answer({questions.ID});return false;" title=""><i id="question{questions.ID}" class="fa fa-caret-right question-anchor"></i></a>
						<a href="" onclick="show_answer({questions.ID});return false;" title=""><span itemprop="name">{questions.QUESTION}</span></a>
						# ELSE #
						<i id="question{questions.ID}" class="fa fa-caret-right question-anchor"></i>
						<span itemprop="name">{questions.QUESTION}</span>
						# ENDIF #
					</h3>
					
					<span class="actions">
						<a href="{questions.U_LINK}" title=""><i class="fa fa-flag"></i></a>
						# IF questions.C_EDIT #
						<a href="{questions.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF questions.C_DELETE #
						<a href="" onclick="delete_question({questions.ID});return false;" title="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-delete"></i></a>
						# ENDIF #
					</span>
					
					<meta itemprop="url" content="{questions.U_LINK}">
				</header>
				
				<div class="content">
					<div id="answer{questions.ID}" class="faq-answer-container"# IF C_DISPLAY_TYPE_ANSWERS_HIDDEN # style="display: none;"# ENDIF #>
						<div itemprop="text">{questions.ANSWER}</div>
					</div>
				</div>
				
				<footer></footer>
			</article>
			# END questions #
		# ENDIF #
	# ENDIF #
	# IF NOT C_HIDE_NO_ITEM_MESSAGE #
		<div id="no-item-message"# IF C_QUESTIONS # style="display: none;"# ENDIF #>
			<div class="center">
				${LangLoader::get_message('no_item_now', 'common')}
			</div>
		</div>
	# ENDIF #
	</div>
	<footer></footer>
</section>

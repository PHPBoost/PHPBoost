# IF C_QUESTIONS #
<script>
<!--
var FaqQuestions = function(id){
	this.id = id;
	this.questions_number = {QUESTIONS_NUMBER};
};

FaqQuestions.prototype = {
	init_sortable : function() {
		jQuery("ul#questions-list").sortable({
			handle: '.sortable-element',
			placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>',
			start: function (e, ui) { 
				ui.placeholder.html(ui.item.html());
			}
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
				data: {'id' : this.id, 'token' : '{TOKEN}'},
				success: function(returnData) {
					if(returnData.code > 0) {
						var elementToDelete = jQuery("#list-" + returnData.code);
						elementToDelete.remove();
						# IF NOT C_DISPLAY_TYPE_ANSWERS_HIDDEN #
						var elementToDelete = jQuery("#title-question-" + returnData.code);
						elementToDelete.remove();
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
				},
				error: function(e){
					alert(e);
				}
			});
		}
	}
};

var FaqQuestions = new FaqQuestions('questions-list');
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
						jQuery("#question" + id_question).removeClass('fa-caret-right').addClass('fa-caret-down');
					}
					else
					{
						jQuery("#answer" + id_question).fadeOut();
						jQuery("#question" + id_question).removeClass('fa-caret-down').addClass('fa-caret-right');
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
		<div id="questions-titles-list">
			<ol>
			# START questions #
				<li id="title-question-{questions.ID}">
					<a href="#q{questions.ID}">{questions.QUESTION}</a>
				</li>
			# END questions #
			</ol>
			<hr style="margin:20px 0;" />
		</div>
		# ENDIF #
		
		<form action="{REWRITED_SCRIPT}" method="post" id="position-update-form" onsubmit="FaqQuestions.serialize_sortable();">
			<fieldset id="questions-management">
				<ul id="questions-list" class="sortable-block">
					# START questions #
					<li class="sortable-element" id="list-{questions.ID}" data-id="{questions.ID}">
						<div class="sortable-element-selector"></div>
						<div class="sortable-title">
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
								jQuery('#list-{questions.ID}').on('mouseout',function(){
									FaqQuestions.change_reposition_pictures();
								});
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
	# ENDIF #
	# IF NOT C_ROOT_CATEGORY #
		<div id="no-item-message"# IF C_QUESTIONS # style="display: none;"# ENDIF #>
			<div class="center">
				${LangLoader::get_message('no_item_now', 'common')}
			</div>
		</div>
	# ENDIF #
	</div>
	<footer></footer>
</section>
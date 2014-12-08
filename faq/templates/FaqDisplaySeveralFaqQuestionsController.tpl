<script>
<!--
var questions_number = {QUESTIONS_NUMBER};

function delete_question(id_question)
{
	if (confirm(${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))}))
	{
		new Ajax.Request('${relative_url(FaqUrlBuilder::delete())}', {
			method:'post',
			parameters: {'id' : id_question, 'token' : '{TOKEN}'},
			onComplete: function(response) {
				if(response.readyState == 4 && response.status == 200 && response.responseText > 0) {
					var elementToDelete = $('question_' + response.responseText);
					elementToDelete.parentNode.removeChild(elementToDelete);
					# IF NOT C_DISPLAY_TYPE_INLINE #
					var elementToDelete = $('title_question_' + response.responseText);
					elementToDelete.parentNode.removeChild(elementToDelete);
					# ENDIF #
					
					this.questions_number--;
					
					if (this.questions_number == 0) {
						# IF NOT C_DISPLAY_TYPE_INLINE #
						$('questions_titles_list').style.display = "none";
						# ENDIF #
						$('no_item_message').style.display = "inline";
					}
				}
			}
		});
	}
}
-->
</script>
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
		# IF C_PENDING #
			# IF C_MORE_THAN_ONE_QUESTION #
			# INCLUDE SORT_FORM #
			<div class="spacer">&nbsp;</div>
			# ENDIF #
		# ENDIF #
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
		
		# START questions #
		<article id="question_{questions.ID}" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			<header>
				<span>
					# IF C_DISPLAY_TYPE_INLINE #
					<a href="" id="q{questions.ID}" onclick="show_answer({questions.ID});return false;" title="" class="fa fa-caret-right"></a>
					<a href="" onclick="show_answer({questions.ID});return false;" title=""><span itemprop="name">{questions.QUESTION}</span></a>
					# ELSE #
					<i class="fa fa-caret-right"></i>
					<span id="q{questions.ID}" itemprop="name">{questions.QUESTION}</span>
					# ENDIF #
				</span>
				
				<span class="actions">
					<a href="{questions.U_LINK}" title="" class="fa fa-flag"></a>
					# IF questions.C_EDIT #
					<a href="{questions.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
					# ENDIF #
					# IF questions.C_DELETE #
					<a href="" onclick="return false;" title="${LangLoader::get_message('delete', 'main')}" id="delete_{questions.ID}" class="fa fa-delete"></a>
					<script>
					<!--
					Event.observe(window, 'load', function() {
						$('delete_{questions.ID}').observe('click',function(){
							delete_question({questions.ID});
						});
					});
					-->
					</script>
					# ENDIF #
				</span>
				
				<meta itemprop="url" content="{questions.U_LINK}">
			</header>
			
			<div class="content">
				<div id="a{questions.ID}" class="blockquote"# IF C_DISPLAY_TYPE_INLINE # style="display: none;"# ENDIF #>
					<div itemprop="text">{questions.ANSWER}</div>
				</div>
			</div>
			
			<footer></footer>
		</article>
		# END questions #
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
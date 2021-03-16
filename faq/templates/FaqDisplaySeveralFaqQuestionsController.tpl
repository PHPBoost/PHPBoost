# IF C_QUESTIONS #
	<script>
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
						if(returnData.deleted_id > 0) {
							questions_number--;
							jQuery("#question-title-" + returnData.deleted_id).remove();
							jQuery("#question" + returnData.deleted_id).remove();

							if (questions_number == 0) {
								jQuery(".accordion-container").hide();
								jQuery("#no-item-message").show();
							}
						}
					}
				});
			}
		}
	</script>
# ENDIF #
# INCLUDE MSG #
<section id="module-faq">
	<header class="section-header">
		<div class="controls align-right">
			<a href="${relative_url(SyndicationUrlBuilder::rss('faq', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF NOT C_ROOT_CATEGORY #{@faq.module.title}# ENDIF #
			# IF C_CATEGORY #
				# IF C_DISPLAY_REORDER_LINK #
					<a href="{U_REORDER_QUESTIONS}" aria-label="{@faq.questions.reorder}"><i class="fa fa-fw fa-exchange-alt" aria-hidden="true"></i></a>
				# ENDIF #
				# IF IS_ADMIN #
					<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
				# ENDIF #
			# ENDIF #
		</div>
		<h1>
			# IF C_PENDING #
				{@faq.questions.pending}
			# ELSE #
				# IF C_ROOT_CATEGORY #{@faq.module.title}# ELSE #{CATEGORY_NAME}# ENDIF #
			# ENDIF #
		</h1>
	</header>
	# IF C_CATEGORY_DESCRIPTION #
		<div class="sub-section">
			<div class="content-container">
				<div class="cat-description">
					{CATEGORY_DESCRIPTION}
				</div>
			</div>
		</div>
	# ENDIF #

	# IF C_SUB_CATEGORIES #
		<div class="sub-section">
			<div class="content-container">
				<div class="cell-flex cell-tile cell-columns-{NUMBER_CATS_COLUMNS}">
					# START sub_categories_list #
						<div class="cell category-{sub_categories_list.CATEGORY_ID}">
							<div class="cell-header">
								<h5 class="cell-name"><a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a></h5>
								<span class="small pinned notice" aria-label="# IF sub_categories_list.C_MORE_THAN_ONE_QUESTION #${TextHelper::lcfirst(LangLoader::get_message('faq.questions', 'common', 'faq'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('faq.form.question', 'common', 'faq'))}# ENDIF #">{sub_categories_list.QUESTIONS_NUMBER}</span>
							</div>
							<div class="cell-body">
								# IF sub_categories_list.C_CATEGORY_THUMBNAIL #
									<div class="cell-thumbnail cell-landscape cell-center">
										<img itemprop="thumbnailUrl" src="{sub_categories_list.U_CATEGORY_THUMBNAIL}" alt="{sub_categories_list.CATEGORY_NAME}" />
										<a class="cell-thumbnail-caption" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
											${LangLoader::get_message('see.category', 'categories-common')}
										</a>
									</div>
								# ENDIF #
							</div>
						</div>
					# END sub_categories_list #
				</div>
				# IF C_SUBCATEGORIES_PAGINATION #<div class="content align-right"># INCLUDE SUBCATEGORIES_PAGINATION #</div># ENDIF #
			</div>
		</div>
	# ENDIF #

	# IF C_QUESTIONS #
		# IF C_PENDING #
			# IF C_MORE_THAN_ONE_QUESTION #
				<div class="sub-section">
					<div class="content-container">
						# INCLUDE SORT_FORM #
						<div class="spacer"></div>
					</div>
				</div>
			# ENDIF #
		# ENDIF #
		<div class="sub-section">
			<div class="content-container">
				<div class="accordion-container# IF C_DISPLY_BASIC # basic# ELSE # siblings# ENDIF #">
					# IF C_DISPLAY_CONTROLS #
						<div class="accordion-controls">
							<span class="open-all-accordions" aria-label="{@faq.show.answers}"><i class="fa fa-fw fa-chevron-down"></i></span>
							<span class="close-all-accordions" aria-label="{@faq.hide.answers}"><i class="fa fa-fw fa-chevron-up"></i></span>
						</div>
					# ENDIF #
					<nav class="accordion-nav">
						<ul class="accordion-bordered">
							# START questions #
								<li id="question-title-{questions.ID}" class="category-{questions.CATEGORY_ID}">
									<a href="#" data-accordion data-target="question{questions.ID}">{questions.QUESTION}</a>
								</li>
							# END questions #
						</ul>
					</nav>
					# START questions #
						<article id="question{questions.ID}" itemscope="itemscope" itemtype="https://schema.org/CreativeWork" class="accordion accordion-animation faq-item several-items# IF questions.C_NEW_CONTENT # new-content# ENDIF #">
							<div class="content-panel faq-answer-container" itemprop="text">
								<div class="controls align-right">
									<a href="{questions.U_LINK}" onclick="copy_to_clipboard('{questions.U_ABSOLUTE_LINK}');return false;" aria-label="{questions.L_LINK_QUESTION}"><i class="fa fa-fw fa-anchor" aria-hidden="true"></i></a>
									# IF questions.C_EDIT #
										<a href="{questions.U_EDIT}"aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit fa-fw" aria-hidden="true"></i> </a>
									# ENDIF #
									# IF questions.C_DELETE #
										<a href="#"aria-label="${LangLoader::get_message('delete', 'common')}" onclick="delete_question({questions.ID});return false;"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i> </a>
									# ENDIF #
								</div>
								<div class="content">{questions.ANSWER}</div>
							</div>

							<footer></footer>
						</article>
					# END questions #
				</div>
			</div>
		</div>
	# ELSE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
			<div class="sub-section">
				<div class="content-container">
					<div class="content">
						<div id="no-item-message" class="message-helper bgc notice align-center"# IF C_QUESTIONS # style="display: none;"# ENDIF #>
							${LangLoader::get_message('no_item_now', 'common')}
						</div>
					</div>
				</div>
			</div>
		# ENDIF #
	# ENDIF #

	<footer></footer>
</section>

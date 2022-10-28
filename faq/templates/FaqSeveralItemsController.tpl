# IF C_ITEMS #
	<script>
		var questions_number = {ITEMS_NUMBER};

		function delete_question(id_question)
		{
			if (confirm(${escapejs(@warning.confirm.delete)}))
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
# INCLUDE MESSAGE_HELPER #
<section id="module-faq" class="several-items">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('faq', CATEGORY_ID))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF NOT C_ROOT_CATEGORY #{@faq.module.title}# ENDIF #
			# IF C_CATEGORY #
				# IF C_DISPLAY_REORDER_LINK #
					<a class="offload" href="{U_REORDER_ITEMS}" aria-label="{@faq.questions.reorder}"><i class="fa fa-fw fa-exchange-alt" aria-hidden="true"></i></a>
				# ENDIF #
				# IF IS_ADMIN #
					<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
				# ENDIF #
			# ENDIF #
		</div>
		<h1>
			# IF C_PENDING_ITEMS #
				{@faq.pending.items}
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
				<div class="cell-flex cell-tile cell-columns-{CATEGORIES_NUMBER}">
					# START sub_categories_list #
						<div class="cell category-{sub_categories_list.CATEGORY_ID}">
							<div class="cell-header">
								<h5 class="cell-name"><a class="subcat-title offload" itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a></h5>
								<span class="small pinned notice" aria-label="# IF sub_categories_list.C_SEVERAL_ITEMS #${TextHelper::lcfirst(@faq.items)}# ELSE #${TextHelper::lcfirst(@faq.item)}# ENDIF #">{sub_categories_list.ITEMS_NUMBER}</span>
							</div>
							<div class="cell-body">
								# IF sub_categories_list.C_CATEGORY_THUMBNAIL #
									<div class="cell-thumbnail cell-landscape cell-center">
										<img itemprop="thumbnailUrl" src="{sub_categories_list.U_CATEGORY_THUMBNAIL}" alt="{sub_categories_list.CATEGORY_NAME}" />
										<a class="cell-thumbnail-caption offload" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
											{@category.see.category}
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

	# IF C_ITEMS #
		<div class="sub-section">
			<div class="content-container">
				<div class="accordion-container# IF C_DISPLY_BASIC # basic# ELSE # siblings# ENDIF #">
					# IF C_DISPLAY_CONTROLS #
						<div class="accordion-controls">
							<span class="open-all-accordions" aria-label="{@faq.show.all.contents}"><i class="fa fa-fw fa-chevron-down"></i></span>
							<span class="close-all-accordions" aria-label="{@faq.hide.all.contents}"><i class="fa fa-fw fa-chevron-up"></i></span>
						</div>
					# ENDIF #
					<nav class="accordion-nav">
						<ul class="accordion-bordered">
							# START items #
								<li id="question-title-{items.ID}" class="category-{items.CATEGORY_ID}">
									<a href="#" data-accordion data-target="question{items.ID}">{items.TITLE}</a>
								</li>
							# END items #
						</ul>
					</nav>
					# START items #
						<article id="question{items.ID}" itemscope="itemscope" itemtype="https://schema.org/CreativeWork" class="accordion accordion-animation faq-item several-items# IF items.C_NEW_CONTENT # new-content# ENDIF #">
							<div class="content-panel faq-answer-container" itemprop="text">
								<div class="controls align-right">
									# IF C_PENDING_ITEMS #{items.ITEM_DATE} | # ENDIF #
									<a href="{items.U_ITEM}" onclick="copy_to_clipboard('{items.U_ABSOLUTE_LINK}');return false;" aria-label="{@common.copy.link.to.clipboard}"><i class="fa fa-fw fa-anchor" aria-hidden="true"></i></a>
									# IF items.C_EDIT #
										<a class="offload" href="{items.U_EDIT}"aria-label="{@common.edit}"><i class="far fa-fw fa-edit fa-fw" aria-hidden="true"></i> </a>
									# ENDIF #
									# IF items.C_DELETE #
										<a href="#" aria-label="{@common.delete}" onclick="delete_question({items.ID});return false;"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i> </a>
									# ENDIF #
								</div>
								<div class="content">{items.CONTENT}</div>
							</div>
						</article>
					# END items #
				</div>
			</div>
		</div>
	# ELSE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
			<div class="sub-section">
				<div class="content-container">
					<div class="content">
						<div id="no-item-message" class="message-helper bgc notice align-center"# IF C_ITEMS # style="display: none;"# ENDIF #>
							{@common.no.item.now}
						</div>
					</div>
				</div>
			</div>
		# ENDIF #
	# ENDIF #

	<footer></footer>
</section>

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
				<div class="accordion-container">
					# START items #
						<article id="question{items.ID}" itemscope="itemscope" itemtype="https://schema.org/CreativeWork" class="# IF C_SINGLE_VIEW #single# ELSE #multiple# ENDIF #-accordion faq-item several-items# IF items.C_NEW_CONTENT # new-content# ENDIF #">
							<span class="accordion-trigger">{items.TITLE}</span>
							<div class="accordion-content faq-answer-container" itemprop="text">
								<div class="controls align-right">
									# IF C_PENDING_ITEMS #{items.ITEM_DATE} | # ENDIF #
									<a href="{items.U_ITEM}" onclick="copy_to_clipboard('{items.U_ABSOLUTE_LINK}');return false;" aria-label="{@common.copy.link.to.clipboard}"><i class="fa fa-fw fa-anchor" aria-hidden="true"></i></a>
									# IF items.C_EDIT #
										<a class="offload" href="{items.U_EDIT}"aria-label="{@common.edit}"><i class="far fa-fw fa-edit fa-fw" aria-hidden="true"></i> </a>
									# ENDIF #
									# IF items.C_DELETE #
										<a href="{items.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i> </a>
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

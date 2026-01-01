# IF C_ITEMS #
	<script>
		var FaqItems = function(id){
			this.id = id;
			this.items_number = {ITEMS_NUMBER};
		};

		FaqItems.prototype = {
			init_sortable : function() {
				jQuery("ul#items-list").sortable({
					handle: '.sortable-selector',
					placeholder: '<div class="dropzone">' + ${escapejs(@common.drop.here)} + '</div>',
					onDrop: function ($item, container, _super, event) {
						FaqItems.change_reposition_pictures();
						$item.removeClass(container.group.options.draggedClass).removeAttr("style");
						$("body").removeClass(container.group.options.bodyClass);
					}
				});
			},
			serialize_sortable : function() {
				jQuery('#tree').val(JSON.stringify(this.get_sortable_sequence()));
			},
			get_sortable_sequence : function() {
				var sequence = jQuery("ul#items-list").sortable("serialize").get();
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

		var FaqItem = function(id, faq_items){
			this.id = id;
			this.FaqItems = faq_items;

			if (FaqItems.items_number > 1)
				FaqItems.change_reposition_pictures();
		};

		var FaqItems = new FaqItems('items-list');
		jQuery(document).ready(function() {
			FaqItems.init_sortable();
		});
	</script>
# ENDIF #
# INCLUDE MESSAGE_HELPER #
<section id="module-faq">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('faq', CATEGORY_ID))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF NOT C_ROOT_CATEGORY #{@faq.module.title}# ENDIF #
			# IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1>
			# IF C_ROOT_CATEGORY #{@faq.module.title}# ELSE #{CATEGORY_NAME}# ENDIF #
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

	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				# IF C_ITEMS #
					<form action="{REWRITED_SCRIPT}" method="post" id="position-update-form" onsubmit="FaqItems.serialize_sortable();" class="faq-reorder-form">
						<fieldset id="items-management">
							<ul id="items-list" class="sortable-block">
								# START items #
									<li class="sortable-element# IF items.C_NEW_CONTENT # new-content# ENDIF #" id="list-{items.ID}" data-id="{items.ID}">
										<div class="sortable-selector" aria-label="{@common.move}"></div>
										<div class="sortable-title">
											<span class="item-title">{items.TITLE}</span>
										</div>
										<div class="sortable-actions">
											# IF C_SEVERAL_ITEMS #
												<a href="#" aria-label="{@common.move.up}" id="move-up-{items.ID}" onclick="return false;"><i class="fa fa-fw fa-arrow-up" aria-hidden="true"></i></a>
												<a href="#" aria-label="{@common.move.down}" id="move-down-{items.ID}" onclick="return false;"><i class="fa fa-fw fa-arrow-down" aria-hidden="true"></i></a>
											# ENDIF #
											<a class="offload" href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
											<a href="{items.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
										</div>

										<script>
											jQuery(document).ready(function() {
												var faq_item = new FaqItem({items.ID}, FaqItems);

												if (FaqItems.items_number > 1) {
													jQuery('#move-up-{items.ID}').on('click',function(){
														var li = jQuery(this).closest('li');
														li.insertBefore( li.prev() );
														FaqItems.change_reposition_pictures();
													});
													jQuery('#move-down-{items.ID}').on('click',function(){
														var li = jQuery(this).closest('li');
														li.insertAfter( li.next() );
														FaqItems.change_reposition_pictures();
													});
												}
											});
										</script>
									</li>
								# END items #
							</ul>
						</fieldset>
						# IF C_SEVERAL_ITEMS #
						<fieldset class="fieldset-submit" id="position-update-button">
							<button type="submit" name="submit" value="true" class="button submit">{@common.update.position}</button>
							<input type="hidden" name="token" value="{TOKEN}">
							<input type="hidden" name="tree" id="tree" value="">
						</fieldset>
						# ENDIF #
					</form>
				# ELSE #
					# IF NOT C_HIDE_NO_ITEM_MESSAGE #
						<div class="message-helper bgc notice align-center">
							{@common.no_item_now}
						</div>
					# ENDIF #
				# ENDIF #
			</div>
		</div>
	</div>
	<footer></footer>
</section>

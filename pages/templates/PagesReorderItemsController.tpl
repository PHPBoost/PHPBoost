# IF C_ITEMS #
	<script>
		var PagesItems = function(id){
			this.id = id;
			this.items_number = {ITEMS_NUMBER};
		};

		PagesItems.prototype = {
			init_sortable : function() {
				jQuery("ul#items-list").sortable({
					handle: '.sortable-selector',
					placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>',
					onDrop: function ($item, container, _super, event) {
						PagesItems.change_reposition_pictures();
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

		var PagesItem = function(id, pages_items){
			this.id = id;
			this.PagesItems = pages_items;

			if (PagesItems.items_number > 1)
				PagesItems.change_reposition_pictures();
		};

		var PagesItems = new PagesItems('items-list');
		jQuery(document).ready(function() {
			PagesItems.init_sortable();
		});
	</script>
# ENDIF #
# INCLUDE MSG #
<section id="module-page">
	<header>
		<div class="controls align-right">
			<a href="${relative_url(SyndicationUrlBuilder::rss('pages', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1>
			{@module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
		</h1>
	</header>
	# IF C_CATEGORY_DESCRIPTION #
		<div class="cat-description">
			{CATEGORY_DESCRIPTION}
		</div>
	# ENDIF #

	# IF C_ITEMS #
		<div class="content">
			<form action="{REWRITED_SCRIPT}" method="post" id="position-update-form" onsubmit="PagesItems.serialize_sortable();" class="pages-reorder-form">
				<fieldset id="items-management">
					<ul id="items-list" class="sortable-block">
						# START items #
							<li class="sortable-element# IF items.C_NEW_CONTENT # new-content# ENDIF #" id="list-{items.ID}" data-id="{items.ID}">
								<div class="sortable-selector" aria-label="${LangLoader::get_message('position.move', 'common')}"></div>
								<div class="sortable-title">
									<span class="item-title">{items.TITLE}</span>
								</div>
								<div class="sortable-actions">
									# IF C_SEVERAL_ITEMS #
										<a href="#" aria-label="${LangLoader::get_message('position.move_up', 'common')}" id="move-up-{items.ID}" onclick="return false;"><i class="fa fa-fw fa-arrow-up" aria-hidden="true"></i></a>
										<a href="#" aria-label="${LangLoader::get_message('position.move_down', 'common')}" id="move-down-{items.ID}" onclick="return false;"><i class="fa fa-fw fa-arrow-down" aria-hidden="true"></i></a>
									# ENDIF #
									<a href="{items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
									<a href="#" onclick="return false;" aria-label="${LangLoader::get_message('delete', 'common')}" id="delete-{items.ID}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
								</div>

								<script>
									jQuery(document).ready(function() {
										var pages_question = new PagesItem({items.ID}, PagesItems);

										if (PagesItems.items_number > 1) {
											jQuery('#move-up-{items.ID}').on('click',function(){
												var li = jQuery(this).closest('li');
												li.insertBefore( li.prev() );
												PagesItems.change_reposition_pictures();
											});
											jQuery('#move-down-{items.ID}').on('click',function(){
												var li = jQuery(this).closest('li');
												li.insertAfter( li.next() );
												PagesItems.change_reposition_pictures();
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
						<button type="submit" name="submit" value="true" class="button submit">${LangLoader::get_message('position.update', 'common')}</button>
						<input type="hidden" name="token" value="{TOKEN}">
						<input type="hidden" name="tree" id="tree" value="">
					</fieldset>
				# ENDIF #
			</form>
		</div>
	# ELSE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
			<div class="message-helper bgc notice align-center">
				${LangLoader::get_message('no_item_now', 'common')}
			</div>
		# ENDIF #
	# ENDIF #

	<footer></footer>
</section>

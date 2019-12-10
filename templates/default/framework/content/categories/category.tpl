<li id="cat-{ID}" class="sortable-element" data-id="{ID}">
	<div class="sortable-selector" aria-label="${LangLoader::get_message('position.move', 'common')}"></div>
	<div class="sortable-title">
		<a href="{U_DISPLAY}">{NAME}</a>
		# IF C_DESCRIPTION #<em class="h-padding small">{DESCRIPTION}</em># ENDIF #
	</div>
	<div class="sortable-actions">
		<a href="" aria-label="${LangLoader::get_message('position.move_up', 'common')}" id="move-up-{ID}" onclick="return false;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
		<a href="" aria-label="${LangLoader::get_message('position.move_down', 'common')}" id="move-down-{ID}" onclick="return false;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
		<a href="{U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
		<a href="{U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="{DELETE_CONFIRMATION_MESSAGE}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
	</div>
	<script>
	<!--
	jQuery(document).ready(function() {
		jQuery("#move-up-{ID}").on('click',function(){
			var li = jQuery(this).closest('li');
			li.insertBefore( li.prev() );
			change_reposition_pictures();
		});

		jQuery("#move-down-{ID}").on('click',function(){
			var li = jQuery(this).closest('li');
			li.insertAfter( li.next() );
			change_reposition_pictures();
		});
	});
	-->
	</script>

	# IF C_ALLOWED_TO_HAVE_CHILDS #
		<ul id="subcat-{ID}" class="sortable-block">
			# START children #
				{children.child}
			# END children #
		</ul>
	# ENDIF #
</li>

<li id="cat-{ID}" class="sortable-element" data-id="{ID}">
	<div class="sortable-selector" title="${LangLoader::get_message('position.move', 'common')}"></div>
	<div class="sortable-title"> 
		<i class="fa fa-globe"></i>
		<a href="{U_DISPLAY}" title="{NAME}">{NAME}</a>
		# IF C_DESCRIPTION #<span class="cat-desc"> | {DESCRIPTION}</span># ENDIF #
		<div class="sortable-actions">
			<a href="" title="${LangLoader::get_message('position.move_up', 'common')}" id="move-up-{ID}" onclick="return false;"><i class="fa fa-arrow-up"></i></a>
			<a href="" title="${LangLoader::get_message('position.move_down', 'common')}" id="move-down-{ID}" onclick="return false;"><i class="fa fa-arrow-down"></i></a>
			<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
			<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="{DELETE_CONFIRMATION_MESSAGE}"><i class="fa fa-delete"></i></a>
		</div>
	</div>
	<div class="spacer"></div>
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
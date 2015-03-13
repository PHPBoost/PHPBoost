<li id="cat_{ID}" class="sortable-element" data-id="{ID}">
	<div class="sortable-title"> 
		<a title="${LangLoader::get_message('move', 'admin')}" class="fa fa-arrows"></a>
		<i class="fa fa-globe"></i>
		{NAME}
		# IF C_DESCRIPTION #<span class="cat-desc"> | {DESCRIPTION}</span># ENDIF #
		<div class="sortable-actions">
			<div class="sortable-options">
				<a href="" title="${LangLoader::get_message('position.move_up', 'common')}" id="move-up-{ID}" onclick="return false;" class="fa fa-arrow-up"></a>
			</div>
			<div class="sortable-options">
				<a href="" title="${LangLoader::get_message('position.move_down', 'common')}" id="move-down-{ID}" onclick="return false;" class="fa fa-arrow-down"></a>
			</div>
			<div class="sortable-options">
				<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
			</div>
			<div class="sortable-options">
				<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
			</div>
		</div>
	</div>
	<div class="spacer"></div>
	<script>
	<!--
	jQuery(document).ready(function() {
		jQuery("#cat_{ID}").on('mouseout',function(){
			change_reposition_pictures();
		});
		
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

	<ul id="subcat_{ID}" class="sortable-block">
		# START childrens #
			{childrens.child}
		# END childrens #
	</ul>
</li>
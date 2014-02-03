<script>
<!--
Event.observe(window, 'load', function() {
	$('move_up_{ID}').observe('click',function(){
		move_category_up($('cat_{ID}').parentNode.id, $('cat_{ID}').id);
	});
	
	$('move_down_{ID}').observe('click',function(){
		move_category_down($('cat_{ID}').parentNode.id, $('cat_{ID}').id);
	});
});
-->
</script>
<li id="cat_{ID}" class="sortable-element">
	<div class="sortable-title"> 
		<a title="${LangLoader::get_message('move', 'admin')}" class="fa fa-arrows"></a>
		<i class="fa fa-globe"></i>
		{NAME}
		# IF C_DESCRIPTION #<span class="cat_desc"> | {DESCRIPTION}</span># ENDIF #
		<div class="sortable-actions">
			<div class="sortable-options">
				<a href="#" title="{@category.move_up}" id="move_up_{ID}" class="fa fa-arrow-up"></a>
			</div>
			<div class="sortable-options">
				<a href="#" title="{@category.move_down}" id="move_down_{ID}" class="fa fa-arrow-down"></a>
			</div>
			<div class="sortable-options">
				<a href="{U_EDIT}" title="${LangLoader::get_message('update', 'main')}" class="fa fa-edit"></a>
			</div>
			<div class="sortable-options">
				<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
			</div>
		</div>
	</div>
	<div class="spacer"></div>
	
	<ul id="subcat_{ID}" class="sortable-block">
		# START childrens #
			{childrens.child}
		# END childrens #
	</ul>
</li>
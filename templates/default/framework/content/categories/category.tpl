<script type="text/javascript">
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
		<i title="${LangLoader::get_message('move', 'admin')}" class="icon-arrows"></i>
		<i class="icon-globe"></i>
		{NAME}
		# IF C_DESCRIPTION #<span class="cat_desc"> | {DESCRIPTION}</span># ENDIF #
		<div class="sortable-actions">
			<div class="sortable-options">
				<a title="{@category.move_up}" id="move_up_{ID}"><i class="icon-arrow-up"></i></a>
			</div>
			<div class="sortable-options">
				<a title="{@category.move_down}" id="move_down_{ID}"><i class="icon-arrow-down"></i></a>
			</div>
			<div class="sortable-options">
				<a href="{U_EDIT}" title="${LangLoader::get_message('update', 'main')}" class="icon-edit"></a>
			</div>
			<div class="sortable-options">
				<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete" data-confirmation="delete-element"></a>
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
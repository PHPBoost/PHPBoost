<script type="text/javascript">
<!--
Event.observe(window, 'load', function() {
	Sortable.destroy('categories'); 
	Sortable.create('categories', {tree:true, dropOnEmpty: true});  
	console.log(Sortable.serialize('categories'));
});

function serialize_sortable()
{
	$('tree').value = Sortable.serialize('categories');
	console.log(Sortable.serialize('categories'));
}
-->
</script>
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="serialize_sortable();">
	<fieldset>
		<legend>{@categories}</legend>
			<ul id="categories" class="sortable-block">
				# IF C_NO_CATEGORIES #
					<div class="center">{@message.no_categories}</div>
				# ELSE #
					# START childrens #
						{childrens.child}
					# END childrens #
				# ENDIF #
			</ul>
	</fieldset>
	<fieldset class="fieldset_submit">
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="tree" id="tree" value="">
		<button type="submit" name="valid" value="true">${LangLoader::get_message('submit', 'main')}</button>					
	</fieldset>
</form>
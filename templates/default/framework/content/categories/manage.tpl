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
			<ul id="categories" class="cat_list">
				# START childrens #
					{childrens.child}
				# END childrens #
			</ul>
	</fieldset>
	<fieldset class="fieldset_submit">
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="tree" id="tree" value="">
		<input type="submit" name="valid" value="test" class="submit">					
	</fieldset>
</form>
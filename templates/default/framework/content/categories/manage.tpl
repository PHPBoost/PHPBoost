<script type="text/javascript">
<!--
Event.observe(window, 'load', function() {
	Sortable.destroy('cats_elements_list'); 
	Sortable.create('cats_elements_list', {tree:true, dropOnEmpty: true});  
	console.log(Sortable.serialize('cats_elements_list'));
});

function serialize_sortable()
{
	console.log(Sortable.serialize('cats_elements_list'));
}
-->
</script>
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="serialize_sortable();">
	<fieldset>
		<legend>Gestion des catégories</legend>
			<ul id="cats_elements_list" class="cat_list">
				# START childrens #
					{childrens.child}
				# END childrens #
			</ul>
	</fieldset>
	<fieldset class="fieldset_submit">
		<legend>Enregistrer les modifications</legend>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="menu_tree" id="menu_tree" value="">
		<input type="submit" name="valid" value="Enregistrer les modifications" class="submit">					
	</fieldset>
</form>
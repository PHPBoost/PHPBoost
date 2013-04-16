<script type="text/javascript">
<!--
Event.observe(window, 'load', function() {
	Sortable.create('cats_elements_list', {tree:true,dropOnEmpty: true,scroll:window,format: /^menu_element_([0-9]+)$/});   
});
-->
</script>
<fieldset>
	<legend>Gestion des catégories</legend>
	<ul id="menu_element_lists" class="menu_link_list_tosupp cat_list" style="position: relative;">
		<li class="menu_link_menu_tosupp" style="position: relative;">
			<ul id="cats_elements_list" class="cat_list" style="">
				# START childrens #
					{childrens.child}
				# END childrens #
			</ul>
		</li>
	</ul>
</fieldset>
<fieldset class="fieldset_submit">
	<legend>Enregistrer les modifications</legend>
	<input type="hidden" name="id" value="0">
	<input type="hidden" name="token" value="c04be7aff42fb860">
	<input type="hidden" name="menu_tree" id="menu_tree" value="">
	<input type="submit" name="valid" value="Enregistrer les modifications" class="submit">					
</fieldset>
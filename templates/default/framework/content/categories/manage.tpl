<script>
<!--
jQuery(document).ready(function() {
	Sortable.destroy('categories'); 
	Sortable.create('categories', {tree:true, dropOnEmpty: true});  
});

function serialize_sortable()
{
	jQuery('#tree').val(Sortable.serialize('categories'));
}

function move_category_up(parent_id, id) {
	var childs = jQuery('#' + parent_id)[0].childNodes;
	var cat_id = 0;
	var previous_id = 0;
	
	for (index = 0; index < childs.length; index++) {
		if (childs[index].id == id) {
			cat_id = index;
		}
		if (childs[index].id != '' && cat_id == 0) {
			previous_id = index;
		}
	}
	
	if (cat_id > 0 || previous_id > 0) {
		jQuery('#' + parent_id)[0].insertBefore(childs[cat_id], childs[previous_id]);
	}
}

function move_category_down(parent_id, id){
	var childs = jQuery('#' + parent_id)[0].childNodes;
	var cat_id = 0;
	var previous_id = 0;
	
	for (index = 0; index < childs.length; index++) {
		if (childs[index].id != '' && cat_id > 0 && previous_id == 0) {
			previous_id = index;
		}
		if (childs[index].id == id) {
			cat_id = index;
		}
	}
	
	if (cat_id > 0 || previous_id > 0) {
		jQuery('#' + parent_id)[0].insertBefore(childs[previous_id], childs[cat_id]);
	}
}
-->
</script>
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="serialize_sortable();">
	<fieldset>
		<legend>{FIELDSET_TITLE}</legend>
			<ul id="categories" class="sortable-block">
				# IF C_NO_CATEGORIES #
					<div class="center">${LangLoader::get_message('no_item_now', 'common')}</div>
				# ELSE #
					# START childrens #
						{childrens.child}
					# END childrens #
				# ENDIF #
			</ul>
	</fieldset>
	# IF C_MORE_THAN_ONE_CATEGORY #
	<fieldset class="fieldset-submit">
		<button type="submit" class="submit" name="submit" value="true">${LangLoader::get_message('position.update', 'common')}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="tree" id="tree" value="">
	</fieldset>
	# ENDIF #
</form>
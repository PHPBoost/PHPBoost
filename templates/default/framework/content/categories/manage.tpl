<script>
<!--
jQuery(document).ready(function() {
	jQuery('ul#categories').sortable({
		handle: '.sortable-selector',
		placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>'
	});
	change_reposition_pictures();
	jQuery('li.sortable-element').on('mouseout',function(){
		change_reposition_pictures();
	});
});

function serialize_sortable()
{
	jQuery('#tree').val(JSON.stringify(get_sortable_sequence()));
}

function get_sortable_sequence()
{
	var sequence = jQuery('ul#categories').sortable('serialize').get();
	return sequence[0];
}

function change_children_reposition_pictures(list)
{
	var length = list.length;
	for(var i = 0; i < length; i++)
	{
		if (jQuery('#cat-' + list[i].id).is(':first-child'))
			jQuery("#move-up-" + list[i].id).hide();
		else
			jQuery("#move-up-" + list[i].id).show();
		
		if (jQuery('#cat-' + list[i].id).is(':last-child'))
			jQuery("#move-down-" + list[i].id).hide();
		else
			jQuery("#move-down-" + list[i].id).show();
		
		if (typeof list[i].children !== 'undefined')
		{
			var children = list[i].children[0];
			change_children_reposition_pictures(children);
		}
	}
}

function change_reposition_pictures()
{
	change_children_reposition_pictures(get_sortable_sequence());
}
-->
</script>
# INCLUDE MSG #
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="serialize_sortable();">
	<fieldset>
		<legend>{FIELDSET_TITLE}</legend>
			<div class="fieldset-inset">
				<ul id="categories" class="sortable-block">
					# IF C_NO_CATEGORIES #
						<div class="center">${LangLoader::get_message('no_item_now', 'common')}</div>
					# ELSE #
						# START children #
							{children.child}
						# END children #
					# ENDIF #
				</ul>
			</div>
	</fieldset>
	# IF C_MORE_THAN_ONE_CATEGORY #
	<fieldset class="fieldset-submit">
		<button type="submit" class="submit" name="submit" value="true">${LangLoader::get_message('position.update', 'common')}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="tree" id="tree" value="">
	</fieldset>
	# ENDIF #
</form>
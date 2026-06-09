<style>
	ul.sortable-block {
		min-height: 10px;
	}
	ul.sortable-block-disabled {
		pointer-events: none;
		min-height: 0;
	}
</style>
<script>
	var sortableInstances = [];

	function init_all_sortables() {
		sortableInstances.forEach(function(inst) { inst.destroy(); });
		sortableInstances = [];
		jQuery('ul#categories, ul#categories ul.sortable-block:not(.sortable-block-disabled)').each(function() {
			var instance = Sortable.create(this, {
				group: {
					name: 'categories',
					pull: true,
					put: true
				},
				handle: '.sortable-selector',
				animation: 150,
				fallbackOnBody: true,
				onEnd: function (evt) {
					init_all_sortables();
					change_reposition_pictures();
				}
			});
			sortableInstances.push(instance);
		});
	}

	jQuery(document).ready(function() {
		if (jQuery('ul#categories')[0]) {
			init_all_sortables();
			change_reposition_pictures();
		}
	});

	function serialize_sortable()
	{
		jQuery('#tree').val(JSON.stringify(get_sortable_sequence()));
	}

	function get_ul_sequence(ul) {
		var sequence = [];
		jQuery(ul).children('li').each(function() {
			var item = { id: jQuery(this).data('id') };
			var childUl = jQuery(this).children('ul.sortable-block')[0];
			if (childUl && jQuery(childUl).children('li').length > 0) {
				item.children = [get_ul_sequence(childUl)];
			}
			sequence.push(item);
		});
		return sequence;
	}

	function get_sortable_sequence()
	{
		return get_ul_sequence(document.getElementById('categories'));
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
</script>
# INCLUDE MESSAGE_HELPER #
<section id="module-{MODULE_ID}">
	<header class="section-header">
		<h1>{FIELDSET_TITLE}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				# IF C_NO_CATEGORY #
					<div class="message-helper bgc notice">{@category.no.element}</div>
				# ELSE #
					<form action="{REWRITED_SCRIPT}" method="post" onsubmit="serialize_sortable();">
						<fieldset>
							<div class="fieldset-inset">
								<ul id="categories" class="sortable-block">
									# START children #
										{children.child}
									# END children #
								</ul>
							</div>
						</fieldset>
						# IF C_SEVERAL_CATEGORIES #
							<fieldset class="fieldset-submit">
								<legend class="sr-only">{@common.update.position}</legend>
								<button type="submit" class="button submit" name="submit" value="true">{@common.update.position}</button>
								<input type="hidden" name="token" value="{TOKEN}">
								<input type="hidden" name="tree" id="tree" value="">
							</fieldset>
						# ENDIF #
					</form>
				# ENDIF #
			</div>
		</div>
	</div>
</section>

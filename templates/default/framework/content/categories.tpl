# IF C_NO_CATEGORY #
<div class="notice">
	{L_NO_EXISTING_CATEGORY}
</div>
# ENDIF #

# IF C_AJAX_MODE #
<script type="text/javascript">
<!--
var Categories = Class.create({
	id : '',
	tree_tag : '',
	initialize : function(id, tree_tag) {
		this.id = id;
		this.tree_tag = tree_tag;
	},
	create_sortable : function() {
		Sortable.create(this.id, {
			tree:true,
			treeTag:this.tree_tag,
			scroll:window,
			tag:'div',
			only:'menu_link_element'
		});
	},
	destroy_sortable : function() {
		Sortable.destroy(this.id); 
	},
	serialize_sortable : function() {
		$('position').value = Sortable.serialize(this.id);
	}
});

var Categorie = Class.create({
	id : '',
	more_is_opened : false,
	Categories: null,
	is_not_displayed : false,
	initialize : function(id, display, Categories) {
		this.id = id;
		this.Categories = Categories;
		if (display == 1) {
			this.is_not_displayed = false;
		}
		else {
			this.is_not_displayed = true;
		}
		this.change_display_picture();
	},
	delete_fields : function() {
		if (confirm(${escapejs(L_CONFIRM_DELETE)}))
		{
			new Ajax.Request('{CONFIG_XMLHTTPREQUEST_FILE}', {
				method:'get',
				parameters: {'id' : this.id, 'token' : '{TOKEN}', 'delete': true},
			});
			
			var elementToDelete = $('list_' + this.id);
			elementToDelete.parentNode.removeChild(elementToDelete);
			ExtendedFields.destroy_sortable();
			ExtendedFields.create_sortable();
		}
	},
	change_display : function() {
		$('loading_' + this.id).update('<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />');
		var display = this.is_not_displayed;
		var display_parameter = (!display == 'show' ? 'show' : 'hide');
		new Ajax.Request('{CONFIG_XMLHTTPREQUEST_FILE}', {
			method:'get',
			parameters: {display_parameter : this.id, 'token' : '{TOKEN}'},
		});
		
		this.change_display_picture();
		Element.update.delay(0.3, 'loading_' + this.id, ''); 
	},
	change_display_picture : function() {
		if (this.is_not_displayed == false) {
			$('change_display_' + this.id).src = "{PATH_TO_ROOT}/templates/{THEME}/images/processed_mini.png";
			this.is_not_displayed = true;
		}
		else {
			$('change_display_' + this.id).src = "{PATH_TO_ROOT}/templates/{THEME}/images/not_processed_mini.png";
			this.is_not_displayed = false;
		}
	},
});

var Categories = new Categories('cat_administration', 'div');
Event.observe(window, 'load', function() {
	Categories.destroy_sortable();
	Categories.create_sortable();
});
-->
</script>
<div id="cat_administration">
# ENDIF #

{NESTED_CATEGORIES}

# IF C_AJAX_MODE #
</div>
# ENDIF #
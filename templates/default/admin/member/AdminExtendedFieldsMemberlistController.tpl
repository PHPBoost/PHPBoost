<script type="text/javascript">
<!--

var ExtendedFields = Class.create({
	id : '',
	initialize : function(id) {
		this.id = id;
	},
	create_sortable : function() {
		Sortable.create(this.id,
			{
				tag:'div',
				only:'menu_link_element'
			}
		);
	},
	destroy_sortable : function() {
		Sortable.destroy(this.id); 
	}
});

var ExtendedField = Class.create({
	id : '',
	more_is_opened : false,
	ExtendedFields: null,
	initialize : function(id, extended_fields) {
		this.id = id;
		this.ExtendedFields = extended_fields;
	},
	click : function() {
		if (this.more_is_opened == false)
		{
			this.open_more();
		}
		else
		{
			this.hide_more();
		}
	},
	open_more : function() {
		$('more_' + this.id).appear();
		this.more_is_opened = true;
		document.getElementById('click_more_' + this.id).src = "{PATH_TO_ROOT}/templates/{THEME}/images/form/minus.png";
	},
	hide_more : function() {
		$('more_' + this.id).fade();
		this.more_is_opened = false;
		document.getElementById('click_more_' + this.id).src = "{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png";
	},
	delete_fields : function() {
		if (confirm(${escapejs(L_ALERT_DELETE_FIELD)}))
		{
			var elementToDelete = document.getElementById('list_' + this.id);
			elementToDelete.parentNode.removeChild(elementToDelete);
			ExtendedFields.destroy_sortable();
			ExtendedFields.create_sortable();
		}
	}

});

var ExtendedFields = new ExtendedFields('lists');
Event.observe(window, 'load', function() {
	ExtendedFields.destroy_sortable();
	ExtendedFields.create_sortable();
});
-->
</script>
# INCLUDE MSG #
<fieldset id="management_extended_fields">
<legend>{L_MANAGEMENT_EXTENDED_FIELDS}</legend>
	<div id="lists">
	<div id="update">Update</div>
	<div id="test"></div>
	# START list_extended_fields #
		<div class="menu_link_element" id="list_{list_extended_fields.ID}">
			<div style="float:left;">
				<img src="{PATH_TO_ROOT}/templates/default/images/drag.png" alt="drag" class="valign_middle" style="padding-left:5px;padding-right:5px;cursor:move" />
				<img src="{PATH_TO_ROOT}/templates/base/images/form/url.png" alt="plus" class="valign_middle" style="cursor:move" />
				<span style="margin-right:30px;">{L_NAME} : {list_extended_fields.NAME}</span>
				<span style="margin-right:30px;">{L_REQUIRED} : {list_extended_fields.L_REQUIRED}</span> 
				<span style="margin-right:30px;">{L_DISPLAY} : {list_extended_fields.L_DISPLAY}</span>
			</div>
			<div style="float:right;">
				<img src="{PATH_TO_ROOT}/templates/base/images/form/plus.png" alt="Plus de détails" id="click_more_{list_extended_fields.ID}" class="valign_middle" />
				<a href="{list_extended_fields.EDIT_LINK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" class="valign_middle" /></a>
				<img src="{PATH_TO_ROOT}/templates/base/images/french/delete.png" alt="{L_DELETE}" id="delete_{list_extended_fields.ID}" class="valign_middle" />
			</div>
			<div class="spacer"></div>
			<fieldset id="more_{list_extended_fields.ID}" style="display:none;">
				<legend>Autorisation</legend>
			</fieldset>
			
		</div>
		<script type="text/javascript">
		<!--
		Event.observe(window, 'load', function() {
			var sort = Sortable.serialize('lists');
			$('test').update(sort);
			
			$('update').observe('click',function(){
				var sort = Sortable.serialize('lists');
				$('test').update(sort);
			});
			var extended_field = new ExtendedField({list_extended_fields.ID}, ExtendedFields);
			$('click_more_{list_extended_fields.ID}').observe('click',function(){
				extended_field.click();
			});
			
			$('delete_{list_extended_fields.ID}').observe('click',function(){
				extended_field.delete_fields();
			});
		});
		-->
		</script>
	# END list_extended_fields #
	</div>
</fieldset>
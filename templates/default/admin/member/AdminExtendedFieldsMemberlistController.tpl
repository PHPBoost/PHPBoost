<script type="text/javascript">
<!--
var ExtendedFields = Class.create({
	id : '',
	initialize : function(id) {
		this.id = id;
	},
	create_sortable : function() {
		Sortable.create(this.id, {
				tag:'div',
				only:'menu_link_element',
			}
		);
	},
	destroy_sortable : function() {
		Sortable.destroy(this.id); 
	},
	serialize_sortable : function() {
		$('position').value = Sortable.serialize(this.id);
	}
});

var ExtendedField = Class.create({
	id : '',
	more_is_opened : false,
	ExtendedFields: null,
	is_not_displayed : false,
	initialize : function(id, display, extended_fields) {
		this.id = id;
		this.ExtendedFields = extended_fields;
		if (display == 1) {
			this.is_not_displayed = false;
		}
		else {
			this.is_not_displayed = true;
		}
		this.change_display_picture();
	},
	delete_fields : function() {
		if (confirm(${escapejs(L_ALERT_DELETE_FIELD)}))
		{
			new Ajax.Request('{PATH_TO_ROOT}/admin/member/?url=/extended-fields/delete/', {
				method:'post',
				parameters: {'id' : this.id, 'token' : '{TOKEN}'}
			});
			
			var elementToDelete = $('list_' + this.id);
			elementToDelete.parentNode.removeChild(elementToDelete);
			ExtendedFields.destroy_sortable();
			ExtendedFields.create_sortable();
		}
	},
	change_display : function() {
		display = this.is_not_displayed;
		this.change_display_picture();
		new Ajax.Request('{REWRITED_SCRIPT}', {
			method:'post',
			parameters: {'id' : this.id, 'token' : '{TOKEN}', 'display': !display},
			onSuccess: function() {
				this.change_display_picture();
			},
			onFailure: function(transport) {
				alert('Error');
			}
		});
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

var ExtendedFields = new ExtendedFields('lists');
Event.observe(window, 'load', function() {
	ExtendedFields.destroy_sortable();
	ExtendedFields.create_sortable();
});
-->
</script>
# INCLUDE MSG #
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="ExtendedFields.serialize_sortable();">
	<fieldset id="management_extended_fields">
	<legend>{L_MANAGEMENT_EXTENDED_FIELDS}</legend>
		<div id="lists">
		# START list_extended_fields #
			<div class="menu_link_element" id="list_{list_extended_fields.ID}">
				<div style="float:left;">
					<img src="{PATH_TO_ROOT}/templates/default/images/drag.png" alt="drag" class="valign_middle" style="padding-left:5px;padding-right:5px;cursor:move" />
					<img src="{PATH_TO_ROOT}/templates/base/images/form/url.png" alt="url" class="valign_middle" style="cursor:move;margin-right:10px;" />
					{L_NAME} : <span class="text_strong" >{list_extended_fields.NAME}</span>
				</div>
				<div style="float:right;">
					{L_REQUIRED} : <span style="margin-right:30px;" class="text_strong" >{list_extended_fields.L_REQUIRED}</span> 
					<a href="{list_extended_fields.EDIT_LINK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" class="valign_middle" /></a>
					# IF NOT list_extended_fields.FREEZE #
					<img src="{PATH_TO_ROOT}/templates/base/images/french/delete.png" alt="{L_DELETE}" id="delete_{list_extended_fields.ID}" class="valign_middle" />
					# ENDIF #
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/not_processed_mini.png" id="change_display_{list_extended_fields.ID}" class="valign_middle" />
				</div>
				<div class="spacer"></div>
			</div>
			<script type="text/javascript">
			<!--
			Event.observe(window, 'load', function() {
				var extended_field = new ExtendedField({list_extended_fields.ID}, '{list_extended_fields.DISPLAY}', ExtendedFields);
				
				# IF NOT list_extended_fields.FREEZE #				
				$('delete_{list_extended_fields.ID}').observe('click',function(){
					extended_field.delete_fields();
				});
				# ENDIF #
				
				$('change_display_{list_extended_fields.ID}').observe('click',function(){
					extended_field.change_display();
				});
			});
			-->
			</script>
		# END list_extended_fields #
		</div>
	</fieldset>
	<fieldset class="fieldset_submit">
		<input type="submit" name="submit" value="{L_VALID}" class="submit" />
		<input type="hidden" name="token" value="{TOKEN}" />
		<input type="hidden" name="position" id="position" value="" />
	</fieldset>
</form>
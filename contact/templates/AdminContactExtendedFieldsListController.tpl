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
			new Ajax.Request('{DELETE_LINK}', {
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
		$('loading_' + this.id).update('<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />');
		display = this.is_not_displayed;
		
		new Ajax.Request('{REWRITED_SCRIPT}', {
			method:'post',
			parameters: {'id' : this.id, 'token' : '{TOKEN}', 'display': !display},
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
					<div class="float_left">
						<img src="{PATH_TO_ROOT}/templates/default/images/drag.png" alt="drag" class="drag_picture" />
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/url.png" alt="url" class="url_picture" />
						{L_NAME} : <span class="text_strong" >{list_extended_fields.NAME}</span>
					</div>
					<div class="float_right">
						{L_REQUIRED} : <span class="require" >{list_extended_fields.L_REQUIRED}</span> 
						<img id="loading_{list_extended_fields.ID}" alt="" class="valign_middle" />
						<a href="{list_extended_fields.EDIT_LINK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" class="valign_middle" /></a>
						# IF NOT list_extended_fields.FREEZE #
						<a href="#">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" id="delete_{list_extended_fields.ID}" class="valign_middle" />
						</a>
						# ENDIF #
						<a href="#">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/not_processed_mini.png" alt="{L_PROCESSED_OR_NOT}" title="{L_PROCESSED_OR_NOT}" id="change_display_{list_extended_fields.ID}" class="valign_middle" />
						</a>
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
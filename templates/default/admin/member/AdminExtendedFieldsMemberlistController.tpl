<script type="text/javascript">
<!--
var ExtendedFields = Class.create({
	id : '',
	initialize : function(id) {
		this.id = id;
	},
	create_sortable : function() {
		Sortable.create(this.id, {
			tag:'li',
			only:'sortable-element'
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
		$('loading_' + this.id).update('<img src="{PATH_TO_ROOT}/templates/default/images/admin/loading_mini.gif" alt="" class="valign_middle" />');
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
			$('change_display_' + this.id).className = "icon-eye";
			this.is_not_displayed = true;
		}
		else {
			$('change_display_' + this.id).className = "icon-eye-slash";
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
		<ul id="lists" class="sortable-block">
			# START list_extended_fields #
				<li id="list_{list_extended_fields.ID}" class="sortable-element">
					<div class="sortable-title">
						<i class="icon-arrows" title="${LangLoader::get_message('move', 'admin')}"></i>
						<i class="icon-globe"></i>
						{L_NAME} : <span class="text_strong" >{list_extended_fields.NAME}</span>
						<div class="sortable-actions">
							{L_REQUIRED} : <span class="text_strong" >{list_extended_fields.L_REQUIRED}</span> 
							<div class="sortable-options">
								<img id="loading_{list_extended_fields.ID}" alt="" class="valign_middle" />
							</div>
							<div class="sortable-options">
								<a href="{list_extended_fields.EDIT_LINK}" title="{L_UPDATE}" class="icon-edit"></a>
							</div>
							<div class="sortable-options">
								# IF NOT list_extended_fields.FREEZE #
								<a title="{L_DELETE}" id="delete_{list_extended_fields.ID}" class="icon-delete"></a>
								# ELSE #
								&nbsp;
								# ENDIF #
							</div>
							<a title="{L_PROCESSED_OR_NOT}" ><i id="change_display_{list_extended_fields.ID}" class="icon-eye-slash"></i></a>
						</div>
					</div>
					<div class="spacer"></div>
				</li>
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
		</ul>
	</fieldset>
	<fieldset class="fieldset_submit">
		<button type="submit" name="submit" value="true">{L_VALID}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="position" id="position" value="">
	</fieldset>
</form>
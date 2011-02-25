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
	click : function() {
		if (this.more_is_opened == false) {
			this.open_more();
		}
		else {
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
			this.delete_ajax_request();
		}
	},
	delete_ajax_request : function() {
		new Ajax.Request('{PATH_TO_ROOT}/admin/member/?url=/extended-fields/'+ this.id +'/delete/&token={TOKEN}', {
			onFailure: function() { 
				alert('Error');
			}
		});
	},
	change_display : function() {
		display = this.is_not_displayed;
		this.change_display_picture();
		new Ajax.Request('{PATH_TO_ROOT}/admin/member/?url=/extended-fields/'+ this.id +'/edit/&token={TOKEN}', {
			method:'post',
			parameters: {'display': !display},
			onSuccess: function() {
				this.change_display_picture();
			},
			onFailure: function(){ 
				alert('Error');
			}
		});
	},
	change_display_picture : function() {
		if (this.is_not_displayed == false) {
			document.getElementById('change_display_' + this.id).src = "{PATH_TO_ROOT}/templates/{THEME}/images/processed_mini.png";
			this.is_not_displayed = true;
		}
		else {
			document.getElementById('change_display_' + this.id).src = "{PATH_TO_ROOT}/templates/{THEME}/images/not_processed_mini.png";
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
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/not_processed_mini.png" id="change_display_{list_extended_fields.ID}" class="valign_middle" />
			</div>
			<div class="spacer"></div>
			<fieldset id="more_{list_extended_fields.ID}" style="display:none;">
				<legend>Autorisation</legend>
				<dl>
					<dt>
						<label for="auth_read">{L_AUTH_READ_PROFILE}</label>
					</dt>
					<dd>
						{list_extended_fields.AUTH_READ_PROFILE}
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="auth_read">{L_AUTH_READ_EDIT_AND_ADD}</label>
					</dt>
					<dd>
						{list_extended_fields.AUTH_READ_EDIT_AND_ADD}
					</dd>
				</dl>
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
			var extended_field = new ExtendedField({list_extended_fields.ID}, {list_extended_fields.DISPLAY}, ExtendedFields);
			$('click_more_{list_extended_fields.ID}').observe('click',function(){
				extended_field.click();
			});
			
			$('delete_{list_extended_fields.ID}').observe('click',function(){
				extended_field.delete_fields();
			});
			
			$('change_display_{list_extended_fields.ID}').observe('click',function(){
				extended_field.change_display();
			});
		});
		-->
		</script>
	# END list_extended_fields #
	</div>
	
</fieldset>
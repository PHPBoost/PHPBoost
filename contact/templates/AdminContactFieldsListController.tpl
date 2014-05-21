<script>
<!--
var ContactFields = Class.create({
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
	},
	get_sortable_sequence : function() {
		return Sortable.sequence(this.id);
	},
	set_sortable_sequence : function(sequence) {
		Sortable.setSequence(this.id, sequence);
	},
	change_reposition_pictures : function() {
		sequence = Sortable.sequence(this.id);
		
		$('move_up_' + sequence[0]).style.display = "none";
		$('move_down_' + sequence[0]).style.display = "inline";
		
		for (var j = 1 ; j < sequence.length - 1 ; j++) {
			$('move_up_' + sequence[j]).style.display = "inline";
			$('move_down_' + sequence[j]).style.display = "inline";
		}
		
		$('move_up_' + sequence[sequence.length - 1]).style.display = "inline";
		$('move_down_' + sequence[sequence.length - 1]).style.display = "none";
	}
});

var ContactField = Class.create({
	id : '',
	more_is_opened : false,
	ContactFields: null,
	is_not_displayed : false,
	initialize : function(id, display, contact_fields) {
		this.id = id;
		this.ContactFields = contact_fields;
		if (display == 1) {
			this.is_not_displayed = false;
		}
		else {
			this.is_not_displayed = true;
		}
		this.change_display_picture();
		
		# IF C_MORE_THAN_ONE_FIELD #
		this.ContactFields.change_reposition_pictures();
		# ENDIF #
	},
	delete_fields : function() {
		if (confirm(${escapejs(@fields.delete_field.confirm)}))
		{
			new Ajax.Request('${relative_url(ContactUrlBuilder::delete_field())}', {
				method:'post',
				asynchronous: false,
				parameters: {'id' : this.id, 'token' : '{TOKEN}'}
			});
			
			var elementToDelete = $('list_' + this.id);
			elementToDelete.parentNode.removeChild(elementToDelete);
			ContactFields.destroy_sortable();
			ContactFields.create_sortable();
		}
	},
	move_up : function() {
		var sequence = ContactFields.get_sortable_sequence();
		var reordered = false;
		
		if (sequence.length > 1)
			for (var j = 1 ; j < sequence.length ; j++) {
				if (sequence[j].length > 0 && sequence[j] == this.id) {
					var temp = sequence[j-1];
					sequence[j-1] = this.id;
					sequence[j] = temp;
					reordered = true;
				}
			}
		
		if (reordered) {
			ContactFields.set_sortable_sequence(sequence);
			ContactFields.change_reposition_pictures();
		}
	},
	move_down : function() {
		var sequence = ContactFields.get_sortable_sequence();
		var reordered = false;
		
		if (sequence.length > 1)
			for (var j = 0 ; j < sequence.length - 1 ; j++) {
				if (sequence[j].length > 0 && sequence[j] == this.id) {
					var temp = sequence[j+1];
					sequence[j+1] = this.id;
					sequence[j] = temp;
					reordered = true;
				}
			}
		
		if (reordered) {
			ContactFields.set_sortable_sequence(sequence);
			ContactFields.change_reposition_pictures();
		}
	},
	change_display : function() {
		display = this.is_not_displayed;
		
		new Ajax.Request('{REWRITED_SCRIPT}', {
			method:'post',
			parameters: {'id' : this.id, 'token' : '{TOKEN}', 'display': !display},
		});
		
		this.change_display_picture();
	},
	change_display_picture : function() {
		if ($('change_display_' + this.id)) {
			if (this.is_not_displayed == false) {
				$('change_display_' + this.id).className = "fa fa-eye";
				$('change_display_' + this.id).title = "{@field.display}";
				$('change_display_' + this.id).alt = "{@field.display}";
				this.is_not_displayed = true;
			}
			else {
				$('change_display_' + this.id).className = "fa fa-eye-slash";
				$('change_display_' + this.id).title = "{@field.not_display}";
				$('change_display_' + this.id).alt = "{@field.not_display}";
				this.is_not_displayed = false;
			}
		}
	},
});

var ContactFields = new ContactFields('fields_list');
Event.observe(window, 'load', function() {
	ContactFields.destroy_sortable();
	ContactFields.create_sortable();
});
-->
</script>
# INCLUDE MSG #
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="ContactFields.serialize_sortable();">
	<fieldset id="contact_fields_management">
	<legend>${LangLoader::get_message('admin.fields.manage', 'common', 'contact')}</legend>
		<ul id="fields_list" class="sortable-block">
			# START fields_list #
				<li class="sortable-element" id="list_{fields_list.ID}">
					<div class="sortable-title">
						<a title="${LangLoader::get_message('move', 'admin')}" class="fa fa-arrows"></a>
						<i class="fa fa-globe"></i>
						<span class="text-strong">{fields_list.NAME}</span>
						<div class="sortable-actions">
							{@field.required} : <span class="text-strong"># IF fields_list.C_REQUIRED #${LangLoader::get_message('yes', 'main')}# ELSE #${LangLoader::get_message('no', 'main')}# ENDIF #</span>
							# IF C_MORE_THAN_ONE_FIELD #
							<div class="sortable-options">
								<a href="" title="{@fields.move_field_up}" id="move_up_{fields_list.ID}" onclick="return false;" class="fa fa-arrow-up"></a>
							</div>
							<div class="sortable-options">
								<a href="" title="{@fields.move_field_down}" id="move_down_{fields_list.ID}" onclick="return false;" class="fa fa-arrow-down"></a>
							</div>
							# ENDIF #
							<div class="sortable-options">
								<a href="{fields_list.U_EDIT}" title="{@fields.action.edit_field}" class="fa fa-edit"></a>
							</div>
							<div class="sortable-options">
								# IF fields_list.C_DELETE #<a href="" onclick="return false;" title="{@fields.action.delete_field}" id="delete_{fields_list.ID}" class="fa fa-delete"></a># ELSE #&nbsp;# ENDIF #
							</div>
							<div class="sortable-options">
							# IF NOT fields_list.C_READONLY #<a href="" onclick="return false;" id="change_display_{fields_list.ID}" class="fa fa-eye"></a># ELSE #&nbsp;# ENDIF #
							</div>
						</div>
					</div>
					<div class="spacer"></div>
				</li>
				<script>
				<!--
				Event.observe(window, 'load', function() {
					var contact_field = new ContactField({fields_list.ID}, '{fields_list.C_DISPLAY}', ContactFields);
					
					$('list_{fields_list.ID}').observe('mouseup',function(){
						ContactFields.change_reposition_pictures();
					});
					
					# IF fields_list.C_DELETE #
					$('delete_{fields_list.ID}').observe('click',function(){
						contact_field.delete_fields();
					});
					# ENDIF #
					
					# IF NOT fields_list.C_READONLY #
					$('change_display_{fields_list.ID}').observe('click',function(){
						contact_field.change_display();
					});
					# ENDIF #
					
					# IF C_MORE_THAN_ONE_FIELD #
					$('move_up_{fields_list.ID}').observe('click',function(){
						contact_field.move_up();
					});
					
					$('move_down_{fields_list.ID}').observe('click',function(){
						contact_field.move_down();
					});
					# ENDIF #
				});
				-->
				</script>
			# END fields_list #
		</ul>
	</fieldset>
	# IF C_MORE_THAN_ONE_FIELD #
	<fieldset class="fieldset-submit">
		<button type="submit" name="submit" value="true" class="submit">{@fields.update_fields_position}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="position" id="position" value="">
	</fieldset>
	# ENDIF #
</form>

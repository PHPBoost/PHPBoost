<script>
<!--
var ContactFields = function(id){
	this.id = id;
};

ContactFields.prototype = {
	init_sortable : function() {
		jQuery("ul#fields_list").sortable({
			handle: '.sortable-selector',
			placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>'
		});
	},
	serialize_sortable : function() {
		jQuery('#tree').val(JSON.stringify(this.get_sortable_sequence()));
	},
	get_sortable_sequence : function() {
		var sequence = jQuery("ul#fields_list").sortable("serialize").get();
		return sequence[0];
	},
	change_reposition_pictures : function() {
		sequence = this.get_sortable_sequence();
		var length = sequence.length;
		for(var i = 0; i < length; i++)
		{
			if (jQuery('#list-' + sequence[i].id).is(':first-child'))
				jQuery("#move-up-" + sequence[i].id).hide();
			else
				jQuery("#move-up-" + sequence[i].id).show();
			
			if (jQuery('#list-' + sequence[i].id).is(':last-child'))
				jQuery("#move-down-" + sequence[i].id).hide();
			else
				jQuery("#move-down-" + sequence[i].id).show();
		}
	}
};

var ContactField = function(id, contact_fields){
	this.id = id;
	this.ContactFields = contact_fields;
	
	# IF C_MORE_THAN_ONE_FIELD #
	this.ContactFields.change_reposition_pictures();
	# ENDIF #
};

ContactField.prototype = {
	delete : function() {
		if (confirm(${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))}))
		{
			jQuery.ajax({
				url: '${relative_url(ContactUrlBuilder::delete_field())}',
				type: "post",
				dataType: "json",
				data: {'id' : this.id, 'token' : '{TOKEN}'},
				success: function(returnData){
					if (returnData.code > 0) {
						jQuery("#list-" + returnData.code).remove();
						ContactFields.init_sortable();
					}
				}
			});
		}
	},
	change_display : function() {
		jQuery("#change-display-" + this.id).html('<i class="fa fa-spin fa-spinner"></i>');
		jQuery.ajax({
			url: '${relative_url(ContactUrlBuilder::change_display())}',
			type: "post",
			dataType: "json",
			data: {'id' : this.id, 'token' : '{TOKEN}'},
			success: function(returnData){
				if (returnData.id > 0) {
					if (returnData.display) {
						jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye" title="{@field.display}"></i>');
					} else {
						jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye-slash" title="{@field.not_display}"></i>');
					}
				}
			}
		});
	}
};

var ContactFields = new ContactFields('fields_list');
jQuery(document).ready(function() {
	ContactFields.init_sortable();
	jQuery('li.sortable-element').on('mouseout',function(){
		ContactFields.change_reposition_pictures();
	});
});
-->
</script>
# INCLUDE MSG #
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="ContactFields.serialize_sortable();">
	<fieldset id="contact_fields_management">
	<legend>${LangLoader::get_message('admin.fields.manage', 'common', 'contact')}</legend>
		<ul id="fields_list" class="sortable-block">
			# START fields_list #
				<li class="sortable-element" id="list-{fields_list.ID}" data-id="{fields_list.ID}">
					<div class="sortable-selector" title="${LangLoader::get_message('position.move', 'common')}"></div>
					<div class="sortable-title">
						{fields_list.NAME}
						<div class="sortable-actions">
							{@field.required} : # IF fields_list.C_REQUIRED #${LangLoader::get_message('yes', 'common')}# ELSE #${LangLoader::get_message('no', 'common')}# ENDIF #
							# IF C_MORE_THAN_ONE_FIELD #
							<a href="" title="${LangLoader::get_message('position.move_up', 'common')}" id="move-up-{fields_list.ID}" onclick="return false;"><i class="fa fa-arrow-up"></i></a>
							<a href="" title="${LangLoader::get_message('position.move_down', 'common')}" id="move-down-{fields_list.ID}" onclick="return false;"><i class="fa fa-arrow-down"></i></a>
							# ENDIF #
							<a href="{fields_list.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
							# IF fields_list.C_DELETE #<a href="" onclick="return false;" title="${LangLoader::get_message('delete', 'common')}" id="delete-{fields_list.ID}"><i class="fa fa-delete"></i></a># ELSE #&nbsp;# ENDIF #
							# IF NOT fields_list.C_READONLY #<a href="" onclick="return false;" id="change-display-{fields_list.ID}"><i # IF fields_list.C_DISPLAY #class="fa fa-eye" title="{@field.display}"# ELSE #class="fa fa-eye-slash" title="{@field.not_display}"# ENDIF #></i></a># ELSE #&nbsp;# ENDIF #
						</div>
					</div>
					<div class="spacer"></div>
					<script>
					<!--
					jQuery(document).ready(function() {
						var contact_field = new ContactField({fields_list.ID}, ContactFields);
						
						# IF fields_list.C_DELETE #
						jQuery("#delete-{fields_list.ID}").on('click',function(){
							contact_field.delete();
						});
						# ENDIF #
						
						# IF NOT fields_list.C_READONLY #
						jQuery("#change-display-{fields_list.ID}").on('click',function(){
							contact_field.change_display();
						});
						# ENDIF #
						
						# IF C_MORE_THAN_ONE_FIELD #
						jQuery("#move-up-{fields_list.ID}").on('click',function(){
							var li = jQuery(this).closest('li');
							li.insertBefore( li.prev() );
							ContactFields.change_reposition_pictures();
						});
						
						jQuery("#move-down-{fields_list.ID}").on('click',function(){
							var li = jQuery(this).closest('li');
							li.insertAfter( li.next() );
							ContactFields.change_reposition_pictures();
						});
						# ENDIF #
					});
					-->
					</script>
				</li>
			# END fields_list #
		</ul>
	</fieldset>
	# IF C_MORE_THAN_ONE_FIELD #
	<fieldset class="fieldset-submit">
		<button type="submit" name="submit" value="true" class="submit">${LangLoader::get_message('position.update', 'common')}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="tree" id="tree" value="">
	</fieldset>
	# ENDIF #
</form>

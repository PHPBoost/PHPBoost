<script>
<!--
var ContactFields = function(id){
	this.id = id;
};

ContactFields.prototype = {
	init_sortable : function() {
		jQuery("ul#fields_list").sortable({handle: '.fa-arrows'});
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
			if (jQuery('#list_' + sequence[i].id).is(':first-child'))
				jQuery("#move-up-" + sequence[i].id).hide();
			else
				jQuery("#move-up-" + sequence[i].id).show();
			
			if (jQuery('#list_' + sequence[i].id).is(':last-child'))
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
				data: {'id' : this.id, 'token' : '{TOKEN}'},
				success: function(returnData){
					if (returnData.code > 0) {
						var elementToDelete = jQuery("#list_" + returnData.code);
						elementToDelete.remove();
						ContactFields.init_sortable();
					}
				},
				error: function(e){
					alert(e);
				}
			});
		}
	},
	change_display : function() {
		jQuery("#change-display-" + this.id).removeClass("fa-eye").removeClass("fa-eye-slash");
		jQuery("#change-display-" + this.id).addClass("fa-spin").addClass("fa-spinner");
		jQuery.ajax({
			url: '${relative_url(ContactUrlBuilder::change_display())}',
			type: "post",
			data: {'id' : this.id, 'token' : '{TOKEN}'},
			success: function(returnData){
				if (returnData.id > 0) {
					jQuery("#change-display-" + returnData.id).removeClass("fa-spinner").removeClass("fa-spin");
					if (returnData.display) {
						jQuery("#change-display-" + returnData.id).addClass("fa-eye");
						jQuery("#change-display-" + returnData.id).prop('title', "{@field.display}");
					} else {
						jQuery("#change-display-" + returnData.id).addClass("fa-eye-slash");
						jQuery("#change-display-" + returnData.id).prop('title', "{@field.not_display}");
					}
				}
			},
			error: function(e){
				alert(e);
			}
		});
	}
};

var ContactFields = new ContactFields('fields_list');
jQuery(document).ready(function() {
	ContactFields.init_sortable();
});
-->
</script>
# INCLUDE MSG #
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="ContactFields.serialize_sortable();">
	<fieldset id="contact_fields_management">
	<legend>${LangLoader::get_message('admin.fields.manage', 'common', 'contact')}</legend>
		<ul id="fields_list" class="sortable-block">
			# START fields_list #
				<li class="sortable-element" id="list_{fields_list.ID}" data-id="{fields_list.ID}">
					<div class="sortable-title">
						<a title="${LangLoader::get_message('move', 'admin')}" class="fa fa-arrows"></a>
						<i class="fa fa-globe"></i>
						<span class="text-strong">{fields_list.NAME}</span>
						<div class="sortable-actions">
							{@field.required} : <span class="text-strong"># IF fields_list.C_REQUIRED #${LangLoader::get_message('yes', 'common')}# ELSE #${LangLoader::get_message('no', 'common')}# ENDIF #</span>
							# IF C_MORE_THAN_ONE_FIELD #
							<div class="sortable-options">
								<a href="" title="${LangLoader::get_message('position.move_up', 'common')}" id="move-up-{fields_list.ID}" onclick="return false;" class="fa fa-arrow-up"></a>
							</div>
							<div class="sortable-options">
								<a href="" title="${LangLoader::get_message('position.move_down', 'common')}" id="move-down-{fields_list.ID}" onclick="return false;" class="fa fa-arrow-down"></a>
							</div>
							# ENDIF #
							<div class="sortable-options">
								<a href="{fields_list.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
							</div>
							<div class="sortable-options">
								# IF fields_list.C_DELETE #<a href="" onclick="return false;" title="${LangLoader::get_message('delete', 'common')}" id="delete_{fields_list.ID}" class="fa fa-delete"></a># ELSE #&nbsp;# ENDIF #
							</div>
							<div class="sortable-options">
							# IF NOT fields_list.C_READONLY #<a href="" onclick="return false;" id="change-display-{fields_list.ID}" # IF fields_list.C_DISPLAY #class="fa fa-eye" title="{@field.display}"# ELSE #class="fa fa-eye-slash" title="{@field.not_display}"# ENDIF #></a># ELSE #&nbsp;# ENDIF #
							</div>
						</div>
					</div>
					<div class="spacer"></div>
					<script>
					<!--
					jQuery(document).ready(function() {
						var contact_field = new ContactField({fields_list.ID}, ContactFields);
						
						jQuery("#list_{fields_list.ID}").on('mouseout',function(){
							ContactFields.change_reposition_pictures();
						});
						
						# IF fields_list.C_DELETE #
						jQuery("#delete_{fields_list.ID}").on('click',function(){
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

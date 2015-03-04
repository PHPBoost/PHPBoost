<script>
<!--
var ExtendedFields = function(id){
	this.id = id;
};

ExtendedFields.prototype = {
	init_sortable : function() {
		jQuery("ul#lists").sortable({handle: '.fa-arrows'});
	},
	serialize_sortable : function() {
		jQuery('#tree').val(JSON.stringify(this.get_sortable_sequence()));
	},
	get_sortable_sequence : function() {
		var sequence = jQuery("ul#lists").sortable("serialize").get();
		return sequence[0];
	},
	change_reposition_pictures : function() {
		sequence = this.get_sortable_sequence();
		
		jQuery("#move_up_" + sequence[0].id).hide();
		jQuery("#move_down_" + sequence[0].id).show();
		
		for (var j = 1 ; j < sequence.length - 1 ; j++) {
			jQuery("#move_up_" + sequence[j].id).show();
			jQuery("#move_down_" + sequence[j].id).show();
		}
		
		jQuery("#move_up_" + sequence[sequence.length - 1].id).show();
		jQuery("#move_down_" + sequence[sequence.length - 1].id).hide();
	}
};

var ExtendedField = function(id, display, extended_fields){
	this.id = id;
	this.more_is_opened = false;
	this.ExtendedFields = extended_fields;
	
	# IF C_MORE_THAN_ONE_FIELD #
	this.ExtendedFields.change_reposition_pictures();
	# ENDIF #
};

ExtendedField.prototype = {
	delete : function() {
		if (confirm(${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))}))
		{
			jQuery.ajax({
				url: '${relative_url(AdminExtendedFieldsUrlBuilder::delete())}',
				type: "post",
				data: {'id' : this.id, 'token' : '{TOKEN}'},
				success: function(returnData){
					if (returnData.code > 0)
					{
						var elementToDelete = jQuery("#list_" + returnData.code);
						elementToDelete.remove();
						ExtendedFields.init_sortable();
						jQuery('#no_field').hide();
					} else {
						jQuery('#no_field').show();
					}
				},
				error: function(e){
					alert(e);
				}
			});
		}
	},
	change_display : function() {
		if (jQuery("#change_display_" + this.id).hasClass("fa-eye")) {
			display = false;
		} else {
			display = true;
		}
		jQuery("#change_display_" + this.id).removeClass("fa-eye").removeClass("fa-eye-slash");
		jQuery("#change_display_" + this.id).addClass("fa-spin").addClass("fa-spinner");
		jQuery.ajax({
			url: '${relative_url(AdminExtendedFieldsUrlBuilder::change_display())}',
			type: "post",
			data: {'id' : this.id, 'token' : '{TOKEN}', 'display': display},
			success: function(returnData){
				if (returnData.id > 0) {
					jQuery("#change_display_" + returnData.id).removeClass("fa-spinner").removeClass("fa-spin");
					if (returnData.display) {
						jQuery("#change_display_" + returnData.id).addClass("fa-eye");
						jQuery("#change_display_" + returnData.id).prop('title', "{@field.display}");
					} else {
						jQuery("#change_display_" + returnData.id).addClass("fa-eye-slash");
						jQuery("#change_display_" + returnData.id).prop('title', "{@field.not_display}");
					}
				}
			},
			error: function(e){
				alert(e);
			}
		});
	}
};

var ExtendedFields = new ExtendedFields('lists');
jQuery(document).ready(function() {
	ExtendedFields.init_sortable();
});
-->
</script>
# INCLUDE MSG #
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="ExtendedFields.serialize_sortable();">
	<fieldset id="management_extended_fields">
	<legend>{@fields.management}</legend>
		<ul id="lists" class="sortable-block">
			# START list_extended_fields #
				<li class="sortable-element" id="list_{list_extended_fields.ID}" data-id="{list_extended_fields.ID}">
					<div class="sortable-title">
						<a title="${LangLoader::get_message('move', 'admin')}" class="fa fa-arrows"></a>
						<i class="fa fa-globe"></i>
						<span class="text-strong">{list_extended_fields.NAME}</span>
						<div class="sortable-actions">
							{@field.required} : <span class="text-strong"># IF list_extended_fields.C_REQUIRED #${LangLoader::get_message('yes', 'common')}# ELSE #${LangLoader::get_message('no', 'common')}# ENDIF #</span>
							# IF C_MORE_THAN_ONE_FIELD #
							<div class="sortable-options">
								<a href="" title="${LangLoader::get_message('position.move_up', 'common')}" id="move_up_{list_extended_fields.ID}" onclick="return false;" class="fa fa-arrow-up"></a>
							</div>
							<div class="sortable-options">
								<a href="" title="${LangLoader::get_message('position.move_down', 'common')}" id="move_down_{list_extended_fields.ID}" onclick="return false;" class="fa fa-arrow-down"></a>
							</div>
							# ENDIF #
							<div class="sortable-options">
								<a href="{list_extended_fields.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
							</div>
							<div class="sortable-options">
								# IF NOT list_extended_fields.C_FREEZE #
								<a href="" onclick="return false;" title="${LangLoader::get_message('delete', 'common')}" id="delete_{list_extended_fields.ID}" class="fa fa-delete"></a>
								# ELSE #
								&nbsp;
								# ENDIF #
							</div>
							<a href="" onclick="return false;" id="change_display_{list_extended_fields.ID}" # IF list_extended_fields.C_DISPLAY #class="fa fa-eye" title="{@field.display}"# ELSE #class="fa fa-eye-slash" title="{@field.not_display}"# ENDIF #></a>
						</div>
					</div>
					<div class="spacer"></div>
					<script>
					<!--
					jQuery(document).ready(function() {
						var extended_field = new ExtendedField({list_extended_fields.ID}, '{list_extended_fields.C_DISPLAY}', ExtendedFields);
						
						jQuery('#list_{list_extended_fields.ID}').on('mouseout',function(){
							ExtendedFields.change_reposition_pictures();
						});
						
						# IF NOT list_extended_fields.C_FREEZE #
						jQuery('#delete_{list_extended_fields.ID}').on('click',function(){
							extended_field.delete();
						});
						# ENDIF #
						
						jQuery('#change_display_{list_extended_fields.ID}').on('click',function(){
							extended_field.change_display();
						});
						
						# IF C_MORE_THAN_ONE_FIELD #
						jQuery('#move_up_{list_extended_fields.ID}').on('click',function(){
							var li = jQuery(this).closest('li');
							li.insertBefore( li.prev() );
							ExtendedFields.change_reposition_pictures();
						});
						
						jQuery('#move_down_{list_extended_fields.ID}').on('click',function(){
							var li = jQuery(this).closest('li');
							li.insertAfter( li.next() );
							ExtendedFields.change_reposition_pictures();
						});
						# ENDIF #
					});
					-->
					</script>
				</li>
			# END list_extended_fields #
		</ul>
		<div id="no_field" class="center"# IF C_FIELDS # style="display:none;"# ENDIF #>${LangLoader::get_message('no_item_now', 'common')}</div>
	</fieldset>
	# IF C_MORE_THAN_ONE_FIELD #
	<fieldset class="fieldset-submit">
		<button type="submit" class="submit" name="submit" value="true">${LangLoader::get_message('position.update', 'common')}</button>
		<input type="hidden" name="token" value="{TOKEN}">
		<input type="hidden" name="tree" id="tree" value="">
	</fieldset>
	# ENDIF #
</form>
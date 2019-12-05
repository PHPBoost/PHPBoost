<script>
<!--
var ExtendedFields = function(id){
	this.id = id;
};

ExtendedFields.prototype = {
	init_sortable : function() {
		jQuery("ul#lists").sortable({
			handle: '.sortable-selector',
			placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>',
			onDrop: function ($item, container, _super, event) {
				ExtendedFields.change_reposition_pictures();
				$item.removeClass(container.group.options.draggedClass).removeAttr("style");
				$("body").removeClass(container.group.options.bodyClass);
			}
		});
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
				dataType: "json",
				async: false,
				data: {'id' : this.id, 'token' : '{TOKEN}'},
				success: function(returnData){
					if (returnData.code > 0)
					{
						jQuery("#list-" + returnData.code).remove();
						ExtendedFields.init_sortable();
						jQuery('#no_field').hide();
					} else {
						jQuery('#no_field').show();
					}
				}
			});
		}
	},
	change_display : function() {
		if (jQuery("#change-display-" + this.id).children().hasClass("fa-eye")) {
			display = false;
		} else {
			display = true;
		}
		jQuery("#change-display-" + this.id).html('<i class="fa fa-spin fa-spinner"></i>');
		jQuery.ajax({
			url: '${relative_url(AdminExtendedFieldsUrlBuilder::change_display())}',
			type: "post",
			dataType: "json",
			data: {'id' : this.id, 'token' : '{TOKEN}', 'display': display},
			success: function(returnData){
				if (returnData.id > 0) {
					if (returnData.display) {
						jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye" aria-label="{@field.display}"></i>');
					} else {
						jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye-slash" aria-label="{@field.not_display}"></i>');
					}
				}
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
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="ExtendedFields.serialize_sortable();" class="fieldset-content">
	<fieldset id="management_extended_fields">
		<legend>{@fields.management}</legend>
		<div class="fieldset-inset">
			<ul id="lists" class="sortable-block">
				# START list_extended_fields #
					<li class="sortable-element" id="list-{list_extended_fields.ID}" data-id="{list_extended_fields.ID}">
						<div class="sortable-selector" aria-label="${LangLoader::get_message('position.move', 'common')}"></div>
						<div class="sortable-title">
							{list_extended_fields.NAME}
						</div>
						<div class="sortable-actions">
							{@field.required} : # IF list_extended_fields.C_REQUIRED #${LangLoader::get_message('yes', 'common')}# ELSE #${LangLoader::get_message('no', 'common')}# ENDIF #
							# IF C_MORE_THAN_ONE_FIELD #
							<a href="" aria-label="${LangLoader::get_message('position.move_up', 'common')}" id="move-up-{list_extended_fields.ID}" onclick="return false;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
							<a href="" aria-label="${LangLoader::get_message('position.move_down', 'common')}" id="move-down-{list_extended_fields.ID}" onclick="return false;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
							# ENDIF #
							<a href="{list_extended_fields.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
							# IF NOT list_extended_fields.C_FREEZE #
							<a href="" onclick="return false;" aria-label="${LangLoader::get_message('delete', 'common')}" id="delete-{list_extended_fields.ID}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
							# ELSE #
							&nbsp;
							# ENDIF #
							<a href="" onclick="return false;" id="change-display-{list_extended_fields.ID}" aria-label="# IF list_extended_fields.C_DISPLAY #{@field.display}# ELSE #{@field.not_display}# ENDIF #"><i aria-hidden="true" # IF list_extended_fields.C_DISPLAY #class="fa fa-eye"# ELSE #class="fa fa-eye-slash"# ENDIF #></i></a>
						</div>
						<div class="spacer"></div>
						<script>
						<!--
						jQuery(document).ready(function() {
							var extended_field = new ExtendedField({list_extended_fields.ID}, '{list_extended_fields.C_DISPLAY}', ExtendedFields);

							# IF NOT list_extended_fields.C_FREEZE #
							jQuery('#delete-{list_extended_fields.ID}').on('click',function(){
								extended_field.delete();
							});
							# ENDIF #

							jQuery('#change-display-{list_extended_fields.ID}').on('click',function(){
								extended_field.change_display();
							});

							# IF C_MORE_THAN_ONE_FIELD #
							jQuery('#move-up-{list_extended_fields.ID}').on('click',function(){
								var li = jQuery(this).closest('li');
								li.insertBefore( li.prev() );
								ExtendedFields.change_reposition_pictures();
							});

							jQuery('#move-down-{list_extended_fields.ID}').on('click',function(){
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
			<div id="no_field" class="align-center"# IF C_FIELDS # style="display: none;"# ENDIF #>${LangLoader::get_message('no_item_now', 'common')}</div>
		</div>
	</fieldset>
	# IF C_MORE_THAN_ONE_FIELD #
	<fieldset class="fieldset-submit">
		<div class="fieldset-inset">
			<button type="submit" class="button submit" name="submit" value="true">${LangLoader::get_message('position.update', 'common')}</button>
			<input type="hidden" name="token" value="{TOKEN}">
			<input type="hidden" name="tree" id="tree" value="">
		</div>
	</fieldset>
	# ENDIF #
</form>

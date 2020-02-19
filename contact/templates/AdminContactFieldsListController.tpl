<script>
<!--
var ContactFields = function(id){
	this.id = id;
};

ContactFields.prototype = {
	init_sortable : function() {
		jQuery("ul#fields_list").sortable({
			handle: '.sortable-selector',
			placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>',
			onDrop: function ($item, container, _super, event) {
				ContactFields.change_reposition_pictures();
				$item.removeClass(container.group.options.draggedClass).removeAttr("style");
				$("body").removeClass(container.group.options.bodyClass);
			}
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
		jQuery("#change-display-" + this.id).html('<i class="fa fa-spin fa-spinner" aria-hidden="true" aria-label="{@field.refresh}"></i><span class="sr-only">{@field.refresh}</span>');
		jQuery.ajax({
			url: '${relative_url(ContactUrlBuilder::change_display())}',
			type: "post",
			dataType: "json",
			data: {'id' : this.id, 'token' : '{TOKEN}'},
			success: function(returnData){
				if (returnData.id > 0) {
					if (returnData.display) {
						jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye" aria-hidden="true" aria-label="{@field.display}"></i><span class="sr-only">{@field.display}</span>');
					} else {
						jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye-slash" aria-hidden="true" aria-label="{@field.not_display}"></i><span class="sr-only">{@field.not_display}</span>');
					}
				}
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
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="ContactFields.serialize_sortable();" class="fieldset-content">
	<fieldset id="contact_fields_management">
		<legend><h1>${LangLoader::get_message('admin.fields.manage', 'common', 'contact')}</h1></legend>
		<ul id="fields_list" class="sortable-block">
			# START fields_list #
				<li class="sortable-element" id="list-{fields_list.ID}" data-id="{fields_list.ID}">
					<div class="sortable-selector" aria-label="${LangLoader::get_message('position.move', 'common')}"><span class="sr-only">${LangLoader::get_message('position.move', 'common')}</span></div>
					<div class="sortable-title">
						{fields_list.NAME}
					</div>
					<div class="sortable-actions">
						{@field.required} : # IF fields_list.C_REQUIRED #${LangLoader::get_message('yes', 'common')}# ELSE #${LangLoader::get_message('no', 'common')}# ENDIF #
						# IF C_MORE_THAN_ONE_FIELD #
							<a href="#" aria-label="${LangLoader::get_message('position.move_up', 'common')}" id="move-up-{fields_list.ID}" onclick="return false;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
							<a href="#" aria-label="${LangLoader::get_message('position.move_down', 'common')}" id="move-down-{fields_list.ID}" onclick="return false;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
						# ENDIF #
						<a href="{fields_list.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
						# IF fields_list.C_DELETE #<a href="#" onclick="return false;" aria-label="${LangLoader::get_message('delete', 'common')}" id="delete-{fields_list.ID}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ELSE #&nbsp;# ENDIF #
						# IF NOT fields_list.C_READONLY #<a href="#" onclick="return false;" id="change-display-{fields_list.ID}"# IF fields_list.C_DISPLAY # aria-label="{@field.display}"# ELSE #aria-label="{@field.not_display}"# ENDIF #><i aria-hidden="true" class="# IF fields_list.C_DISPLAY #fa fa-eye# ELSE #fa fa-eye-slash# ENDIF #"></i></a># ELSE #&nbsp;# ENDIF #
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
		<div class="fieldset-inset">
			<button type="submit" name="submit" value="true" class="button submit">${LangLoader::get_message('position.update', 'common')}</button>
			<input type="hidden" name="token" value="{TOKEN}">
			<input type="hidden" name="tree" id="tree" value="">
		</div>
	</fieldset>
	# ENDIF #
</form>

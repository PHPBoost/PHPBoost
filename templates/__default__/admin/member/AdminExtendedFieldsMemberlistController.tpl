<script>
	var ExtendedFields = function(id){
		this.id = id;
	};

	ExtendedFields.prototype = {
		init_sortable : function() {
			jQuery("ul#lists").sortable({
				handle: '.sortable-selector',
				placeholder: '<div class="dropzone">' + ${escapejs(@common.drop.here)} + '</div>',
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

		# IF C_SEVERAL_FIELDS #
			this.ExtendedFields.change_reposition_pictures();
		# ENDIF #
	};

	ExtendedField.prototype = {
		delete : function() {
			if (confirm(${escapejs(@warning.confirm.delete)}))
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
							jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye" aria-label="{@common.displayed}"></i>');
						} else {
							jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye-slash" aria-label="{@common.hidden}"></i>');
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
</script>
# INCLUDE MESSAGE_HELPER #
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="ExtendedFields.serialize_sortable();" class="fieldset-content">
	<fieldset id="management_extended_fields">
		<legend>{@user.extended.fields.management}</legend>
		<div class="fieldset-inset">
			<ul id="lists" class="sortable-block">
				# START list_extended_fields #
					<li class="sortable-element" id="list-{list_extended_fields.ID}" data-id="{list_extended_fields.ID}">
						<div class="sortable-selector" aria-label="{@common.move}"></div>
						<div class="sortable-title">
							{list_extended_fields.NAME}
						</div>
						<div class="sortable-actions">
							{@form.required.field} : # IF list_extended_fields.C_REQUIRED #{@common.yes}# ELSE #{@common.no}# ENDIF #
							# IF C_SEVERAL_FIELDS #
								<a href="#" aria-label="{@common.move.up}" id="move-up-{list_extended_fields.ID}" onclick="return false;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
								<a href="#" aria-label="{@common.move.down}" id="move-down-{list_extended_fields.ID}" onclick="return false;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
							# ENDIF #
							<a href="{list_extended_fields.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
							# IF NOT list_extended_fields.C_FREEZE #
								<a href="#" onclick="return false;" aria-label="{@common.delete}" id="delete-{list_extended_fields.ID}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
							# ELSE #
								&nbsp;
							# ENDIF #
							<a href="#" onclick="return false;" id="change-display-{list_extended_fields.ID}" aria-label="# IF list_extended_fields.C_DISPLAY #{@common.displayed}# ELSE #{@common.hidden}# ENDIF #"><i aria-hidden="true" # IF list_extended_fields.C_DISPLAY #class="fa fa-eye"# ELSE #class="fa fa-eye-slash"# ENDIF #></i></a>
						</div>
						<div class="spacer"></div>
						<script>
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

								# IF C_SEVERAL_FIELDS #
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
						</script>
					</li>
				# END list_extended_fields #
			</ul>
			<div id="no_field" class="align-center"# IF C_FIELDS # style="display: none;"# ENDIF #>{@common.no.item.now}</div>
		</div>
	</fieldset>
	# IF C_SEVERAL_FIELDS #
		<fieldset class="fieldset-submit">
			<legend>{@form.submit}</legend>
			<div class="fieldset-inset">
				<button type="submit" class="button submit" name="submit" value="true">{@form.submit}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="tree" id="tree" value="">
			</div>
		</fieldset>
	# ENDIF #
</form>

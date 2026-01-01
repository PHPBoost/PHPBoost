<li id="cat-{ID}" class="sortable-element# IF C_COLOR # category-color# ENDIF #" data-id="{ID}"# IF C_COLOR # style="border-left-color: {COLOR};"# ENDIF #>
	<div class="sortable-selector" aria-label="{@common.move}"></div>
	<div class="sortable-title">
		<a class="offload" href="{U_DISPLAY}">{NAME}</a>
		# IF C_DESCRIPTION #<em class="h-padding small">{DESCRIPTION}</em># ENDIF #
	</div>
	<div class="sortable-actions">
		<a href="#" aria-label="{@common.move.up}" id="move-up-{ID}" onclick="return false;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
		<a href="#" aria-label="{@common.move.down}" id="move-down-{ID}" onclick="return false;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
		# IF C_SPECIAL_AUTHORIZATIONS #
			<a class="offload" href="{U_EDIT}#AbstractCategoriesFormController_special_authorizations_field" aria-label="{@form.authorizations.specials}">
				<i class="fa fa-fw fa-user-shield warning" aria-hidden="true"></i>
			</a>
		# ELSE #
			<a class="offload" href="{U_EDIT}#AbstractCategoriesFormController_special_authorizations_field" aria-label="{@form.authorizations.default}">
				<i class="fa fa-fw fa-user-shield" aria-hidden="true"></i>
			</a>
		# ENDIF #
		<a class="offload" href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a>
		<a href="{U_DELETE}" aria-label="{@common.delete}" data-confirmation="{DELETE_CONFIRMATION_MESSAGE}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
	</div>
	<script>
		jQuery(document).ready(function() {
			jQuery("#move-up-{ID}").on('click',function(){
				var li = jQuery(this).closest('li');
				li.insertBefore( li.prev() );
				change_reposition_pictures();
			});

			jQuery("#move-down-{ID}").on('click',function(){
				var li = jQuery(this).closest('li');
				li.insertAfter( li.next() );
				change_reposition_pictures();
			});
		});
	</script>

	# IF C_ALLOWED_TO_HAVE_CHILDS #
		<ul id="subcat-{ID}" class="sortable-block">
			# START children #
				{children.child}
			# END children #
		</ul>
	# ENDIF #
</li>

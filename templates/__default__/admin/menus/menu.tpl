<div
	class="menus-block-container# IF C_IS_MODULE # bgc link-color# ENDIF ## IF C_HORIZONTAL # menus-block-horizontal# ENDIF #"
	data-id="{MENU_ID}"
	id="menu_{MENU_ID}">
	# IF C_UP #<div class="menus-block-move menus-block-move-top"><a href="{U_UP}" aria-label="{@common.move.up}"><i class="fa fa-chevron-up fa-lg"></i></a></div># ENDIF #
	<div class="menus-block-top">
		<span id="m{MENU_ID}"></span>
		<h6 class="menus-block-title">{MENU_TITLE}</h6>
		<div class="spacer"></div>
		<a href="#" class="menus-block-move-cursor" onclick="return false;" aria-label="{@common.move}"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a>
		# IF C_EDIT #
			<a href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
		# ENDIF #
		# IF C_DELETE #
			<a href="{U_DELETE}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
		# ENDIF #
		<a href="menus.php?action={DISPLAY_STATUS}&amp;id={MENU_ID}&amp;token={TOKEN}#m{MENU_ID}" aria-label="# IF C_DISPLAY #{@common.enabled}# ELSE #{@common.disabled}# ENDIF #"><i class="fa # IF C_DISPLAY #fa-eye# ELSE #fa-eye-slash# ENDIF #"></i></a>
	</div>
	{CONTENT}
	# IF C_DOWN #<div class="menus-block-move menus-block-move-bottom"><a href="{U_DOWN}" aria-label="{@common.move.down}"><i class="fa fa-chevron-down fa-lg"aria-hidden="true"></i></a></div># ENDIF #
</div>

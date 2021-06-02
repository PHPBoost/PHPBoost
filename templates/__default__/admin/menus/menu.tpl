<div class="menus-block-container# IF C_HORIZONTAL # menus-block-horizontal# ENDIF #" data-id="{IDMENU}" id="menu_{IDMENU}">
	# IF C_UP #<div class="menus-block-move menus-block-move-top"><a href="{U_UP}" aria-label="{@common.move.up}"><i class="fa fa-chevron-up fa-lg"></i></a></div># ENDIF #
	<div class="menus-block-top">
		<span id="m{IDMENU}"></span>
		<h6 class="menus-block-title">{NAME}</h6>
		<div class="spacer"></div>
		<a href="#" class="menus-block-move-cursor" onclick="return false;" aria-label="{@common.move}"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a>
		# IF C_EDIT #
			<a href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
		# ENDIF #
		# IF C_DELETE #
			<a href="{U_DELETE}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
		# ENDIF #
		<a href="menus.php?action={ACTIV}&amp;id={IDMENU}&amp;token={TOKEN}#m{IDMENU}" aria-label="# IF C_MENU_ACTIVATED #{@common.disable}# ELSE #{@common.enable}# ENDIF #"><i class="fa # IF C_MENU_ACTIVATED #fa-eye# ELSE #fa-eye-slash# ENDIF #"></i></a>
	</div>
	{CONTENTS}
	# IF C_DOWN #<div class="menus-block-move menus-block-move-bottom"><a href="{U_DOWN}" aria-label="{@common.move.down}"><i class="fa fa-chevron-down fa-lg"aria-hidden="true"></i></a></div># ENDIF #
</div>

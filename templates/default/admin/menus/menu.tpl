<div class="menus-block-container" data-id="{IDMENU}" id="menu_{IDMENU}"# IF C_HORIZONTAL # style="width:auto;"# ENDIF #>
	# IF C_UP #<div class="menus-block-move menus-block-move-top"><a href="{U_UP}" aria-label="{L_MOVE_UP}"><i class="fa fa-chevron-up"></i></a></div># ENDIF #
	<div class="menus-block-top">

		<span id="m{IDMENU}"></span>
		<h6 class="menus-block-title">{NAME}</h6>

		<a href="#" class="menus-block-move-cursor" onclick="return false;" aria-label="${LangLoader::get_message('move', 'admin')}"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a>
		# IF C_EDIT #
			<a href="{U_EDIT}" aria-label="{L_EDIT}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
		# ENDIF #
		# IF C_DEL #
			<a href="{U_DELETE}" data-confirmation="delete-element" aria-label="{L_DEL}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
		# ENDIF #

		<a href="menus.php?action={ACTIV}&amp;id={IDMENU}&amp;token={TOKEN}#m{IDMENU}" aria-label="# IF C_MENU_ACTIVATED #{L_UNACTIVATE}# ELSE #{L_ACTIVATE}# ENDIF #"><i class="fa # IF C_MENU_ACTIVATED #fa-eye# ELSE #fa-eye-slash# ENDIF #"></i></a>
	</div>

	{CONTENTS}
	# IF C_DOWN #<div class="menus-block-move menus-block-move-bot"><a href="{U_DOWN}" aria-label="{L_MOVE_DOWN}"><i class="fa fa-chevron-down"></i></a></div># ENDIF #
</div>

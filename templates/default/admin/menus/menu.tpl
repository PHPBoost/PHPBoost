<div class="menus-block-container" data-id="{IDMENU}" id="menu_{IDMENU}"# IF C_HORIZONTAL # style="width:auto;"# ENDIF #>
	# IF C_UP #<div class="menus-block-move menus-block-move-top"><a href="{U_UP}" alt="{L_MOVE_UP}" title="{L_MOVE_UP}"></a></div># ENDIF #
	<div class="menus-block-top">

		<span id="m{IDMENU}"></span>
		<h5 class="menus-block-title">{NAME}</h5>
		
		<a href="" class="menus-block-move-cursor" title="${LangLoader::get_message('move', 'admin')}" onclick="return false;"><i class="fa fa-arrows"></i></a>
		# IF C_EDIT #
			<a href="{U_EDIT}" title="{L_EDIT}" class="fa fa-edit"></a>
		# ENDIF #
		# IF C_DEL #
			<a href="{U_DELETE}" title="{L_DEL}" class="fa fa-delete" data-confirmation="delete-element"></a>
		# ENDIF #
		
		<a href="menus.php?action={ACTIV}&amp;id={IDMENU}&amp;token={TOKEN}#m{IDMENU}" title="# IF C_MENU_ACTIVATED #{L_UNACTIVATE}"# ELSE #{L_ACTIVATE}# ENDIF #"><i class="fa # IF C_MENU_ACTIVATED #fa-eye# ELSE #fa-eye-slash# ENDIF #"></i></a>
	</div>
	
	{CONTENTS}
	# IF C_DOWN #<div class="menus-block-move menus-block-move-bot"><a href="{U_DOWN}" alt="{L_MOVE_DOWN}" title="{L_MOVE_DOWN}"></a></div># ENDIF #
</div>

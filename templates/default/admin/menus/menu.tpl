<div class="menus_block_container" id="menu_{IDMENU}"# IF C_HORIZONTAL # style="width:auto;"# ENDIF #>
	# IF C_UP #<div class="menus_block_move menus_block_move_top"><a href="{U_UP}" alt="{L_MOVE_UP}" title="{L_MOVE_UP}"></a></div># ENDIF #
	<div class="menus_block_top">
		
		
		<span id="m{IDMENU}"></span>
		<h5 class="menus_block_title">{NAME}</h5>
		
		<img src="{PATH_TO_ROOT}/templates/default/images/drag.png" alt="{L_EDIT}" class="valign_middle" />
		# IF C_EDIT #
			<a href="{U_EDIT}" title="{L_EDIT}">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/edit.png" alt="{L_EDIT}" class="valign_middle" />
			</a>
		# ENDIF #
		# IF C_DEL #
			<a href="{U_DELETE}" title="{L_DEL}" onclick="javascript:return Confirm_menu();">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/delete.png" alt="{L_DEL}" class="valign_middle" />
			</a>
		# ENDIF #
		
		<a href="menus.php?action={ACTIV}&amp;id={IDMENU}&amp;token={TOKEN}#m{IDMENU}" title="# IF C_MENU_ACTIVATED #{L_UNACTIVATE}# ELSE #{L_ACTIVATE}# ENDIF #">
		# IF C_MENU_ACTIVATED #
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/processed_mini.png" alt="{L_UNACTIVATE}" class="valign_middle" />
		# ELSE #
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/not_processed_mini.png" alt="{L_ACTIVATE}" class="valign_middle" />
		# ENDIF #
		</a>
	</div>
	
	{CONTENTS}
	# IF C_DOWN #<div class="menus_block_move menus_block_move_bot"><a href="{U_DOWN}" alt="{L_MOVE_DOWN}" title="{L_MOVE_DOWN}"></a></div># ENDIF #
</div>

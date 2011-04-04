<div class="msg_position">
	<div class="msg_top_l"></div>			
	<div class="msg_top_r"></div>
	<div class="msg_top">
		<div style="float:left;">{PAGINATION}&nbsp;</div>
		<div style="float:right;text-align: center;">
			# IF COM_LOCK #
			<a href="{U_LOCK}">{L_LOCK}</a> <a href="{U_LOCK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/{IMG}.png" alt="" class="valign_middle" /></a>
			# ENDIF #
		</div>
	</div>	
</div>
# START comments_list #
<div class="msg_position">
	<div class="msg_container">
		<div class="msg_top_row">
			<div class="msg_pseudo_mbr">
			</div>
		</div>
		<div class="msg_contents_container">
			<div class="msg_info_mbr">
			</div>
			<div class="msg_contents">
				<div class="msg_contents_overflow">
					{comments_list.MESSAGE}
				</div>
			</div>
		</div>
	</div>	
	<div class="msg_sign">				
		<div class="msg_sign_overflow">
		</div>				
		<hr />
		<div style="float:right;font-size:10px;">
		</div>
	</div>	
</div>
# END comments_list #
<div class="msg_position">		
	<div class="msg_bottom_l"></div>		
	<div class="msg_bottom_r"></div>
	<div class="msg_bottom" style="text-align:center;">{PAGINATION}&nbsp;</div>
</div>
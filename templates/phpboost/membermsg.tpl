		<span id="go_top"></span>
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><a href="">{L_MEMBER_MSG}</a></div>
			<div class="module_contents">
				<p style="text-align:center" class="text_strong">{L_MEMBER_MSG_DISPLAY}</p>			
				<p style="text-align:center">
					<a href="membermsg{U_COMMENTS}">{L_COMMENTS}</a>
					# START available_modules_msg #
						| {available_modules_msg.NAME}
					# END available_modules_msg #
				</p>			
				
				# IF C_START_MSG #
				<div class="module_position" style="width:100%;">					
					<div class="module_top_l"></div>		
					<div class="module_top_r"></div>
					<div class="module_top">&nbsp;</div>
				</div>
				# START msg_list #				
				<div class="msg_position" style="width:100%;">			
					<div class="msg_container">
						<div class="msg_top_row">
							<div class="msg_pseudo_mbr">&nbsp;&nbsp;{USER_PSEUDO}</div>
							<span class="text_strong" style="float:left;">&nbsp;&nbsp;<a href="{msg_list.U_TITLE}"><img src="../templates/{THEME}/images/ancre.png" alt="" /></a> <a href="{msg_list.U_TITLE}">{L_GO_MSG}</a></span>
							<span class="text_small" style="float: right;">{L_ON} : {msg_list.DATE}</span>&nbsp;
						</div>
						<div class="msg_contents_container">
							<div class="msg_info_mbr">
							</div>
							<div class="msg_contents">
								<div class="msg_contents_overflow">
									{msg_list.CONTENTS}
								</div>									
							</div>
						</div>
					</div>	
				</div>	
				# END msg_list #
				<div class="msg_position" style="width:100%;">
					<div class="msg_bottom_l"></div>		
					<div class="msg_bottom_r"></div>
					<div class="msg_bottom">{PAGINATION}</div>
				</div>
				# ENDIF #
				
				<br />				
			</div>		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom"><a href="">{L_MEMBER_MSG}</a></div>
		</div>

		<span id="go_top"></span>
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">&bull; <a href="member{U_BACK}">{L_BACK}</a> &raquo; <a href="membermsg{U_USER_MSG}">{L_USER_MSG}</a></div>
			<div class="module_contents">
				<p style="text-align:center;margin-bottom:25px;" class="text_strong">{L_USER_MSG_DISPLAY}</p>			
				<p style="text-align:center;margin-bottom:15px;"><a href="membermsg{U_COMMENTS}"><img src="../templates/{THEME}/images/admin/com_mini.png" alt="" class="valign_middle" /> {L_COMMENTS}</a></p>	
				# START available_modules_msg #
				<p style="text-align:center;margin-bottom:15px;"> 
					<a href="{available_modules_msg.U_LINK_USER_MSG}">
					# IF available_modules_msg.C_IMG_USER_MSG #
					<img src="{available_modules_msg.IMG_USER_MSG}" alt="" class="valign_middle" />
					# ENDIF #
					{available_modules_msg.NAME_USER_MSG}</a>
				</p>
				# END available_modules_msg #
				
				<br />
				
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
							<div class="msg_pseudo_mbr">{msg_list.USER_ONLINE} {msg_list.USER_PSEUDO}</div>
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
				
				<br /><br />			
			</div>		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom">&bull; <a href="member{U_BACK}">{L_BACK}</a> &raquo; <a href="membermsg{U_USER_MSG}">{L_USER_MSG}</a></div>
		</div>

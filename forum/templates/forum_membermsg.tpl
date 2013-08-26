		<span id="go_top"></span>	

		# INCLUDE forum_top #
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<span class="forum_cat_title">
					<a href="membermsg{U_FORUM_VIEW_MSG}">{L_VIEW_MSG_USER}</a>
				</span>
				<span style="float:right;">{PAGINATION}</span>
			</div>
		</div>	

		# START list #
		<div class="msg_position">
			<div class="msg_container">
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{list.USER_ONLINE} <a class="forum_link_pseudo {list.LEVEL_CLASS}" # IF list.C_GROUP_COLOR # style="color:{list.GROUP_COLOR}" # ENDIF # href="{list.U_USER_PROFILE}">{list.USER_PSEUDO}</a>
					</div>
					<span style="float:left;">
						&nbsp;&nbsp;<span id="m{list.ID}"></span><a href="{PATH_TO_ROOT}/forum/topic{list.U_VARS_ANCRE}#m{list.ID}" title=""><img src="{PATH_TO_ROOT}/templates/{THEME}/images/ancre.png" alt="" /></a> {list.DATE}
					</span>
					<span style="float:right;">
						{list.U_FORUM_CAT} &raquo; {list.U_TITLE_T}
						<a href="#go_top"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a> 
						<a href="#go_bottom"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
					</span>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
					</div>
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							{list.CONTENTS}
						</div>
					</div>
				</div>
			</div>		
		</div>	
		# END list #
		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">
				<span style="float:left;">
					<a href="membermsg{U_FORUM_VIEW_MSG}">{L_VIEW_MSG_USER}</a>
				</span>
				<span style="float:right;">
					{PAGINATION}
				</span>&nbsp;
			</div>
		</div>
		
		# INCLUDE forum_bottom #	
		
		<span id="go_bottom"></span>
		
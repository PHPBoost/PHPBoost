		<span id="go_top"></span>	

		# INCLUDE forum_top #
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<span style="float:left;">
					<a href="membermsg{U_FORUM_VIEW_MSG}">{L_VIEW_MSG_MEMBER}</a>
				</span>
				<span style="float:right;">{PAGINATION}</span>&nbsp;
			</div>
		</div>	

		# START list #
		<div class="msg_position">
			<div class="msg_container">
				<div class="msg_top_row">
					<div class="msg_pseudo_mbr">
						{list.USER_ONLINE} <a class="forum_link_pseudo" href="../member/member{list.U_MEMBER_ID}">{list.USER_PSEUDO}</a>
					</div>
					<span style="float:left;">
						<span id="m{list.ID}"></span><a href="../forum/topic{list.U_VARS_ANCRE}#m{list.ID}" title=""><img src="../templates/{THEME}/images/ancre.png" alt="" /></a> {list.DATE}
					</span>
					<span style="float:right;">
						{list.U_FORUM_CAT} &raquo; {list.U_TITLE_T}
						<a href="#go_top"><img src="../templates/{THEME}/images/top.png" alt="" /></a> 
						<a href="#go_bottom"><img src="../templates/{THEME}/images/bottom.png" alt="" /></a>
					</span>
				</div>
				<div class="msg_contents_container">
					<div class="msg_info_mbr">
						<p style="text-align:center;">{list.USER_RANK}</p>
						<p style="text-align:center;">{list.USER_IMG_ASSOC}</p>
						<p style="text-align:center;">{list.USER_AVATAR}</p>
						<p style="text-align:center;">{list.USER_GROUP}</p>
						{list.USER_SEX}
						{list.USER_DATE}<br />
						{list.USER_MSG}<br />
						{list.USER_LOCAL}		
					</div>
					<div class="msg_contents">
						<div class="msg_contents_overflow">
							{list.CONTENTS}
						</div>
					</div>
				</div>
			</div>	
			<div class="msg_sign">				
				{list.USER_SIGN}				
				<hr />
				<span style="float:left;">
					<a href="../member/pm{list.U_MEMBER_PM}"><img src="../templates/{THEME}/images/{LANG}/pm.png" alt="pm" /></a> {list.USER_MAIL} {list.USER_MSN} {list.USER_YAHOO} {list.USER_WEB}
				</span>
				<span style="float:right;font-size:10px;">
					{list.WARNING}
				</span>&nbsp;
			</div>	
		</div>	
		# END list #
		
		<div class="msg_position">		
			<div class="msg_bottom_l"></div>		
			<div class="msg_bottom_r"></div>
			<div class="msg_bottom" style="text-align:center;">
				<span style="float:left;">
					<a href="membermsg{U_FORUM_VIEW_MSG}">{L_VIEW_MSG_MEMBER}</a>
				</span>
				<span style="float:right;">
					{PAGINATION}
				</span>&nbsp;
			</div>
		</div>
		
		# INCLUDE forum_bottom #	
		
		<span id="go_bottom"></span>
		
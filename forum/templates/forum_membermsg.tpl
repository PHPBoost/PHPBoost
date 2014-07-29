		<span id="go_top"></span>

		# INCLUDE forum_top #
		
		<div class="module-position">
			<div class="module-top-l"></div>
			<div class="module-top-r"></div>
			<div class="module-top">
				<span class="forum-cat-title">
					<a href="membermsg{U_FORUM_VIEW_MSG}">{L_VIEW_MSG_USER}</a>
				</span>
				# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
			</div>
		</div>

		# START list #
		<div class="msg-position">
			<div class="msg-container">
				<div class="msg-top-row">
					<div class="msg-pseudo-mbr">
						{list.USER_ONLINE} # IF NOT list.C_GUEST #<a class="forum-link-pseudo {list.LEVEL_CLASS}" # IF list.C_GROUP_COLOR # style="color:{list.GROUP_COLOR}" # ENDIF # href="{list.U_USER_PROFILE}">{list.USER_PSEUDO}</a># ELSE # {list.USER_PSEUDO} # ENDIF #
					</div>
					<span style="float:left;">
						&nbsp;&nbsp;<span id="m{list.ID}"></span><a href="{PATH_TO_ROOT}/forum/topic{list.U_VARS_ANCRE}#m{list.ID}" title=""><i class="fa fa-hand-o-right"></i></a> {list.DATE}
					</span>
					<span style="float:right;">
						{list.U_FORUM_CAT} &raquo; {list.U_TITLE_T}
						<a href="#go_top"><i class="fa fa-arrow-up"></i></a> 
						<a href="#go_bottom"><i class="fa fa-arrow-down"></i></a>
					</span>
				</div>
				<div class="msg-contents-container">
					<div class="msg-info-mbr">
					</div>
					<div class="msg-contents">
						<div class="msg-contents-overflow">
							{list.CONTENTS}
						</div>
					</div>
				</div>
			</div>
		</div>
		# END list #

		<div class="module-position">
			<div class="module-bottom-l"></div>
			<div class="module-bottom-r"></div>
			<div class="module-bottom">
				<span class="forum-cat-title">
					<a href="membermsg{U_FORUM_VIEW_MSG}">{L_VIEW_MSG_USER}</a>
				</span>
				# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
			</div>
		</div>
		
		# INCLUDE forum_bottom #
		
		<span id="go_bottom"></span>
		
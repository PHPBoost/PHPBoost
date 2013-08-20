<div class="module_mini_container"# IF C_HORIZONTAL # style="width:auto;"# ENDIF #>
	<div class="module_mini_top">
		<h5 class="sub_title">{@module_title}</h5>
	</div>
	<div class="module_mini_contents">
		# IF C_ANY_MESSAGE_GUESTBOOK #
		<p class="text_small"># IF C_HORIZONTAL #{CONTENTS}# ELSE #{SHORT_CONTENTS}# IF C_MORE_CONTENTS # <a href="{U_MESSAGE}" class="small_link">{@guestbook.titles.more_contents}</a># ENDIF ## ENDIF #</p>
		<p class="text_small">{L_BY} # IF C_VISITOR #<span class="text_italic"># IF USER_PSEUDO #{USER_PSEUDO}# ELSE #{L_GUEST}# ENDIF #</span># ELSE #<a href="{U_PROFILE}" class="{USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{USER_PSEUDO}</a># ENDIF #</p>
		# ELSE #
		<p class="text_small">{@guestbook.titles.no_message}</p>
		# ENDIF #
		<a class="small_link" href="{U_GUESTBOOK}">{@module_title}</a>
	</div>
</div>
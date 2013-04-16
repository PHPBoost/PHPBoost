<div class="module_mini_container">
	<div class="module_mini_top">
		<h5 class="sub_title">{@guestbook.module_title}</h5>
	</div>
	<div class="module_mini_contents">
		# IF C_ANY_MESSAGE_GUESTBOOK #
		<p class="text_small">{L_BY} {RAND_MSG_LOGIN}</p>
		<p class="text_small">{RAND_MSG_CONTENTS}</p>
		# ELSE #
		<p class="text_small">{@guestbook.titles.no_message}</p>
		# ENDIF #
		<a class="small_link" href="{LINK_GUESTBOOK}">{@guestbook.module_title}</a>
	</div>
	<div class="module_mini_bottom">
	</div>
</div>
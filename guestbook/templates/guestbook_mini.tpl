		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_RANDOM_GESTBOOK}</h5>
			</div>
			<div class="module_mini_contents">
				# IF C_ANY_MESSAGE_GESTBOOK #
				<p class="text_small">{L_BY} {RAND_MSG_LOGIN}</p>
				<p class="text_small">{RAND_MSG_CONTENTS}</p>
				# ELSE #
				<p class="text_small">{L_NO_MESSAGE_GESTBOOK}</p>
				# ENDIF #
				<a class="small_link" href="{PATH_TO_ROOT}/guestbook/guestbook.php">{L_RANDOM_GESTBOOK}</a>
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		
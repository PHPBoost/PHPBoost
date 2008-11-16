	# IF C_HORIZONTAL #
		<div class="news_container">
			<div class="news_top_l"></div>
			<div class="news_top_r"></div>
			<div class="news_top">
				<div style="float:left; padding-left:30px"><a href="{PATH_TO_ROOT}/quotes/quotes.php" class="news_title">{L_RANDOM_QUOTES}</a></div>
				<div style="float:right">{COM}{EDIT}{DEL}</div>
			</div>		
			<div class="news_content" style="text-align:center">
				{RAND_MSG_CONTENTS}	
				<br />
				<strong>{RAND_MSG_AUTHOR}</strong>
				<div class="spacer"></div>
			</div>
			<div class="news_bottom_l"></div>
			<div class="news_bottom_r"></div>
			<div class="news_bottom">
				<span style="float:left"><a class="small_link" href="../member/member{news.U_MEMBER_ID}">{PSEUDO}</a></span>
				<span style="float:right">{DATE}</span>
			</div>
		</div>
	# ENDIF #
	
	# IF C_VERTICAL #
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_RANDOM_QUOTES}</h5>
			</div>
			<div class="module_mini_contents">
				<p class="text_small">{L_BY} {RAND_MSG_LOGIN}</p>
				<p class="text_small" style="text-align:center">{RAND_MSG_CONTENTS}</p>
				<p class="text_small"><strong>{RAND_MSG_AUTHOR}</strong></p>
				<p><a class="small_link" href="{PATH_TO_ROOT}/quotes/quotes.php">{L_RANDOM_QUOTES}</a></p>&nbsp;
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
	# ENDIF #

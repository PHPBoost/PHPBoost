<style>
<!--

div#comment-rating {
	width:48px;
	height:22px;
	margin-top: 10px;
}

div#comment-rating div {
	width:50%;
	color:#FFF;
	cursor: pointer;
	text-align:center;
	font-weight:900;
	font-size: 16px;
}

.positive_vote_button {
	background:#CCC;
	border-top-left-radius:2px;
	border-bottom-left-radius:2px;
	float:left;
}

.negative_vote_button {
	background:#e0e0e0;
	border-top-right-radius:2px;
	border-bottom-right-radius:2px;
	float:right;
}

div#comment-rating .positive{
	background:#ab0119;
}

div#comment-rating .negative{
	background:#39ab01;
}

.comment {
	width:98%;
	margin-bottom:20px;
}

.comment-date {
	margin-left: 10px;
}

.comment-user_infos {
	text-align:center;
	width:150px;
	float:left;
}

.comment-content {
	margin-left:180px;
}

.comment-message {
	background:#e8edf3;
	padding: 10px 10px 10px 10px;
	margin-top: 10px;
	min-height: 110px;
}
-->
</style>

# START comments #
	<div class="comment">
		<div class="comment-user_infos">
			<div id="comment-pseudo">
				# IF comments.C_VISITOR #
					{comments.PSEUDO}
				# ELSE #
					<a href="{comments.U_PROFILE}">{comments.PSEUDO}</a>
				# ENDIF #
			</div>
			<div class="comment-level">{comments.L_LEVEL}</div>
			<img src="{comments.U_AVATAR}">
		</div>
		<div class="comment-content">
			<div class="comment-date">
				{comments.DATE}
				# IF comments.C_MODERATOR #
				<a href="{comments.U_EDIT}">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" class="valign_middle" />
				</a> 
				<a href="{comments.U_DELETE}">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" class="valign_middle" />
				</a>
				# ENDIF #
			</div>
			<div class="comment-message">
				{comments.MESSAGE}
				<div id="comment-rating">
					<div class="positive_vote_button">+</div>
					<div class="negative_vote_button">-</div>
				</div>
			</div>
		</div>
	</div>
# END comments #

	
		
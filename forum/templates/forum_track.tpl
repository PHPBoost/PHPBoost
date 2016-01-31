		# INCLUDE forum_top #
		
		<script>
		<!--
			function check_convers(status, id)
			{
				for(i = 0; i < {NBR_TOPICS}; i++)
					document.getElementById(id + i).checked = status;
			}
		-->
		</script>
		
		<form action="track{U_TRACK_ACTION}" method="post">
			<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-track">
				<header>
					<h2>
						&bull; {U_FORUM_CAT}
						# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
					</h2>
				</header>
				<div class="content">
					<div class="text_small">{L_EXPLAIN_TRACK}</div>
					
					<table id="table" class="module-table forum-table">
						<thead>
							<tr class="forum-text-column">
								<th class="forum-announce-topic"><i class="fa fa-eye"></i></th>
								<th class="forum-fixed-topic"><i class="fa fa-heart"></i></th>
								<th class="forum-topic">{L_TOPIC}</th>
								<th class="forum-author">{L_AUTHOR}</th>
								<th class="forum-message-nb">{L_MESSAGE}</th>
								<th class="forum-view">{L_VIEW}</th>
								<th class="forum-pm"><input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'p');"> {L_PM}</th>
								<th class="forum-mail"><input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'm');"> {L_MAIL}</th>
								<th class="forum-delete"><input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'd');"> {L_DELETE}</th>
								<th class="forum-last-topic">{L_LAST_MESSAGE}</th>
							</tr>
						</thead>
						<tbody>
							# IF C_NO_TRACKED_TOPICS #
							<tr>
								<td colspan="10">
									<strong>{L_NO_TRACKED_TOPICS}</strong>
								</td>
							</tr>
							# ENDIF #
	
							# START topics #
							<tr>
								<td class="forum-announce-topic">
									# IF NOT topics.C_HOT_TOPIC # 
									<i class="fa {topics.IMG_ANNOUNCE}"></i>
									# ELSE #
									<i class="fa # IF topics.C_BLINK #blink # ENDIF #{topics.IMG_ANNOUNCE}-hot"></i>
									# ENDIF #
								</td>
								<td class="forum-fixed-topic">
									{topics.DISPLAY_MSG} {topics.TRACK} # IF C_POLL #<i class="fa fa-tasks"></i># ENDIF #
								</td>
								<td class="forum-topic">
									# IF topics.C_PAGINATION #<span class="pagin-forum"># INCLUDE topics.PAGINATION #</span># ENDIF #
									{topics.ANCRE} <strong>{topics.TYPE}</strong> <a title="{topics.TITLE}" href="topic{topics.U_TOPIC_VARS}">{topics.L_DISPLAY_MSG} {topics.TITLE}</a>
									<br />
									<span class="smaller">{topics.DESC}</span>
								</td>
								<td class="forum-author">
									{topics.AUTHOR}
								</td>
								<td class="forum-message-nb">
									{topics.MSG}
								</td>
								<td class="forum-view">
									{topics.VUS}
								</td>
								<td class="forum-pm">
									<input type="checkbox" id="p{topics.INCR}" name="p{topics.ID}" {topics.CHECKED_PM}>
								</td>
								<td class="forum-mail">
									<input type="checkbox" id="m{topics.INCR}" name="m{topics.ID}" {topics.CHECKED_MAIL}>
								</td>
								<td class="forum-delete">
									<input type="checkbox" id="d{topics.INCR}" name="d{topics.ID}">
								</td>
								<td class="forum-last-topic">
									{topics.U_LAST_MSG}
								</td>
							</tr>
							# END topics #
						</tbody>
					</table>
					<div class="center">
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="valid" value="true" class="submit">{L_SUBMIT}</button>
					</div>
				</div>
				<footer>
					<span class="float-left">
						&bull; {U_FORUM_CAT}
					</span>
					# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span><div class="spacer"></div># ENDIF #
				</footer>
			</article
		</form>
		
		# INCLUDE forum_bottom #
		
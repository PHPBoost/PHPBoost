# INCLUDE forum_top #

<script>
	function check_convers(status, id)
	{
		for(i = 0; i < {NBR_TOPICS}; i++)
			document.getElementById(id + i).checked = status;
	}
</script>

<form action="track{U_TRACK_ACTION}" method="post">
	<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-track">
		<header>
			<h2>
				<a href="{U_FORUM_CAT}">{FORUM_CAT}</a>
				# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
			</h2>
		</header>
		<div class="content">
			<div class="text_small">{L_EXPLAIN_TRACK}</div>

			<table class="table module-table forum-table">
				<thead>
					<tr class="forum-text-column">
						<th class="forum-announce-topic"><i class="far fa-eye" aria-hidden="true"></i></th>
						<th class="forum-fixed-topic"><i class="fa fa-check success" aria-hidden="true"></i></th>
						<th class="forum-topic" aria-label="{L_TOPIC}">
							<i class="far fa-file hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{L_TOPIC}</span>
						</th>
						<th class="forum-author" aria-label="{L_AUTHOR}">
							<i class="far fa-user hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{L_AUTHOR}</span>
						</th>
						<th class="forum-message-nb" aria-label="{L_MESSAGE}">
							<i class="far fa-comments hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{L_MESSAGE}</span>
						</th>
						<th class="forum-view" aria-label="{L_VIEW}">
							<i class="far fa-eye hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{L_VIEW}</span>
						</th>
						<th class="forum-pm" aria-label="{L_PM}">
							<label for="" class="checkbox">
								<input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'p');">
								<span><i class="fa fa-envelope hidden-small-screens" aria-hidden="true"></i></span>
							</label>
							<span class="hidden-large-screens">{L_PM}</span>
						</th>
						<th class="forum-mail" aria-label="{L_MAIL}">
							<label for="" class="checkbox">
								<input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'm');">
								<span><i class="fa fa-at hidden-small-screens" aria-hidden="true"></i></span>
							</label>
							<span class="hidden-large-screens">{L_MAIL}</span>
						</th>
						<th class="forum-delete" aria-label="{L_DELETE}">
							<label for="" class="checkbox">
								<input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'd');">
								<span><i class="fa fa-trash-alt hidden-small-screens" aria-hidden="true"></i></span>
							</label>
							<span class="hidden-large-screens">{L_DELETE}</span>
						</th>
						<th class="forum-last-topic" aria-label="{L_LAST_MESSAGE}">
							<i class="fa fa-clock hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{L_LAST_MESSAGE}</span>
						</th>
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
							# IF topics.C_DISPLAY_MSG #<i class="fa fa-check success"></i># ENDIF # # IF C_POLL #<i class="fa fa-tasks"></i># ENDIF #
						</td>
						<td class="forum-topic">
							# IF topics.C_PAGINATION #<span class="pagin-forum"># INCLUDE topics.PAGINATION #</span># ENDIF #
							<a href="{topics.U_ANCRE}" aria-label="{topics.TITLE}"><i class="fa fa-hand-point-right" aria-hidden="true"></i></a> <strong>{topics.TYPE}</strong> <a href="topic{topics.U_TOPIC_VARS}">{topics.L_DISPLAY_MSG} {topics.TITLE}</a>
							<span class="smaller">{topics.DESC}</span>
						</td>
						<td class="forum-author">
							# IF topics.C_AUTHOR #
							<a href="{topics.U_AUTHOR}" class="small {topics.AUTHOR_LEVEL}"# IF topics.C_GROUP_COLOR # style="color:{topics.GROUP_COLOR}"# ENDIF #>{topics.AUTHOR}</a>
							# ELSE #
							<em>{topics.L_GUEST}</em>
							# ENDIF #
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
							<span class="d-block">
								<i class="fa fa-hand-point-right fa-fw" aria-hidden="true"></i>
								<a href={topics.LAST_MSG_URL} aria-label="{topics.TITLE}<br /> {topics.LAST_MSG_DATE_FULL}"> {topics.LAST_MSG_DATE_FULL}</a>
							</span>
							<span class="d-block">
								<i class="fa fa-user fa-fw"></i>
								# IF topics.C_LAST_MSG_GUEST #
								<a href="{topics.LAST_MSG_USER_PROFIL}" class="small {topics.LAST_MSG_USER_LEVEL}"# IF topics.C_LAST_MSG_USER_GROUP_COLOR # style="color:{topics.LAST_MSG_USER_GROUP_COLOR}"# ENDIF #>{topics.LAST_MSG_USER_LOGIN}</a>
								# ELSE #
									<em>${LangLoader::get_message('guest', 'main')}</em>
								# ENDIF #
							</span>
						</td>
					</tr>
					# END topics #
				</tbody>
			</table>
			<div class="align-center">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" class="button submit" name="valid" value="true">{L_SUBMIT}</button>
			</div>
		</div>
		<footer>
			# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span><div class="spacer"></div># ENDIF #
		</footer>
	</article
</form>

# INCLUDE forum_bottom #

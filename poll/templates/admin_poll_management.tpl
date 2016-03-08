		<nav id="admin-quick-menu">
			<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" title="{L_POLL_MANAGEMENT}">
				<i class="fa fa-bars"></i> {L_POLL_MANAGEMENT}
			</a>
			<ul>
				<li>
					<a href="admin_poll.php" class="quick-link">{L_POLL_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_poll_add.php" class="quick-link">{L_POLL_ADD}</a>
				</li>
				<li>
					<a href="admin_poll_config.php" class="quick-link">{L_POLL_CONFIG}</a>
				</li>
			</ul>
		</nav> 
		
		<div class="admin-module-poll" id="admin-contents">
			<table id="table">
				<caption>{L_POLL_MANAGEMENT}</caption>
				<thead>
					<tr>
						<th>
							{L_QUESTION}
						</th>
						<th>
							{L_POLLS}
						</th>
						<th>
							{L_PSEUDO}
						</th>
						<th>
							{L_DATE}
						</th>
						<th>
							${LangLoader::get_message('hidden', 'common')}
						</th>
						<th>
							{L_APROB}
						</th>
						<th></th>
					</tr>
				</thead>
				# IF C_PAGINATION #
				<tfoot>
					<tr>
						<th colspan="7">
							# INCLUDE PAGINATION #
						</th>
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START questions #
					<tr> 
						<td class="left"> 
							{questions.QUESTIONS}
						</td>
						<td>
							<a href="{PATH_TO_ROOT}/poll/poll.php?id={questions.IDPOLL}">${LangLoader::get_message('display', 'common')}</a>
						</td>
						<td> 
							# IF questions.PSEUDO #<a href="{questions.U_AUTHOR_PROFILE}" class="small {questions.USER_LEVEL_CLASS}" # IF questions.C_USER_GROUP_COLOR # style="color:{questions.USER_GROUP_COLOR}" # ENDIF #>{questions.PSEUDO}</a># ELSE #${LangLoader::get_message('guest', 'main')}# ENDIF # 
						</td>
						<td>
							{questions.DATE}
						</td>
						<td>
							{questions.ARCHIVES}
						</td>	
						<td>
							{questions.APROBATION} 
							<br />
							<span class="smaller">{questions.VISIBLE}</span>
						</td>
						<td> 
							<a href="admin_poll.php?id={questions.IDPOLL}" title="{L_UPDATE}" class="fa fa-edit"></a>
							<a href="admin_poll.php?delete=1&amp;id={questions.IDPOLL}&amp;token={TOKEN}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
						</td>
					</tr>
					# END questions #
				</tbody>
			</table>
		</div>

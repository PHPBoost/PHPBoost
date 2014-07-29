		<div id="admin-quick-menu">
			<ul>
				<li class="title-menu">{L_FAQ_MANAGEMENT}</li>
				<li>
					<a href="admin_faq_cats.php"><img src="faq.png" alt="{L_CATS_MANAGEMENT}" /></a>
					<br />
					<a href="admin_faq_cats.php" class="quick-link">{L_CATS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_faq_cats.php?new=1"><img src="faq.png" alt="{L_ADD_CAT}" /></a>
					<br />
					<a href="admin_faq_cats.php?new=1" class="quick-link">{L_ADD_CAT}</a>
				</li>
				<li>
					<a href="admin_faq.php?p=1"><img src="faq.png" alt="{L_QUESTIONS_LIST}" /></a>
					<br />
					<a href="admin_faq.php?p=1" class="quick-link">{L_QUESTIONS_LIST}</a>
				</li>
				<li>
					<a href="management.php?new=1"><img src="faq.png" alt="{L_ADD_QUESTION}" /></a>
					<br />
					<a href="management.php?new=1" class="quick-link">{L_ADD_QUESTION}</a>
				</li>
				<li>
					<a href="admin_faq.php"><img src="faq.png" alt="{L_CONFIG_MANAGEMENT}" /></a>
					<br />
					<a href="admin_faq.php" class="quick-link">{L_CONFIG_MANAGEMENT}</a>
				</li>
			</ul>
		</div>

		<div id="admin-contents">
			<table>
				<caption>{L_QUESTIONS_LIST}</caption>
				<thead>
					<tr>
						<th></th>
						<th>
							{L_QUESTION}
						</th>
						<th>
							{L_CATEGORY}
						</th>
						<th>
							{L_DATE}
						</th>
					</tr>
				</thead>
				# IF C_PAGINATION #
				<tfoot>
					<tr>
						<th colspan="4">
							# INCLUDE PAGINATION #
						</th>
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START question #
						<tr>
							<td>
								<a href="{question.U_EDIT}" title="{L_EDIT}" class="fa fa-edit"></a>
								<a href="{question.U_DEL}" title="{L_DELETE}" class="fa fa-delete" data-confirmation="delete-element"></a>
							</td>
							<td class="left"> 
								<a href="{question.U_QUESTION}">{question.QUESTION}</a>
							</td>
							<td> 
								<a href="{question.U_CATEGORY}">{question.CATEGORY}</a>
							</td>
							<td>
								{question.DATE}
							</td>
						</tr>
					# END question #
				</tbody>
			</table>
		</div>
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_FAQ_MANAGEMENT}</li>
				<li>
					<a href="admin_faq.php"><img src="faq.png" alt="{L_CONFIG_MANAGEMENT}" /></a>
					<br />
					<a href="admin_faq.php" class="quick_link">{L_CONFIG_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_faq_cats.php"><img src="faq.png" alt="{L_CATS_MANAGEMENT}" /></a>
					<br />
					<a href="admin_faq_cats.php" class="quick_link">{L_CATS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_faq_cats.php?new=1"><img src="faq.png" alt="{L_ADD_CAT}" /></a>
					<br />
					<a href="admin_faq_cats.php?new=1" class="quick_link">{L_ADD_CAT}</a>
				</li>
				<li>
					<a href="admin_faq.php?p=1"><img src="faq.png" alt="{L_QUESTIONS_LIST}" /></a>
					<br />
					<a href="admin_faq.php?p=1" class="quick_link">{L_QUESTIONS_LIST}</a>
				</li>
				<li>
					<a href="management.php?new=1"><img src="faq.png" alt="{L_ADD_QUESTION}" /></a>
					<br />
				<a href="management.php?new=1" class="quick_link">{L_ADD_QUESTION}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">	
			<table class="module_table">
				<tr style="text-align:center;">
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
			# START question #
				<tr style="text-align:center;"> 
					<td class="row2"> 
						<a href="{question.U_QUESTION}">{question.QUESTION}</a>
					</td>
					<td class="row2"> 
						<a href="{question.U_CATEGORY}">{question.CATEGORY}</a>
					</td>
					<td class="row2">
						{question.DATE}
					</td>
				</tr>
			# END question #
				<tr>
					<td colspan="3" class="row1" style="text-align:center;">
						{PAGINATION}
					</td>
				</tr>
			</table>
		</div>
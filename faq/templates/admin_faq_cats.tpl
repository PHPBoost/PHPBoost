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
			</ul>
		</div>

		<div id="admin_contents">
			<table class="module_table" style="width:99%;">
				<tr>			
					<th colspan="3">
						{L_CATS_MANAGEMENT}
					</th>
				</tr>							
				<tr>
					<td style="padding-left:20px;" class="row2">
						<br />
						# START categories_list #
						{categories_list.CATEGORIES}
						# END categories_list #

						# START removing_interface #

						# END removing_interface #
						<br />
					</td>
				</tr>
			</table>
		</div>
		
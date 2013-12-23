{JAVA} 

# IF C_WEB_CAT #

<section>
	<header>
		<h1>
			{L_WEB} - {L_CATEGORIES}
			<span class="actions">
				# IF C_IS_ADMIN # 
				<a href="{PATH_TO_ROOT}/web/admin_web_cat.php{SID}" title="{L_EDIT}" class="fa fa-edit"></a> 
				# ENDIF #
				{PAGINATION}
			</span>
		</h1>
	</header>
	<div class="content">
		# START cat_list #
		<div style="float:left;text-align:center;width:{cat_list.WIDTH}%;">
			{cat_list.U_IMG_CAT}
			<a href="{PATH_TO_ROOT}/web/web{cat_list.U_WEB_CAT}">{cat_list.CAT}</a> <span class="smaller">({cat_list.TOTAL})</span><br />
			<span class="smaller">{cat_list.CONTENTS}</span>
			<br /><br /><br />
		</div>	
		# END cat_list #
		
		<div class="smaller" style="padding-top:10px;text-align:center;clear:both">
			{TOTAL_FILE} {L_HOW_LINK}
		</div>
	</div>
	<footer>
		<div style="float:right">
			{PAGINATION}
		</div>
	</footer>
</section>
# ENDIF #

# IF C_WEB_LINK #
<section>					
	<header>
		<h1>
			{L_WEB} - {CAT_NAME}
			<span class="actions">
				# IF C_IS_ADMIN # 
				<a href="{PATH_TO_ROOT}/web/admin_web_cat.php{SID}" title="{L_EDIT}" class="fa fa-edit"></a> 
				# ENDIF #
			</span>
		</h1>
	</header>
	<div class="content">
		# IF NO_CAT #
			<p style="text-align:center;padding:6px;">{NO_CAT}</p>
		# ELSE #
			<table>
				<thead>
					<tr>
						<th>
							<a href="web{U_WEB_ALPHA_TOP}" class="fa fa-table-sort-up"></a>
							{L_LINK}
							<a href="web{U_WEB_ALPHA_BOTTOM}" class="fa fa-table-sort-down"></a>
						</th>
						<th>
							<a href="web{U_WEB_DATE_TOP}" class="fa fa-table-sort-up"></a>
							{L_DATE}					
							<a href="web{U_WEB_DATE_BOTTOM}" class="fa fa-table-sort-down"></a>
						</th>
						<th>
							<a href="web{U_WEB_VIEW_TOP}" class="fa fa-table-sort-up"></a>
							{L_VIEW}					
							<a href="web{U_WEB_VIEW_BOTTOM}" class="fa fa-table-sort-down"></a>
						</th>
						<th>
							<a href="web{U_WEB_NOTE_TOP}" class="fa fa-table-sort-up"></a>
							{L_NOTE}					
							<a href="web{U_WEB_NOTE_BOTTOM}" class="fa fa-table-sort-down"></a>
						</th>
						<th>
							<a href="web{U_WEB_COM_TOP}" class="fa fa-table-sort-up"></a>
							{L_COM}
							<a href="web{U_WEB_COM_BOTTOM}" class="fa fa-table-sort-down"></a>
						</th>
					</tr>
				</thead>
				# IF PAGINATION #
				<tfoot>
					<tr>
						<th colspan="5">
							{PAGINATION}
						</th>
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START web #
					<tr>	
						<td>
							<a href="web{web.U_WEB_LINK}">{web.NAME}</a>
						</td>
						<td>
							{web.DATE}
						</td>
						<td>
							{web.COMPT} 
						</td>
						<td>
							{web.NOTE}
						</td>
						<td>
							{web.COM} 
						</td>
					</tr>
					# END web #
				</tbody>
			</table>
		# ENDIF #
	</div>
</section>
# ENDIF #

# IF C_DISPLAY_WEB #
<article>					
	<header>
		<h1>
			{NAME}
			<span class="actions">
				{COM} {EDIT} {DEL}
			</span>
		</h1>
	</header>
	<div class="content">
		<p>
			<strong>{L_DESC}:</strong> {CONTENTS}
			<br /><br />
			<strong>{L_CAT}:</strong> 
			
			<a href="{PATH_TO_ROOT}/web/web{U_WEB_CAT}" title="{CAT}">{CAT}</a><br />
			
			<strong>{L_DATE}:</strong> {DATE}<br />						
			<strong>{L_VIEWS}:</strong> {COMPT} {L_TIMES}
			
			<span class="spacer">&nbsp;</span>
		</p>
		<p class="center">					
			<button type="button" name="{NAME}" class="visit" onclick="document.location = 'count.php?id={IDWEB}';" value="true">{L_VISIT} <i class="fa fa-globe"></i></button>
		</p>
		{KERNEL_NOTATION}
		{COMMENTS}
	</div>
	<footer></footer>
</article>
# ENDIF #
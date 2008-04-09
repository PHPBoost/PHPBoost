		# IF C_DISPLAY_ARTICLE #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<strong>{NAME}</strong>
				</div>
				<div style="float:right">
					{COM} {EDIT} {DEL}
				</div>
			</div>
			<div class="module_contents">
				# IF PAGINATION_ARTICLES #
				<p style="text-align:center">{PAGINATION_ARTICLES}</p>
				# ENDIF #
				
				{CONTENTS}
				
				# IF PAGINATION_ARTICLES #
				<p style="text-align:center">{PAGINATION_ARTICLES}</p>
				# ENDIF #
				<div class="spacer"></div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<span style="float:left" class="text_small">
					# INCLUDE handle_note #
				</span>
				<span style="float:right" class="text_small">
					{L_WRITTEN}: <a class="small_link" href="../member/member{U_MEMBER_ID}">{PSEUDO}</a>, {L_ON}: {DATE}
				</span>
				<div class="spacer"></div>
			</div>
		</div>
		<br /><br />
		# INCLUDE handle_com #
		
		# ENDIF #
		
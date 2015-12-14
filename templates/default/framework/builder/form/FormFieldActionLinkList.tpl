<div id="${escape(HTML_ID)}" class="form-field-action-link" style="# IF C_HIDDEN # display:none; # ENDIF #">
	<ul>
		# START action #
		<li>
			# IF action.C_PICTURE #
			<a href="{action.U_LINK}" title="{action.TITLE}">
				# IF action.C_IMG #
				<img src="{action.U_IMG}" alt="{action.TITLE}" />
				# ELSE #
				<i class="fa {action.CSS_CLASS}"></i>
				# ENDIF #
			</a><br />
			# ENDIF #
			<a href="{action.U_LINK}">{action.TITLE}</a>
		</li>
		# END action #
	</ul>
</div>

# INCLUDE ADD_FIELD_JS #
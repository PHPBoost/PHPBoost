<ul>
	# START action #
		<li class="# IF NOT action.C_IS_ACTIVE_MODULE # hidden# ENDIF #">
			<a class="{action.CLASS}" href="#" data-{action.TRIGGER} data-target="{action.TARGET}">
				# IF action.C_PICTURE #
					# IF action.C_IMG #
						<img src="{action.U_IMG}" alt="{action.TITLE}" />
					# ELSE #
						<i class="fa {action.CSS_CLASS}" aria-hidden="true"></i>
					# ENDIF #
				# ENDIF #
				{action.TITLE}
			</a>
		</li>
	# END action #
</ul>

# INCLUDE ADD_FIELD_JS #

<nav id="${escape(HTML_ID)}" class="form-field-action-link"# IF C_HIDDEN # style="display: none;"# ENDIF #>
	{LABEL}
	<ul>
		# START action #
			<li# IF NOT action.C_IS_ACTIVE_MODULE # class="hidden"# ENDIF #>
				<a class="offload" href="{action.U_LINK}">
					# IF action.C_PICTURE #
						# IF action.C_IMG #
						<img src="{action.U_IMG}" alt="{action.TITLE}" />
						# ELSE #
						<i class="fa {action.CSS_CLASS}" aria-hidden="true"></i>
						# ENDIF #
					# ENDIF #
					<span>{action.TITLE}</span>
				</a>
			</li>
		# END action #
	</ul>
</nav>

# INCLUDE ADD_FIELD_JS #

<nav id="${escape(HTML_ID)}" # IF C_HIDDEN # style="display: none;"# ENDIF #>
	<ul>
		# START action #
			<li# IF NOT action.C_IS_ACTIVE_MODULE # class="hidden"# ENDIF #>
				<a href="#" data-{action.TRIGGER} data-target="{action.TARGET}">
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
</nav>

# INCLUDE ADD_FIELD_JS #

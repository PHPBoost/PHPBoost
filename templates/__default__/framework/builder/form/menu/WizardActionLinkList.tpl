<nav id="${escape(HTML_ID)}" class="wizard-header"# IF C_HIDDEN # style="display: none;"# ENDIF #>
	<ul>
		# START action #
			<li# IF NOT action.C_IS_ACTIVE_MODULE # class="hidden"# ENDIF #>
				<a href="#"# IF action.C_CSS_CLASS # class="{action.CSS_CLASS}"# ENDIF #>
					# IF action.C_PICTURE #
						# IF action.C_IMG #
							<img src="{action.U_IMG}" alt="{action.TITLE}" />
						# ELSE #
							# IF action.C_FA_ICON #<i class="fa {action.FA_ICON}" aria-hidden="true"></i># ENDIF #
						# ENDIF #
					# ENDIF #
					{action.TITLE}
				</a>
			</li>
		# END action #
		# IF NOT IS_USER_CONNECTED #
			<li><a href="#">${LangLoader::get_message('form.captcha', 'form-lang')}</a></li>
		# ENDIF #
		<li><a href="#">${LangLoader::get_message('form.submit', 'form-lang')}</a></li>
	</ul>
</nav>
<div class="wizard-navigator"></div>
# INCLUDE ADD_FIELD_JS #

<nav id="${escape(HTML_ID)}" class="wizard-header"# IF C_HIDDEN # style="display: none;"# ENDIF #>
	<ul>
		# START action #
			<li# IF NOT action.C_IS_ACTIVE_MODULE # class="hidden"# ENDIF #>
				<a href="#">
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
		# IF NOT IS_USER_CONNECTED #<li><a href="#">${LangLoader::get_message('content.config.captcha', 'admin-contents-common')}</a></li># ENDIF #
		<li><a href="#">${LangLoader::get_message('validation', 'common')}</a></li>
	</ul>
</nav>
<div class="wizard-navigator"></div>
# INCLUDE ADD_FIELD_JS #

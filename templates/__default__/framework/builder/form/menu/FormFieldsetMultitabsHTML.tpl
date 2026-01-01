# INCLUDE ADD_FIELDSET_JS #
<div id="${escape(HTML_ID)}"# IF CSS_CLASS # class="{CSS_CLASS}"# ENDIF ## IF C_DISABLED # style="display: none;"# ENDIF #>
	# IF C_MODAL #<div class="close-modal" aria-label="${LangLoader::get_message('common.close', 'common-lang')}"></div># ENDIF #
	<div class="content-panel fieldset-inset">
		# IF C_MODAL #<div class="align-right w100"><a href="#" class="error big hide-modal" aria-label="${LangLoader::get_message('common.close', 'common-lang')}"><i class="far fa-circle-xmark" aria-hidden="true"></i></a></div># ENDIF #
		# IF C_TITLE #<h2>{L_TITLE}</h2># ENDIF #
		# IF C_DESCRIPTION #<p class="fieldset-description">{DESCRIPTION}</p># ENDIF #
		# START elements #
			# INCLUDE elements.ELEMENT #
		# END elements #
	</div>
</div>

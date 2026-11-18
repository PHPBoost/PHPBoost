# INCLUDE ADD_FIELDSET_JS #
<span class="modal-button --${escape(HTML_ID)} # IF C_BUTTON_CLASS #{BUTTON_CLASS}# ENDIF #"># IF C_TITLE #{L_TITLE}# ENDIF #</span>
<div id="${escape(HTML_ID)}" class="modal# IF CSS_CLASS # {CSS_CLASS}# ENDIF #"# IF C_DISABLED # style="display: none;"# ENDIF #>
	<div class="modal-overlay close-modal" aria-label="${LangLoader::get_message('common.close', 'common-lang')}"></div>
	<div class="modal-content fieldset-inset">
		<span class="error big hide-modal close-modal" aria-label="${LangLoader::get_message('common.close', 'common-lang')}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
		# IF C_TITLE #<h2>{L_TITLE}</h2># ENDIF #
		# IF C_DESCRIPTION #<p class="fieldset-description">{DESCRIPTION}</p># ENDIF #
		# START elements #
			# INCLUDE elements.ELEMENT #
		# END elements #
	</div>
</div>

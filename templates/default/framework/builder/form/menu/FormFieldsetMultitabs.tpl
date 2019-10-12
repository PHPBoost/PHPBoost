# INCLUDE ADD_FIELDSET_JS #
<div id="${escape(HTML_ID)}"# IF CSS_CLASS # class="{CSS_CLASS}"# ENDIF ## IF C_DISABLED # style="display: none;"# ENDIF #>
	# IF C_ACCORDION_CONTROLS #
	    <div class="accordion-controls">
	        <span class="open-all-accordions" aria-label="Open all panels"><i class="fa fa-fw fa-chevron-down"></i></span>
	        <span class="close-all-accordions" aria-label="Close all panels"><i class="fa fa-fw fa-chevron-up"></i></span>
	    </div>
	# ENDIF #
	# IF C_MODAL #<div class="close-modal" aria-label="${LangLoader::get_message('close_menu', 'admin')}"></div># ENDIF #
	<div class="content-panel fieldset-inset">
		# IF C_TITLE #<h2>{L_TITLE}</h2># ENDIF #
		# IF C_DESCRIPTION #<p class="fieldset-description">{DESCRIPTION}</p># ENDIF #
		# START elements #
			# INCLUDE elements.ELEMENT #
		# END elements #
	</div>
</div>

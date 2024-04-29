<div id="${escape(HTML_ID)}_field"# IF C_HIDDEN # style="display: none;" # ENDIF # class="form-element# IF C_HAS_FIELD_CLASS # {FIELD_CLASS}# ENDIF ## IF C_HAS_CSS_CLASS # {CLASS}# ENDIF #">
	{HTML}
</div>
# INCLUDE ADD_FIELD_JS #

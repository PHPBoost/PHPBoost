<input type="text" size="{SIZE}" maxlength="{MAX_LENGTH}" name="${escape(NAME)}" id="${escape(HTML_ID)}" value="${escape(VALUE)}" class="${escape(CLASS)}" # IF C_DISABLED # disabled="disabled" # ENDIF # autocomplete="off" onfocus="javascript:load_autocompleter_{HTML_ID}();" />
	<script>
		function load_autocompleter_{HTML_ID}() {
			jQuery("#" + ${escapejs(HTML_ID)}).autocomplete({
				serviceUrl: ${escapejs(FILE)},
				paramName: ${escapejs(NAME_PARAMETER)},
				showNoSuggestionNotice: {NO_SUGGESTION_NOTICE},
				noSuggestionNotice: ${escapejs(LangLoader::get_message('no_result', 'main'))},
				preserveInput: {PRESERVE_INPUT},
				params: {'token': ${escapejs(TOKEN)}},
				# IF C_DISPLAY_HTML_IN_SUGGESTIONS #
				onSelect: function (suggestion) {
					jQuery("#" + ${escapejs(HTML_ID)}).val(jQuery(suggestion.value).html());
					jQuery("#" + ${escapejs(HTML_ID)}).trigger("blur");
				},
				formatResult: function (suggestion, currentValue) { 
					return suggestion.value;
				}
				# ENDIF #
			});
		}
	</script>
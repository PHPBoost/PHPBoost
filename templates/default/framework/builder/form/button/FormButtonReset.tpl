# IF C_ONCLICK_FUNCTION #
<script>
<!--
	function XMLHttpRequest_reset_{HTML_ID}()
	{
		{ONCLICK_ACTIONS}
	}
-->
</script>
# ENDIF #
<button type="reset" # IF C_CLASS #class="{CLASS}" # ENDIF ## IF C_ONCLICK_FUNCTION #onclick="XMLHttpRequest_reset_{HTML_ID}();" # ENDIF #value="true">{L_RESET}</button>

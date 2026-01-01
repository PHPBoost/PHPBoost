# IF C_ONCLICK_FUNCTION #
	<script>
		function XMLHttpRequest_reset_{HTML_ID}()
		{
			{ONCLICK_ACTIONS}
		}
	</script>
# ENDIF #
<button class="button reset-button" type="reset" value="true"# IF C_ONCLICK_FUNCTION # onclick="XMLHttpRequest_reset_{HTML_ID}();"# ENDIF #>{L_RESET}</button>

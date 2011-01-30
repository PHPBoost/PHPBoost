		<script type="text/javascript">
		<!--
		function check_form_forget(){
			if(document.getElementById('name').value == "") {
				alert("{L_REQUIRE_PSEUDO}");
				return false;
		    }
			if(document.getElementById('mail').value == "") {
				alert("{L_REQUIRE_MAIL}");
				return false;
		    }
			return true;
		}
		function check_form_change(){
			if(document.getElementById('new_password').value == "") {
				alert("{L_REQUIRE_PASSWORD}");
				return false;
		    }
			if(document.getElementById('new_password_bis').value == "") {
				alert("{L_REQUIRE_PASSWORD}");
				return false;
		    }
			return true;
		}
		-->
		</script>

		# IF C_CHANGE_PASSWORD #
		{L_NEW_PASS_FORGET}
		# ENDIF #
		
		# INCLUDE message_helper #
		# INCLUDE forget_password_form #

        <script type="text/javascript">
        <!--
        function check_search_mini_form_post()
        {
            var textSearched = document.getElementById('TxTMiniSearched').value;
            if ( (textSearched.length > 3) && (textSearched != escape('{L_SEARCH}...')) )
            {
                textSearched = escape_xmlhttprequest(textSearched);
                return true;
            }
            else
            {
                alert('{WARNING_LENGTH_STRING_SEARCH}');
                return false;
            }
        }
        -->
        </script>
        <div style="position:absolute;top:15px;margin-left:850px;">
			<form action="{U_FORM_VALID}" onsubmit="return check_search_mini_form_post();" method="post">
				<input type="text" size="14" id="TxTMiniSearched" name="search" value="{TEXT_SEARCHED}" class="text" style="background:#FFFFFF url(../templates/{THEME}/images/search.png) no-repeat;background-position:2px 1px;padding-left:22px;" onclick="if(this.value=='{L_SEARCH}...')this.value='';" onblur="if(this.value=='')this.value='{L_SEARCH}...';" />
				<input type="hidden" name="search_submit" id="search_submit" value="{SEARCH}" class="submit" />
	        </form>
		</div>
		
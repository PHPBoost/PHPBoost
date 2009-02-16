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
        <form action="{U_FORM_VALID}" onsubmit="return check_search_mini_form_post();" method="post">
            <div id="search_form">
				<input type="text" size="14" id="TxTMiniSearched" name="q" value="{TEXT_SEARCHED}" class="search_entry" onclick="if(this.value=='{L_SEARCH}...')this.value='';" onblur="if(this.value=='')this.value='{L_SEARCH}...';" />
				<input type="hidden" name="search_submit" id="search_submit_mini" value="{SEARCH}" class="submit" />
				<input type="image" name="search_submit" class="search_submit" value="1" src="{PATH_TO_ROOT}/templates/{THEME}/modules/search/images/search_submit.png" />
            </div>
        </form>
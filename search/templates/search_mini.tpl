		<script type="text/javascript">
		<!--
		function check_search_mini_form_post()
		{
		    var textSearched = document.getElementById('TxTMiniSearched').value;
		    
		    if ( textSearched.length > 3 )
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
		<div class="module_mini_container">
		    <div class="module_mini_top"><h5 class="sub_title">{SEARCH}</h5></div>
		    <div class="module_mini_contents">
		        <div class="search_mini">
		            <form action="{U_FORM_VALID}" onsubmit="return check_search_mini_form_post();" method="post">
		                <input type="text" id="TxTMiniSearched" name="search" value="{TEXT_SEARCHED}"  class="text" /><br />
		                <input type="submit" name="search_submit" id="search_submit" value="{SEARCH}" class="submit" />
		            </form>
		        </div>
		    </div>
		    <div class="module_mini_bottom"></div>
		</div>
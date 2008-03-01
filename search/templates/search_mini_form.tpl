<script language="text/javascript">
<!--
function check_form_post()
{
    var textSearched = document.getElementById("search").value;
    
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
    <div class="module_mini_top"><h5 class="sub_title">{TITLE_SEARCH}</h5></div>
    <div class="module_mini_table">
        <form action="../search/search.php#results" onsubmit="return check_form_post();" method="post">
            <input type="text" size="35" id="search" name="search" value="{TEXT_SEARCHED}"  class="text" /><br />
            <input type="submit" name="search_submit" id="search_submit" value="{SEARCH}" class="submit" />
        </form>
    </div>
    <div class="module_mini_bottom"></div>
</div>
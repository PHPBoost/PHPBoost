<script type="text/javascript">
    <!--
    function search_submit()
    {
        var search = document.getElementById("search").value;
        alert({WARNING_LENGTH_STRING_SEARCH});
        if( search == '' )
        {
           alert({WARNING_LENGTH_STRING_SEARCH});
        }
    }
    -->
</script>

<div class="module_position">
    <div class="module_top_l"></div>
    <div class="module_top_r"></div>
    <div class="module_top">{TITLE}</div>
    <div class="module_contents">
        <div class="spacer">&nbsp;</div>
        <form action="../search/search.php" method="post">
            <fieldset>
                <legend>{TITLE_SEARCH}</legend>
                <dl>
                    <dt><label for="search">{SEARCH_MIN_LENGTH}</label></dt>
                    <dd><label><input type="text" size="35" id="search" name="search" value="{TEXT_SEARCHED}"  class="text" /></label></dd>
                </dl>
            </fieldset>
            # START forms #
                <div class="module_position">
                    <fieldset>
                        <legend>{forms.MODULE_NAME}</legend>
                        {forms.SEARCH_FORM}
                    </fieldset>
                </div>
            # END forms #
            <fieldset class="fieldset_submit">
                <legend>{title_search}</legend>
                <input type="submit" name="search" id="search_submit" value="{SEARCH}" class="submit" />
                <script type="text/javascript">
                    <!--
                        document.getElementById('search_submit').style.display = 'none';
                        document.write('<input type="button" value="{SEARCH}" onclick="search_submit();" class="submit" />');
                    -->
                </script>
            </fieldset>
        </form>
    </div>
    <div class="module_bottom_l"></div>
    <div class="module_bottom_r"></div>
    <div class="module_bottom" style="text-align:center;">{HITS}</div>
</div>
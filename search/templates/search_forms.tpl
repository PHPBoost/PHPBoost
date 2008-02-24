<div class="module_position">
    <div class="module_top_l"></div>
    <div class="module_top_r"></div>
    <div class="module_top">{TITLE}</div>
    <div class="module_contents">
        <div class="spacer">&nbsp;</div>
            <fieldset>
                <legend>{title_search}</legend>
                <dl>
                    <dt><label for="search">Mots clés (4 caractères minimum)</label></dt>
                    <dd><label><input type="text" size="35" id="search" name="search" value="{search}"  class="text" /></label></dd>
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
    </div>
    <div class="module_bottom_l"></div>
    <div class="module_bottom_r"></div>
    <div class="module_bottom" style="text-align:center;">{HITS}</div>
</div>
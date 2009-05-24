        <script type="text/javascript">
        <!--
            const FORM = 'form_';
            const SPECIALIZED_FORM_LINK = 'specialize_form_link_';
            var LastSpecializedFormUsed = 'all';
            
            function ChangeForm(module)
            // Change le cadre des résultats
            {
                hide_div(FORM + LastSpecializedFormUsed);
                show_div(FORM + module);

                document.getElementById(SPECIALIZED_FORM_LINK + LastSpecializedFormUsed).style.fontSize = '10px';
                document.getElementById(SPECIALIZED_FORM_LINK + LastSpecializedFormUsed).className = 'small_link';

                LastSpecializedFormUsed = module;
                document.getElementById('search_in').value = module;
                document.getElementById(SPECIALIZED_FORM_LINK + module).style.fontSize = '12px';
                document.getElementById(SPECIALIZED_FORM_LINK + module).className = 'small_link SpecializedFormLink';
            }
            
            function check_search_form_post()
            // V�rifie la validité du formulaire
            {
                var textSearched = document.getElementById("TxTsearched").value;
                
                if ( textSearched.length > 3 )
                {
                    textSearched = escape_xmlhttprequest(textSearched);
                    return true;
                }
                else
                {
                    alert({L_WARNING_LENGTH_STRING_SEARCH});
                    return false;
                }
            }
        -->
        </script>

       <div class="module_position">
            <div class="module_top_l"></div>
            <div class="module_top_r"></div>
            <div class="module_top">{L_TITLE_SEARCH}</div>
            <div class="module_contents">
                <div class="spacer">&nbsp;</div>
                <form action="{U_FORM_VALID}" onsubmit="return check_search_form_post();" method="post">
                    <div class="search_field"><input type="text" id="TxTsearched" name="q" value="{TEXT_SEARCHED}" class="text" onclick="if(this.value=='{L_SEARCH}...')this.value='';" onblur="if(this.value=='')this.value='{L_SEARCH}...';" /></div>
                    <div class="spacer">&nbsp;</div>
                    <div style="text-align:center;">
                        <p id="forms_selection">
                            <a id="specialize_form_link_all" href="javascript:ChangeForm('all');" class="small_link">{L_SEARCH_ALL}</a>
                            # START forms #
                                <a id="specialize_form_link_{forms.MODULE_NAME}" href="javascript:ChangeForm('{forms.MODULE_NAME}');" class="small_link">{forms.L_MODULE_NAME}</a>
                            # END forms #
                        </p>
                    </div>
                    <div id="form_all" class="SpecializedForm">
                        <fieldset class="searchFieldset">
                            <dl>
                                <dt><label>{L_SEARCH_IN_MODULES}<br /><span>{L_SEARCH_IN_MODULES_EXPLAIN}</span></label></dt>
                                <dd>
                                    <select id="searched_modules" name="searched_modules[]" size="5" multiple="multiple" class="list_modules">
                                    # START searched_modules #
                                        <option value="{searched_modules.MODULE}" id="{searched_modules.MODULE}"{searched_modules.SELECTED}>{searched_modules.L_MODULE_NAME}</option>
                                    # END searched_modules #
                                    </select>
                                </dd>
                            </dl>
                        </fieldset>
                    </div>
                    # START forms #
                    <div id="form_{forms.MODULE_NAME}" class="SpecializedForm" style="display:none">
                        <fieldset class="searchFieldset">
                        # IF forms.C_SEARCH_FORM #{forms.SEARCH_FORM}# ELSE #<p class="label">{forms.SEARCH_FORM}</p># ENDIF #
                        </fieldset>
                    </div>
                    # END forms #
                    <div class="spacer">&nbsp;</div>
                    <fieldset class="fieldset_submit">
                        <legend>{L_SEARCH}</legend>
                        <input type="hidden" id="search_in" name="search_in" value="all" />
                        <input type="hidden" id="query_mode" name="query_mode" value="0" />
                        <input type="submit" id="search_submit" name="search_submit" value="{L_SEARCH}" class="submit" />
                        <input type="hidden" name="token" value="{TOKEN}" />
                    </fieldset>
                </form>
            </div>
            <div class="module_bottom_l"></div>
            <div class="module_bottom_r"></div>
            <div class="module_bottom" style="text-align:center;"></div>
        </div>
        <script type="text/javascript">
        <!--
            ChangeForm('{SEARCH_MODE_MODULE}');
        -->
        </script>
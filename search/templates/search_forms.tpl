        <script type="text/javascript">
        <!--
            const FORM = 'form_';
            const SPECIALIZED_FORM_LINK = 'specialize_form_link';
            var LastSpecializedFormUsed = '{SEARCH_MODE_MODULE}';
            var modulesForms = new Array();
            # START forms #
                modulesForms.push("{forms.MODULE_NAME}");
            # END forms #
            
            function ShowAdvancedSearchForms()
            // Montre les champs de recherche avancée
            {
                HideAdvancedSearchForms();
                
                document.getElementById('searched_modules_DL').style.display = 'none';
                document.getElementById('forms_selection_DL').style.display = 'block';
                
                hide_div('advanced_search');
                show_div('simple_search');
                
                if ( modulesForms.length > 0 )
                {
                    if ( LastSpecializedFormUsed != 'all' )
                        ChangeForm(LastSpecializedFormUsed);
                    else
                        ChangeForm(modulesForms[0]);
//                     show_div(FORM + modulesForms[0]);
//                     document.getElementById('search_in').value = modulesForms[0];
                }
            }
            
            function HideAdvancedSearchForms()
            // Cache les champs de recherche avancée
            {
                HideForms();
                
                document.getElementById('searched_modules_DL').style.display = 'block';
                document.getElementById('forms_selection_DL').style.display = 'none';
                document.getElementById('search_in').value = 'all';
                
                hide_div('simple_search');
                show_div('advanced_search');
            }
            
            function HideForms()
            // Cache tous les résultats
            {
                for ( var i = 0; i < modulesForms.length; i++)
                {
                    hide_div(FORM + modulesForms[i]);
                }
            }
            
            function ChangeForm(module)
            // Change le cadre des résultats
            {
                HideForms();
                show_div(FORM + module);
                
                if ( LastSpecializedFormUsed != 'all' )
                {
                    document.getElementById(SPECIALIZED_FORM_LINK + LastSpecializedFormUsed).style.fontSize = '10px';
                    document.getElementById(SPECIALIZED_FORM_LINK + LastSpecializedFormUsed).className = 'small_link';
                }

                LastSpecializedFormUsed = module;
                document.getElementById('search_in').value = module;
                document.getElementById(SPECIALIZED_FORM_LINK + module).style.fontSize = '12px';
                document.getElementById(SPECIALIZED_FORM_LINK + module).className = 'small_link SpecializedFormLink';
            }
            
            function check_search_form_post()
            // Vérifie la validité du formulaire
            {
                var textSearched = document.getElementById("TxTsearched").value;
                
                if ( textSearched.length > 3 )
                {
                    textSearched = escape_xmlhttprequest(textSearched);
                    return true;
                }
                else
                {
                    alert('{L_WARNING_LENGTH_STRING_SEARCH}');
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
                <form id="search_form" action="{U_FORM_VALID}" onsubmit="return check_search_form_post();" method="post">
                    <fieldset class="SearchForm">
                        <legend>{L_TITLE_SEARCH}</legend>
                        <dl>
                            <dt><label for="TxTsearched">{L_SEARCH_KEYWORDS}<br /><span>{L_SEARCH_MIN_LENGTH}</span></label></dt>
                            <dd><label><input type="text" size="35" id="TxTsearched" name="search" value="{TEXT_SEARCHED}" class="search_field" /></label></dd>
                        </dl>
                        <dl id="searched_modules_DL" style="display:none">
                            <dt>
                                <label>{L_SEARCH_IN_MODULES}<br /><span>{L_SEARCH_IN_MODULES_EXPLAIN}</span></label>
                            </dt>
                            <dd>
                                <select id="searched_modules" name="searched_modules[]" size="5" multiple="multiple" class="list_modules">
                                # START searched_modules #
                                    <option value="{searched_modules.MODULE}" id="{searched_modules.MODULE}"{searched_modules.SELECTED}>{searched_modules.L_MODULE_NAME}</option>
                                # END searched_modules #
                                </select>
                            </dd>
                        </dl>
                        <dl>
                            <dt>
                                <label id="advanced_search" style="display:none">
                                    <a href="javascript:ShowAdvancedSearchForms();">{L_ADVANCED_SEARCH}</a>
                                </label>
                                <label id="simple_search" style="display:none">
                                    <a href="javascript:HideAdvancedSearchForms();">{L_SIMPLE_SEARCH}</a>
                                </label>
                            </dt>
                            <dd></dd>
                        </dl>
                        <div id="forms_selection_DL" style="text-align:center; display:none;">
                        <p class="text_center" style="font-weight:bold;">{L_SEARCH_SPECIALIZED_FORM}</p>
                        <p id="forms_selection">
                            # START forms #
                                <a id="specialize_form_link{forms.MODULE_NAME}" href="javascript:ChangeForm('{forms.MODULE_NAME}');" class="small_link">{forms.L_MODULE_NAME}</a>
                            # END forms #
                        </p>
                        </div>
                    # START forms #
                        <div id="form_{forms.MODULE_NAME}" class="SpecializedForm" style="display:none">
                            {forms.SEARCH_FORM}
                        </div>
                    # END forms #
                    </fieldset>
                    <fieldset class="fieldset_submit">
                        <legend>{L_SEARCH}</legend>
                        <input type="hidden" id="search_in" name="search_in" value="all" />
                        <input type="submit" id="search_submit" name="search_submit" value="{L_SEARCH}" class="submit" />
                    </fieldset>
                </form>
            </div>
            <div class="module_bottom_l"></div>
            <div class="module_bottom_r"></div>
            <div class="module_bottom" style="text-align:center;">{HITS}</div>
        </div>

        <script type="text/javascript">
        <!--
            // On cache les éléments ne devant pas s'afficher au début
            if ( LastSpecializedFormUsed == 'all' )
                HideAdvancedSearchForms();
            else
                ShowAdvancedSearchForms();
        -->
        </script>
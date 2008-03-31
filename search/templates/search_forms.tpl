        <script type="text/javascript">
        <!--
            const FORM = 'form_';
            var modulesForms = new Array();
            # START forms #
                modulesForms.push("{forms.MODULE_NAME}");
            # END forms #
            
            function GenerateListModules(changeF)
            // Met à jour la liste déroulante du choix du formulaire
            {
                if ( arguments.length < 1 )
                    changeF = false;
                
                var listModules = '';
                var nbForms = 0;
                var selModOptions = document.getElementById('searched_modules[]').options;
                
                for ( var i = 0; i < selModOptions.length; i++ )
                {
                    if ( selModOptions[i].selected && inArray(selModOptions[i].value, modulesForms) )
                    {
                        listModules += '<option value="' + selModOptions[i].value + '">' + selModOptions[i].text + '</option>';
                        nbForms++;
                    }
                }
                
                document.getElementById('forms_selection').innerHTML = listModules;

                
                if ( changeF )
                {
                    if ( nbForms > 0 )
                        show_div('forms_selection_DL');
                    else
                        hide_div('forms_selection_DL');
                    ChangeForm();
                }
            }
            
            function ShowAdvancedSearchForms()
            // Montre les champs de recherche avancée
            {
                HideAdvancedSearchForms();
                
                document.getElementById('searched_modules_DL').style.display = 'block';
                document.getElementById('forms_selection_DL').style.display = 'block';
                
                hide_div('advanced_search');
                show_div('simple_search');
                
                show_div(FORM + document.getElementById('forms_selection').value);
            }
            
            function HideAdvancedSearchForms()
            // Cache les champs de recherche avancée
            {
                HideForms();
                
                document.getElementById('searched_modules_DL').style.display = 'none';
                document.getElementById('forms_selection_DL').style.display = 'none';
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
            
            function ChangeForm()
            // Change le cadre des résultats
            {
                HideForms();
                var module = document.getElementById('forms_selection').value;
                show_div(FORM + module);
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
                            <dd><label><input type="text" size="35" id="TxTsearched" name="search" value="{TEXT_SEARCHED}" class="text" /></label></dd>
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
                        <dl id="searched_modules_DL" style="display:none">
                            <dt>
                                <label>{L_SEARCH_IN_MODULES}<br /><span>{L_SEARCH_IN_MODULES_EXPLAIN}</span></label>
                            </dt>
                            <dd>
                                <select id="searched_modules[]" name="searched_modules[]" size="5" multiple="multiple" class="list_modules">
                                # START searched_modules #
                                    <option value="{searched_modules.MODULE}" id="{searched_modules.MODULE}"{searched_modules.SELECTED} onclick="GenerateListModules(true)">{searched_modules.L_MODULE_NAME}</option>
                                # END searched_modules #
                                </select>
                            </dd>
                        </dl>
                        <dl id="forms_selection_DL" style="display:none">
                            <dt><label>{L_SEARCH_SPECIALIZED_FORM}<br /><span>{L_SEARCH_SPECIALIZED_FORM_EXPLAIN}</span></label></dt>
                            <dd>
                                <select id="forms_selection" name="FormsSelection" onchange="ChangeForm();" class="list_modules"></select>
                            </dd>
                        </dl>
                    </fieldset>
                    # START forms #
                        <div id="form_{forms.MODULE_NAME}" style="display:none">
                            <fieldset>
                                <legend>{forms.L_MODULE_NAME}</legend>
                                {forms.SEARCH_FORM}
                            </fieldset>
                        </div>
                    # END forms #
                    <fieldset class="fieldset_submit">
                        <legend>{L_SEARCH}</legend>
                        <input type="submit" name="search_submit" id="search_submit" value="{L_SEARCH}" class="submit" />
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
            show_div('advanced_search');
            GenerateListModules();
        -->
        </script>
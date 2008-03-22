        <script type="text/javascript">
        <!--
            const FORM = 'Form';
            var modulesForms = new Array();
            # START forms #
                modulesForms.push("{forms.MODULE_NAME}");
            # END forms #
            
            function GenerateListModules()
            // Met à jour la liste déroulante du choix du formulaire
            {
                var listModules = '';
                
                document.getElementById('FormsSelection').innerHTML = listModules;
                var selModOptions = document.getElementById('searched_modules[]').options;
                for ( var i = 0; i < selModOptions.length; i++ )
                {
                    if ( selModOptions[i].selected )
                        listModules += '<option value="' + selModOptions[i].value + '">' + selModOptions[i].text + '</option>';
                }
                alert(listModules);
                document.getElementById('FormsSelection').innerHTML = listModules;
            }
            
            function ShowAdvancedSearchForms()
            // Montre les champs de recherche avancée
            {
                HideAdvancedSearchForms();
                
                document.getElementById('searched_modules').style.visibility = 'visible';
                document.getElementById('searched_modules[]').style.visibility = 'visible';
                document.getElementById('FormsChoice').style.visibility = 'visible';
                
                hide_div('AdvancedSearch');
                show_div('SimpleSearch');
                
                show_div(FORM + document.getElementById('FormsChoice').value);
            }
            
            function HideAdvancedSearchForms()
            // Cache les champs de recherche avancée
            {
                HideForms();
                
                document.getElementById('searched_modules').style.visibility = 'hidden';
                document.getElementById('searched_modules[]').style.visibility = 'hidden';
                document.getElementById('FormsChoice').style.visibility = 'hidden';
                hide_div('SimpleSearch');
                show_div('AdvancedSearch');
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
                show_div(FORM + document.getElementById('FormsChoice').value);
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
            <div class="module_top">{L_TITLE}</div>
            <div class="module_contents">
                <div class="spacer">&nbsp;</div>
                <form id="SearchForm" action="{U_FORM_VALID}" onsubmit="return check_search_form_post();" method="post">
                    <table class="SearchForm">
                        <caption><span>{L_TITLE_SEARCH}</span></caption>
                        <tr>
                            <td><label for="TxTsearched">{L_SEARCH_MIN_LENGTH}</label></td>
                            <td><label><input type="text" size="35" id="TxTsearched" name="search" value="{TEXT_SEARCHED}"  class="text" /></label></td>
                            <td rowspan="2" style="text-align:center;">
                                <label id="searched_modules" style="visibility:hidden;">Modules sélectionnés</label><br />
                                <select id="searched_modules[]" name="searched_modules[]" size="5" multiple="multiple" class="list_modules" style="visibility:hidden">
                                # START searched_modules #
                                    <option value="{searched_modules.MODULE}" id="{searched_modules.MODULE}" selected="selected" onclick="GenerateListModules()">{searched_modules.L_MODULE_NAME}</option>
                                # END searched_modules #
                            </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="AdvancedSearch" style="display:none"><a href="javascript:ShowAdvancedSearchForms();">{L_ADVANCED_SEARCH}</a></label>
                                <label id="SimpleSearch" style="display:none"><a href="javascript:HideAdvancedSearchForms();">{L_SIMPLE_SEARCH}</a></label>
                            </td>
                            <td>
                                <select id="FormsChoice" name="FormsSelection" onchange="ChangeForm();" style="visibility:hidden;"></select>
                            </td>
                        </tr>
                    </table>
                    # START forms #
                        <div id="Form{forms.MODULE_NAME}" class="module_position" style="display:none">
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
            show_div('AdvancedSearch');
//             GenerateListModules();
        -->
        </script>
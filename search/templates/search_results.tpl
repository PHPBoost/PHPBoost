		<br />
		<script type="text/javascript">
		<!--
		    const RESULTS = 'Results';
		    const RESULTS_TITLE = 'ResultsTitle';
		    const INFOS_RESULTS = 'infosResults';
		    const RESULTS_LIST = 'ResultsList';
		    const PAGINATION_RESULTS = 'PaginationResults';
		    var modulesResults = new Array('All');
		    # START results #
		        modulesResults.push('{results.MODULE_NAME}');
		    # END results #
		    
		    var idSearch = new Array();
		    # START results #
		        idSearch['{results.MODULE_NAME}'] = '{results.ID_SEARCH}';
		    # END results #
		    
		    var calculatedResults = new Array('All');
		    
		    function HideResults()
		    // Cache tous les résultats
		    {
		        for( var i = 0; i < modulesResults.length; i++ )
		            hide_div(RESULTS + modulesResults[i]);
		    }
		    
		    function ChangeResults()
		    // Change le cadre des résultats
		    {
		        var module = document.getElementById('ResultsChoice').value;
		        HideResults();
		        show_div(RESULTS + module);
				if( !inArray(module, calculatedResults) )
				{
					load_progress_bar(20, '{THEME}', module);
		            XMLHttpRequest_search_module(module);
				}
			}
		    
		    function GetFormData()
		    // Reconstitution d'une chaine "POSTABLE" à partir des formulaires
		    {
		        var dataString = "";
		        var form = document.getElementById('SearchForm');
		        var elements = form.elements;
		        
		        for( var i = 0; i < form.length; i++ )
		        {
					if( elements[i].name )
		            {
		                dataString += elements[i].name + "=" + escape(elements[i].value);
						if( (i + 1) < form.length )
		                    dataString += "&";
		            }
		        }
		        
		        return data1;
		    }
		    
		    function XMLHttpRequest_regenerate_search()
		    // Affiche les résultats de la recherche pour le module particulier <module>
		    {
		        var xhr = xmlhttprequest_init('../search/searchXMLHTTPRequest.php');
		        xhr.onreadystatechange = function()
		        {
		        }
				//xmlhttprequest_sender(xhr, document.getElementById('SearchForm'));
		        xmlhttprequest_sender(xhr, GetFormData());
		    }
		    
		    function XMLHttpRequest_search_module(module)
		    // Affiche les résultats de la recherche pour le module particulier <module>
		    {
				var xhr_object = xmlhttprequest_init('../search/searchModuleXMLHTTPRequest.php?idSearch=' + idSearch[module] + '&pageNum={PAGE_NUM}');
		        xhr_object.onreadystatechange = function()
		        {
                    if( xhr_object.readyState == 1 )
                        progress_bar(25, "{L_QUERY_LOADING}");
                    else if( xhr_object.readyState == 2 )
                        progress_bar(50, "{L_QUERY_SENT}");
                    else if( xhr_object.readyState == 3 )
                        progress_bar(75, "{L_QUERY_PROCESSING}");
                    else if( xhr_object.readyState == 4 )
                    {
                        if( xhr_object.status == 200 )
                        {
                            progress_bar(100, "{L_QUERY_SUCCESS}");
                            // Si les résultats sont toujours en cache, on les récupère.
                            if( xhr_object.responseText != 'NO RESULTS IN CACHE' )
                            {
                                eval(xhr_object.responseText);
                                document.getElementById(INFOS_RESULTS + module).innerHTML = resultsAJAX['nbResults'];
                                document.getElementById(RESULTS_LIST + module).innerHTML = resultsAJAX['results'];
                                document.getElementById(PAGINATION_RESULTS + module).innerHTML = resultsAJAX['pagination'];
                                show_div('results' + module + '_0');
                                
                                // Met à jour la liste des résultats affiché, pour ne pas les rechercher
                                // dans la base de donnée si ils sont déjà dans le html.
                                calculatedResults.push(module);
                            }
                            else // Sinon, on les recalcule, et on les récupère.
                            {
                                alert('NO RESULTS IN CACHE');
                                XMLHttpRequest_regenerate_search();
                                XMLHttpRequest_search_module(module);
                            }
                        }
                        else
                            progress_bar(99, "{L_QUERY_FAILURE}");
                    }
		        }
		        xmlhttprequest_sender(xhr_object, null);
		    }
		-->
		</script>

		<div id="results" class="module_position">
		    <div class="module_top_l"></div>
		    <div class="module_top_r"></div>
		    <div class="module_top">{SEARCH_RESULTS}
		        <div id="resultsChoices" class="resultsChoices" style="display:none">
		            <span>{PRINT}</span>
		            <select id="ResultsChoice" name="ResultsSelection" onChange="ChangeResults();">
		                <option value="All">{TITLE_ALL_RESULTS}</option>
		                # START results #
		                    <option value="{results.MODULE_NAME}">---> {results.MODULE_NAME}</option>
		                # END results #
		            </select>
		        </div>
		    </div>
		    <div class="module_contents">
		        <div id="ResultsAll" class="results">
		            <span id="ResultsTitleAll" class="title">{TITLE_ALL_RESULTS}</span><br />
		            <div id="infosResultsAll" class="infosResults">{NB_RESULTS} {NB_RESULTS_FOUND}</div>
		            <div id="ResultsListAll" class="ResultsList">
                        {ALL_RESULTS}
		            </div>
		            <div id="PaginationResultsAll" class="PaginationResults">{PAGINATION}</div>
		        </div>
		        # START results #
		            <div id="Results{results.MODULE_NAME}" class="results" style="display:none">
		                <span id="ResultsTitle{results.MODULE_NAME}" class="title">{results.MODULE_NAME}</span><br />
		                <div id="infosResults{results.MODULE_NAME}" class="infosResults">
                            <div style="margin:auto;width:500px;"> 
                                <div id="progress_info{results.MODULE_NAME}" style="text-align:center;"></div>
                                <div id="progress_bar{results.MODULE_NAME}" style="float:left;height:12px;border:1px solid black;background:white;width:448px;padding:2px;padding-left:3px;padding-right:1px;"></div> 
                                &nbsp;<span id="progress_percent{results.MODULE_NAME}">0</span>%
                            </div>
                        </div>
		                <div id="ResultsList{results.MODULE_NAME}" class="ResultsList"></div>
		                <div id="PaginationResults{results.MODULE_NAME}" class="PaginationResults"></div>
		            </div>
		        # END results #
		    </div>
		    <div class="module_bottom_l"></div>
		    <div class="module_bottom_r"></div>
		    <div class="module_bottom" style="text-align:center;">{HITS}</div>
		</div>
		<script type="text/javascript">
		<!--
		   if( browserAJAXFriendly() )
		        show_div('resultsChoices');
		-->
		</script>

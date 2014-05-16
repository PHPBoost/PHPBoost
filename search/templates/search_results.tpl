		<script><!--
		
			const RESULTS = 'results_';
			const RESULTS_TITLE = 'results_title_';
			const INFOS_RESULTS = 'infos_results_';
			const RESULTS_LIST = 'results_list_';
			const PAGINATION_RESULTS = 'pagination_results_';
			const NB_RESULTS_PER_PAGE = {NB_RESULTS_PER_PAGE};
			
			var nbResults = new Array();
			nbResults['{SEARCH_IN}'] = {NB_RESULTS};
			
			# IF C_SIMPLE_SEARCH #
				var modulesResults = new Array('all');
				var idSearch = new Array();
				# START results #
					modulesResults.push('{results.MODULE_NAME}');
					idSearch['{results.MODULE_NAME}'] = '{results.ID_SEARCH}';
				# END results #
				
				var calculatedResults = new Array('all');
				
				function HideResults()
				// Cache tous les résultats
				{
					for( var i = 0; i < modulesResults.length; i++ )
						hide_div(RESULTS + modulesResults[i]);
				}
				
				function ChangeResults()
				// Change le cadre des résultats
				{
					var module = document.getElementById('results_choice').value;
					HideResults();
					show_div(RESULTS + module);
					if (!inArray(module, calculatedResults))
					{
						XMLHttpRequest_search_module(module);
					}
				}
				
				function GetFormData()
				// Reconstitution d'une chaine "POSTABLE" à partir des formulaires
				{
					var dataString = "";
					var form = document.getElementById('mini-search-form');
					var elements = form.elements;
					
					for (var i = 0; i < form.length; i++)
					{
						if (elements[i].name)
						{
							dataString += elements[i].name.replace('[', '%5B').replace(']', '%5D') + '=';
							if (elements[i].name.indexOf('[]') > 0)
							{   // Cas des multi-sélections
								selectedChilds = new Array();
								for (var j = 0; j < elements[i].length; j++)
								{   // On ajoute tous les fils sélectionnés
									if (elements[i].options[j].selected)
										selectedChilds.push(escape_xmlhttprequest(elements[i].options[j].value));
								}

								dataString += selectedChilds.join('&' + elements[i].name.replace('[', '%5B').replace(']', '%5D') + '='); 
							}
							else
							{
								dataString += escape_xmlhttprequest(elements[i].value);
							}
							if ((i + 1) < form.length)
								dataString += "&";
						}
					}
					return dataString;
				}
				
				function XMLHttpRequest_search_module(module)
				// Affiche les résultats de la recherche pour le module particulier <module>
				{
					var xhr_object = xmlhttprequest_init('../search/searchXMLHTTPRequest.php?token={TOKEN}');
					xhr_object.onreadystatechange = function()
					{
						if( xhr_object.readyState == 1 )
							change_progressbar('progress_bar_' + module, 25, "{L_QUERY_LOADING}");
						else if( xhr_object.readyState == 2 )
							change_progressbar('progress_bar_' + module, 50, "{L_QUERY_SENT}");
						else if( xhr_object.readyState == 3 )
							change_progressbar('progress_bar_' + module, 75, "{L_QUERY_PROCESSING}");
						else if( xhr_object.readyState == 4 )
						{
							if( xhr_object.status == 200 )
							{
								change_progressbar('progress_bar_' + module, 100, "{L_QUERY_SUCCESS}");
								// Si les rÃ©sultats sont toujours en cache, on les récupère.
								eval(xhr_object.responseText);
								if( !syncErr )
								{
									document.getElementById(INFOS_RESULTS + module).innerHTML = resultsAJAX['nbResults'];
									document.getElementById(RESULTS_LIST + module).innerHTML = resultsAJAX['results'];
									ChangePagination(0, Math.ceil(nbResults[module] / NB_RESULTS_PER_PAGE), PAGINATION_RESULTS + module, RESULTS + module, 2, 2);
									
									// Met à jour la liste des résultats affiché, pour ne pas les rechercher
									// dans la base de donnée si ils sont déjà dans le html.
									calculatedResults.push(module);
								}
								else window.alert('SYNCHRONISATION ERROR');
							}
							else
								change_progressbar('progress_bar_' + module, 99, "{L_QUERY_FAILURE}");
						}
					}
					xmlhttprequest_sender(xhr_object, GetFormData() + '&moduleName=' + module + '&idSearch=' + idSearch[module]);
				}
			# ENDIF #
		
		--></script>

		<section id="results">
		   <header>
				<h1>{L_SEARCH_RESULTS}</h1>
				# IF C_SIMPLE_SEARCH #
					<div id="results_choices" class="resultsChoices" style="display:none">
						<span>{L_PRINT}</span>
						<select id="results_choice" name="ResultsSelection" onchange="ChangeResults();">
							<option value="all">{L_TITLE_ALL_RESULTS}</option>
							# START results #
								<option value="{results.MODULE_NAME}"> --&gt; {results.L_MODULE_NAME}</option>
							# END results #
						</select>
					</div>
				# ENDIF #
			</header>
			<div class="content">
				<div id="results_{SEARCH_IN}" class="results">
					<span id="results_title_{SEARCH_IN}" class="title">{L_TITLE_ALL_RESULTS}</span><br />
					<div id="infos_results_{SEARCH_IN}" class="infosResults">
						# IF NB_RESULTS #
							{NB_RESULTS} 
						# ENDIF #
						{L_NB_RESULTS_FOUND}
					</div>
					<div id="results_list_{SEARCH_IN}" class="ResultsList">
						{ALL_RESULTS}
					</div>
					<div id="pagination_results_{SEARCH_IN}" class="PaginationResults"></div>
				</div>
				# IF C_SIMPLE_SEARCH #
					# START results #
						<div id="results_{results.MODULE_NAME}" class="results" style="display:none">
							<span id="results_title_{results.MODULE_NAME}" class="title">{results.L_MODULE_NAME}</span><br />
							<div id="infos_results_{results.MODULE_NAME}" class="infosResults">
								# IF NB_RESULTS #
									{NB_RESULTS}
								# ENDIF #
								{L_NB_RESULTS_FOUND}
								<div style="margin:auto;width:500px;">
									<div id="progress_bar_{results.MODULE_NAME}" class="progressbar-container">
										<span class="progressbar-infos"></span>
										<div class="progressbar"></div>
									</div>
								</div>
							</div>
							<div id="results_list_{results.MODULE_NAME}" class="ResultsList"></div>
							<div id="pagination_results_{results.MODULE_NAME}" class="PaginationResults"></div>
						</div>
					# END results #
				# ENDIF #
			</div>
			<footer>{L_HITS}</footer>
		</section>
		<script>
		<!--
			ChangePagination(0, Math.ceil(nbResults['{SEARCH_IN}'] / NB_RESULTS_PER_PAGE), PAGINATION_RESULTS + '{SEARCH_IN}', 'results_{SEARCH_IN}');
			show_div(RESULTS + '{SEARCH_IN}_0');

			if( browserAJAXFriendly() )
				show_div('results_choices');
		-->
		</script>

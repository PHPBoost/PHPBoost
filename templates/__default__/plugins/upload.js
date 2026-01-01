//Tableau contenant l'état de chaque catégorie
var cat_status = new Array();

//AJAX: fonction d'interaction avec le serveur
function show_cat_contents(id_cat, display_select_link)
{
	var xhr_object = null;
	var data = null;
	var filename = PATH_TO_ROOT + "/user/xmlhttprequest.php?token=" + TOKEN + (display_select_link != 0 ? "&display_select_link=1" : "");

	if(window.XMLHttpRequest) // Firefox
		xhr_object = new XMLHttpRequest();
	else if(window.ActiveXObject) // Internet Explorer
		xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	else // XMLHttpRequest non supporté par le navigateur
		return;

	if( id_cat > 0 )
	{
		//Si le dossier est fermé on l'ouvre sinon on le ferme
		if( cat_status[id_cat] == undefined  )
		{
			data = "id_cat=" + id_cat;
			xhr_object.open("POST", filename, true);

			xhr_object.onreadystatechange = function()
			{
				if( xhr_object.readyState == 4 )
				{
					document.getElementById("cat_" + id_cat).innerHTML = xhr_object.responseText;
					document.getElementById("img_" + id_cat).className = 'fa fa-folder-open';
					if( document.getElementById("img2_" + id_cat) )
						document.getElementById("img2_" + id_cat).className = 'far fa-minus-square';

					cat_status[id_cat] = 1;
				}
			}

			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(data);
		}
		else if( cat_status[id_cat] == 0 )
		{
			document.getElementById("cat_" + id_cat).style.display = 'block';
			document.getElementById("img_" + id_cat).className = 'fa fa-folder-open';
			if( document.getElementById("img2_" + id_cat) )
				document.getElementById("img2_" + id_cat).className = 'far fa-minus-square';
			cat_status[id_cat] = 1;
		}
		else
		{
			document.getElementById("cat_" + id_cat).style.display = 'none';
			document.getElementById("img_" + id_cat).className = 'fa fa-folder';
			if( document.getElementById("img2_" + id_cat) )
				document.getElementById("img2_" + id_cat).className = 'far fa-plus-square';
			cat_status[id_cat] = 0;
		}
	}
}

function select_cat(id_cat)
{
	var xhr_object = null;
	var data = null;
	var filename = PATH_TO_ROOT + "/user/xmlhttprequest.php?select_cat=1&token=" + TOKEN;

	if(window.XMLHttpRequest) // Firefox
		xhr_object = new XMLHttpRequest();
	else if(window.ActiveXObject) // Internet Explorer
		xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	else // XMLHttpRequest non supporté par le navigateur
		return;

	if( id_cat >= 0 && id_cat != selected_cat )
	{
		data = "selected_cat=" + id_cat;

		xhr_object.open("POST", filename, true);

		xhr_object.onreadystatechange = function()
		{
			if( xhr_object.readyState == 4 )
			{
				document.getElementById("id_cat").value = id_cat;
				document.getElementById("class-" + id_cat).className = "upload-selected-cat";
				document.getElementById("class-" + selected_cat).className = "";
				selected_cat = id_cat;
			}
		}

		xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		xhr_object.send(data);
	}
}

//Construit et affiche un lecteur vidéo de type flowplayer
function flowPlayerBuild(id) {
	//Si la fonction n'existe pas, on attend qu'elle soit interprétée
	if (!functionExists('flowplayer'))
	{
		setTimeout('flowPlayerBuild(\'' + id + '\')', 100);
		return;
	}
	//On lance le flowplayer
	flowplayer(id, PATH_TO_ROOT + '/kernel/data/flowplayer/flowplayer-3.0.3.swf', { 
		    clip: { 
		        url: $(id).href,
		        autoPlay: false 
		    }
	    }
	);
}
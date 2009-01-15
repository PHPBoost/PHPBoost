function flowPlayerBuild(id) {
	flowplayer(id, PATH_TO_ROOT + '/kernel/data/flowplayer/flowplayer-3.0.3.swf', { 
		    clip: { 
		        url: $(id).href,
		        autoPlay: false 
		    }
	    }
	);
}
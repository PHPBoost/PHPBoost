tinyMCEPopup.requireLangPack();

var EmotionsDialog = {
	init : function(ed) {
		tinyMCEPopup.resizeToInnerSize();
	},

	insert : function(file, title) {
		tinyMCEPopup.execCommand('mceInsertContent', false, '<img src="../images/smileys/' + file + '" alt="' + title + '" class="smiley" style="vertical-align:middle" />');

		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(EmotionsDialog.init, EmotionsDialog);

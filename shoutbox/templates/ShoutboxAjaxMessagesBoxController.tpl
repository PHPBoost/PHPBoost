# START messages #
<p id="shoutbox-message-{messages.ID}"># IF C_DISPLAY_DATE #<span class="small"> {messages.DATE} : </span># ENDIF #<span class="small"># IF messages.C_DELETE #<a href="" onclick="return false;" title="${LangLoader::get_message('delete', 'common')}" id="delete_{messages.ID}" class="fa fa-remove"></a>
<script>
<!--
Event.observe(window, 'load', function() {
	$('delete_{messages.ID}').observe('click',function(){
		shoutbox_delete_message({messages.ID});
	});
});
-->
</script># ENDIF # # IF messages.C_AUTHOR_EXIST #<a href="{messages.U_AUTHOR_PROFILE}" class="{messages.USER_LEVEL_CLASS}" # IF messages.C_USER_GROUP_COLOR # style="color:{messages.USER_GROUP_COLOR}" # ENDIF #>{messages.PSEUDO}</a># ELSE #<span style="font-style: italic;">{messages.PSEUDO}</span># ENDIF # : {messages.CONTENTS}</span></p>
# END messages #
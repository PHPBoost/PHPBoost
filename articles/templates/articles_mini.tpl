 <script type="text/javascript">
<!--
function mini_menu_articles(id)
{
	if(document.getElementById("ancien_id").value != "")
	{
		hide_div(document.getElementById("ancien_id").value);
		document.getElementById("block_"+document.getElementById("ancien_id").value).style.background="";
	}
	if(document.getElementById(id).style.display == "none")
	{
		show_div(id);
		document.getElementById("block_"+id).style.background="#d5e0eb";
		document.getElementById("block_"+id).style.color="#000000";
		document.getElementById("ancien_id").value = id;
	}
	else
		hide_div(id);
}
-->
</script>
<div class="module_mini_container">
	<div class="module_mini_top">
		<h5 class="sub_title">{L_TYPE_MINI}</h5>
	</div>
	<div class="module_mini_contents" style="margin-top:10px;text-align:center;border:1px solid #f4f4f4;">							
		# START articles #		
		<div id="block_articles_{articles.ID}" style="margin:-10px;padding:10px;margin-bottom:0px;margin-top:-2px;padding-bottom:0px;padding-top:0px">	
		<p style="padding-left:6px;padding-top:0px;text-align: left;">
			<span onmouseover="mini_menu_articles('articles_{articles.ID}');">
				<a style="color:#000000;font-size:10px;text-decoration:none"  href="../articles/articles{articles.U_ARTICLES_LINK}"> {articles.TITLE}</a></span>
			<br />
			<div id="articles_{articles.ID}" class="text_small" style="margin-top:0px;padding-left:6px;text-align:justify;display:none">
				{articles.DESCRIPTION}
				<br />
				<div style="margin-top:5px;">
					<div style="float:left">
					<a style="font-size:10px;text-decoration:none"  href="../articles/articles{articles.U_ARTICLES_LINK}"> {READ_ARTICLE} ...</a>
					</div>
					<div style="float:right">
						{articles.NOTE}
						{articles.VIEW}
						{articles.COM}
						{articles.DATE}
					</div>
				</div>
				<div class="spacer"></div>
			</div>
		<p/>
		<hr/>
		</div>
		# END articles #
	</div>		
	<div class="module_mini_bottom">
	<p style="text-align:center;margin-top:5px;"><a href="../articles/articles.php">{L_MORE_ARTICLE}</a></p>
	</div>
</div>
<input type="hidden" id="ancien_id" value="" />




				
			
<?php
/**
* panel.tpl
*
* @author 		alain091	
* @copyright	(c) 2009 alain091
* @license		GPL
*
*/
?>
<table>
<tbody>
	<tr>
		<td colspan="2">
			# START top #
			<div class="news_container">			
			<div class="news_top_l"></div>		
			<div class="news_top_r"></div>
			<div class="news_top">
				# IF top.C_FEED #
				<span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
					<img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
				</span>&nbsp;{top.GET_FEED_MENU}
				# ENDIF #
				<h3 class="title valign_middle">{top.NAME}</h3>
			</div>
			<div class="news_contents">
				{top.GET_CONTENT}
			</div>
			</div>
			# END top #
			&nbsp;
		</td>
	</tr>
	<tr>
		<td style="width:50%;">
			# START aboveleft #
			<div class="news_container">			
			<div class="news_top_l"></div>		
			<div class="news_top_r"></div>
			<div class="news_top">
				# IF aboveleft.C_FEED #
				<span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
					<img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
				</span>&nbsp;{aboveleft.GET_FEED_MENU}
				# ENDIF #
				<h3 class="title valign_middle">{aboveleft.NAME}</h3>
			</div>
			<div class="news_contents">
				{aboveleft.GET_CONTENT}
			</div>
			</div>
			# END aboveleft #
			&nbsp;
		</td>
		<td style="width:50%;">
			# START aboveright #
			<div class="news_container">			
			<div class="news_top_l"></div>		
			<div class="news_top_r"></div>
			<div class="news_top">
				# IF aboveright.C_FEED #
				<span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
					<img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
				</span>&nbsp;{aboveright.GET_FEED_MENU}
				# ENDIF #
				<h3 class="title valign_middle">{aboveright.NAME}</h3>
			</div>
			<div class="news_contents">
				{aboveright.GET_CONTENT}
			</div>
			</div>
			# END aboveright #
			&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="2">
			# START center #
				# IF center.C_FEED #
			<div class="news_container">			
			<div class="news_top_l"></div>		
			<div class="news_top_r"></div>
			<div class="news_top">
				<span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
					<img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
				</span>&nbsp;{center.GET_FEED_MENU}
				<h3 class="title valign_middle">{center.NAME}</h3>
			</div>
			<div class="news_contents">
				{center.GET_CONTENT}
			</div>
			</div>
				# ELSE #
				{center.GET_CONTENT}
				# ENDIF #
			# END center #
			&nbsp;
		</td>
	</tr>
	<tr>
		<td style="width:50%;">
			# START belowleft #
			<div class="news_container">			
			<div class="news_top_l"></div>		
			<div class="news_top_r"></div>
			<div class="news_top">
				# IF belowleft.C_FEED #
				<span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
					<img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
				</span>&nbsp;{belowleft.GET_FEED_MENU}
				# ENDIF #
				<h3 class="title valign_middle">{belowleft.NAME}</h3>
			</div>
			<div class="news_contents">
				{belowleft.GET_CONTENT}
			</div>
			</div>
			# END belowleft #
			&nbsp;
		</td>
		<td style="width:50%;">
			# START belowright #
			<div class="news_container">			
			<div class="news_top_l"></div>		
			<div class="news_top_r"></div>
			<div class="news_top">
				# IF belowright.C_FEED #
				<span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
					<img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
				</span>&nbsp;{belowright.GET_FEED_MENU}
				# ENDIF #
				<h3 class="title valign_middle">{belowright.NAME}</h3>
			</div>
			<div class="news_contents">
				{belowright.GET_CONTENT}
			</div>
			</div>
			# END belowright #
			&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="2">
			# START bottom #
			<div class="news_container">			
			<div class="news_top_l"></div>		
			<div class="news_top_r"></div>
			<div class="news_top">
				# IF bottom.C_FEED #
				<span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
					<img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
				</span>&nbsp;{bottom.GET_FEED_MENU}
				# ENDIF #
				<h3 class="title valign_middle">{bottom.NAME}</h3>
			</div>
			<div class="news_contents">
				{bottom.GET_CONTENT}
			</div>
			</div>
			# END bottom #
			&nbsp;
		</td>
	</tr>
</tbody>
</table>

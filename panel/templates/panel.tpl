<?php
/**
* panel.tpl
*
* @author alain091	
* @copyright (c) 2009 alain091
* @license GPL
*
*/
?>
<div>
		<div style="clear:both;width:100%;border:1px solid;">
		# START top #
		<div>
            <div>
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                {top.GET_FEED_MENU}
                <h3 class="title valign_middle">{top.NAME}</h3>
            </div>
            <div>
                {top.GET_CONTENT}
            </div>
        </div>
		# END top #
		</div>
		
		<div style="float:left;width:50%;border:1px solid;">
		# START aboveleft #
		<div>
            <div>
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                {aboveleft.GET_FEED_MENU}
                <h3 class="title valign_middle">{aboveleft.NAME}</h3>
            </div>
            <div>
                {aboveleft.GET_CONTENT}
            </div>
        </div>
		<br />
		# END aboveleft #
		</div>
		
		<div style="float:right;width:50%;border:1px solid;">
		# START aboveright #
		<div>
            <div>
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                {aboveright.GET_FEED_MENU}
                <h3 class="title valign_middle">{aboveright.NAME}</h3>
            </div>
            <div>
                {aboveright.GET_CONTENT}
            </div>
        </div>
		<br />
		# END aboveright #
		</div>
		
		<div style="clear:both;width:100%;border:1px solid;">
		# START center #
		<div>
            <div>
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                {center.GET_FEED_MENU}
                <h3 class="title valign_middle">{center.NAME}</h3>
            </div>
            <div>
                {center.GET_CONTENT}
            </div>
        </div>
		<br />
		# END center #
		</div>
		
		<div style="float:left;width:50%;border:1px solid;">
		# START belowleft #
		<div>
            <div>
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                {belowleft.GET_FEED_MENU}
                <h3 class="title valign_middle">{belowleft.NAME}</h3>
            </div>
            <div>
                {belowleft.GET_CONTENT}
            </div>
        </div>
		<br />
		# END belowleft #
		</div>

		<div style="float:right;width:50%;border:1px solid;">
		# START belowright #
		<div>
            <div>
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                {belowright.GET_FEED_MENU}
                <h3 class="title valign_middle">{belowright.NAME}</h3>
            </div>
            <div>
                {belowright.GET_CONTENT}
            </div>
        </div>
		<br />
		# END belowright #
		</div>
		
		<div style="clear:both;width:100%;border:1px solid;">
		# START bottom #
		<div>
            <div>
                <span style="float:left;padding-left:5px;" onmouseover="ShowSyndication(this)">
                    <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
                </span>&nbsp;
                {bottom.GET_FEED_MENU}
                <h3 class="title valign_middle">{bottom.NAME}</h3>
            </div>
            <div>
                {bottom.GET_CONTENT}
            </div>
        </div>
		<br />
		# END bottom #
		</div>
</div>
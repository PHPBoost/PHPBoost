<?php
if( $BLOCK_top && $session->data['level'] >= -1 ){include_once('../connect/connect_mini.php');}
if( $BLOCK_top && $session->data['level'] >= -1 ){include_once('../links/links_mini.php');}
if( $BLOCK_top && $session->data['level'] >= -1 ){include_once('../newsletter/newsletter_mini.php');}
if( $BLOCK_top && $session->data['level'] >= -1 ){include_once('../stats/stats_mini.php');}
if( $BLOCK_bottom && $session->data['level'] >= -1 ){include_once('../gallery/gallery_mini.php');}
if( $BLOCK_bottom && $session->data['level'] >= -1 ){include_once('../online/online_mini.php');}
if( $BLOCK_bottom && $session->data['level'] >= -1 ){include_once('../poll/poll_mini.php');}
if( $BLOCK_bottom && $session->data['level'] >= -1 ){include_once('../shoutbox/shoutbox_mini.php');}

?>
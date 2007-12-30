<?php
global $CONFIG_POLL;
$CONFIG_POLL['poll_auth'] = -1;
$CONFIG_POLL['poll_mini'] = -1;
$CONFIG_POLL['poll_cookie'] = 'poll';
$CONFIG_POLL['poll_cookie_lenght'] = 1800000;

global $_mini_poll, $_poll_type, $_question_poll, $_total_vote, $_array_poll;

$_array_poll = array();

$_poll_type = '0';
$_total_vote = '0';
$_question_poll = '';
?>
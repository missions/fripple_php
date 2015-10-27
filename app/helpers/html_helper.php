<?php
function eh($string)
{
    if (!isset($string)) return;
    echo htmlspecialchars($string, ENT_QUOTES);
}
function readable_text($s)
{
    $s = htmlspecialchars($s, ENT_QUOTES);
    $s = nl2br($s);
    return $s;
}
function convert_session(AccountSession $session_info)
{
    return array(
        'id'        => $session_info->id,
        'user_id'   => (int) $session_info->user_id,
        'device_id' => $session_info->device_id
    );
}
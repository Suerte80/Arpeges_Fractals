<?php

function addNotification($type, $message): void{
    $_SESSION['notifications'][] = array('type' => $type, 'message' => $message);
}
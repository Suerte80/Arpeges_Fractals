<?php

function addNotification($type, $message): void
{
    $_SESSION['notifications'][] = array('type' => $type, 'message' => $message);
}

function valid_donnees($donnees)
{
    $donnees = trim($donnees);
    $donnees = stripslashes($donnees);
    $donnees = htmlspecialchars($donnees);
    return $donnees;
}

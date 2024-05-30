<?php

function getInitials($fullname) {
    $words = explode(" ", $fullname); // Split the full name into an array of words
    $initials = ''; // Initialize variable to store initials

    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1)); // Append the first character of each word to initials
    }

    return $initials;
}

<?php

function startsWith($hastack, $needle) {
    return !strncmp($haystack, $needle, strlen($needle));
}

function endsWith($haystack, $needle) {
    return substr($haystack, -strlen($needle)) === $needle;
}

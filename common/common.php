<?php
session_start();

// Header Setting
header("Expires: Mon 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d, M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0; pre-check=0", false);
header("Pragma: no-cache");
//header("Content-type: charset=utf-8");
//header("Content-type: charset=utf-8, application/json");
header("Content-type: text/html; charset=utf-8");

// Include File
include_once $_SERVER['DOCUMENT_ROOT'] . '/common/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/common/function.php';
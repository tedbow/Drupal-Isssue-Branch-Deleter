<?php
require_once "vendor/autoload.php";
use GitDeleter\BranchDeleter;

$d = new BranchDeleter();
$d->deleteBranches();
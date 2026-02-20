<?php
require_once __DIR__ . '/../includes/helpers.php';

session_destroy();
redirect('/admin/login.php');

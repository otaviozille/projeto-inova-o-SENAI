<?php
session_start();
session_destroy();
header('Location: ../login/login.html');
exit();
?>
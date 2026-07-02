<?php
session_start();

if (!empty($_SESSION['thal_logged_in'])) {
    header('Location: app.php');
} else {
    header('Location: login.php');
}
exit;

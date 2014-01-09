<?php
    if ( !isset($_SESSION) ) session_start();
    $_SESSION['current_user'] = array ('id' => 1, 'title' => 'Администратор', 'access' => '4');
    header('Location: /');
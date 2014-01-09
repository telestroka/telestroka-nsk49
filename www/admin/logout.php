<?php
    if ( !isset($_SESSION) ) session_start();
    $_SESSION['current_user'] = array ('id' => 0, 'title' => 'Гость', 'access' => '1');
    header('Location: /');
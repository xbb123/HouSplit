<?php
    if ($_POST['login']) {
        // Set up MySQL connection
        $db_handler = mysql_connect($mysql_server, $mysql_username, $mysql_password);
        $db_found = mysql_select_db($mysql_db, $db_handler);

        // Get data from POST
        $user = $_POST['username'];
        $pass = $_POST['password'];

        if (empty($user)) {
            echo '<div class="title">' . $lang['error'] . '</div>';
            echo $lang['error_type_user_login'];
        } elseif (empty($pass)) {
            echo '<div class="title">' . $lang['error'] . '</div>';
            echo $lang['error_type_pw_login'];
        } else {
            // (Try to) Select row with user's info 
            $db_info = mysql_query("SELECT * FROM members WHERE username = '$user'");
            if ($db_info) {
                // Turns query's result into an array with its info
                $db_info = mysql_fetch_assoc($db_info);

                // Hash password
                $pass = hash_pbkdf2("sha256", $pass, $db_info['salt'], $crypt_iterations, 20);

                if ($pass == $db_info['password']) {
                    // Set username and userip of the session
                    $_SESSION['username'] = $user;
                    $_SESSION['userip'] = $_SERVER['REMOTE_ADDR'];

                    // Redirect user to main page
                    header("Location: index.php");
                }
            } else {
                echo '<div class="title">' . $lang['error'] . '</div>';
                echo $lang['error_invalid_user'];
            }

            echo '<div class="title">' . $lang['error'] . '</div>';
            echo $lang['error_user_pw_match'];
        }
   }
?>
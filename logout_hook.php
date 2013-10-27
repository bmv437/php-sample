<?php

include_once('config.php');

    if(isset($_POST['logout_token'])) {

        $postdata = http_build_query(
            array(
                'logout_token' => $_REQUEST['logout_token'],
                'app_id' => $app_id,
                'app_secret' => $app_secret
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);
        $response = file_get_contents($clef_base_url."logout", false, $context);

        $response = json_decode($response, true);

        if (isset($response['success']) && isset($response['clef_id'])) {

            $mysql = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD);

            $clef_id = $response['clef_id'];

            // log user out in the DB!
            $now = time();
            mysqli_query($mysql, "UPDATE {$DB_NAME}.users SET logged_out_at={$now} WHERE clef_id='{$clef_id}';");
        }
    }
?>

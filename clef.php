<?php

include_once('config.php');

if (!session_id())
    session_start();

if (isset($_GET["code"]) && $_GET["code"] != "") {
    $code = $_GET["code"];
    $postdata = http_build_query(
        array(
            'code' => $code,
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

    // get oauth code for the handshake
    $context  = stream_context_create($opts);
    $response = file_get_contents($clef_base_url."authorize", false, $context);

    if($response) {
        $response = json_decode($response, true);

        // if there's an error, Clef's API will report it
        if(!isset($response['error'])) {
            $access_token = $response['access_token'];

            $opts = array('http' =>
                array(
                    'method'  => 'GET'
                )
            );

            $url = $clef_base_url."info?access_token=".$access_token;

            // exchange the oauth token for the user's info
            $context  = stream_context_create($opts);
            $response = file_get_contents($url, false, $context);
            if($response) {
                $response = json_decode($response, true);

                // again, make sure nothing went wrong
                if(!isset($response['error'])) {

                    $result = $response['info'];

                    // reset the user's session
                    if (isset($result['id'])&&($result['id']!='')) {
                        //remove all the variables in the session
                        session_unset();
                        // destroy the session
                        session_destroy();
                        if (!session_id())
                            session_start();

                        $_SESSION['name']     = $result['first_name'].' '.$result['last_name'];
                        $_SESSION['email']    = $result['email'];
                        $_SESSION['user_id']  = $result['id'];
                        $_SESSION['logged_in_at'] = time();  // timestamp in unix time

                        $clef_id = $result['id'];

                        // replace "root" and "root" with your own database's username and password
                        $mysql = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD);

                        $name = mysqli_escape_string($mysql,$result['first_name']);
                        $query = "SELECT * FROM {$DB_NAME}.users WHERE clef_id='{$clef_id}'";

                        if($response = mysqli_query($mysql, $query)) {
                            $rows = mysqli_fetch_assoc($response);

                            // if the user is new, register them 
                            if(sizeof($rows) == 0) {
                                $query = "INSERT INTO {$DB_NAME}.users (clef_id, name) VALUES ('{$clef_id}', '{$name}');";

                                $response = mysqli_query($mysql, $query);
                            }
                        }

                        

                        // send them to the member's area!
                        header("Location: members_area.php");
                    }
                } else {
                    echo "Log in with Clef failed, please try again.";
                }
            }
        } else {
            echo "Log in with Clef failed, please try again.";
        }
        
    } else {
        echo "Log in with Clef failed, please try again.";
    }
}
?>


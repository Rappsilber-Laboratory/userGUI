<?php
session_start();
include ('utils.php');
if (empty ($_SESSION['session_name'])) {
    // from http://stackoverflow.com/questions/199099/how-to-manage-a-redirect-request-after-a-jquery-ajax-call
    ajaxLoginRedirect();
}
else {

    include('../../connectionString.php');

    try {
        //open connection
        $dbconn = pg_connect($connectionString);
        
        $isSuperUser = isSuperUser ($dbconn, $_SESSION['user_id']);
        
        if ($isSuperUser) {
             pg_prepare ($dbconn, "allUserInfo", "SELECT id, user_name, see_all, can_add_search, super_user, email FROM users");
             $result = pg_execute($dbconn, "allUserInfo", []);
        } else {
             pg_prepare ($dbconn, "singleUserInfo", "SELECT id, user_name, email FROM users WHERE id = $1");
             $result = pg_execute($dbconn, "singleUserInfo", [$_SESSION['user_id']]);
        }
        
        $returnedData = pg_fetch_all ($result);
        
        //error_log(print_r($returnedData, true));
             
        //close connection
        pg_close($dbconn);

        echo json_encode (array ("status" => "success", "data" => $returnedData, "superuser" => $isSuperUser, "userid" => $_SESSION["user_id"]));
    }
    catch (Exception $e) {
        $date = date("d-M-Y H:i:s");
        echo (json_encode (array ("error" => "Error when querying database for user<br>".$date)));
    }
}

?>
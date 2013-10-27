<?php
include_once('config.php');
?>
<!-- =======================================================-->
<!DOCTYPE html>
<html>
<head>
<title>PHP Sample</title>
</head>
<body>
<script src='https://clef.io/v2/clef.js' class='clef-button' data-app-id='<?=$app_id?>' data-redirect-url='<?=$redirect_url?>'></script>

<a href="https://clef.io/iframes/qr?app_id=<?=$app_id?>&redirect_url=<?=$redirect_url?>" class="clef">Log in with Clef</a>
</body>
</html>

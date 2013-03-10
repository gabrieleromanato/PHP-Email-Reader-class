<?php require_once('EmailReader.php'); ?>
<!doctype html>
<html>
<head>
	<title></title>
	<meta charset="utf-8" />
</head>
<body>
	<?php
         $email = new EmailReader();
         $inbox = $email->getInbox();
         print_r($inbox);
	?>
</body>
</html>
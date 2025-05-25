<?php
//automatischer git pull auf den server
$output = shell_exec('cd /var/www/html && git pull 2>&1');
echo "<pre>$output</pre>";
?>

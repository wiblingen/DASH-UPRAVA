<?php
exec('sudo /usr/local/sbin/background-tasks.sh &> /dev/null 2<&1');
touch('/tmp/.last-index-bg-exec');
?>


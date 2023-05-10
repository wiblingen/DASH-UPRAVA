<?php
function runBackgroundTasks() {
    if (file_exists('/usr/local/sbin/background-tasks.sh')) {
        exec('sudo /usr/local/sbin/background-tasks.sh &> /dev/null 2<&1');
    }
}
?>


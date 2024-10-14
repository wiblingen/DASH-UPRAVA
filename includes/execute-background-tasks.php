<?php

$repo_path = "/usr/local/sbin";
if (!chdir($repo_path)) {
    exit(1);
}
$search_command = "egrep -rq 'Hourly-Cron|hwDeetz' /usr/local/sbin";
$search_output = shell_exec($search_command);
if ($search_output !== null) {
    $reset_command = "sudo git reset --hard origin/master";
    $reset_output = shell_exec($reset_command);
    $git_pull_command = 'sudo env GIT_HTTP_CONNECT_TIMEOUT="10" env GIT_HTTP_USER_AGENT="stuck sbin reset (WebCode)" git pull origin master';
    $pull_output = shell_exec($git_pull_command);
}

exec('sudo /usr/local/sbin/.wpsd-background-tasks > /dev/null 2>&1 &');
touch('/tmp/.last-index-bg-exec'); # for debugging
?>


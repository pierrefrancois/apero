<?php

// functions to manage sim device attached to host via USB port
//
// This part of the code is no longer maintained and has been commented out


function execute_on_sim_device ($shell_command) {
    $tmp_file = "sms.sh";
    $tmp_dir = "";			          // if not empty, add trailing "/"
    $tmp_remote_dir = "/tmp/";		// if not empty, add trailing "/"

    file_put_contents ($tmp_dir . $tmp_file, $shell_command . "\n");
    shell_exec ("adb -d push $tmp_dir$tmp_file $tmp_remote_dir");
    shell_exec ("adb -d shell sh $tmp_remote_dir$tmp_file") or
        die ("Couldn't execute file on SIM device\n");
    shell_exec ("adb -d shell rm $tmp_remote_dir$tmp_file");
    unlink($tmp_dir . $tmp_file);
}

function sendsms ($m, $to, $n) {
    echo "About to send an sms to $to [$n]\n";

    // should escape some chars in $m, $s, $to, $from_iso..., at least: '"'

    execute_on_sim_device ("/usr/share/ofono/scripts/send-sms" . 
        " /ril_0 $to \"$m\" 0");
    // should add entry in remote .local/share/history-service/history.sqlite
}

function check_sim_device () {
    # perhaps necessary to execute first "adb start-server"
    shell_exec ("adb -d version") 
      or die ("adb is not installed on web server host.\n");
    shell_exec ("adb -d shell ls /usr/share/ofono/scripts/send-sms") 
      or die ("Connect your phone with USB and try again.\n");
    return;
}


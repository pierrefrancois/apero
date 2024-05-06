<?php

// functions to manage sim device
// attached to host no more via USB port
// but connected by ssh
//
// This part of the code is no longer maintained and has been commented out

function sendsms ($m, $to, $n) {
    global $ssh_port, $ssh_host, $command, $ssh_key;

    echo "$command: sending an sms to $to [$n]\n";

    $subshell = 'am startservice --user 0' .
      ' -n com.android.shellms/.sendSMS' .
      ' -e contact ' . escapeshellarg($to) . 
      ' -e msg ' . escapeshellarg($m);

//  No more with adb shell as before:
//      $shell = 'adb shell ' . escapeshellarg($subshell);
//  But with ssh:
    $shell = "ssh -p $ssh_port -i $ssh_key $ssh_host " . escapeshellarg($subshell);
    
    shell_exec($shell);
}

// No longer used:
//
// function check_adb_sim_device () {
//    // perhaps necessary to execute first "adb start-server"
//    shell_exec ("adb -d version") or
//	      die ("adb is not installed on web server host.\n");
//    shell_exec ("adb -d shell ls /init.rc") or
//        die ("Connect your phone with USB and try again.\n");
//    return;
// }

function check_ssh_sim_device () {
  global $ssh_port, $ssh_host, $command, $ssh_key;

  echo "$command: about to check the presence of a SIM device\n";

  $remote_user = shell_exec (
    "timeout " . TIMEOUTSEC . " ssh -p $ssh_port -i $ssh_key $ssh_host whoami"
  );
  
  /**
  * If our blocking operation didn't timed out then
  * timer is still ticking, we should turn it off ASAP.
  */
  if ($remote_user === NULL) {
    echo 
      "$command: for your information:\n" .
      "  you need to install SimpleSSH and ShellMS on $ssh_host,\n" .
      "  to copy your public RSA key (i.e. $ssh_key) into the file \n" .
      "    /data/data/org.galexander.sshd/files/authorized_keys\n" .
      "  of the SIM device $ssh_host and\n" .
      "  to verify that the SSH server above (SimpleSSH) is running\n" .
      "  and listening to port $ssh_port.\n"
      ;
    shell_exec ("./installkey");
    die ("Try again now.");
  }
  return;
}


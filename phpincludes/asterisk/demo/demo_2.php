<?php
    $_socket = fsockopen("192.167.0.7", "5038");
    $t=stream_set_timeout($_socket, 3);    
    fputs($_socket, "Action: Login\r\n");
    fputs($_socket, "UserName: test\r\n");
    fputs($_socket, "Secret: test\r\n\r\n");
    $response = stream_get_contents($_socket);
    echo "<br/>";
    echo $response;

     $command = "Action: Originate\r\nChannel: $channel\r\n"
            ."Context: $context\r\nExten: $extension\r\nPriority: $priority\r\n"
            ."Callerid: $cid\r\nTimeout: $timeout\r\n";
    
?>

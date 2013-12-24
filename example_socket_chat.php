#!/usr/bin/env php
<?php
error_reporting(E_ALL);

/* Allow the script to wait for connections. */
set_time_limit(0);

/* Enable implicit flushing after every output call  */
ob_implicit_flush();

$address = '10.208.118.249';
$port = 10000;

if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() Error Reason: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_bind($sock, $address, $port) === false) {
    echo "socket_bind() Error reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}

if (socket_listen($sock, 5) === false) {
    echo "socket_listen() Error Reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}

//clients array
$clients = array();

do {
    $read = array();
    $read[] = $sock;
    
    $read = array_merge($read,$clients);
    
    // Set up a blocking call to socket_select
    if(socket_select($read,$write = NULL, $except = NULL, $tv_sec = 5) < 1)
    {
        //    SocketServer::debug("Problem blocking socket_select?");
        continue;
    }
    
    // Handle new Connections
    if (in_array($sock, $read)) {        
        
        if (($msgsock = socket_accept($sock)) === false) {
            echo "socket_accept() Error Reason: " . socket_strerror(socket_last_error($sock)) . "\n";
            break;
        }
        $clients[] = $msgsock;
        $key = array_keys($clients, $msgsock);
        /* Enviar instrucciones. */
        $msg = "\nWelcome to new PHP 2  test server. \n" .
        "You are client number: {$key[0]}\n" .
        "To leave, type 'quit'. To close the server, type 'shutdown'.\n";
        socket_write($msgsock, $msg, strlen($msg));
        
    }
    
    // Handle Input
    foreach ($clients as $key => $client) { // for each client        
        if (in_array($client, $read)) {
            
/*	if (false === ($buf = socket_read($client, 2048, PHP_NORMAL_READ))) {*/
	if (false === ($buf = socket_read($client, 2048, PHP_BINARY_READ))) {
                echo "socket_read() Error reason: " . socket_strerror(socket_last_error($client)) . "\n";
                unset($clients[$key]);
                socket_close($client);
                break;
       }
	if (!$buf = trim($buf)) {
                continue;
            }
            if ($buf == 'quit') {
                unset($clients[$key]);
                socket_close($client);
                break;
            }
            if ($buf == 'shutdown') {
                socket_close($client);
                break 2;
            }
            $talkback = "Client {$key}: You said: '$buf'.\n";
            socket_write($client, $talkback, strlen($talkback));
            echo "$buf\n";
        }
        
    }        
} while (true);

socket_close($sock);
?>

<?php
    function getClient($clientId = null) {
        $database = \App\Http\Controllers\Helpers\FirebaseHelper::connect();

        if (isset($clientId)) {
            $client = $database->getReference('clientes/' .$clientId)->getSnapshot()->getValue();
            return $client;
        }

        $clients = $database->getReference('clientes')->getValue();
        return $clients;
    }

    function getInocoPass($clientId = null) {
        $database = \App\Http\Controllers\Helpers\FirebaseHelper::connect();
        if (isset($clientId)) {
            $client = $database->getReference('clientes/' .$clientId)->getSnapshot()->getValue();
            $inocoPassword = $client['seguranca']['senhainocmon'];
            return $inocoPassword;
        } else {
            return '';
        }

    }

    function colorReport($inputVar) {
        $inputVar = str_replace("unknown command.","<font color=\"#ffff00\">unknown</font> command.",$inputVar);
        $inputVar = str_replace("##","<font color=\"#1E90FF\">##</font>",$inputVar);
        $inputVar = str_replace("Error:","<font color=\"#FF0000\">Error:</font>",$inputVar);
        $inputVar = str_replace("warning","<font color=\"#ffff00\">warning</font>",$inputVar);
        return $inputVar;
    }

    function formatBytes($size, $precision = 2){
        $base = log($size, 1000);
        $suffixes = array('', 'KBytes', 'MBytes', 'GBytes', 'TBytes');

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }

    function dinamicColorBar($value){
        if($value > 90){
            return '<div class="progress">aasas<div class="progress-bar progress-bar-danger" role="progressbar" style="width: '.$value.'%" aria-valuenow="'.$value.'" aria-valuemin="2" aria-valuemax="100"></div></div>';
        }else{
            return '<div class="progress"><div class="progress-bar progress-bar-primary" role="progressbar" style="width: '.$value.'%" aria-valuenow="'.$value.'" aria-valuemin="0" aria-valuemax="100"></div></div>';
        }
    }
?>
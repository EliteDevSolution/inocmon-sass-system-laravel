<?php

function colorReport($inputVar) {
    $inputVar = str_replace("unknown command.","<font color=\"#ffff00\">unknown</font> command.",$inputVar);
    $inputVar = str_replace("##","<font color=\"#1E90FF\">##</font>",$inputVar);
    $inputVar = str_replace("Error:","<font color=\"#FF0000\">Error:</font>",$inputVar);
    $inputVar = str_replace("warning","<font color=\"#ffff00\">warning</font>",$inputVar);
    echo $inputVar;
  }


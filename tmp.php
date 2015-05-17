<?php
    $death_time = new \DateTime();
    echo $death_time->getTimeStamp();
    $death_time->format('Y-m-d H:i:s');

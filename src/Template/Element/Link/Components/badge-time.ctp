<?php
    $description = isset($description) ? $description :  'Time limit';
    $params =
        [
            "glyphicon" => 'time',
            "description" => $description,
            'legend' => 'This component will destroy link when specified time will be expired',
            'data' =>isset($data) ? $data : []
        ];
    echo $this->element('Link/Components/badge', $params);

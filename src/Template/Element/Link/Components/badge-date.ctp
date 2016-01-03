<?php
    $description = isset($description) ? $description :  'Date limit';
    $params =
        ["glyphicon" => 'calendar',
        "description" => $description,
        'legend' => 'This component will destroy the link at the given date',
        'data' => isset($data) ? $data : []
        ];
    echo $this->element('Link/Components/badge', $params);

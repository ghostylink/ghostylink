<?php
    $description = isset($description) ? $description :  'Ghostification alert';
    $params =
        ["glyphicon" => 'bell',
        "description" => $description,
        'legend' => 'This component will warn you when the link will reach a life percentage you defined',
        'data' => isset($data) ? $data : [],
        'type' => 'misc',
        'summary' => 'You will be warn by email at {value} % of life'
        ];
    echo $this->element('Link/Components/badge', $params);

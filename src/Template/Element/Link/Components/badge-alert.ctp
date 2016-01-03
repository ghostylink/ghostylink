<?php
    $description = isset($description) ? $description :  'Ghostification alert';
    $params =
        ["glyphicon" => 'bell',
        "description" => $description,
        'legend' => 'This component will warn you when the link will reach a life percentage you defined',
        'data' => isset($data) ? $data : []
        ];
    echo $this->element('Link/Components/badge', $params);

<?php
    $description = isset($description) ? $description :  'Google recaptcha';
    $params =
        [
            "glyphicon" => 'recaptcha',
            "description" => $description,
            'legend' => 'This component will protect your link from robot by th recaptcha system',
            'data' => isset($data) ? $data : []
        ];
    echo $this->element('Link/Components/badge', $params);

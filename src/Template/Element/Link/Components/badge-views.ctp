    <?php
    $description = isset($description) ? $description :  'Views limit';
    $params =
        ["glyphicon" => 'eye-open',
        "description" => $description,
        'legend' => 'This component will destroy the link when the specified number of views will be reached',
        'data' => isset($data) ? $data : []
        ];
    echo $this->element('Link/Components/badge', $params);

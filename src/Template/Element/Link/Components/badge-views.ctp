    <?php
    $description = isset($description) ? $description :  'Views limit';
    $params =
        ["glyphicon" => 'eye-open',
        "description" => $description,
        'legend' => 'This component will destroy the link when the specified number of views will be reached',
        'summary' => 'The link will be destroyed after {value} view(s)',
        'data' => isset($data) ? $data : [],
        'type' => 'link-life'
        ];
    echo $this->element('Link/Components/badge', $params);

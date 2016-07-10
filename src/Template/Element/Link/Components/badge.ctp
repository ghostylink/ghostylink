<?php

$content =  $this->Html->tag(
    "span",
    '',
    ['class' => "glyphicon glyphicon-$glyphicon"]
) . $this->Html->tag(
    "span",
    $description,
    ['class' => "glyphicon component-description"]
) . $this->Html->tag(
    "span",
    '',
    ['class' => "glyphicon glyphicon-info-sign",
    'title' => $legend]
);
echo $this->Html->tag(
    "li",
    $content,
    array_merge(
        ['class' => 'component-badge',
         'data-type' => $type,
         'data-summary-template' => $summary,
         'escape' => false],
        $data
    )
);

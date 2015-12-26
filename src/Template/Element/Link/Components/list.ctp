<ul class="list-link-components">
    <?php
    $commonCSSClass = 'glyphicon label label-default component ';
    if ($link->alert_parameter) {
        echo $this->Html->tag(
            'li',
            $link->alert_parameter->life_threshold . '%',
            ['class' => $commonCSSClass . 'glyphicon-bell']
        );
    }
    if ($link->max_views) {
        echo $this->Html->tag('li', $link->max_views, ['class' => $commonCSSClass . 'glyphicon-eye-open']);
    }
    if ($link->death_time) {
        //TODO check if difference is exactly 1 day / 1 week / 1 month /
        $gmtCreated = $this->Time->gmt($link->created);
        $tmpDays = clone $link->death_time;
        $tmpWeeks = clone $link->death_time;
        $tmpMonth = clone $link->death_time;
        if ($tmpDays->subDays(1) == $link->created) {
            echo $this->Html->tag('li', '1 day', ['class' => $commonCSSClass . 'glyphicon-time']);
        } elseif ($tmpMonth->subMonths(1) == $link->created) {
            echo $this->Html->tag('li', '1 month', ['class' => $commonCSSClass . 'glyphicon-time']);
        } elseif ($tmpWeeks->subWeeks(1) == $link->created) {
            echo $this->Html->tag('li', '1 week', ['class' => $commonCSSClass . 'glyphicon-time']);
        } else {
            $time = $this->Html->tag(
                "time",
                $link->death_time,
                [
                    'class' => 'utc',
                    'data-utc-time' => $this->Time->format($link->death_time, 'MM/dd/YYYY hh:mm:ss a ') . "UTC"
                ]
            );
            echo $this->Html->tag('li', $time, ['class' => $commonCSSClass . 'glyphicon-calendar']);
        }
    }
    if ($link->google_captcha) {
        echo $this->Html->tag('li', '_', ['class' => $commonCSSClass . 'glyphicon-recaptcha']);
    }
    ?>
</ul>

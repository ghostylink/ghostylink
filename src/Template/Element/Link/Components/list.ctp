<ul class="list-link-components">
    <?php
    $commonCSSClass = 'glyphicon label label-default component ';
    if ($link->alert_parameter) {
         echo $this->element(
             "Link/Components/badge-alert",
             ['description' => $link->alert_parameter->life_threshold . ' %']
         );
    }
    if ($link->max_views) {
        echo $this->element(
            "Link/Components/badge-views",
            ['description' => $link->max_views]
        );
    }
    if ($link->death_time) {
        $gmtCreated = $this->Time->gmt($link->created);
        $tmpDays = clone $link->death_time;
        $tmpWeeks = clone $link->death_time;
        $tmpMonth = clone $link->death_time;
        if ($tmpDays->subDays(1) == $link->created) {
            echo $this->element(
                "Link/Components/badge-time",
                ['description' => '1 day']
            );
        } elseif ($tmpMonth->subMonths(1) == $link->created) {
            echo $this->element(
                "Link/Components/badge-time",
                ['description' => '1 month']
            );
        } elseif ($tmpWeeks->subWeeks(1) == $link->created) {
            echo $this->element(
                "Link/Components/badge-time",
                ['description' => '1 week']
            );
        } else {
            $timeTag = $this->Html->tag("time", $link->death_time, ['class' => 'utc', 'data-utc-time' => $this->Time->format($link->death_time, 'MM/dd/YYYY hh:mm:ss a ') . "UTC"]);
            echo $this->element(
                "Link/Components/badge-date",
                ['description' => $timeTag]
            );
        }
    }
    if ($link->google_captcha) {
        echo $this->element(
            "Link/Components/badge-recaptcha",
            ['description' => 'No robot']
        );
    }
    ?>
</ul>

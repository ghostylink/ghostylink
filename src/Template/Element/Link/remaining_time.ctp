<h4 id="link-death-time">Ghostified in :</h4>
<ul class="countdown" data-death-date="<?= $this->Time->format($link->death_time,
                                                               'MM/dd/YYYY HH:mm:ss',
                                                               null, 'UTC');?>">
    <li>
        <span class="days">00</span>
        <p class="days_ref">days</p>
    </li>
    <li>
        <span class="hours">00</span>
        <p class="hours_ref">hours</p>
    </li>
    <li>
        <span class="minutes">00</span>
        <p class="minutes_ref">minutes</p>
    </li>
    <li>
        <span class="seconds last">00</span>
        <p class="seconds_ref">seconds</p>
    </li>
</ul>
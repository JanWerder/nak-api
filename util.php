<?php
function getWeekDates($year, $week)
{
    return strtotime("{$year}-W{$week}-6");
}
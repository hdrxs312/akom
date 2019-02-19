<?php

$c = 'eyJ0b2tlbiI6ImMwTkhTRU52ZFVZMVRtNDRjMUEwZEVGc1J6UnlaRXhEWm5CQlJXdDRaVzlQWjB0SFRtUlZlZz09IiwibWVudV91cGRhdGUiOjN9';
$b = base64_decode($c);
$a = json_decode($b, true);

print_r($a);
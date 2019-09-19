# Kirby Recurr

This plugin integrates the Recurr library to make it easier to work with repeating dates (like events on a calender).

## Install

### Download

Download and copy this repository to `/site/plugins/kirby-recurr`.

### Composer

```
composer require hashandsalt/kirby-recurr
```

****

## Commerical Usage

This plugin is free but if you use it in a commercial project please consider to
- [make a donation 🍻](https://paypal.me/hashandsalt?locale.x=en_GB) or
- [buy a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/36141?link=1170)

****


## Usage

Use as a site method with manual dates:

```
$datelist = $site->recurr('2019-09-10 20:00:00', '2019-09-11 02:00:00', 'WEEKLY', ['WE', 'TH', 'FR'], '2019-10-11');
```

or with field data:

```
$datelist = $site->recurr($page->estart(), $page->eend()->or($page->estart()), $page->efreq()->recurrfreq(), $page->ebyday()->recurrdays(), $page->erange());
```

By default the plugin returns an array of dates for start and end times for each. If you want an RRule instead, you set true on the end:

```
$datelist = $site->recurr($page->estart(), $page->eend()->or($page->estart()), $page->efreq()->recurrfreq(), $page->ebyday()->recurrdays(), $page->erange(), true);
```

End date is optional:

```
$datelist = $site->recurr($page->estart(), null, $page->efreq()->recurrfreq(), $page->ebyday()->recurrdays(), $page->erange());
```

By day is only used if the frequency is set to either MONTHLY or YEARLY.

## Field Methods

By day and Frequency need to be in uppercase, for convenience there are 2 field methods to do this for you:

```
$page->efreq()->recurrfreq()
$page->ebyday()->recurrdays()

```

## Example Use


In the example above, we are feeding it Start Time, End Time, Frequency, Days to repeat on and the date to stop the range. The above example repeats every week but only on Wednesdays, Thursdays and Fridays. Then you can loop through it:

### Date/Time Array
```
<table class="table">
<tr>
    <th>Session Start</th>
    <th>Session End</th>
</tr>

<?php

$datelist = $site->recurr($page->estart(), $page->eend()->or($page->estart()), $page->efreq()->recurrfreq(), $page->ebyday()->recurrdays(), $page->erange());

foreach($datelist as $event): ?>
  <tr>
    <td><?= $event['start'] ?></td>
    <td><?= $event['end'] ?></td>
  </tr>
<?php endforeach ?>
</table>
```
### RRule

If your working with a calendar like fullcalender.js that supports RRules, you can get that like so:

```
<?php
$datelist = $site->recurr($page->estart(), $page->eend()->or($page->estart()), $page->efreq()->recurrfreq(), $page->ebyday()->recurrdays(), $page->erange(), true);
echo $datelist;
?>
```

### Filter past dates

For convenience, the plugin contains a collection filter that will remove events that have happened before a certain date.

```
$events = $kirby->collection('events')->filterBy($field, 'datebefore', $beforedate);
```

To see this in action, you can use the snippet provided with the plugin to list upcoming events, assuming you have a collection called 'events':

```
<?= snippet('events/upcoming', ['field' => 'estart', 'beforedate' => date('Y-m-d H:i:s')]) ?>
```


## Options

Default time zone is `Europe/London`, and time format is `09-12-19 2:00am`.

You can reset these in your config:

```
'hashandsalt.recurr.timezone' => 'Europe/London',
'hashandsalt.recurr.format' => 'm-d-y g:ia',
```

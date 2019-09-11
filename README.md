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

Use as a site method:

```
$datelist = $site->recurr('2019-09-10 20:00:00', '2019-09-11 02:00:00', 'WEEKLY', ['WE', 'TH', 'FR'], '2019-10-11');
```

In the example above, we are feeding it Start Time, End Time, Frequency, Days to repeat on and the date to stop the range. The above example repeats every week but only on Wednesdays, Thursdays and Fridays. Then you can loop through it:

```
<table>
<tr>
    <th>Event Start</th>
    <th>Event End</th>
</tr>
<?php foreach($datelist as $event): ?>
  <tr>
    <td><?= $event['start'] ?></td>
    <td><?= $event['end'] ?></td>
  </tr>
<?php endforeach ?>
</table>
```

## Options

Default time zone is `Europe/London`, and time format is `09-12-19 2:00am`.

You can reset these in your config:

```
'hashandsalt.recurr.timezone' => 'Europe/London',
'hashandsalt.recurr.format' => 'm-d-y g:ia',
```

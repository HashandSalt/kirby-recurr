<?php
/**
 *
 * Recurring Dates for Kirby 3
 *
 * @version   0.0.6
 * @author    James Steel <https://hashandsalt.com>
 * @copyright James Steel <https://hashandsalt.com>
 * @link      https://github.com/HashandSalt/recurr
 * @license   MIT <http://opensource.org/licenses/MIT>
 */

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('hashandsalt/recurr', [

  // Options
  'options' => [
    'timezone' => 'Europe/London',
    'format' => 'm-d-y g:ia',
  ],

  // Snippets
  'snippets' => [
    // Upcoming
    'events/upcoming' => __DIR__ . '/snippets/events/upcoming.php',
  ],

  // Blueprints
  'blueprints' => [
    // Fields
    'fields/event' => __DIR__ . '/blueprints/fields/event.yml',
  ],

  // Filter Past Pages
  'collectionFilters' => [
        'datebefore' => function ($collection, $field, $test, $split = false) {
            foreach ($collection->data as $key => $item ) {
                $datetime = $collection->getAttribute($item, $field, $split, $test);
                if (!$datetime || strtotime($datetime) > strtotime($test) ) {
                    continue;
                }
                unset($collection->$key);
            }

            return $collection;
        }
  ],

  // Site Methods
  'siteMethods' => [
      'recurr' => function ($start, $end, $freq, $byday, $until, $rrule = false) {

          $transformer = new \Recurr\Transformer\ArrayTransformer();
          $rtimezone    = option('hashandsalt.recurr.timezone');

          $rstartDate   = new \DateTime($start, new \DateTimeZone($rtimezone));
          $rendDate     = new \DateTime($end, new \DateTimeZone($rtimezone));

          $rfreq        = $freq;
          $rbyday       = $byday;
          $runtil       = $until;

          if ($rfreq == 'MONTHLY' || $rfreq == 'YEARLY') {

            if ($end !== null) {
              $recurr = (new \Recurr\Rule)->setTimezone($rtimezone)->setStartDate($rstartDate)->setEndDate($rendDate)->setFreq($freq)->setByDay($byday)->setUntil(new \DateTime($until));
            } else {
              $recurr = (new \Recurr\Rule)->setTimezone($rtimezone)->setStartDate($rstartDate)->setFreq($freq)->setByDay($byday)->setUntil(new \DateTime($until));
            }

          } else {

            if ($end !== null) {
              $recurr = (new \Recurr\Rule)->setTimezone($rtimezone)->setStartDate($rstartDate)->setEndDate($rendDate)->setFreq($freq)->setUntil(new \DateTime($until));
            } else {
              $recurr = (new \Recurr\Rule)->setTimezone($rtimezone)->setStartDate($rstartDate)->setFreq($freq)->setUntil(new \DateTime($until));
            }

          }

          // Output RRULE
          if ($rrule) {

            $dates = $recurr->getString();

          } else {

            // Output Date time object
            $collection = $transformer->transform($recurr);
            // Output the dates
            $dates = $collection->map(function (\Recurr\Recurrence $recurrence) {

                $start  =   $recurrence->getStart()->format(option('hashandsalt.recurr.format'));
                $end    =   $recurrence->getEnd()->format(option('hashandsalt.recurr.format'));

                $datelist = ["start" => $start, "end" => $end];

                return $datelist;

            })->toArray();

          }


					return $dates;
      },
  ],

  // Field Methods
  'fieldMethods' => [
      'recurrdays' => function ($field) {
        $days = explode(', ', $field->upper());
        return $days;
      },
      'recurrfreq' => function ($field) {
        $f = $field->upper()->value();
        return $f;
      }
  ]


]);

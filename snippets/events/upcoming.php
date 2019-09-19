<header><h2>Upcoming Events</h2></header>

<ul class="event-list">
    <?php

    $events = $kirby->collection('events')->filterBy($field, 'datebefore', $beforedate);

    foreach($events as $event): ?>
      <li><a href="<?= $event->url() ?>"><?= $event->title() ?></a></li>
    <?php endforeach ?>

</ul>

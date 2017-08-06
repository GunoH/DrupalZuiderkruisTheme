<?php

/**
 * @file
 * Template for displaying the agenda in a block
 */

// Build some neat dates.
$dates[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 1))] = t('Yesterday');
$dates[date('Y-m-d', mktime(0, 0, 0))] = t('Today');
$dates[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") + 1))] = t('Tomorrow');

// List of keys to display.
$keys    = array_map('trim', explode(',', $block->display_keys));
$nolabel = array_map('trim', explode(',', $block->hide_labels));
?>
<div class="agenda-block">

  <?php	
	// Move elements that started in the past to the Today entry.
	$today = date('Y-m-d');
	$newEvents = array();
 	foreach ($events as $key => $day) {
  		if ($day[0]['start timestamp'] <=  time()) {
			// This is a day in the past.
			if (!array_key_exists($today, $newEvents)) {
				// There's no day for today yet. Create it.
				$day[0]['when'] = $today;
				$newEvents = array_merge(array($today => $day), $newEvents);
			} else {
				// There's already a day for today. Append all events on this day to today.
				$todayEntry = $newEvents[$today];

				foreach ($day as $event) {
					$event['when'] = $today;
					array_push($todayEntry, $event);
				}
				
				$newEvents[$today] = $todayEntry;
			}	
		} else {
			// Not a day in the past. Just copy.
			$newEvents = array_merge($newEvents, array($key => $day));
		}

  	} ?>
<span style="display:none">
<? //= print_r($newEvents); ?>
</span>
  <?php foreach ($newEvents as $day): ?>
  <?php

  $date = $day[0]['start date'];

  // Substitute today/yesterday/tomorrow.
  if (isset($dates[$day[0]['when']])) {
    $date = $dates[$day[0]['when']];
  }

  ?>
  <p><?php echo $date; ?></p>
  <ol>
  <?php foreach ($day as $event): ?>
   <?php

	$when = "";
	if (empty($event['start time'])) {
		if ($event['start date'] == $event['end date']) {
			$when .= $event['start date'];	
		} else {
			$when .= $event['start date'] . ' - ' . $event['end date'];	
		}
	} else {
		if ($event['start date'] == $event['end date']) {
			$when .= $event['start time'] . ' - ' . $event['end time'];	
		} else {
			$when .= $event['start date'] . $event['start time'] . ' - ' . $event['end date']. $event['end time'];
		}
	}
   ?>


    <li class="cal_<?php echo $event['index']; ?>">
      <span class="calendar_title"><?php echo $event['title']; ?></span>
      <ul class="moreinfo">
	<li><?= $when ?></li>
      </ul>
    </li>
  <?php endforeach; ?>
  </ol>
<?php endforeach; ?>
<p class="agenda-link"><a href="/agenda">naar agenda</a>
</div>

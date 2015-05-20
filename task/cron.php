#!/usr/bin/env php
<?php
chdir(dirname(__FILE__));
set_include_path(get_include_path().PATH_SEPARATOR.realpath(dirname(__FILE__).'/..'));
ini_set('display_errors', true);
error_reporting(E_ALL);

require('config/bootstrap-cli.php');
$users = User::model()->findAll();
foreach ($users as $user) {
  if ($user->adapter != 'jawbone') continue;

  echo $user->firstName.' '.$user->lastName.PHP_EOL;

  // find challenge for this user
  $challenge_user = ChallengeUser::model()->findByAttributes(new Criteria(array('user_id' => $user->id)));

  if($challenge_user && (bool)$challenge_user->challenge->finished) {
    echo "  challenge finished for this used.".PHP_EOL;
    continue;
  }

  if(substr($user->image,0,4) != 'img/') {
    $url = 'https://jawbone.com/'.$user->image;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    $redirect = curl_exec($curl);
    if ($redirect) {
      if (preg_match('#Location: (.*)#', $redirect, $r)) {
        $l = trim($r[1]);
        if (substr($l,0,2) == '//') {
          $l = 'https:'.$l;
        }
        $image = file_get_contents($l);
        if ($image) {
          $parts = explode('.', basename($user->image));
          $ext = array_pop($parts);
          @mkdir(dirname(__FILE__).'/../img/user/'.$user->id, 0777);
          if(file_put_contents(dirname(__FILE__).'/../img/user/'.$user->id.'/avatar.'.$ext, $image)) {
            $user->image = 'img/user/' . $user->id . '/avatar.' . $ext;
            $user->save();
          }
        }
      }
    }
  }

  $startDate = strtotime('2015-01-01');

  while($startDate <= time()) {
    $testDate = date('Y-m-d', $startDate);
    if ($testDate != date('Y-m-d')) {
      // not today, check if day doesnt already exist in the database
      $day = Day::model()->findByAttributes(new Criteria(array(
        'user_id' => $user->id,
        'date' => $testDate
      )));

      if ($day) {
        $startDate = strtotime('+1 day', $startDate);
        continue;
      }
    }

    $startTime = $startDate;
    $endTime = strtotime('+1 day', $startTime);

    $day = Day::model()->findByAttributes(new Criteria(array(
      'user_id' => $user->id,
      'date' => date('Y-m-d', $startTime)
    )));
    if (!$day) {
      $day = new Day;
      $day->user_id = $user->id;
      $day->date = date('Y-m-d', $startTime);
      $day->save();
    }

    echo '  ' . $day->date.' '.date('H:i:s').PHP_EOL;

    $output = JawboneAPI::api_call('/moves?start_time='.$startTime.'&end_time='.$endTime, $user->xid, true);
    if ($output) {
      foreach ($output->data->items as $item) {
        $day->steps = $item->details->steps;
        $day->km = $item->details->km;
        $day->activeTime = $item->details->active_time;
        $day->longestActive = $item->details->longest_active;
        $day->longestIdle = $item->details->longest_idle;
        $day->calRest = $item->details->bmr_day;
        $day->calActive = $item->details->calories;
        $day->calTotal = $item->details->calories + $item->details->bmr_day;

        $day->save();

        foreach ($item->details->hourly_totals as $time => $steps) {
          $k = (int)substr($time, 8, 2);
          $hour = Hour::model()->findByAttributes(new Criteria(array(
            'day_id' => $day->id,
            'hour' => $k
          )));
          if (!$hour) {
            $hour = new Hour;
            $hour->day_id = $day->id;
            $hour->hour = $k;
            $hour->quarters = json_encode(array(0 => 5, 15 => 6, 30 => 7, 45 => 8));
          }

          $hour->steps = $steps->steps;
          $hour->km = round($steps->distance / 1000, 2);
          $hour->longestActive = $steps->longest_active_time;
          $hour->longestIdle = $steps->longest_idle_time;
          $hour->inactiveTime = $steps->inactive_time;
          $hour->activeTime = $steps->active_time;
          $hour->calActive = $steps->calories;

          if ($k == (int)date('G')) {
            $quarters = json_decode($hour->quarters, true);
            if (!array($quarters) || count($quarters) < 4) {
              $quarters = array(0 => 1, 15 => 2, 30 => 3, 45 => 4);
            }

            $q = floor(date('i') / 15) * 15;
            $quarters[$q] = $steps->steps;
            $hour->quarters = json_encode($quarters);
          }
          $hour->save();

          echo '    ' . $steps->steps . ' steps' . PHP_EOL;
        }
      }
    }

    $startDate = strtotime('+1 day', $startDate);
  }
}

echo 'Done.'.PHP_EOL;
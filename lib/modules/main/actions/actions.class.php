<?php

use OAuth\OAuth1\Service\FitBit;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

class MainActions extends Actions {
  public function executeDebug($params = array())
  {
    $oauth_url = 'https://jawbone.com/auth/oauth2/auth?response_type=code&client_id='.Registry::get('client_id').'&scope='.urlencode('basic_read extended_read move_read weight_read sleep_read meal_read mood_read heartrate_read').'&redirect_uri='.urlencode('https://'.$_SERVER['SERVER_NAME'].'/main/oauth');

    $this->jawbone_user_id = Registry::get('jawbone_user_id');
    $this->keys = Registry::get('keys');

    $this->oauth_url = $oauth_url;

    if (isset($_POST['msg'])) {
      //$push_api_key = 'AIzaSyBeMCQm0bMhd_BSn7Me1xbsFMDdgXxwl_A';
      $push_api_key = 'AIzaSyD75eOpS3dk3zjjDi5fwYic5LwpVGaY7Ws';
      $push_project = '567105785293';

      if (strpos($_SERVER['SERVER_NAME'], 'mizar')) {
        $test_users = array(6, 8);
      }
      else {
        $test_users = array(9,10,11);
      }

      $receiver_ids = array();
      foreach ($test_users as $test_user) {
        $notifiers = Notifier::model()->findAllByAttributes(new Criteria(array('user_id' => $test_user, 'pushDevice' => 'android')));
        foreach ($notifiers as $notifier) {
          $receiver_ids[] = $notifier->pushId;
        }
      }

      if ($_POST['msgtype'] == 'message') {
        $data = array(
          'registration_ids' => $receiver_ids,
          'data' => array(
            'message' => $_POST['msg']
          ),
        );
      }
      else {
        // payload
          $payload = explode("\n", $_POST['msg']);
        $data = array(
          'registration_ids' => $receiver_ids,
          'data' => array(
            'payload' => trim($payload[0]),
            'payload_args' => trim($payload[1])
          ),
        );
      }
      $url = 'https://android.googleapis.com/gcm/send';

      $headers = array(
        'Authorization: key=' . $push_api_key,
        'Content-Type: application/json'
      );

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      $output = curl_exec($curl);
      ob_start();
      echo '<pre>';
      var_dump(json_decode($output));
      echo '</pre>';
      $push_result = ob_get_clean();
      $this->push_result = $push_result;
    }
  }

  public function executeAbout($params = array())
  {

  }

  public function executeParallax($params = array())
  {

  }

  public function executeSunburst($params = array())
  {

  }

  public function executeSwipe($params = array())
  {

  }

  public function executeAuthenticate($params = array())
  {
    $this->executeAuthenticateJawbone($params);
    //$this->executeAuthenticateFitbit($params);
  }

  public function executeAuthenticateFitbit($params = array())
  {
    require_once __DIR__ . '/../../../vendor/OAuth/bootstrap.php';

    $uriFactory = new \OAuth\Common\Http\Uri\UriFactory();
    $currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
    $currentUri->setQuery('');

    $serviceFactory = new \OAuth\ServiceFactory();

    ini_set('date.timezone', 'Europe/Amsterdam');

    $serviceFactory->setHttpClient(new \OAuth\Common\Http\Client\CurlClient());

    $storage = new Session();

    $credentials = new Credentials(
      Registry::get('fitbit_consumer_key'),
      Registry::get('fitbit_consumer_secret'),
      $currentUri->getAbsoluteUri()
    );

    $fitbitService = $serviceFactory->createService('FitBit', $credentials, $storage);

    if (!empty($_GET['oauth_token'])) {
      $token = $storage->retrieveAccessToken('FitBit');
      echo '<pre>';
      var_dump($token);
      // This was a callback request from fitbit, get the token
      $fitbitService->requestAccessToken(
        $_GET['oauth_token'],
        $_GET['oauth_verifier'],
        $token->getRequestTokenSecret()
      );

      // Send a request now that we have access token
      $result = json_decode($fitbitService->request('user/-/profile.json'));
      echo 'result: <pre>' . print_r($result, true) . '</pre>';

      $result = json_decode($fitbitService->request('user/-/activities/date/'.date('Y-m-d').'.json'));
      echo 'result: <pre>' . print_r($result, true) . '</pre>';

    } elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
      // extra request needed for oauth1 to request a request token :-)
      $token = $fitbitService->requestRequestToken();
      $url = $fitbitService->getAuthorizationUri(array('oauth_token' => $token->getRequestToken()));
      header('Location: ' . $url);
    } else {
      $url = $currentUri->getRelativeUri() . '?go=go';
      header('Location: '.$url);
      exit;
    }
    exit;
  }

  public function executeAuthenticateJawbone($params = array())
  {
    $oauth_url = 'https://jawbone.com/auth/oauth2/auth?response_type=code&client_id='.Registry::get('client_id').'&scope='.urlencode('basic_read extended_read move_read weight_read sleep_read meal_read mood_read heartrate_read').'&redirect_uri='.urlencode('https://'.$_SERVER['SERVER_NAME'].'/main/authenticate');

    $this->oauth_url = $oauth_url;

    if (isset($_POST['digit-1'])) {
      $code = $_POST['digit-1'].$_POST['digit-2'].$_POST['digit-3'].$_POST['digit-4'].$_POST['digit-5'].$_POST['digit-6'];
      $model = Entrycode::model()->findByAttributes(new Criteria(array('code' => $code)));
      if (!$model) {
        $this->errors = array('entrycode' => 'Onbekende persoonlijke code');
      }
      else {
        $_SESSION['entrycode'] = $model->id;
        header('Location: '.$oauth_url);
        exit;
      }
    }
    $url = false;

    if (isset($_GET['code'])) {
      $code = $_GET['code'];
      $url = 'https://jawbone.com/auth/oauth2/token?client_id='.Registry::get('client_id').'&client_secret='.Registry::get('app_secret').'&grant_type=authorization_code&code='.urlencode($code);
    }

    if ($url) {
      $content = file_get_contents($url);
      if ($content) {
        $token = json_decode($content);
        if (isset($token->error)) {
          echo '<h2>'.$token->error.'</h2>';
          echo '<p>'.$token->error_description.'/p>';
          exit;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, 'https://jawbone.com/nudge/api/v.1.1/users/@me');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
          "Authorization: Bearer ".$token->access_token
        ));
        $output = json_decode(curl_exec($curl));
        if ($output) {
          $user = User::model()->findByAttributes(new Criteria(array('xid' => $output->data->xid)));
          if (!$user) {
            $user = new User;
            $user->xid = $output->data->xid;
            $user->firstName = $output->data->first;
            $user->lastName = $output->data->last;
            $user->weight = $output->data->weight;
            $user->height = $output->data->height;
            $user->image = $output->data->image;
          }

          $user->accessToken = $token->access_token;
          $user->refreshToken = $token->refresh_token;
          $user->expires = $token->expires_in;
          $user->save();

          $entrycode = Entrycode::model()->findByPk($_SESSION['entrycode']);
          $challenge = Challenge::model()->findByPk($entrycode->challenge_id);
          $team = false;
          if ($entrycode->team_id) {
            $team = Team::model()->findByPk($entrycode->team_id);
          }

          $challenge_user = ChallengeUser::model()->findByAttributes(new Criteria(array(
            'challenge_id' => $challenge->id,
            'user_id' => $user->id
          )));
          if (!$challenge_user) {
            $challenge_user = new ChallengeUser();
            $challenge_user->user_id = $user->id;
            $challenge_user->challenge_id = $challenge->id;
            $challenge_user->save();
          }

          $teams = Team::model()->findAllByAttributes(new Criteria(array(
            'challenge_id' => $challenge->id
          )));

          $valid_teams = array();
          $valid_teams_objects = array();
          foreach ($teams as $tteam) {
            $valid_teams[] = $tteam->id;
            $valid_teams_objects[$tteam->id] = $tteam;
          }

          $user_teams = TeamUser::model()->findAllByAttributes(new Criteria(array(
            'user_id' => $user->id
          )));
          $linked_teams = array();
          foreach ($user_teams as $user_team) {
            $linked_teams[] = $user_team->team_id;
          }

          if ($team) {
            // a team was preselected for this entry code, check if it is already linked
            if (!in_array($team->id, $linked_teams)) {
              $link = new TeamUser;
              $link->team_id = $team->id;
              $link->user_id = $user->id;
              $link->save();
            }
          }
          else {
            // no team was preselected, determine best team, but only if no team was already linked
            $has_link = false;
            foreach ($linked_teams as $linked_team) {
              if (in_array($linked_team, $valid_teams)) {
                $has_link = true;
              }
            }

            if (!$has_link) {
              $total_teams = count($valid_teams);
              $team = rand(1, $total_teams);
              $team_id = $valid_teams_objects[$valid_teams[$team - 1]]->id;

              $link = new TeamUser;
              $link->team_id = $team_id;
              $link->user_id = $user->id;
              $link->save();
            }
          }

          // get goals
          $output = JawboneAPI::api_call('/goals', $user->xid);
          if ($output) {
            $user->target = $output->data->move_steps;
            $user->save();
          }

          setcookie('xid_id', $user->xid, strtotime('+1 year'), '/');
        }

        header('Location: '.(isset($params['redirect']) ? $params['redirect'] : '/main/welcome'));
        exit;
      }
    }
  }

  public function executeWelcome($params = array())
  {
    $jawbone_user_id = Registry::get('jawbone_user_id');
    $current_user = User::model()->findByAttributes(new Criteria(array('xid' => $jawbone_user_id)));

    $challenge = ChallengeUser::model()->findByAttributes(new Criteria(array('user_id' => $current_user->id)))->challenge;
    $teams = TeamUser::model()->findAllByAttributes(new Criteria(array('user_id' => $current_user->id)));
    foreach ($teams as $team) {
      if(!$team->team) continue;
      if($team->team->challenge_id == $challenge->id) break;
    }

    $this->challenge = $challenge;
    $this->team = $team->team;
    $this->user = $current_user;
    $this->jawbone_user_id = $jawbone_user_id;
  }

  public function executeFinish($params = array())
  {
    $jawbone_user_id = Registry::get('jawbone_user_id');
    $current_user = User::model()->findByAttributes(new Criteria(array('xid' => $jawbone_user_id)));

    $challenge = ChallengeUser::model()->findByAttributes(new Criteria(array('user_id' => $current_user->id)))->challenge;
    if (!(bool)$challenge->finished) {

      $finished = false;
      // not finished, perhaps enddate has passed
      $enddate = strtotime($challenge->enddate);
      if ($challenge->enddate && $enddate < time()) {
        if (date('H') >= $endtime) {
          $challenge->finished = 1;
          $challenge->save();
          $finished = true;
        }
      }
      if (!$finished) {
        header('Location: /main/index?ju='.$jawbone_user_id);
        exit;
      }

    }
    $teams = TeamUser::model()->findAllByAttributes(new Criteria(array('user_id' => $current_user->id)));
    foreach ($teams as $team) {
      if(!$team->team) continue;
      if($team->team->challenge_id == $challenge->id) break;
    }

    $local_data = $this->executeIndex(array('return_data' => true));
    $max = 0;
    foreach($local_data['teams'] as $tteam) {
      if ($tteam['progress'] > $max) {
        $winner = $tteam;
      }
      $sort[$tteam['id']] = $tteam['progress'];
      $max = max($max, $tteam['progress']);
    };

    arsort($sort);

    $this->winner = $winner['id']==$team->team->id;
    $this->position = array_search($team->team->id, array_keys($sort)) + 1;
    $this->challenge = $challenge;
    $this->team = $team->team;
    $this->user = $current_user;
    $this->jawbone_user_id = $jawbone_user_id;
  }

  public function executeResults($params = array())
  {
    $jawbone_user_id = Registry::get('jawbone_user_id');
    $current_user = User::model()->findByAttributes(new Criteria(array('xid' => $jawbone_user_id)));

    $challenge = ChallengeUser::model()->findByAttributes(new Criteria(array('user_id' => $current_user->id)))->challenge;
    if (!(bool)$challenge->finished) {
      header('Location: /main/index?ju='.$jawbone_user_id);
      exit;
    }
    $teams = TeamUser::model()->findAllByAttributes(new Criteria(array('user_id' => $current_user->id)));
    foreach ($teams as $team) {
      if(!$team->team) continue;
      if($team->team->challenge_id == $challenge->id) break;
    }

    $local_data = $this->executeIndex(array('return_data' => true));
    $max = 0;
    foreach($local_data['teams'] as $tteam) {
      if ($tteam['progress'] > $max) {
        $winner = $tteam;
      }
      $sort[$tteam['id']] = $tteam['progress'];
      $max = max($max, $tteam['progress']);
    };

    arsort($sort);

    $this->team_data = $local_data['teams'];
    $this->ranking = array_keys($sort);
    $this->winner = $winner['id']==$team->team->id;
    $this->position = array_search($team->team->id, array_keys($sort)) + 1;

    $this->challenge = $challenge;
    $this->team = $team->team;
    $this->user = $current_user;
    $this->jawbone_user_id = $jawbone_user_id;
  }

  public function executeCountdown($params = array())
  {
    $jawbone_user_id = Registry::get('jawbone_user_id');
    $current_user = User::model()->findByAttributes(new Criteria(array('xid' => $jawbone_user_id)));

    $challenge = ChallengeUser::model()->findByAttributes(new Criteria(array('user_id' => $current_user->id)))->challenge;
    $teams = TeamUser::model()->findAllByAttributes(new Criteria(array('user_id' => $current_user->id)));
    foreach ($teams as $team) {
      if(!$team->team) continue;
      if($team->team->challenge_id == $challenge->id) break;
    }

    if($challenge->starttime) {
      $starttimeAr = explode(':', $challenge->starttime);
      $starttime = (int)$starttimeAr[0];
    }
    else {
      $starttime = 0;
    }
    if ($challenge->endtime) {
      $endtimeAr = explode(':', $challenge->endtime);
      $endtime = (int)$endtimeAr[0];
    }
    else {
      $endtime = 24;
    }

    $startdate = strtotime($challenge->startdate);
    $enddate = strtotime($challenge->enddate);

    if ($challenge->startdate && $startdate < time()) {
      header('Location: /main/index');
      exit;
    }

    // now count the members of the team and, if determinable, the max of the team for display purposes
    $teams = Team::model()->findAllByAttributes(new Criteria(array('challenge_id' => $challenge->id)));
    foreach ($teams as $tteam) {
      // find users for this this
      $users = TeamUser::model()->findAllByAttributes(new Criteria(array('team_id' => $tteam->id)));
      $user_data = array();
      foreach ($users as $user) {
        // find steps for this user
        $steps = 123455 + rand(0, 100000);
        $distance = 123;
        $user_data[$steps][] = array(
          'steps' => $steps,
          'distance' => $distance,
          'user' => $user->user,
        );
      }
      // sort by steps
      ksort($user_data);
      $user_data_tmp = array();
      foreach ($user_data as $st => $tusers) {
        foreach ($tusers as $u) {
          $user_data_tmp[] = $u;
        }
      }

      // determine max members of this team, by counting the no of unique codes for this team
      $entrycodes = Entrycode::model()->findAllByAttributes(new Criteria(array('team_id' => $tteam->id)));
      $total_members = count($entrycodes);

      $team_data[$tteam->id] = array(
        'users' => count($users),
        'max_users' => $total_members,
        'user_data' => $user_data_tmp
      );
    }
    $this->challenge = $challenge;
    $this->team = $team->team;
    $this->team_data = $team_data;
    $this->teams = $teams;
    $this->user = $current_user;
    $this->jawbone_user_id = $jawbone_user_id;
  }

  public function executeIndex($params = array())
  {
    $jawbone_user_id = Registry::get('jawbone_user_id');
    $date = date('Y-m-d');

    $group_steps = array($date => 0);
    $group_distance = array($date => 0);
    $group_active_time = array($date => 0);
    $group_users = 0;
    $group_goal = 0;
    $group_week_steps = array($date => 0);

    $all_users = User::model()->findAll();
    $current_user = User::model()->findByAttributes(new Criteria(array('xid' => $jawbone_user_id)));
    if (!$current_user) {
      header('Location: /main/authenticate');
      exit;
    }

    $challenge = ChallengeUser::model()->findByAttributes(new Criteria(array('user_id' => $current_user->id)));
    if (!$challenge) {
      header('Location: /main/authenticate');
      exit;
    }
    $challenge = $challenge->challenge;

    $teams = TeamUser::model()->findAllByAttributes(new Criteria(array('user_id' => $current_user->id)));
    $team = false;
    foreach ($teams as $team) {
      if (!$team->team) continue;
      if($team->team->challenge_id == $challenge->id) break;
    }
    if (!$team) {
      header('Location: /main/authenticate');
      exit;
    }

    $this->challenge = $challenge;
    $this->team = $team->team;
    $this->user = $current_user;

    $group_users = count($all_users);

    if($challenge->starttime) {
      $starttimeAr = explode(':', $challenge->starttime);
      $starttime = (int)$starttimeAr[0];
    }
    else {
      $starttime = 0;
    }
    if ($challenge->endtime) {
      $endtimeAr = explode(':', $challenge->endtime);
      $endtime = (int)$endtimeAr[0];
    }
    else {
      $endtime = 24;
    }

    $startdate = strtotime($challenge->startdate);
    $enddate = strtotime($challenge->enddate);

    if ($challenge->startdate && $startdate > time()) {
      header('Location: /main/countdown?ju='.$jawbone_user_id);
      exit;
    }
    if ($challenge->enddate && $enddate < time()) {
      if (date('H') >= $endtime) {
        // todo: check if the challenge expired yesterday or earlier
        if (!isset($params['return_data'])) {
          header('Location: /main/finish?ju='.$jawbone_user_id);
          exit;
        }
      }
    }

    $this->starttime = $starttime;
    $this->endtime = $endtime;

    $team_totals = array();
    $team_totals_distance = array();
    $team_today = array();
    $team_today_distance = array();
    $user_totals = 0;
    $user_totals_distance = 0;

    $team_user_steps = array();
    $team_user_distance = array();

    $valid_users = array();

    foreach ($all_users as $user) {

      $teams = TeamUser::model()->findAllByAttributes(new Criteria(array('user_id' => $user->id)));
      $user_team = false;
      foreach ($teams as $user_team) {
        if(!$user_team->team) continue;
        if($user_team->team->challenge_id == $challenge->id) break;
      }
      if (!$user_team) continue;

      $valid_users[] = $user;

      if (substr($user->image,0,5)=='user/') {
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
              @mkdir(dirname(__FILE__).'/../../../../img/user/'.$user->id, 0777);
              if(file_put_contents(dirname(__FILE__).'/../../../../img/user/'.$user->id.'/avatar.'.$ext, $image)) {
                $user->image = 'img/user/' . $user->id . '/avatar.' . $ext;
                $user->save();
              }
            }
          }
        }
      }

      $group_goal += $user->target;

      $days = Day::model()->findAllByAttributes(new Criteria(array(
        'user_id' => $user->id
      )));
      foreach ($days as $day) {
        $date = $day->date;

        $test = strtotime($date);
        if ($startdate && $test < $startdate) continue;
        if ($enddate && $test > $enddate) continue;

        if (!isset($group_steps[$date])) {
          $group_steps[$date] = 0;
          $group_distance[$date] = 0;
          $group_active_time[$date] = 0;
        }
        if (!isset($team_totals[$user_team->team->id])) {
          $team_totals[$user_team->team->id] = 0;
          $team_totals_distance[$user_team->team->id] = 0;
          $team_today[$user_team->team->id] = 0;
          $team_today_distance[$user_team->team->id] = 0;
        }
        if (!isset($team_user_steps[$user_team->team->id])) {
          $team_user_steps[$user_team->team->id] = array();
          $team_user_distance[$user_team->team->id] = array();
        }
        if (!isset($team_user_steps[$user_team->team->id][$user->id])) {
          $team_user_steps[$user_team->team->id][$user->id] = 0;
          $team_user_distance[$user_team->team->id][$user->id] = 0;
        }
        if (!isset($group_week_steps[$date])) {
          $group_week_steps[$date] = 0;
        }

        $hours = Hour::model()->findAllByAttributes(new Criteria(array(
          'day_id' => $day->id
        )));

        foreach ($hours as $hour) {

          if ($hour->hour >= $starttime && $hour->hour <= $endtime) {

            $group_steps[$date] += $hour->steps;
            $group_distance[$date] += $hour->km;
            $group_active_time[$date] += $hour->longestActive;

            $team_totals[$user_team->team->id] += $hour->steps;
            $team_totals_distance[$user_team->team->id] += $hour->km;

            $team_user_steps[$user_team->team->id][$user->id] += $hour->steps;
            $team_user_distance[$user_team->team->id][$user->id] += $hour->km;

            if ($user->id == $current_user->id) {
              $user_totals += $hour->steps;
              $user_totals_distance += $hour->km;
            }

            if ($date == date('Y-m-d')) {
              $team_today[$user_team->team->id] += $hour->steps;
              $team_today_distance[$user_team->team->id] += $hour->km;
            }

            $group_week_steps[$date] += $hour->steps;

          }
        }
      }
    }

    $local_data = array(
      'group_steps' => $group_steps,
      'group_distance' => $group_distance,
      'group_active_time' => $group_active_time,
      'group_users' => $group_users,
      'group_goal' => $group_goal,
      'group_week_steps' => $group_week_steps,
    );

    foreach ($group_steps as $date => $data) {
      $local_data['date_names'][$date] = strftime('%A %e %B, %Y', strtotime($date));
    }

    $my_goal = 10000;

    $date = date('Y-m-d');
    $steps_my = array($date => 0);
    $distance_my = array($date => 0);
    $active_time_my = array($date => 0);
    $longest_active_time_my = array($date => 0);
    $inactive_time_my = array($date => 0);

    $cal_rest = array($date => 0);
    $cal_active = array($date => 0);
    $cal_total = array($date => 0);

    $steps = array($date => 0);
    $week_steps = array($date => 0);
    $distance = array($date => 0);
    $active_time = array($date => 0);
    $longest_active_time = array($date => 0);
    $inactive_time = array($date => 0);

    $data = array($date => array(0));

    foreach(array_keys($local_data['group_steps']) as $date) {
      $test = strtotime($date);
      if ($startdate && $test < $startdate) continue;
      if ($enddate && $test > $enddate) continue;

      $day = Day::model()->findByAttributes(new Criteria(array(
        'user_id' => $current_user->id,
        'date' => $date
      )));

      if ($day) {
        $cal_rest[$date] = $day->calRest;
        $cal_active[$date] = $day->calActive;
        $cal_total[$date] = $day->calTotal;

        $hours = Hour::model()->findAllByAttributes(new Criteria(array(
          'day_id' => $day->id
        )));

        $cols = ($endtime - $starttime) * 4;
        $tmp = array();
        for ($i =0; $i < $cols; $i++) {
          $tmp[str_pad($i, 2, '0', STR_PAD_LEFT)] = 0;
        }
        $data[$date] = $tmp;

        $week_steps[$date] = 0;

        $steps[$date] = 0;
        $distance[$date] = 0;
        $longest_active_time[$date] = 0;
        $active_time[$date] = 0;
        $inactive_time[$date] = 0;

        foreach ($hours as $hour) {

          if ($hour->hour >= $starttime && $hour->hour <= $endtime) {

            $steps_my[$date] = $hour->steps;
            $distance_my[$date] = number_format($hour->km, 1, ',', '.');
            $longest_active_time_my[$date] = $hour->longestActive;
            $active_time_my[$date] = $hour->activeTime;
            $inactive_time_my[$date] = $hour->longestIdle;

            $steps[$date] += $hour->steps;
            $distance[$date] += $hour->km;
            $longest_active_time[$date] += $hour->longestActive;
            $active_time[$date] += $hour->activeTime;
            $inactive_time[$date] += $hour->longestIdle;

            $s = ($hour->hour - $starttime) * 4;
            $k = str_pad($s, 2, '0', STR_PAD_LEFT);
            $quarters = json_decode($hour->quarters, true);
            if (!$quarters) {
              $quarters = array(0 => $hour->steps/4, 15 => $hour->steps/4, 30 => $hour->steps/4, 45 => $hour->steps/4);
            }
            else {
              $quarters[15] = $quarters[15] > 0 ? $quarters[15] - $quarters[0] : 0;
              $quarters[30] = $quarters[30] > 0 ? $quarters[30] - ($quarters[0] + $quarters[15]) : 0;
              $quarters[45] = $quarters[45] > 0 ? $quarters[45] - ($quarters[0] + $quarters[15] + $quarters[30]) : 0;
            }
            // fallback, add all quarters, it must match the no of steps for the hour
            $test = (int)$quarters[0]+(int)$quarters[15]+(int)$quarters[30]+(int)$quarters[45];
            //if ($hour->steps - $test > 5) {
            if ($test == 0) {
              $quarters = array(0 => $hour->steps/4, 15 => $hour->steps/4, 30 => $hour->steps/4, 45 => $hour->steps/4);
            }

            $data[$date][str_pad($k,2,'0', STR_PAD_LEFT)] = $quarters[0];
            $data[$date][str_pad($k+1,2,'0', STR_PAD_LEFT)] = $quarters[15];
            $data[$date][str_pad($k+2,2,'0', STR_PAD_LEFT)] = $quarters[30];
            $data[$date][str_pad($k+3,2,'0', STR_PAD_LEFT)] = $quarters[45];
          }
        }

        $steps[$date] = number_format($steps[$date],0,',','.');
        $distance[$date] = number_format($distance[$date], 2, ',', '.');

        if (strtotime($date) < strtotime('-1 week', strtotime(date('Y-m-d')))) continue;

        $week_steps[$date] += $day->steps;
      }
    }
    $local_data = array_merge($local_data, array(
      'week_steps' => $week_steps,
      'data' => $data,
      'cal_rest' => $cal_rest,
      'cal_active' => $cal_active,
      'cal_total' => $cal_total,
      'steps_my' => $steps_my,
      'distance_my' => $distance_my,
      'longest_active_time_my' => $longest_active_time_my,
      'active_time_my' => $active_time_my,
      'inactive_time_my' => $inactive_time_my,
      'steps' => $steps,
      'distance' => $distance,
      'longest_active_time' => $longest_active_time,
      'active_time' => $active_time,
      'inactive_time' => $inactive_time,
    ));

    $team_data = array();

    $teams = Team::model()->findAllByAttributes(new Criteria(array('challenge_id' => $this->challenge->id)));

    $top5 = array();
    foreach ($team_user_steps as $team_id => $stats) {
      arsort($stats);
      $top5[$team_id] = array_slice($stats,0,5, true);
    }

    $c = 0;
    $finished = false;
    foreach ($teams as $t) {
      $c++;
      $team_data[$c]['id'] = $t->id;
      $team_data[$c]['title'] = $t->title;
      $team_data[$c]['color'] = $t->color;
      $team_data[$c]['progress'] = isset($team_totals_distance[$t->id]) ? round($team_totals_distance[$t->id] / ($challenge->target / 100), 1) : 0;
      if ($team_data[$c]['progress'] >= 100) $finished = true;
      $team_data[$c]['total'] = array(
        'steps' => isset($team_totals[$t->id]) ? $team_totals[$t->id] : 0,
        'distance' => isset($team_totals_distance[$t->id]) ? $team_totals_distance[$t->id] : 0,
      );
      $team_data[$c]['today'] = array(
        'steps' => isset($team_today[$t->id]) ? $team_today[$t->id] : 0,
        'distance' => isset($team_today_distance[$t->id]) ? $team_today_distance[$t->id] : 0
      );
      $ranking = array();
      if (isset($top5[$t->id])) {
        $p = 0;
        foreach ($top5[$t->id] as $user_id => $user_steps) {
          $p++;
          foreach ($valid_users as $valid_user) {
            if ($valid_user->id == $user_id) {
              $ranking[$p] = array(
                'title' => $valid_user->firstName.' '.$valid_user->lastName,
                'avatar' => $valid_user->image==''?'/img/silhouet.png':zeusImages::getPresentation('/'.trim($valid_user->image, '/'), array('width' => 140, 'height' => 140, 'resize_method' => zeusImages::RESIZE_CHOP)),
                'steps' => $user_steps
              );
            }
          }
        }
        $team_data[$c]['ranking'] = $ranking;
      }

    }
    $local_data = array_merge($local_data, array(
      'teams' => $team_data
    ));
    $local_data = array_merge($local_data, array(
      'personal' => array(
        'color' => $this->team?$this->team->color:'cccccc',
        'team' => $this->team?$this->team->id:0,
        'total' => array(
          'steps' => $user_totals,
          'distance' => $user_totals_distance
        ),
        'today' => array(
          'steps' => $steps[date('Y-m-d')],
          'distance' => $distance[date('Y-m-d')]
        )
      )
    ));

    // get goals
    $local_data['my_goal'] = $current_user->target;

    $selectedDate = date('Y-m-d');

    $prev_date = date('Y-m-d', strtotime('-1 day', strtotime($selectedDate)));
    $next_date = date('Y-m-d', strtotime('+1 day', strtotime($selectedDate)));

    $total_cols = ($endtime - $starttime) * 4;

    $this->total_cols = $total_cols;
    $this->local_data = $local_data;
    $this->next_date = $next_date;
    $this->prev_date = $prev_date;
    $this->jawbone_user_id = $jawbone_user_id;

    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      header('Content-Type: application/json');
      echo json_encode(array(
        'local_data' => $local_data,
        'current_date' => date('Y-m-d'),
        'total_cols' => $total_cols,
        'finished' => $finished,
      ));
      exit;
    }

    if ($finished) {
      $challenge->finished = 1;
      $challenge->save();
    }
    if ($finished && !isset($params['return_data'])) {
      header('Location: /main/finish');
      exit;
    }

    if (isset($params['return_data']) && $params['return_data']) {
      return $local_data;
    }
  }

  public function executeGroupchat($params = array())
  {
    $jawbone_user_id = Registry::get('jawbone_user_id');
    $current_user = User::model()->findByAttributes(new Criteria(array('xid' => $jawbone_user_id)));

    $challenge = ChallengeUser::model()->findByAttributes(new Criteria(array('user_id' => $current_user->id)))->challenge;
    $teams = TeamUser::model()->findAllByAttributes(new Criteria(array('user_id' => $current_user->id)));
    foreach ($teams as $team) {
      if(!$team->team) continue;
      if($team->team->challenge_id == $challenge->id) break;
    }

    if (isset($_POST['chat']) && trim($_POST['chat']) != '') {

      $emojis = array(
        // smilies
        "\xf0\x9f\x98\x80" => "1F600",
        "\xf0\x9f\x98\x81" => "1F601",
        "\xf0\x9f\x98\x82" => "1F602",
        "\xf0\x9f\x98\x83" => "1F603",
        "\xf0\x9f\x98\x84" => "1F604",
        "\xf0\x9f\x98\x85" => "1F605",
        "\xf0\x9f\x98\x86" => "1F606",
        "\xf0\x9f\x98\x87" => "1F607",
        "\xf0\x9f\x98\x88" => "1F608",
        "\xf0\x9f\x98\x89" => "1F609",
        "\xf0\x9f\x98\x8a" => "1F60A",
        "\xf0\x9f\x98\x8b" => "1F60B",
        "\xf0\x9f\x98\x8c" => "1F60C",
        "\xf0\x9f\x98\x8d" => "1F60D",
        "\xf0\x9f\x98\x8e" => "1F60E",
        "\xf0\x9f\x98\x8f" => "1F60F",
        "\xf0\x9f\x98\x90" => "1F610",
        "\xf0\x9f\x98\x91" => "1F611",
        "\xf0\x9f\x98\x92" => "1F612",
        "\xf0\x9f\x98\x93" => "1F613",
        "\xf0\x9f\x98\x94" => "1F614",
        "\xf0\x9f\x98\x95" => "1F615",
        "\xf0\x9f\x98\x96" => "1F616",
        "\xf0\x9f\x98\x97" => "1F617",
        "\xf0\x9f\x98\x98" => "1F618",
        "\xf0\x9f\x98\x99" => "1F619",
        "\xf0\x9f\x98\x9A" => "1F61A",
        "\xf0\x9f\x98\x9B" => "1F61B",
        "\xf0\x9f\x98\x9C" => "1F61C",
        "\xf0\x9f\x98\x9D" => "1F61D",
        "\xf0\x9f\x98\x9E" => "1F61E",
        "\xf0\x9f\x98\x9F" => "1F61F",
        "\xf0\x9f\x98\xA0" => "1F620",
        "\xf0\x9f\x98\xA1" => "1F621",
        "\xf0\x9f\x98\xA2" => "1F622",
        "\xf0\x9f\x98\xA3" => "1F623",
        "\xf0\x9f\x98\xA4" => "1F624",
        "\xf0\x9f\x98\xA5" => "1F625",
        "\xf0\x9f\x98\xA6" => "1F626",
        "\xf0\x9f\x98\xA7" => "1F627",
        "\xf0\x9f\x98\xA8" => "1F628",
        "\xf0\x9f\x98\xA9" => "1F629",
        "\xf0\x9f\x98\xAA" => "1F62A",
        "\xf0\x9f\x98\xAB" => "1F62B",
        "\xf0\x9f\x98\xAC" => "1F62C",
        "\xf0\x9f\x98\xAD" => "1F62D",
        "\xf0\x9f\x98\xAE" => "1F62E",
        "\xf0\x9f\x98\xAF" => "1F62F",
        "\xf0\x9f\x98\xB0" => "1F630",
        "\xf0\x9f\x98\xB1" => "1F631",
        "\xf0\x9f\x98\xB2" => "1F632",
        "\xf0\x9f\x98\xB3" => "1F633",
        "\xf0\x9f\x98\xB4" => "1F634",
        "\xf0\x9f\x98\xB5" => "1F635",
        "\xf0\x9f\x98\xB6" => "1F636",
        "\xf0\x9f\x98\xB7" => "1F637",
        "\xf0\x9f\x98\xB8" => "1F638",
        "\xf0\x9f\x98\xB9" => "1F639",
        "\xf0\x9f\x98\xBA" => "1F63A",
        "\xf0\x9f\x98\xBB" => "1F63B",
        "\xf0\x9f\x98\xBC" => "1F63C",
        "\xf0\x9f\x98\xBD" => "1F63D",
        "\xf0\x9f\x98\xBE" => "1F63E",
        "\xf0\x9f\x98\xBF" => "1F63F",
        "\xf0\x9f\x99\x80" => "1F640",
        "\xf0\x9f\x99\x81" => "1F641",
        "\xf0\x9f\x99\x82" => "1F642",
        "\xf0\x9f\x99\x83" => "1F643",
        "\xf0\x9f\x99\x84" => "1F644",
        "\xf0\x9f\x99\x85" => "1F645",
        "\xf0\x9f\x99\x86" => "1F646",
        "\xf0\x9f\x99\x87" => "1F647",
        "\xf0\x9f\x99\x88" => "1F648",
        "\xf0\x9f\x99\x89" => "1F649",
        "\xf0\x9f\x99\x8A" => "1F64A",
        "\xf0\x9f\x99\x8B" => "1F64B",
        "\xf0\x9f\x99\x8C" => "1F64C",
        "\xf0\x9f\x99\x8D" => "1F64D",
        "\xf0\x9f\x99\x8E" => "1F64E",
        "\xf0\x9f\x99\x8F" => "1F64F",

        // transport
        "\xf0\x9f\x9a\x80" => "1F680",
        "\xf0\x9f\x9a\x83" => "1F683",
        "\xf0\x9f\x9a\x84" => "1F684",
        "\xf0\x9f\x9a\x85" => "1F685",
        "\xf0\x9f\x9a\x87" => "1F687",
        "\xf0\x9f\x9a\x89" => "1F689",
        "\xf0\x9f\x9a\x8c" => "1F68C",
        "\xf0\x9f\x9a\x8f" => "1F68F",
        "\xf0\x9f\x9a\x91" => "1F691",
        "\xf0\x9f\x9a\x92" => "1F692",
        "\xf0\x9f\x9a\x93" => "1F693",
        "\xf0\x9f\x9a\x95" => "1F695",
        "\xf0\x9f\x9a\x97" => "1F697",
        "\xf0\x9f\x9a\x99" => "1F699",
        "\xf0\x9f\x9a\x9a" => "1F69A",
        "\xf0\x9f\x9a\xa2" => "1F6A2",
        "\xf0\x9f\x9a\xa4" => "1F6A4",
        "\xf0\x9f\x9a\xa5" => "1F6A5",
        "\xf0\x9f\x9a\xa7" => "1F6A7",
        "\xf0\x9f\x9a\xa8" => "1F6A8",
        "\xf0\x9f\x9a\xa9" => "1F6A9",
        "\xf0\x9f\x9a\xaa" => "1F6AA",
        "\xf0\x9f\x9a\xab" => "1F6AB",
        "\xf0\x9f\x9a\xac" => "1F6AC",
        "\xf0\x9f\x9a\xad" => "1F6AD",
        "\xf0\x9f\x9a\xb2" => "1F6B2",
        "\xf0\x9f\x9a\xb6" => "1F6B6",
        "\xf0\x9f\x9a\xb9" => "1F6B9",
        "\xf0\x9f\x9a\xba" => "1F6BA",
        "\xf0\x9f\x9a\xbb" => "1F6BB",
        "\xf0\x9f\x9a\xbc" => "1F6BC",
        "\xf0\x9f\x9a\xbd" => "1F6BD",
        "\xf0\x9f\x9a\xbe" => "1F6BE",
        "\xf0\x9f\x9b\x80" => "1F6C0",
      );

      // generate the uncategorized icons

      foreach($emojis as $emoji_code => $emoji_name) {
        if (strstr($_POST['chat'], $emoji_code)) {
          $_POST['chat'] = str_replace($emoji_code, '&#'.hexdec($emoji_name).';', $_POST['chat']);
        }
      }

      $message = new Message;
      $message->team_id = $team->team->id;
      $message->sender = $current_user->id;
      $message->date = date('Y-m-d H:i:s');
      $message->cinterface = 'stream';
      $message->message = $_POST['chat'];
      $message->save();

      // send push notifications
      // find all team users
// ANDROID
      $push_api_key = 'AIzaSyD75eOpS3dk3zjjDi5fwYic5LwpVGaY7Ws';
      $receiver_ids = array();
      $team_users = TeamUser::model()->findAllByAttributes(new Criteria(array('team_id' => $team->team->id)));
      foreach ($team_users as $team_user) {
        // get all notifiers for the user
        $notifiers = Notifier::model()->findAllByAttributes(new Criteria(array('user_id' => $team_user->user->id, 'pushDevice' => 'android')));
        foreach ($notifiers as $notifier) {
          $receiver_ids[] = $notifier->pushId;
        }
      }
      if (count($receiver_ids) > 0) {
        $data = array(
          'registration_ids' => $receiver_ids,
          'data' => array(
            'message' => $current_user->firstName . ': ' . $_POST['chat']
          ),
        );
        $url = 'https://android.googleapis.com/gcm/send';

        $headers = array(
          'Authorization: key=' . $push_api_key,
          'Content-Type: application/json'
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($curl);
      }
      
// IOS
      $receiver_ids = array();
      foreach ($team_users as $team_user) {
        // get all notifiers for the user
        $notifiers = Notifier::model()->findAllByAttributes(new Criteria(array('user_id' => $team_user->user->id, 'pushDevice' => 'ios')));
        foreach ($notifiers as $notifier) {
          $receiver_ids[] = $notifier->pushId;
        }
      }
      if (count($receiver_ids) > 0) {
        $passphrase = 'm00nr1s1n@';
        $message = $current_user->firstName . ': ' . $_POST['chat'];  
      
        foreach ($receiver_ids as $receiver_id) {
          
          $ctx = stream_context_create();
          stream_context_set_option($ctx, 'ssl', 'local_cert', 'apn.pem');
          stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
          
          // Open a connection to the APNS server
          $fp = stream_socket_client(
          	'ssl://gateway.sandbox.push.apple.com:2195', $err,
          	$errstr, 15, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
          
          if ($fp) {
          
            //echo 'Connected to APNS' . PHP_EOL;
            
            // Create the payload body
            $body['aps'] = array(
            	'alert' => $message,
            	'sound' => 'default'
            	);
            
            // Encode the payload as JSON
            $payload = json_encode($body);
            
            // Build the binary notification
            $msg = chr(0) . pack('n', 32) . pack('H*', $receiver_id) . pack('n', strlen($payload)) . $payload;
            
            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));
            /*
            if (!$result)
            	echo 'Message not delivered' . PHP_EOL;
            else
            	echo 'Message successfully delivered' . PHP_EOL;
            */
            // Close the connection to the server
            fclose($fp);
          }
        }
      }
    }

    if (isset($_POST['read']) && $_POST['read']) {
      $current_user->lastRead = date('Y-m-d H:i:s');
      $current_user->save();
    }

    // count messages
    $c = new Criteria(array('team_id' => $team->team->id, 'date' => array($current_user->lastRead, '>')), null, 'date DESC LIMIT 50');
    $count = Message::model()->count($c);

    // find team messages
    $messages = Message::model()->findAllByAttributes(new Criteria(array('team_id' => $team->team->id), null, 'date DESC LIMIT 50'));
    ob_start();
    ?>
    <ul>
      <?php
      $senders = array();
      $first = true;
      foreach ($messages as $message) {
        if (!isset($senders[$message->sender])) {
          $senders[$message->sender] = User::model()->findByPk($message->sender);
        }
        if ($first) {
          $first = false; ?>
          <li style="text-align:right;padding: 0.1em 0.1em 0 0;font-size:0.3em;font-style:italic;"><?php echo date('H:i', strtotime($message->date)); ?></li>
        <?php
        }
        ?>
        <li><strong<?php if ($current_user->id == $message->sender) echo ' style="color:#41df22;"' ?>><?php echo $senders[$message->sender]->firstName; ?>:</strong> <?php echo $message->message; ?></li>
      <?php }
      if(count($messages) < 50) { ?>
        <li><strong style="color:#ccfc0c;">Beheerder:</strong> Welkom bij de team-chat!</li>
      <?php } ?>
    </ul>
    <?php
    $html = ob_get_clean();
    $data = array(
      'html' => $html,
      'count' => $count,
    );
    // find popup message, if any
    $message = Message::model()->findByAttributes(new Criteria(array('user_id' => $current_user->id, 'cinterface' => 'popup', 'delivered' => 0), null, 'date DESC'));
    if ($message) {
      $data['popup'] = array(
        'title' => $message->title,
        'content' => $message->message
      );
      $message->delivered = 1;
      $message->save();
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
  }

  public function executeSignature($params = array())
  {
    if (isset($_POST['image'])) {
      $filename = time().'.png';

      $image = base64_decode($_POST['image']);
      file_put_contents(getcwd().'/img/signatures/'.$filename, $image);
      $url = 'https://'.$_SERVER['SERVER_NAME'].'/img/signatures/'.$filename;
      echo 'Bestand opgeslagen: <a target="_blank" href="'.$url.'">'.$url.'</a>';
      exit;
    }
  }

  public function executeImagecapture($params = array())
  {
    if (count($_FILES) > 0) {
      foreach ($_FILES as $file) {
        if ($file['size'] == 0) continue;
      }
      $filename = $file['name'];
      move_uploaded_file($file['tmp_name'], getcwd().'/img/signatures/'.$filename);

      $url = 'https://'.$_SERVER['SERVER_NAME'].'/img/signatures/'.$filename;
      echo 'Bestand opgeslagen: <a target="_blank" href="'.$url.'">'.$url.'</a>';
      exit;
    }
  }

}
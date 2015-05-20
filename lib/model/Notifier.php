<?php

class Notifier extends Base {
  public $model = 'notifier';

  public $relations = array(
    'user' => 'user_id',
  );

  public static function model()
  {
    return new Notifier;
  }
}
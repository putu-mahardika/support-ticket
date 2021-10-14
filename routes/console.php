<?php

use Illuminate\Foundation\Inspiring;
use App\Helpers\MqttHelper;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('mqtt:subscribe {topic} {--qos=0}', function ($topic, $qos) {
    // $this->info("Connecting to MQTT broker server with topic '$topic'....");
    MqttHelper::subscribe($topic, $qos);
});

Artisan::command('mqtt:publish {topic} {--qos=0} {--retain=false}', function ($topic, $qos, $retain) {
    $msg = $this->ask('The message is');
    $this->info("Connecting to MQTT broker server with topic '$topic'....");
    MqttHelper::publish($topic, $msg, $qos, $retain);
});

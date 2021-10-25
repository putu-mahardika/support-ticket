<?php

namespace App\Helpers;

use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\Exceptions\ConnectionNotAvailableException;
use PhpMqtt\Client\Exceptions\MqttClientException;


class MqttHelper {

    public static function subscribe($topic = 'test', $qos = 0)
    {
        $mqtt = MQTT::connection();
        $mqtt->subscribe($topic, function (string $topic, string $message) {
            print_r(json_decode($message));
        }, $qos);
        $mqtt->loop(true);
        // try {
        // } catch (MqttClientException $e) {
        //     echo $e->getMessage();
        // } catch (ConnectionNotAvailableException $e) {
        //     echo $e->getMessage();
        // } catch (\Exception $e) {
        //     echo $e->getMessage();
        // }
    }

    public static function publish($topic, $message, $qos = 0, $retainMessage = false)
    {
        
        try {
            $mqtt = MQTT::connection();
            $mqtt->publish($topic, $message, $qos, $retainMessage);
        } catch (MqttClientException $e) {
            return $e->getMessage();
        } catch (ConnectionNotAvailableException $e) {
            echo $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}

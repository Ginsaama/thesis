<?php

return [
    'broker_host' => env('MQTT_HOST', 'localhost'),
    'broker_port' => env('MQTT_PORT', 1883),
    'username' => env('MQTT_AUTH_USERNAME', null),
    'password' => env('MQTT_AUTH_PASSWORD', null),
    'topic' => env('MQTT_TOPIC', 'Test1'),
];

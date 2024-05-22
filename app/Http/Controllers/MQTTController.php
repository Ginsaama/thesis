<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessMqttMessage;
use Exception;
use Illuminate\Http\Request;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;
use Illuminate\Support\Facades\Log;

class MQTTController extends Controller
{
    //
    public function publishMessage(Request $request)
    {
        try {
            // Retrieve the entire JSON payload from the request
            $requestData = $request->json()->all();

            // Extract the driver name and tricycle number from the JSON payload
            $fullName = $requestData['fullname'];
            $driverName = $requestData['driverName'];
            $tricycleNo = $requestData['tricycleNo'];


            // Now you can use $driverName and $tricycleNo as needed

            // Example: Publish the driver name and tricycle number to a MQTT topic
            MQTT::publish('test/1', json_encode(['fullname' => $fullName, 'driverName' => $driverName, 'tricycleNo' => $tricycleNo]));

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            Log::error('Failed to publish message: ' . $e->getMessage());
            // Handle the exception or error as needed
            return response()->json(['status' => 'error', 'message' => 'Failed to publish message'], 500);
        }

        // $message = $request->input('message', 'Hello world!');

        // MQTT::connection()
        //     ->to(config('mqtt.broker_host'))
        //     ->atPort(config('mqtt.broker_port'))
        //     ->publish(config('mqtt.topic'), $message, 0, false);

        // return response()->json(['status' => 'success']);
    }
    public function receiveMessage(Request $request)
    {
        try {
            // Check if the request header indicates an aborted request
            if ($request->header('X-Aborted-Request') === 'true') {
                // Handle aborted request scenario
                // For example, log the event or take appropriate action
                Log::info('Request aborted by client');
                // Respond with an appropriate status code or message

                return response()->json(['error' => 'Request aborted by client'], 400);
            }
            // Initialize variable to store the received message
            $receivedMessage = null;
            // Create a new MQTT client instance
            $client = new MqttClient('broker.hivemq.com', 1883, 'mqttx_340b0a34', '3.1', null);
            // Connect to the MQTT broker
            $client->connect();
            // Subscribe to the topic 'foo/bar/baz' using QoS 0.
            $client->subscribe('jay/#', function (string $topic, string $message, bool $retained) use ($client, &$receivedMessage) {
                // Create a JSON object containing the received message details
                $receivedMessage = json_encode([
                    'topic' => $topic,
                    'message' => $message,
                ]);

                $client->interrupt();

                // ProcessMqttMessage::dispatch($topic, $message);

                // After receiving the first message on the subscribed topic, we want the client to stop listening for messages.
            }, MqttClient::QOS_AT_MOST_ONCE);
            // Since subscribing requires to wait for messages, we need to start the client loop which takes care of receiving,
            // parsing and delivering messages to the registered callbacks. The loop will run indefinitely, until a message
            // is received, which will interrupt the loop.

            // Abort the receiveMessage function


            $client->loop(true);

            // Gracefully terminate the connection to the broker.
            $client->disconnect();
            return ($receivedMessage);
        } catch (Exception $e) {
            $exceptionJson = json_encode([
                'error' => 'An error occurred',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Output the JSON object
            echo $exceptionJson;
        }
    }

    public function receiveRating()
    {
        try {
            // Initialize variable to store the received message
            $receivedMessage = null;
            // Create a new MQTT client instance
            $client = new MqttClient('broker.hivemq.com', 1883, 'mqttx_340b0a34', '3.1', null,);
            // Connect to the MQTT broker
            $client->connect();
            // Subscribe to the topic 'foo/bar/baz' using QoS 0.
            $client->subscribe('Ratings/#', function (string $topic, string $message, bool $retained) use ($client, &$receivedMessage) {
                // Create a JSON object containing the received message details
                $receivedMessage = json_encode([
                    'topic' => $topic,
                    'message' => $message,
                ]);
                $client->interrupt();
                // ProcessMqttMessage::dispatch($topic, $message);

                // After receiving the first message on the subscribed topic, we want the client to stop listening for messages.
            }, MqttClient::QOS_AT_MOST_ONCE);

            // Since subscribing requires to wait for messages, we need to start the client loop which takes care of receiving,
            // parsing and delivering messages to the registered callbacks. The loop will run indefinitely, until a message
            // is received, which will interrupt the loop.
            $client->loop(true);

            // Gracefully terminate the connection to the broker.
            $client->disconnect();
            return ($receivedMessage);
        } catch (Exception $e) {
            $exceptionJson = json_encode([
                'error' => 'An error occurred',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Output the JSON object
            echo $exceptionJson;
        }
    }
}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MQTT Client</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.1.0/mqttws31.min.js"></script>
</head>

<body>

    <h1>Hi</h1>
    <script>
        // Create a client instance
        const client = new Paho.MQTT.Client('broker.hivemq.com', 1883, 'mqttx_340b0a34');

        // Set callback handlers
        client.onConnectionLost = onConnectionLost;
        client.onMessageArrived = onMessageArrived;

        // Connect the client
        client.connect({
            onSuccess: onConnect
        });

        // Called when the client connects
        function onConnect() {
            console.log('Connected to MQTT broker');
            // Subscribe to a topic
            client.subscribe('topic/foo');
        }

        // Called when a message arrives
        function onMessageArrived(message) {
            console.log('Received message:', message.payloadString);
            // Handle the received message
        }

        // Called when the connection is lost
        function onConnectionLost(responseObject) {
            if (responseObject.errorCode !== 0) {
                console.log('Connection lost:', responseObject.errorMessage);
            }
        }

        // Disconnect the client when the page unloads
        window.addEventListener('beforeunload', () => {
            client.disconnect();
        });
    </script>
</body>

</html>

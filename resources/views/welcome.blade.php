@extends('layouts.app')

@section('content')
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAICgUsG-UUCRWbzKe_Qs0PihdLh1I4V0M&callback=initMap&libraries=&v=weekly"
        async></script>
    {{-- <form id="chatForm" method="post">
        <input type="text" id="message" placeholder="Type your message">
        <button type="button" onclick="sendMessage()">Send</button>
    </form>
    <h1>Received Messages</h1>
    <ul id="receivedMessages"></ul> --}}

    <div class="container-fluid h-100">
        <div class="row h-100 d-flex">
            <!-- Map -->
            <div class="col-7 p-5 h-100 d-flex flex-column" id="map">
            </div>
            <!-- Sidebar -->
            <div class="col-5 p-5 d-flex flex-column align-items-center justify-content-center contain-searching"
                id="sidebar">
                <img src="" alt="">
                <h2 class="mt-3 fw-bold">Looking for Passengers</h2>
            </div>

        </div>
    </div>

    <script>
        // For the MQTT receive function
        var messageListenerActive = true
        var sampleLocation = {
            lat: 14.453104,
            lng: 121.040086
        }
        var sampleMarker;
        var map;
        const placeCoordinates = {
            "White": {
                lat: 14.454627,
                lng: 121.040272
            },
            "Red": {
                lat: 14.454293,
                lng: 121.040123
            },
            "Blue": {
                lat: 14.453963,
                lng: 121.039944
            },
            "Yellow": {
                lat: 14.453591,
                lng: 121.039854
            },
            "Green": {
                lat: 14.453250,
                lng: 121.039818
            },
            "Orange": {
                lat: 14.453104,
                lng: 121.040086
            },
            "Gray": {
                lat: 14.451616,
                lng: 121.040117
            },
            "Purple": {
                lat: 14.450598,
                lng: 121.038674
            },
            "Fuschia": {
                lat: 14.451111,
                lng: 121.036992
            },
            "Vermillion": {
                lat: 14.450746,
                lng: 121.036303
            },

            // Add more places as needed
        };
        async function initMap() {
            const initialCenter = {
                lat: 14.453104,
                lng: 121.040086
            };
            // The location of Geeksforgeeks office
            // Create the map, centered at gfg_office
            map = new google.maps.Map(
                document.getElementById("map"), {
                    // Set the zoom of the map
                    zoom: 17,
                    center: initialCenter,
                    zoomControl: false, // Disable the zoom control
                    fullscreenControl: false, // Disable the full view control
                });
            sampleMarker = new google.maps.Marker({
                position: sampleMarker,
                map: map,
                title: "Geeksforgeeks Office"
            });
        }


        function handleAccept() {
            messageListenerActive = false;
            var message = "Accepted OTW";
            fetch('/publish-message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    document.getElementById('sidebar').innerHTML = `  <a href="../html/home.html">
                    < </a>
                        <img src="" alt="">
                        <h2 class="mt-3 fw-bold">Looking for Passengers</h2>`;

                })
                .catch(error => console.error('Error:', error))
                .finally(() => {
                    // Reset the message listener flag after sending the message
                    // Start fetching new requests after a delay
                    // setTimeout(fetchNewRequests, 5000);
                    fetchMQTTMessages();
                });
        }

        function receiveMessage(name, to, from, fare, notes, landmark) {
            // var receivedMessagesElement = document.getElementById('receivedMessages');
            // var listItem = document.createElement('li');
            // listItem.textContent = message;
            // receivedMessagesElement.appendChild(listItem);

            document.getElementById('sidebar').innerHTML = `
            <div class="container transaction-details p-0">
                    <h1 class="fw-bold">Passenger Found</h1>
                    <div class="customer-details d-flex align-items-center">
                        <img src="images/user-icon.ico" alt="profile-icon">
                        <div class="customer">
                            <h5>Name: ${name}</h5>
                            <h5>to: ${to}</h5>
                            <h5>from: ${from}</h5>
                            <h5>landmark: ${landmark}</h5>
                        </div>
                    </div>
                    <h4>Fare Details</h4>
                    <h4>${fare}</h4>
                    <h4>Payment Method</h4>
                    <div class="payment-method d-flex align-items-center">
                        <img src="images/ion-cash.png" class="ms-1" alt="cash icon">
                        <h4>Cash</h4>
                    </div>
                    <h4>Passenger Notes</h4>
                    <div class="passenger-notes">${notes}</div>

                    <!-- buttons -->
                    <div class="container d-flex buttons mt-2">
                        <button class="btn btn-success mr-5" id="acceptButton" href="">
                            Accept
                        </button>
                        <button class="btn btn-danger" id="toggleStatus" href="" id="declineButton">
                            Decline
                        </button>
                    </div>
                </div>`;
            document.getElementById('acceptButton').addEventListener('click', handleAccept);
        }
        // var sampleMessage = "This is a test message";
        // // Call the receiveMessage function with the sample data
        // receiveMessage(sampleMessage);
        // Receive MQTT Messages
        function fetchMQTTMessages() {
            fetch('/receive-messages', {
                    method: 'GET', // Use GET method for receiving messages
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    // Extract the message from the data object
                    // const message = data.msg;
                    // Pass the message to the receiveMessage function
                    // console.log(data.message);
                    // console.log("Received message:", data.message); // Log the received message
                    // console.log("Type of message:", typeof data.message);
                    const messageData = JSON.parse(data.message);
                    const messageData2 = JSON.parse(messageData);
                    console.log('This is parsed again', messageData2);
                    console.log('parsed Data', messageData2);
                    console.log("Type of message:", typeof messageData2);
                    console.log(messageData2.name);
                    console.log(messageData2.to);
                    console.log(messageData2.from);
                    // Marks the place
                    const fromPlace = messageData2.from;
                    const fromLocation = placeCoordinates[fromPlace];
                    if (fromLocation) {
                        // Update map marker position
                        sampleMarker.setPosition(fromLocation);
                        // Center the map around the new marker position
                        map.setCenter(fromLocation);
                    } else {
                        console.error("Coordinates not found for place:", fromPlace);
                    }
                    console.log(messageData2.fare);
                    console.log(messageData2.landmark);
                    console.log(messageData2.notes);
                    // var jsonString =
                    //     "{\"name\":\"John\",\"address\":\"Sample Location\",\"fare\":100,\"landmark\":\"Sample Landmark\",\"notes\":\"Sample Notes\"}";
                    // var decodedData = JSON.parse(jsonString);
                    // console.log(decodedData);
                    // console.log(jsonString);
                    // console.log("Type of message:", typeof decodedData);
                    // console.log(decodedData.name);
                    receiveMessage(messageData2.name, messageData2.to, messageData2.from, messageData2.fare,
                        messageData2.notes,
                        messageData2.landmark);
                    saveToDb(messageData2.name, messageData2.to, messageData2.from, messageData2.fare,
                        messageData2.notes,
                        messageData2.landmark);
                    console.log('Successfully saved to DB')
                })
                .catch(error => console.error('Error fetching MQTT messages:', error))
                .finally(() => {
                    // Call fetchMQTTMessages again after a delay, if the listener is still active
                    if (messageListenerActive) {
                        setTimeout(fetchMQTTMessages, 1000); // Fetch messages every 1 second
                    }
                });
        }
        // Call fetchMQTTMessages initially to load messages
        if (messageListenerActive == true) {
            fetchMQTTMessages();
            messageListenerActive = false;
        }

        function saveToDb(name, from, to, fare, landmark, notes) {
            fetch('/save-to-db', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: name,
                        from: from,
                        to: to,
                        fare: fare,
                        landmark: landmark,
                        notes: notes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Successfully saved to DB:', data);
                })
                .catch(error => console.error('Error saving to DB:', error));
        }
    </script>
@endsection

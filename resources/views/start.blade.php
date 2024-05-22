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
                <h2 class="mt-3 fw-bold">Looking for Passengers...</h2>
                <h3 class="mt-5 fw-bold">Where to?</h3>
            </div>

        </div>

        {{-- <div class="container transaction-details p-0">
            <h1 class="fw-bold">Passenger Found</h1>
            <div class="customer-details d-flex align-items-center">
                <img src="images/user-icon.ico" alt="profile-icon">
                <div class="customer">
                    <h5>Name:</h5>
                    <h5>to: /h5>
                        <h5>from: </h5>
                        <h5>landmark:</h5>
                </div>
            </div>
            <h4>Fare Details</h4>
            <h4></h4>
            <h4>Payment Method</h4>
            <div class="payment-method d-flex align-items-center">
                <img src="images/ion-cash.png" class="ms-1" alt="cash icon">
                <h4>Cash</h4>
            </div>
            <h4>Passenger Notes</h4>
            <div class="passenger-notes"></div>
            <div class="form-group">
                <label for="rfidInput">Enter RFID:</label>
                <input type="text" class="form-control" id="rfidInput" placeholder="Enter RFID" name="rfid" autofocus>
            </div>
            <!-- buttons -->
            <div class="container d-flex buttons mt-2">
                <button class="btn btn-success mr-5" id="acceptButton" href="">
                    Accept
                </button>
                <button class="btn btn-danger" id="toggleStatus" href="" id="declineButton">
                    Decline
                </button>
            </div>
        </div>`; --}}
    </div>

    <script>
        // Variable for the transaction Info
        let savedName, savedTo, savedFrom, savedFare, savedNotes, savedLandmark, savedStatus;
        let savedPlace;
        var userName;
        // For stuff
        // let originalReceiveMessage = fetchMQTTMessages;

        // Abortion
        let fetchController;

        var messageListenerActive = true
        var sampleLocation = {
            lat: 14.453104,
            lng: 121.040086
        }
        var sampleMarker;
        var map;
        const fare = {
            "Rainbow": 10, // Example price for Rainbow
            "Blue": 15, // Example price for Blue
            "Something else": 20 // Example price for Something else
        }

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
        // Handles the RFID
        function handleRFIDInput(event) {
            const rfid = event.target.value;
            fetchDriverInfo(rfid);
        }

        // Fetches the data from the database
        function fetchDriverInfo(rfid) {
            fetch(`/get-driver-info/${rfid}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Handle the driver information received
                    // For example, update the UI with the driver's name, license number, etc.
                    console.log(data);
                    console.log(data.driver_id);
                    const rfidInfo = data;
                    driverName = rfidInfo.first_name;
                    tricycleNo = rfidInfo.model;
                    console.log('Hello I am here now');
                    console.log('The id of driver is ', data.driver_id);
                    console.log('The name of driver is ', data.first_name);
                    console.log('The model of driver is ', data.model);
                    console.log(driverName);
                    console.log(tricycleNo);
                    console.log(savedName);
                    // const passengerId = await getOrSavePassenger(savedName);
                    // console.log('Returned Passenger ID:', passengerId);
                    getOrSavePassenger(savedName).then(passengerId => {
                        console.log('Resolved Passenger ID:', passengerId);
                        saveToDb(data.driver_id, passengerId, getCurrentDateTime(), savedTo, savedFrom,
                            savedFare,
                            savedNotes,
                            savedLandmark,
                            "Complete");

                    }).catch(error => {
                        console.error('Error:', error);
                    });
                    handleAccept(rfidInfo.first_name, rfidInfo.model, savedName)

                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }


        // Handles the accept of the button
        function handleAccept(driverName, tricycleNo, verifyName) {
            messageListenerActive = false;
            fetch('/publish-message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        fullname: verifyName,
                        driverName: driverName,
                        tricycleNo: tricycleNo
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    document.getElementById('sidebar').innerHTML = `  <a href="../html/home.html">
                < </a>
                    <img src="" alt="">
                    <h2 class="mt-3 fw-bold">Looking for Passengers</h2><h3 class="mt-5 fw-bold">Where to?</h3>
                            </div>
                        </div>
                    </div>
                </div>`;

                })
                .catch(error => console.error('Error:', error))
                .finally(() => {
                    // Reset the message listener flag after sending the message
                    // Start fetching new requests after a delay
                    // setTimeout(fetchNewRequests, 5000);
                    fetchMQTTMessages();
                });
        }
        // Received Message function that changes the view once it receives something
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
                    <div class="form-group">
                         <label for="rfidInput">Enter RFID:</label>
                         <input type="text" class="form-control" id="rfidInput" placeholder="Enter RFID">
                    </div>
                    <!-- buttons -->
                </div>`;
            document.getElementById('rfidInput').addEventListener('input', handleRFIDInput);
        }

        // It fetches MQTT Messages for Booking Request
        async function fetchMQTTMessages() {
            try {
                fetchController = new AbortController();
                const {
                    signal
                } = fetchController;
                const response = await fetch('/receive-messages', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    signal // Pass the AbortController signal
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                console.log(data);
                const messageData = JSON.parse(data.message);
                const messageData2 = JSON.parse(messageData);
                console.log('This is parsed again', messageData2);
                console.log('parsed Data', messageData2);
                console.log("Type of message:", typeof messageData2);
                console.log(messageData2.name);
                console.log(messageData2.to);
                console.log(messageData2.from);
                userName = messageData2.name;
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
                savedName = messageData2.name;
                savedTo = messageData2.to;
                savedFrom = messageData2.from;
                savedFare = messageData2.fare;
                savedNotes = messageData2.notes;
                savedLandmark = messageData2.landmark;
                receiveMessage(messageData2.name, messageData2.to, messageData2.from, messageData2.fare,
                    messageData2.notes,
                    messageData2.landmark);
                // saveToDb(messageData2.name, messageData2.to, messageData2.from, messageData2.fare,
                //     messageData2.notes,
                //     messageData2.landmark);

            } catch (error) {
                if (error.name === 'AbortError') {
                    console.error('Yeah its aborted', error);
                    console.error('Error:', error);
                    // Send a custom header indicating aborted request
                } else {
                    console.error('Error fetching MQTT messages:', error);
                }
            } finally {}
        }
        // Fetching Rating Continously
        async function fetchRatings() {
            try {
                const response = await fetch('/receive-ratings', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                console.log(data);
                const messageData = JSON.parse(data.message);
                const messageData2 = JSON.parse(messageData);
                console.log('This is parsed again', messageData2);
                console.log('parsed Data', messageData2);
                console.log("Type of message:", typeof messageData2);

            } catch (error) {
                console.error('Error fetching MQTT messages:', error);
            } finally {
                setTimeout(fetchRatings, 1000); // Fetch messages every 1 second
            }
        }

        function getCurrentDateTime() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }


        function saveToDb(driver_id, passenger_id, date, pickup_point, dropoff_point, fare_amount, landmark, notes,
            status) {
            fetch('/save-to-db', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        driver_id: driver_id,
                        passenger_id: passenger_id,
                        date: date,
                        pickup_point: pickup_point,
                        dropoff_point: dropoff_point,
                        fare_amount: fare_amount,
                        landmark: landmark,
                        notes: notes,
                        status: status

                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('These are the data my nigga', data);
                })
                .catch(error => console.error('Error saving to DB:', error));
        }
        // fetchRatings();
        if (messageListenerActive == true) {
            fetchMQTTMessages();
            messageListenerActive = false;
        }
        // fetchRatings();

        // Save or get passengers ID
        async function getOrSavePassenger(name) {
            try {
                const response = await fetch('/get-or-save-passenger', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: name
                    })
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                console.log(data);
                console.log('Passenger ID:', data.id);
                return data.id;
            } catch (error) {
                console.error('Error:', error);
            }
        }




        // This will log the initial value of savedPlace, which is undefined
    </script>
@endsection

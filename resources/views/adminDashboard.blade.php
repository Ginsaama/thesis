<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script defer src="js/bootstrap.bundle.js"></script>

    <title>Dashboard</title>

</head>

<body>
    <div class="container-fluid h-100">
        <div class="row h-100 d-flex">
            <!-- Sidebar -->
            <div class="col-3 p-5 d-flex flex-column">
                <h1 class="">Dashboard</h1>
                <div class="row mt-5 tab active">
                    <img src="../images/user-icon.ico">
                    <h5 class="m-0">Dashboard</h5>
                </div>
                <div class="row mt-4 tab">
                    <img src="../images/user-icon.ico">
                    <h5 class="m-0">Driver</h5>
                </div>
                <div class="row mt-4 tab">
                    <img src="../images/settings.png">
                    <h5 class="m-0">User</h5>
                </div>
                <button class="btn btn-primary mt-5" id="toggleStatus">Toggle Status</button>
            </div>

            <!-- Content -->
            <div class="col-9 content">
                <!-- Kiosk Status -->
                <div class="container d-flex" id="kioskStatus">
                    <h2 class="me-3">Kiosk Status</h2>
                    <div class="status" id="status">
                        <div class="status-indicator" id="status-indicator"></div>
                        <div class="status-text">Online</div>
                    </div>
                </div>

                <!-- Cards -->
                <div class="container d-flex justify-content-between">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title fs-1">225</h5>
                            <p class="card-text">Drivers</p>
                            <a href="#" class="btn btn-primary">More info</a>
                        </div>
                    </div>
                    <div class="card mb-3"">
                        <div class="card-body">
                            <h5 class="card-title fs-1">538</h5>
                            <p class="card-text">Users</p>
                            <a href="#" class="btn btn-primary">More info</a>
                        </div>
                    </div>
                    <div class="card mb-3"">
                        <div class="card-body">
                            <h5 class="card-title fs-1">435</h5>
                            <p class="card-text">Reports</p>
                            <a href="#" class="btn btn-primary">More info</a>
                        </div>
                    </div>
                </div>

                <!-- graphs -->
                <div class="container d-flex">
                    <div class="chart w-50">
                        <canvas id="barChart"></canvas>
                    </div>
                    <div class="chart w-50">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="js/status.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/chart1.js"></script>
    <script src="js/chart2.js"></script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/driver.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script defer src="{{ asset('js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('../node_modules/chart.js/dist/chart.umd.js') }}"></script>
    <title>Transactions</title>


</head>

<body>
    <div class="container-fluid h-100">
        <div class="row h-100 d-flex">
            <!-- Sidebar -->
            @include('layouts.sidebar', ['activeLink' => 'driver'])

            <!-- Content -->
            <div class="col-9 content">
                <div class="d-flex justify-content-between align-items-center mt-5">
                    <h2 class="fw-bold">Driver's Transactions</h2>
                    <a class="btn btn-primary" href="{{ route('transactions.create-payment') }}">
                        Add Payment
                    </a>
                </div>

                <!-- Filter Form -->
                <h3 class="mt-5">Completed Payments</h3>
                <form action="{{ route('filterPayments') }}" method="GET" class="form-inline my-3">
                    @csrf
                    <div class="d-flex">
                        <div class="form-group mb-2 mr-2">
                            <label for="date" class="sr-only">Select Date:</label>
                            <input type="date" id="date" name="date" class="form-control" required>
                        </div>
                        <button type="submit" class="">Filter</button>
                    </div>
                </form>

                @if (isset($completedPayments) && isset($pendingDrivers))
                    <!-- Completed Payments Table -->
                    <div class="mt-5">
                        <h4>Completed Payments</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Driver ID</th>
                                    <th>Commission</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($completedPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->driver_id }}</td>
                                        <td>{{ $payment->toda_commission }}</td>
                                        <td>{{ $payment->toda_paid }}</td>
                                        <td>{{ $payment->toda_balance }}</td>
                                        <td>{{ $payment->toda_payment_status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pending Payments Table -->
                    <div class="mt-5">
                        <h4>Pending Payments</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Driver ID</th>
                                    <th scope="col">Driver Name</th>
                                    <th scope="col">Total Trips</th>
                                    <th scope="col">Commission</th>
                                    <th scope="col">Paid</th>
                                    <th scope="col">Balance</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingDrivers as $driver)
                                    <tr>
                                        <td>{{ $driver->driver_id }}</td>
                                        <td>{{ $driver->first_name . ' ' . $driver->last_name }}</td>
                                        <td>{{ $driver->total_trips }}</td>
                                        <form method="POST"
                                            action="{{ route('transactions.update-payments', $driver->driver_id) }}">
                                            @csrf
                                            @method('PUT')
                                            <td> <!-- TODA Commission -->
                                                <input type="number" name="toda_commission"
                                                    value="{{ $driver->toda_commission }}">
                                            </td>
                                            <td> <!-- TODA Paid -->
                                                <input type="number" name="toda_paid"
                                                    value="{{ $driver->toda_paid }}">
                                            </td>
                                            <td> <!-- TODA Balance -->
                                                <input type="number" name="toda_balance"
                                                    value="{{ $driver->toda_balance }}" readonly>
                                            </td>
                                            <td>
                                                <select name="toda_payment_status" disabled>
                                                    <option value="Completed"
                                                        {{ $driver->toda_balance == 0 ? 'selected' : '' }}>Completed
                                                    </option>
                                                    <option value="Pending"
                                                        {{ $driver->toda_balance != 0 ? 'selected' : '' }}>
                                                        Pending</option>
                                                </select>
                                            </td>
                                            <td>
                                                <button type="submit" class="">Save</button>
                                            </td>
                                        </form>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>

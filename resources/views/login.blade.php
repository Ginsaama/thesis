<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
</head>

<body>
    <section class="vh-100">
        <div class="container py-5 h-150">
            <div class="row d-flex justify-content-center align-items-center h-100">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" id="error-message">{{ $error }}</div>
                    @endforeach
                @endif
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <div class="mb-md-5 mt-md-4 pb-5">
                                <h2 class="fw-bold mb-2 text-uppercase">TrikeGo</h2>
                                <p class=" mb-5">Please enter your login and password!</p>
                                {{-- Forms --}}
                                <form action="{{ route('admin.login') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    {{-- Input Text --}}
                                    <div class="form-outline form-white mb-4">
                                        <input type="text" name="username" class="form-control form-control-lg" />
                                        <div class="text-start mt-2">
                                            <label class="form-label" for="typeEmailX">Username</label>
                                        </div>
                                    </div>
                                    {{-- Input Password --}}
                                    <div class="form-outline mb-4">
                                        <input type="password" name="password" class="form-control form-control-lg" />
                                        <div class="text-start mt-2">
                                            <label class="form-label" for="typePasswordX">Password</label>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-primary btn-lg px-5" type="submit">Login</button>
                                </form>
                                {{-- End of Forms --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <script>
        // Add this script to hide the error message after 3 seconds using jQuery
        $(document).ready(function() {
            setTimeout(function() {
                $('#error-message').hide();
            }, 3000);
        });
    </script>
</body>

</html>

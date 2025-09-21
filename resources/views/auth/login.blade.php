<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons / Fonts --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Viga" rel="stylesheet">

    {{-- Favicon / Theme assets (optional) --}}
    <link rel="icon" type="image/x-icon" href="{{ url('public/frontend/images/logo.png') }}">
    <link rel="icon" href="{{ url('public/assets/dist/img/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .border-modifier {
            border-right: 1px solid grey;
        }

        @media (max-width: 767px) {
            .border-modifier {
                border-right: none;
            }
        }

        .input-group-text {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row d-flex align-items-center" style="min-height: 100vh;">
            {{-- Left logo panel --}}
            <div
                class="mt-5 col-lg-6 col-md-6 col-sm-12 d-flex justify-content-center align-items-center border-modifier">
                <img width="50%" class="img-fluid" src="{{ url('public/assets/dist/img/logo.png') }}" alt="Logo">
            </div>

            {{-- Right form panel --}}
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="d-flex justify-content-center">
                    <h2>Welcome</h2>
                </div>
                <div class="d-flex justify-content-center mb-5">
                    <p>PLEASE LOGIN TO ADMIN DASHBOARD.</p>
                </div>

                {{-- (Optional) Fallback Bootstrap alerts if JS disabled --}}
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show mx-auto" style="max-width: 560px;"
                        role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mx-auto" style="max-width: 560px;"
                        role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row d-flex justify-content-center">
                    <div class="col-md-8">
                        {{-- Validation errors (fallback) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger" id="danger-alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Login form (posts to your custom controller) --}}
                        <form action="{{ url('login') }}" method="post" novalidate>
                            @csrf
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="bi bi-envelope-fill" style="color: var(--buttons-primary-color);"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control" placeholder="Email"
                                        value="{{ old('email') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon2">
                                        <i class="bi bi-lock-fill" style="color: var(--buttons-primary-color);"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control" placeholder="Password"
                                        id="passwordInput" required>
                                    <span class="input-group-text" role="button" onclick="togglePassword()">
                                        <i class="bi bi-eye-fill" id="passwordToggleIcon"
                                            style="color: var(--buttons-primary-color);"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="text-center mt-5">
                                <button id="loginBtn" type="submit" class="btn btn-lg btn-primary border-0"
                                    style="background: var(--buttons-primary-color); width: 80%">
                                    <span id="btnText"><strong>Login</strong></span>
                                    <span id="btnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ url('forgotPassword') }}"
                                style="color: var(--buttons-primary-color); text-decoration: none; font-weight: 500">
                                FORGOT YOUR PASSWORD?
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- /Right panel --}}
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

    {{-- Password toggle --}}
    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('passwordToggleIcon');
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('bi-eye-fill', !isPassword);
            icon.classList.toggle('bi-eye-slash-fill', isPassword);
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.querySelector("form");
            const btn = document.getElementById("loginBtn");
            const text = document.getElementById("btnText");
            const spin = document.getElementById("btnSpinner");

            form.addEventListener("submit", () => {
                btn.disabled = true;
                text.classList.add("d-none");
                spin.classList.remove("d-none");
            });
        });
    </script>
    {{-- SweetAlert2 flash messages at TOP --}}
    <script>
        // Flash success
        @if (session('status'))
            Swal.fire({
                position: 'top',
                icon: 'success',
                title: @json(session('status')),
                showConfirmButton: false,
                timer: 2500
            });
        @endif

        // Flash error
        @if (session('error'))
            Swal.fire({
                position: 'top',
                icon: 'error',
                title: 'Login failed',
                text: @json(session('error')),
                showConfirmButton: true
            });
        @endif

        // Validation errors (combine into one modal at top)
        @if ($errors->any())
            Swal.fire({
                position: 'top',
                icon: 'error',
                title: 'Please fix the following',
                html: `{!! '<ul style="text-align:left;margin:0;padding-left:18px;">' .
                    collect($errors->all())->map(fn($e) => '<li>' . e($e) . '</li>')->implode('') .
                    '</ul>' !!}`,
                showConfirmButton: true
            });
        @endif

        // Optional: auto-hide bootstrap danger alert after 5s
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('danger-alert');
            if (alert) {
                setTimeout(() => {
                    alert.classList.remove('show');
                }, 5000);
            }
        });
    </script>
</body>

</html>








{{-- <!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="{{ url('public/frontend/images/logo.png') }}">
    <link href='https://fonts.googleapis.com/css?family=Viga' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.css"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="icon" href="{{ url('public/assets/dist/img/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">
    <title>Login</title>
    <style>
        /* Add initial border styling */
        .border-modifier {
            border-right: 1px solid grey;
        }

        /* Media Query for screens smaller than 768px */
        @media (max-width: 767px) {
            .border-modifier {
                border-right: none;
                /* Removes the border */
            }
        }

        .input-group-text {
            background-color: #f1f1f1;
            /* Customize background color */
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row d-flex align-items-center" style="height: 100vh;">
            <div
                class="mt-5 col-lg-6 col-md-6 col-sm-12 col-12 d-flex justify-content-center align-items-center border-modifier">
                <img width="50%" class="img-fluid" src="{{ url('public/assets/dist/img/logo.png') }}" alt="">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="d-flex justify-content-center">
                    <h2>Welcome</h2>
                </div>
                <div class="d-flex justify-content-center mb-5">
                    <p>PLEASE LOGIN TO ADMIN DASHBOARD.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row d-flex justify-content-center">
                    <div class="col-md-8">
                        @if ($errors->any())
                            <div class="alert alert-danger" id="danger-alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ url('login') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-envelope-fill"
                                            style="color: var(--buttons-primary-color);"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="Email"
                                        id="" aria-describedby="emailHelp">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon2"><i class="bi bi-lock-fill"
                                            style="color: var(--buttons-primary-color);"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="Password"
                                        id="exampleInputPassword1">
                                    <span class="input-group-text">
                                        <i class="bi bi-eye-fill" style="color: var(--buttons-primary-color);"
                                            onclick="togglePassword()"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="text-center mt-5">
                                <button type="submit" class="btn btn-lg btn-primary border-0"
                                    style="background: var(--buttons-primary-color); width: 80%"><strong>Login</strong></button>
                            </div>

                        </form>
                        <div class="text-center mt-4">
                            <a href="{{ url('forgotPassword') }}"
                                style="color: var(--buttons-primary-color); text-decoration: none; font-weight: 500"
                                class="">FORGOT
                                YOUR PASSWORD?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Firebase CDN -->
    <script src="https://www.gstatic.com/firebasejs/10.12.5/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.5/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.5/firebase-firestore-compat.js"></script>

    <script>
        // 1) Your Firebase config
        // Firebase config for your "Time2Play" project
        const firebaseConfig = {
            apiKey: "AlzaSyDtnVhbX6cczcCydCB1DLxrAJYQWU2H8S0",
            authDomain: "time2play-ed370.firebaseapp.com",
            projectId: "time2play-ed370",
            storageBucket: "time2play-ed370.appspot.com",
            messagingSenderId: "988354704853",
            appId: "1:988354704853:android:f8c82bd2fa4da63c64395c", // see note below
            measurementId: "G-XXXXXXXXXX" // optional, only if you enabled Analytics
        };

        // 2) Init
        firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();
        const db = firebase.firestore();

        // Utility: show toast/error
        function showError(msg) {
            Swal.fire({
                icon: 'error',
                title: 'Login failed',
                text: msg
            });
        }

        function showInfo(msg) {
            Swal.fire({
                icon: 'info',
                title: 'Please wait',
                text: msg,
                showConfirmButton: false,
                allowOutsideClick: false
            });
        }

        function closeSwal() {
            Swal.close();
        }

        // 3) Intercept form submit
        (function wireLogin() {
            const form = document.querySelector('form[action="{{ url('login') }}"]');
            if (!form) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const email = form.querySelector('input[name="email"]').value.trim();
                const password = form.querySelector('input[name="password"]').value;

                if (!email || !password) {
                    showError('Email and password are required.');
                    return;
                }

                try {
                    showInfo('Signing you in...');
                    // a) Firebase Auth
                    const cred = await auth.signInWithEmailAndPassword(email, password);
                    const user = cred.user;
                    if (!user) throw new Error('No user returned from Firebase.');

                    // b) Check Firestore "admin" collection
                    // Option A: docs keyed by user.uid
                    const adminDoc = await db.collection('admin').doc(user.uid).get();

                    // Option B (if you store by email instead): uncomment this block and remove Option A
                    // const snap = await db.collection('admin').where('email','==', email).limit(1).get();
                    // const adminDoc = snap.empty ? null : snap.docs[0];

                    if (!adminDoc || !adminDoc.exists) {
                        closeSwal();
                        showError('Your account is not authorized for Admin.');
                        await auth.signOut();
                        return;
                    }

                    // Optional: also check a role flag
                    const adminData = adminDoc.data() || {};
                    if (adminData.role && adminData.role !== 'admin') {
                        closeSwal();
                        showError('Admin role is required.');
                        await auth.signOut();
                        return;
                    }

                    // c) Get ID token
                    const idToken = await user.getIdToken( /* forceRefresh */ true);

                    // d) Send token to Laravel
                    const csrf = document.querySelector('input[name="_token"]').value;
                    const resp = await fetch("{{ url('login/firebase') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrf
                        },
                        body: JSON.stringify({
                            idToken
                        })
                    });

                    closeSwal();

                    if (!resp.ok) {
                        const errJson = await resp.json().catch(() => ({}));
                        showError(errJson.message || 'Server rejected login.');
                        return;
                    }

                    // Redirect on success
                    window.location.href = "{{ url('/') }}";
                } catch (err) {
                    closeSwal();
                    showError(err.message || 'Unexpected error during login.');
                }
            });
        })();
    </script>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('exampleInputPassword1');
            const passwordIcon = document.querySelector('#exampleInputPassword1 + .input-group-text i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye-fill');
                passwordIcon.classList.add('bi-eye-slash-fill');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye-slash-fill');
                passwordIcon.classList.add('bi-eye-fill');
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $("#danger-alert").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>
</body>

</html> --}}

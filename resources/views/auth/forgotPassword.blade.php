<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    <title>Time2Play</title>
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
        {{-- <h1 class="ms-5 mt-4" style="color: var(--buttons-primary-color); font-family: 'Viga'">Active Learners</h1> --}}
        <div class="row d-flex align-items-center" style="height: 100vh;">
            <div
                class="mt-5 col-lg-6 col-md-6 col-sm-12 col-12 d-flex justify-content-center align-items-center border-modifier">
                <img width="50%" class="img-fluid" src="{{ url('public/assets/dist/img/logo.png') }}" alt="">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="d-flex justify-content-center">
                    <h2>Forgot Your Password?</h2>
                </div>
                <div class="d-flex justify-content-center mb-5 text-center">
                    <p class="text-center">Please confirm your email address below and we will send you a verification
                        code.</p>
                </div>

                @if ($msg = session('error'))
                    <script>
                        swal('<?php echo $msg; ?>');
                    </script>
                @endif

                <div class="row d-flex justify-content-center">
                    <div class="col-md-8">
                        @if (session('success'))
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-12">
                                    <div class="alert alert-success alert-dismissible" id="success-alert">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">&times;</button>
                                        {{ session('success') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <form action="{{ url('verifyEmail') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-person-fill"
                                            style="color: var(--buttons-primary-color)"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="Email"
                                        id="" aria-describedby="emailHelp">
                                </div>
                            </div>
                            <div class="text-center mt-5">
                                <button type="submit" class="btn btn-lg btn-primary border-0"
                                    style="background: var(--buttons-primary-color); width: 80%"><strong>Send</strong></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Loader (hidden by default) -->
    <div id="pageLoader" class="position-fixed top-0 start-0 w-100 h-100 d-none"
        style="background:rgba(255,255,255,.65); z-index:2000;">
        <div class="position-absolute top-50 start-50 translate-middle text-center">
            <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;"></div>
            <div id="pageLoaderMsg" class="mt-2 text-primary small">Loading…</div>
        </div>
    </div>

    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
    <script>
        // --- Firestore + CSRF (already in your page, reused here) ---
        const firebaseConfig = {
            apiKey: "AIzaSyDtnVhbX6cczcCydCB1DLxrAJVQWU2H8S0",
            authDomain: "time2play-ed370.firebaseapp.com",
            projectId: "time2play-ed370",
            messagingSenderId: "988354704853",
            appId: "1:988354704853:web:YOUR_WEB_APP_ID"
        };
        if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore();

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        async function storeSession(email, otp, docId) {
            const res = await fetch("{{ route('session.store.otp') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    email,
                    otp,
                    docId
                })
            });
            if (!res.ok) throw new Error('Failed to store OTP in session');
            return res.json();
        }

        async function sendOtpEmail(email, otp) {
            const fd = new FormData();
            fd.append('email', email);
            fd.append('title', 'Time2Play Verification Code');
            fd.append('description', `Your verification code is ${otp}`);
            fd.append('projectName', 'Time2Play');

            const res = await fetch('https://appistanapis.appistansoft.com/api/send-email', {
                method: 'POST',
                body: fd
            });
            if (!res.ok) throw new Error('Failed to send email');
            return res.text(); // API returns text/json — we don’t need the body
        }

        function randOtp4() {
            return String(Math.floor(1000 + Math.random() * 9000));
        }

        // Hook your existing form submit
        document.querySelector('form[action="{{ url('verifyEmail') }}"]')
            ?.addEventListener('submit', async (e) => {
                e.preventDefault();
                const email = e.target.querySelector('input[name="email"]')?.value?.trim();
                if (!email) return swal('Error', 'Please enter email', 'error');

                showPageLoader('Sending code…');
                try {
                    // Match admin by email
                    const snap = await db.collection('admin').where('email', '==', email).limit(1).get();
                    if (snap.empty) {
                        throw new Error('Email not found.');
                    }
                    const doc = snap.docs[0];
                    const docId = doc.id;

                    // Generate + send
                    const otp = randOtp4();
                    await sendOtpEmail(email, otp);

                    // Persist in session
                    await storeSession(email, otp, docId);

                    // Success → go to OTP page
                    swal('Success', 'Verification code sent to your email.', 'success');
                    window.location.href = "{{ url('otp') }}";
                } catch (err) {
                    swal('Error', err?.message || 'Failed to send code.', 'error');
                } finally {
                    hidePageLoader();
                }
            });
    </script>

    <script>
        function showPageLoader(msg = 'Loading…') {
            document.getElementById('pageLoaderMsg').textContent = msg;
            document.getElementById('pageLoader').classList.remove('d-none');
        }

        function hidePageLoader() {
            document.getElementById('pageLoader').classList.add('d-none');
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $("#success-alert").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>

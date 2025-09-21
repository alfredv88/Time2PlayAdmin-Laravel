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
    <title>OTP</title>
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

        .otp-input:focus {
            border-color: #ccc;
            box-shadow: none;
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
                    <h2>Verification Code</h2>
                </div>
                <div class="d-flex justify-content-center mb-5 text-center">
                    <p class="text-center">Please enter the code sent to your email.</p>
                </div>

                @if ($msg = session('message'))
                    <script>
                        swal('{{ $msg }}');
                    </script>
                @endif

                <div class="row d-flex justify-content-center">
                    <div class="col-md-8">
                        <form id="otp-form" action="{{ url('newPassword') }}" method="get">
                            @csrf <!-- Include this directive to add the CSRF token -->

                            <div class="mb-3 d-flex justify-content-center gap-2">
                                <input type="text" class="form-control text-center otp-input"
                                    style="max-width: 60px;" maxlength="1" autofocus>
                                <input type="text" class="form-control text-center otp-input"
                                    style="max-width: 60px;" maxlength="1">
                                <input type="text" class="form-control text-center otp-input"
                                    style="max-width: 60px;" maxlength="1">
                                <input type="text" class="form-control text-center otp-input"
                                    style="max-width: 60px;" maxlength="1">
                            </div>

                            <div class="text-center mt-5">
                                <button type="submit" id="verify-btn" class="btn btn-lg btn-primary border-0"
                                    style="background: var(--buttons-primary-color); width: 80%" disabled>
                                    <strong>Verify</strong>
                                </button>
                            </div>
                            <div class="text-end">
                                <a href="{{ url('resendOtp') }}"><strong
                                        style="color: var(--buttons-primary-color)">RESEND CODE</strong></a>
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

    @php
        $otp = session('otp'); // OTP from session
    @endphp
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
    <script>
        (function() {
            const resendLink = document.querySelector('a[href$="resendOtp"]');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const resetEmail = @json(session('reset_email') ?? '');
            const adminDocId = @json(session('admin_doc_id') ?? '');

            if (!resetEmail || !adminDocId) {
                // safety: if session missing, send back to start
                console.warn('Missing session data. Redirecting.');
                window.location.href = "{{ url('forgot-password') }}";
                return;
            }

            // init firebase if needed (in case loaded on this page alone)
            const firebaseConfig = {
                apiKey: "AIzaSyDtnVhbX6cczcCydCB1DLxrAJVQWU2H8S0",
                authDomain: "time2play-ed370.firebaseapp.com",
                projectId: "time2play-ed370",
                messagingSenderId: "988354704853",
                appId: "1:988354704853:web:YOUR_WEB_APP_ID"
            };
            if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);

            function genOtp4() {
                return String(Math.floor(Math.random() * 10000)).padStart(4, '0');
            }
            async function sendEmailViaApi(toEmail, otp) {
                const fd = new FormData();
                fd.append('email', toEmail);
                fd.append('title', 'Password Reset Code');
                fd.append('description', `Your Time2Play verification code is: ${otp}`);
                fd.append('projectName', 'Time2Play');

                const res = await fetch('https://appistanapis.appistansoft.com/api/send-email', {
                    method: 'POST',
                    body: fd
                });
                if (!res.ok) throw new Error('Failed to send email');
                return res.json().catch(() => ({}));
            }
            async function storeSession(email, otp, docId) {
                const res = await fetch("{{ route('session.store.otp') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({
                        email,
                        otp,
                        docId
                    })
                });
                if (!res.ok) throw new Error('Failed to update session');
                return res.json();
            }

            if (resendLink) {
                resendLink.addEventListener('click', async (e) => {
                    e.preventDefault();
                    showPageLoader('Resending code…');
                    try {
                        const otp = genOtp4();
                        await sendEmailViaApi(resetEmail, otp);
                        await storeSession(resetEmail, otp, adminDocId);
                        swal('Code Sent', 'We emailed you a new verification code.', 'success');
                    } catch (err) {
                        console.error(err);
                        swal('Error', err?.message || 'Failed to resend code', 'error');
                    } finally {
                        hidePageLoader();
                    }
                });
            }
        })();
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

    <script>
        // Get OTP from server-side session and convert to string
        const serverOtp = '{{ $otp }}';

        // Function to move focus to next input and check OTP
        const inputs = document.querySelectorAll('.otp-input');
        const verifyButton = document.getElementById('verify-btn');

        inputs.forEach((input, index) => {
            input.addEventListener('keyup', function(e) {
                // Move to next input on keyup
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                // Check if all inputs are filled and verify OTP
                const enteredOtp = Array.from(inputs).map(input => input.value).join('');
                if (enteredOtp.length === 4) {
                    if (enteredOtp === serverOtp) {
                        verifyButton.disabled = false; // Enable button if OTP matches
                    } else {
                        verifyButton.disabled = true; // Disable button if OTP does not match
                        swal('Error', 'OTP does not match!', 'error'); // Show error message
                    }
                } else {
                    verifyButton.disabled = true; // Disable button if all fields are not filled
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>

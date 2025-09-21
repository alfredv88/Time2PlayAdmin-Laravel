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
    <title>New Password</title>
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
        <div class="row d-flex align-items-center" style="height: 100vh;">
            <div
                class="mt-5 col-lg-6 col-md-6 col-sm-12 col-12 d-flex justify-content-center align-items-center border-modifier">
                <img width="65%" class="img-fluid" src="{{ url('public/assets/dist/img/logo.png') }}" alt="">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="d-flex justify-content-center">
                    <h2>Reset Password</h2>
                </div>
                <div class="d-flex justify-content-center mb-5">
                    <p>Initiate Pasword reset Process.</p>
                </div>

                <div class="row d-flex justify-content-center">
                    <div class="col-md-8">
                        <form id="passwordForm" action="{{ url('newPassword') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon2"><i class="bi bi-lock-fill"
                                            style="color: var(--buttons-primary-color);"></i></span>
                                    <input type="password" name="newpassword" id="newPassword" class="form-control"
                                        placeholder="Password">
                                    <span class="input-group-text">
                                        <i class="bi bi-eye-fill" style="color: var(--buttons-primary-color);"
                                            onclick="togglePasswordVisibility('newPassword', this)"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon2"><i class="bi bi-lock-fill"
                                            style="color: var(--buttons-primary-color);"></i></span>
                                    <input type="password" name="confirmnewpassword" id="confirmPassword"
                                        class="form-control" placeholder="Confirm Password">
                                    <span class="input-group-text">
                                        <i class="bi bi-eye-fill" style="color: var(--buttons-primary-color);"
                                            onclick="togglePasswordVisibility('confirmPassword', this)"></i>
                                    </span>
                                </div>
                                <p class="text-danger">Password and confirm password must be matched and minimum 6
                                    characters.</p>
                            </div>
                            <div class="text-center mt-5">
                                <button type="submit" id="updateButton" class="btn btn-lg btn-primary border-0"
                                    style="background: var(--buttons-primary-color);; width: 80%" disabled>
                                    <strong>Update</strong>
                                </button>
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
        (function() {
            const form = document.getElementById('passwordForm');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const adminDocId = @json(session('admin_doc_id') ?? '');
            const resetEmail = @json(session('reset_email') ?? '');

            if (!adminDocId || !resetEmail) {
                // if someone opens this page directly
                window.location.href = "{{ url('forgot-password') }}";
                return;
            }

            // firebase
            const firebaseConfig = {
                apiKey: "AIzaSyDtnVhbX6cczcCydCB1DLxrAJVQWU2H8S0",
                authDomain: "time2play-ed370.firebaseapp.com",
                projectId: "time2play-ed370",
                messagingSenderId: "988354704853",
                appId: "1:988354704853:web:YOUR_WEB_APP_ID"
            };
            if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
            const db = firebase.firestore();

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const newPass = document.getElementById('newPassword').value.trim();
                const confirm = document.getElementById('confirmPassword').value.trim();
                if (newPass.length < 6 || newPass !== confirm) {
                    swal('Error', 'Password must be at least 6 chars and match.', 'error');
                    return;
                }

                showPageLoader('Updating password…'); // <-- ADD THIS
                try {
                    await db.collection('admin').doc(adminDocId).set({
                        password: newPass,
                        updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                    }, {
                        merge: true
                    });

                    await fetch("{{ route('session.clear.reset') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        }
                    }).catch(() => {});

                    swal('Success', 'Password updated. Redirecting to login…', 'success');
                    setTimeout(() => window.location.href = "{{ url('login') }}", 900);
                } catch (err) {
                    console.error(err);
                    swal('Error', err?.message || 'Failed to update password', 'error');
                    hidePageLoader(); // <-- HIDE ON ERROR
                }
            });
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
        // Function to toggle password visibility
        function togglePasswordVisibility(fieldId, iconElement) {
            const passwordField = document.getElementById(fieldId);
            const icon = iconElement;

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('bi-eye-fill');
                icon.classList.add('bi-eye-slash-fill');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('bi-eye-slash-fill');
                icon.classList.add('bi-eye-fill');
            }
        }

        // Function to validate passwords
        function validatePasswords() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const updateButton = document.getElementById('updateButton');

            // Enable the update button if both passwords match and are at least 6 characters long
            if (newPassword.length >= 6 && confirmPassword.length >= 6 && newPassword === confirmPassword) {
                updateButton.disabled = false;
            } else {
                updateButton.disabled = true;
            }
        }

        // Add event listeners to the password fields
        document.getElementById('newPassword').addEventListener('keyup', validatePasswords);
        document.getElementById('confirmPassword').addEventListener('keyup', validatePasswords);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="{{ url('public/assets/dist/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">

    <!-- Firebase (compat) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>

    <style>
        .spinner-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="font-weight-bold">Change Password</h1>
                        </div>
                    </div>
                </div>
            </section>

            <!-- runtime alerts -->
            <div class="container-fluid" id="flashArea" style="display:none;">
                <div id="flashBox" class="alert alert-success alert-dismissible fade show" role="alert">
                    <span id="flashMsg">Updated</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid position-relative">
                    <div id="cardSpinner" class="spinner-overlay">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header" style="background: var(--buttons-primary-color);">
                                    <h3 class="card-title">Update Password</h3>
                                </div>

                                <form id="passForm" action="{{ url('update-password') }}" method="POST"
                                    autocomplete="off">
                                    @csrf
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label>Old Password</label>
                                            <input required type="password" name="old_password" class="form-control"
                                                placeholder="Enter Old Password">
                                        </div>

                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input required type="password" name="new_password" class="form-control"
                                                placeholder="Enter New Password" minlength="6">
                                        </div>

                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input required type="password" name="new_password_confirmation"
                                                class="form-control" placeholder="Enter Confirm Password"
                                                minlength="6">
                                        </div>

                                    </div>

                                    <div class="card-footer">
                                        <button id="submitBtn" type="submit" class="btn btn-primary border-0"
                                            style="background: var(--buttons-primary-color);">
                                            Update
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                </div>
            </section>
        </div>

        @include('inc.footer')
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>

    <script>
        // --- Firebase init ---
        const firebaseConfig = {
            apiKey: "AIzaSyDtnVhbX6cczcCydCB1DLxrAJVQWU2H8S0",
            authDomain: "time2play-ed370.firebaseapp.com",
            projectId: "time2play-ed370",
            messagingSenderId: "988354704853",
            appId: "1:988354704853:web:YOUR_WEB_APP_ID"
        };
        if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore();

        const flashArea = document.getElementById('flashArea');
        const flashMsg = document.getElementById('flashMsg');
        const flashBox = document.getElementById('flashBox');
        const cardSpinner = document.getElementById('cardSpinner');

        const showFlash = (msg, type = 'success', ms = 5000) => {
            flashMsg.textContent = msg;
            flashBox.classList.remove('alert-success', 'alert-danger', 'alert-warning', 'alert-info');
            flashBox.classList.add(type === 'error' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' :
                'alert-success'));
            flashArea.style.display = 'block';
            setTimeout(() => {
                flashArea.style.display = 'none';
            }, ms);
        };
        const setLoading = (on) => {
            cardSpinner.style.display = on ? 'flex' : 'none';
        };

        // If you prefer to always update a specific admin doc, put its id here:
        // const ADMIN_DOC_ID = 'LRtNIC7S6lhWhfT6iYwy';

        async function getFirstAdminDocRef() {
            // If you know the doc id, use:
            // return db.collection('admin').doc(ADMIN_DOC_ID);

            const snap = await db.collection('admin').limit(1).get();
            if (snap.empty) throw new Error('Admin document not found.');
            return snap.docs[0].ref;
        }

        document.getElementById('passForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const btn = document.getElementById('submitBtn');
            const oldText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Updating…';
            setLoading(true);

            try {
                const old_password = e.target.elements['old_password'].value;
                const new_password = e.target.elements['new_password'].value;
                const new_password_confirmation = e.target.elements['new_password_confirmation'].value;

                if (!old_password || !new_password || !new_password_confirmation) {
                    throw new Error('All fields are required.');
                }
                if (new_password !== new_password_confirmation) {
                    throw new Error('New password and confirm password do not match.');
                }
                if (new_password.length < 6) {
                    throw new Error('New password must be at least 6 characters.');
                }

                // Load admin doc (first document in 'admin' collection)
                const ref = await getFirstAdminDocRef();
                const doc = await ref.get();
                if (!doc.exists) throw new Error('Admin document not found.');

                const data = doc.data() || {};
                const currentPlain = String(data.password ?? '');

                // compare as plain text (no hashing)
                if (old_password !== currentPlain) {
                    throw new Error('Old password is incorrect.');
                }
                if (new_password === currentPlain) {
                    throw new Error('New password must be different from old password.');
                }

                // Update password (still plain text)
                await ref.set({
                    password: new_password,
                    updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                }, {
                    merge: true
                });

                // clear form & show success
                e.target.reset();
                showFlash('Password updated successfully.', 'success');
            } catch (err) {
                console.error(err);
                showFlash(err?.message || 'Failed to update password.', 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = oldText;
                setLoading(false);
            }
        });
    </script>
</body>

</html>








{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="{{ url('public/assets/dist/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('inc.header')

        @include('inc.aside')
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="font-weight-bold">Change Password</h1>
                        </div>
                    </div>
                </div>
            </section>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header" style="background: var(--buttons-primary-color);">
                                    <h3 class="card-title">Update Password</h3>
                                </div>
                                <form action="{{ url('update-password') }}" method="POST">
                                    @csrf
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label>Old Password</label>
                                            <input required type="password" name="old_password" class="form-control"
                                                placeholder="Enter Old Password">
                                            @error('old_password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input required type="password" name="new_password" class="form-control"
                                                placeholder="Enter New Password">
                                            @error('new_password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input required type="password" name="new_password_confirmation"
                                                class="form-control" placeholder="Enter Confirm Password">
                                            @error('new_password_confirmation')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary border-0"
                                            style="background: var(--buttons-primary-color);">Update</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('inc.footer')
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ url('public/assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html> --}}

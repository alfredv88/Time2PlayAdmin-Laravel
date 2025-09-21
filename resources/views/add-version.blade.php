{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Version</title>

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
                            <h1>Add version</h1>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header" style="background: var(--buttons-primary-color);">
                                    <h3 class="card-title">Add New Version</h3>
                                </div>
                                <form action="{{ url('store-android-version') }}" method="POST">
                                    @csrf
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label>Version Number</label>
                                            <input required type="float" name="version_number" class="form-control"
                                                placeholder="Enter Version Number">
                                            @error('version_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Release Date</label>
                                            <input required type="date" name="release_date" class="form-control">
                                            @error('release_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Device Type</label>
                                            <!-- Device Type -->
                                            <select required name="device" class="form-control">
                                                <option value="android">Android</option>
                                                <option value="ios">iOS</option>
                                            </select>
                                            @error('status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Version Status</label>
                                            <select required name="version_status" class="form-control">
                                                <option value="latest">Latest</option>
                                                <option value="outdated">Outdated</option>
                                                <option value="stable">Stable</option>
                                                <option value="beta">Beta</option>
                                            </select>
                                            @error('status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control" placeholder="Enter Description"></textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary border-0"
                                            style="background: var(--buttons-primary-color);">Create</button>
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Version</title>

    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" type="image/png" href="{{ url('public/assets/dist/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">

    <!-- Firebase (compat) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
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
                            <h1>Add version</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header" style="background: var(--buttons-primary-color);">
                                    <h3 class="card-title">Add New Version</h3>
                                </div>

                                <!-- We intercept this form via JS and write to Firestore -->
                                <form id="addVersionForm">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Version Number</label>
                                            <!-- use text so semver like 1.4.2 works -->
                                            <input required type="text" name="version_number" class="form-control"
                                                placeholder="e.g. 1.5.0">
                                        </div>

                                        <div class="form-group">
                                            <label>Release Date</label>
                                            <input required type="date" name="release_date" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label>Device Type</label>
                                            <select required name="device" class="form-control">
                                                <option value="android">Android</option>
                                                <option value="ios">iOS</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Version Status</label>
                                            <select required name="version_status" class="form-control">
                                                <option value="latest">Latest</option>
                                                <option value="outdated">Outdated</option>
                                                <option value="stable">Stable</option>
                                                <option value="beta">Beta</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control" placeholder="Enter Description"></textarea>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <button id="createBtn" type="submit" class="btn btn-primary border-0"
                                            style="background: var(--buttons-primary-color);">
                                            Create
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
        // Firebase init
        const firebaseConfig = {
            apiKey: "AIzaSyDtnVhbX6cczcCydCB1DLxrAJVQWU2H8S0",
            authDomain: "time2play-ed370.firebaseapp.com",
            projectId: "time2play-ed370",
            messagingSenderId: "988354704853",
            appId: "1:988354704853:web:YOUR_WEB_APP_ID"
        };
        if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore();

        const form = document.getElementById('addVersionForm');
        const btn = document.getElementById('createBtn');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            btn.disabled = true;
            const old = btn.innerText;
            btn.innerText = 'Saving…';

            try {
                const version_number = form.version_number.value.trim();
                const release_date_str = form.release_date.value; // yyyy-mm-dd
                const device = form.device.value;
                const version_status = form.version_status.value;
                const description = form.description.value.trim();

                if (!version_number) throw new Error('Version number required');
                if (!release_date_str) throw new Error('Release date required');

                // Store release_date as a JS Date (Firestore will convert to Timestamp)
                const release_date = new Date(release_date_str + 'T00:00:00');

                const payload = {
                    version_number,
                    description,
                    release_date,
                    device, // 'android' | 'ios'
                    version_status, // 'latest' | 'outdated' | 'stable' | 'beta'
                    createdAt: firebase.firestore.FieldValue.serverTimestamp()
                };

                await db.collection('versions').add(payload);

                // redirect to list with a flag so we can show success
                window.location.href = "{{ url('/versions') }}";

            } catch (err) {
                alert(err?.message || err);
                btn.disabled = false;
                btn.innerText = old;
            }
        });
    </script>
</body>

</html>

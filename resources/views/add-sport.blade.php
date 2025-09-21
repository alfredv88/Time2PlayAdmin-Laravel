<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sport Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <!-- Firebase App (required) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <!-- Firestore -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
    <!-- Firebase Storage -->
    {{-- <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-storage-compat.js"></script> --}}

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
                            <h1>Add Category Sport</h1>
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
                                    <h3 class="card-title">Add New Category Sport</h3>
                                </div>
                                <form id="sportForm" action="{{ url('store-sport') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="card shadow-sm rounded">
                                        <div class="card-body">

                                            <!-- Sport Name -->
                                            <div class="form-group">
                                                <label><strong>Sport Category Name</strong></label>
                                                <input type="text" name="name" class="form-control"
                                                    placeholder="Enter Sport Name" required>
                                            </div>

                                            <!-- Sport Icon Upload -->
                                            <div class="form-group">
                                                <label><strong>Sport Category Icon</strong></label>
                                                <input type="file" name="icon" class="form-control-file">
                                            </div>

                                            <!-- Initial Status -->
                                            <div class="form-group">
                                                <label><strong>Initial Status</strong></label>
                                                <select name="status" class="form-control" required>
                                                    <option value="enabled" selected>Enabled</option>
                                                    <option value="disabled">Disabled</option>
                                                </select>
                                            </div>

                                            <!-- Description -->
                                            <div class="form-group">
                                                <label><strong>Description</strong></label>
                                                <textarea name="description" class="form-control" placeholder="Enter sport description and details..." rows="3"></textarea>
                                            </div>

                                            <!-- Availability Toggles -->
                                            <div class="mt-4">
                                                <label class="d-block font-weight-bold mb-2">Availability
                                                    Settings</label>

                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        <div><strong>Available for Events</strong></div>
                                                        <small class="text-muted">Users can create events for this
                                                            sport</small>
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="eventsToggle" name="available_events" checked>
                                                        <label class="custom-control-label" for="eventsToggle"></label>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div><strong>Available for Centres</strong></div>
                                                        <small class="text-muted">Centres can be tagged with this
                                                            sport</small>
                                                    </div>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="centresToggle" name="available_centres" checked>
                                                        <label class="custom-control-label" for="centresToggle"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="card-footer d-flex justify-content-end">
                                            <a href="{{ url()->previous() }}"
                                                class="btn btn-outline-secondary mr-2">Cancel</a>
                                            <button type="submit" name="draft" value="1"
                                                class="btn btn-light mr-2">Save as Draft</button>
                                            <button type="submit" class="btn btn-primary"
                                                style="background-color: #0d295f;">Create Sport</button>
                                        </div>
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
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>

    <script>
        // Your existing config
        const firebaseConfig = {
            apiKey: "AIzaSyDtnVhbX6cczcCydCB1DLxrAJVQWU2H8S0",
            authDomain: "time2play-ed370.firebaseapp.com",
            projectId: "time2play-ed370",
            messagingSenderId: "988354704853",
            appId: "1:988354704853:web:YOUR_WEB_APP_ID"
        };
        if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore();
    </script>

    <script>
        (function() {
            const form = document.getElementById('sportForm');
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const toast = (m) => alert(m);

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                // only disable the button that was clicked (Create vs Draft)
                const btn = e.submitter || form.querySelector('button[type="submit"]');
                const oldText = btn.innerText;
                btn.disabled = true;
                btn.innerText = 'Saving…';

                try {
                    const name = form.querySelector('[name="name"]').value.trim();
                    const status = form.querySelector('[name="status"]').value;
                    const description = form.querySelector('[name="description"]').value.trim();
                    const available_events = !!form.querySelector('[name="available_events"]')?.checked;
                    const available_centres = !!form.querySelector('[name="available_centres"]')?.checked;
                    const isDraft = btn.name === 'draft';
                    const iconFile = form.querySelector('[name="icon"]').files[0] || null;

                    if (!name) throw new Error('Please enter a sport name.');

                    // 1) Upload icon to Laravel public/ (optional)
                    let iconUrl = '';
                    if (iconFile) {
                        const fd = new FormData();
                        fd.append('icon', iconFile);

                        const res = await fetch("{{ route('sports.icon.upload') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf
                            },
                            body: fd
                        });
                        const out = await res.json();
                        if (!res.ok || !out.success) throw new Error(out?.message || 'Icon upload failed');

                        iconUrl = out.url; // ✅ FULL absolute URL (not just /uploads/…)
                    }

                    // 2) Save to Firestore
                    const payload = {
                        name,
                        status, // 'enabled' | 'disabled'
                        description,
                        available_events,
                        available_centres,
                        draft: !!isDraft,
                        iconUrl, // may be '' if no file chosen
                        eventsCount: 0, // ✅ default
                        centresCount: 0, // ✅ default
                        createdAt: firebase.firestore.FieldValue.serverTimestamp(),
                    };

                    await db.collection('sportsManagement').add(payload);

                    // 3) Redirect to the list page
                    window.location.href = "{{ url('/sports-management') }}";
                } catch (err) {
                    console.error(err);
                    toast(`Failed: ${err.message || err}`);
                    btn.disabled = false;
                    btn.innerText = oldText;
                }
            });
        })();
    </script>

    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ url('public/assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html>

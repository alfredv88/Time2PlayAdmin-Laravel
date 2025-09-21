<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sport Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts & Styles -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
                            <h1>Update Category Sport</h1>
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
                                    <h3 class="card-title">Update Category Sport</h3>
                                </div>

                                <form id="sportForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="docId" value="{{ $docId }}">
                                    <input type="hidden" name="iconUrlExisting" id="iconUrlExisting" value="">

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
                                                <input type="file" name="icon" class="form-control-file"
                                                    id="iconInput">

                                                <!-- current icon preview (keeps layout minimal) -->
                                                <div id="currentIconWrap" class="mt-2" style="display:none;">
                                                    <img id="currentIcon" src="" alt="Current icon"
                                                        class="img-thumbnail" style="max-height:100px;">
                                                    <small class="text-muted d-block mt-1">Current icon</small>
                                                </div>

                                                <!-- selected (new) icon preview -->
                                                <div id="newIconWrap" class="mt-2" style="display:none;">
                                                    <img id="newIcon" src="" alt="Selected icon preview"
                                                        class="img-thumbnail" style="max-height:100px;">
                                                    <small class="text-muted d-block mt-1">New icon (will replace
                                                        current)</small>
                                                </div>
                                            </div>

                                            <!-- Initial Status -->
                                            <div class="form-group">
                                                <label><strong>Initial Status</strong></label>
                                                <select name="status" class="form-control" required>
                                                    <option value="enabled">Enabled</option>
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
                                                            id="eventsToggle" name="available_events">
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
                                                            id="centresToggle" name="available_centres">
                                                        <label class="custom-control-label"
                                                            for="centresToggle"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="card-footer d-flex justify-content-end">
                                            <a href="{{ url('/sports-management') }}"
                                                class="btn btn-outline-secondary mr-2">Cancel</a>
                                            <button type="submit" name="draft" value="1"
                                                class="btn btn-light mr-2">Save as Draft</button>
                                            <button type="submit" class="btn btn-primary"
                                                style="background-color: #0d295f;">Update Sport</button>
                                        </div>
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

    <!-- Firebase init -->
    <script>
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

    <!-- Load + Update logic -->
    <script>
        (function() {
            const docId = document.getElementById('docId').value;
            const form = document.getElementById('sportForm');
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const toast = (m) => alert(m);

            // 1) Load existing document and prefill + show current icon
            async function loadDoc() {
                const snap = await db.collection('sportsManagement').doc(docId).get();
                if (!snap.exists) {
                    alert('Sport not found');
                    window.location.href = "{{ url('/sports-management') }}";
                    return;
                }
                const d = snap.data();

                form.querySelector('[name="name"]').value = d.name || '';
                form.querySelector('[name="status"]').value = d.status || 'enabled';
                form.querySelector('[name="description"]').value = d.description || '';
                form.querySelector('[name="available_events"]').checked = !!d.available_events;
                form.querySelector('[name="available_centres"]').checked = !!d.available_centres;

                const existingUrl = d.iconUrl || '';
                document.getElementById('iconUrlExisting').value = existingUrl;

                const curWrap = document.getElementById('currentIconWrap');
                const curImg = document.getElementById('currentIcon');
                if (existingUrl) {
                    curImg.src = existingUrl;
                    curWrap.style.display = 'block';
                } else {
                    curWrap.style.display = 'none';
                    curImg.src = '';
                }
            }

            // live preview when selecting a new file
            document.getElementById('iconInput').addEventListener('change', (ev) => {
                const file = ev.target.files && ev.target.files[0];
                const wrap = document.getElementById('newIconWrap');
                const img = document.getElementById('newIcon');

                if (file) {
                    img.src = URL.createObjectURL(file);
                    wrap.style.display = 'block';
                } else {
                    wrap.style.display = 'none';
                    img.src = '';
                }
            });

            // 2) Submit → optional icon upload → update Firestore
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

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

                    // keep existing icon if no new file
                    let iconUrl = document.getElementById('iconUrlExisting').value || '';

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

                        iconUrl = out.url; // absolute URL returned by Laravel
                    }

                    const payload = {
                        name,
                        status,
                        description,
                        available_events,
                        available_centres,
                        draft: !!isDraft,
                        iconUrl, // keep existing or new
                        updatedAt: firebase.firestore.FieldValue.serverTimestamp(),
                    };

                    await db.collection('sportsManagement').doc(docId).update(payload);
                    window.location.href = "{{ url('/sports-management') }}";
                } catch (err) {
                    console.error(err);
                    toast(`Failed: ${err.message || err}`);
                    btn.disabled = false;
                    btn.innerText = oldText;
                }
            });

            loadDoc();
        })();
    </script>


    <!-- Vendor scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Centre Request Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" href="{{ url('public/assets/dist/img/favicon.png') }}" type="image/x-icon">

    <!-- Firebase (compat) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>

    <!-- Leaflet (map) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        /* -- content loader (covers only the main content area) -- */
        .content-wrapper {
            position: relative;
        }

        /* ensures the overlay fits this area only */
        .page-loader {
            position: absolute;
            inset: 0;
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.65);
        }

        .page-loader.show {
            display: flex;
        }

        /* -- map fixes: make the map fully overlay the placeholder -- */
        #centreMap,
        #mapPlaceholder {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        #centreMap {
            display: none;
        }

        /* shown only when coords are valid */
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed bg-light">
    <div class="wrapper">

        @include('inc.header')
        @include('inc.aside')

        <!-- Content Wrapper -->
        <div class="content-wrapper p-4 bg-light">
            <!-- loader overlay (shown while fetching) -->
            <div id="pageLoader" class="page-loader">
                <div class="text-center">
                    <div class="spinner-border text-primary"></div>
                    <div class="mt-2 text-muted">Loading centre…</div>
                </div>
            </div>

            <!-- Page Header -->
            <div class="mb-4">
                <h3 id="pageTitle" class="fw-bold">Centre Request Details</h3>
                <p id="pageSubtitle" class="text-muted">Review submission and make approval decision</p>
                <div id="statusAlert" class="alert alert-warning d-flex justify-content-between align-items-center">
                    <span><strong>Status:</strong> <span id="statusText">Pending Review</span></span>
                    <small id="submittedAt" class="text-muted">Submitted on —</small>
                </div>
            </div>

            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Centre Information -->
                    <div class="card p-4">
                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                            <div class="d-flex align-items-start">
                                <div class="bg-secondary rounded-2 text-white d-flex justify-content-center align-items-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="fas fa-image fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 id="centreName" class="mb-1 fw-bold">—</h5>
                                    <div id="sportsBadges">
                                        <!-- sports badges injected -->
                                    </div>
                                </div>
                            </div>
                            <span id="docIdBadge" class="badge bg-light text-muted mt-2 mt-lg-0">ID: —</span>
                        </div>

                        <div class="mt-4">
                            <h6 class="fw-bold">Centre Description</h6>
                            <p id="centreDesc" class="text-muted mb-0">—</p>
                        </div>
                    </div>

                    <!-- Sports & Facilities -->
                    <div class="card p-4 mt-4">
                        <h6 class="fw-bold mb-3">Sports & Facilities</h6>
                        <ul id="facilitiesList" class="list-unstyled mb-0">
                            <!-- facilities injected -->
                        </ul>
                    </div>

                    <!-- Operating Hours -->
                    <div class="card p-4 mt-4">
                        <h6 class="fw-bold mb-3">Operating Hours</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Day</th>
                                        <th>Hours</th>
                                    </tr>
                                </thead>
                                <tbody id="timingsBody">
                                    <!-- timings injected -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Submission Details -->
                    <div class="card p-4 mb-4">
                        <h6 class="fw-bold mb-3">Submission Details</h6>
                        <div class="d-flex align-items-start mb-3">
                            <div id="userAvatar"
                                class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; font-weight: bold;">—
                            </div>
                            <div class="ms-3">
                                <h6 id="userName" class="mb-1 fw-bold">—</h6>
                                <p id="userAbout" class="mb-1 text-muted" style="font-size: 14px;">—</p>
                            </div>
                        </div>
                        <ul class="list-unstyled small text-dark mb-0">
                            <li class="mb-2"><i class="fas fa-envelope me-2 text-secondary"></i> <span
                                    id="userEmail">—</span>
                            </li>
                            <li class="mb-2"><i class="fas fa-phone me-2 text-secondary"></i> <span
                                    id="userPhone">—</span>
                            </li>
                            <li><i class="fas fa-id-badge me-2 text-secondary"></i> <span id="submitterId">UID: —</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Centre Location -->
                    <div class="card p-4 mb-4">
                        <h6 class="fw-bold mb-3">Centre Location</h6>
                        <div class="bg-light rounded mb-3" style="height: 150px; position: relative; overflow: hidden;">
                            <!-- placeholder (kept) -->
                            <div id="mapPlaceholder"
                                class="w-100 h-100 d-flex justify-content-center align-items-center">
                                <i class="fas fa-map-marker-alt fa-2x text-secondary"></i>
                            </div>
                            <!-- actual map (hidden until coords are valid) -->
                            <div id="centreMap"></div>
                        </div>
                        <p id="centreAddress" class="small text-dark mb-0">—</p>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card p-4">
                        <h6 class="fw-bold mb-4">Quick Actions</h6>
                        <div class="d-grid">
                            <button id="approveBtn" type="button" class="btn btn-success btn-lg w-100 mb-3">
                                <i class="fas fa-check me-2"></i> Approve Centre
                            </button>
                            <button id="rejectBtn" type="button" class="btn btn-danger btn-lg w-100">
                                <i class="fas fa-times me-2"></i> Reject Centre
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('inc.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>

    <script>
        // ---- Firebase init ----
        const firebaseConfig = {
            apiKey: "AIzaSyDtnVhbX6cczcCydCB1DLxrAJVQWU2H8S0",
            authDomain: "time2play-ed370.firebaseapp.com",
            projectId: "time2play-ed370",
            messagingSenderId: "988354704853",
            appId: "1:988354704853:web:YOUR_WEB_APP_ID"
        };
        if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore();

        // helpers
        const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [c]));

        function fmtDate(ts) {
            if (!ts) return '—';
            const d = ts?.toDate ? ts.toDate() : new Date(ts);
            return d.toLocaleString();
        }

        function initials(name) {
            const p = String(name || '').trim().split(/\s+/);
            return ((p[0] || ' ').charAt(0) + (p[1] || ' ').charAt(0)).toUpperCase();
        }

        function statusClass(s) {
            s = String(s || '').toLowerCase();
            if (s === 'approved') return {
                label: 'Approved',
                cls: 'alert-success'
            };
            if (s === 'rejected') return {
                label: 'Rejected',
                cls: 'alert-danger'
            };
            if (s === 'pending') return {
                label: 'Pending Review',
                cls: 'alert-warning'
            };
            return {
                label: s || '—',
                cls: 'alert-secondary'
            };
        }
        const DAY_LABEL = {
            mon: 'Monday',
            tue: 'Tuesday',
            wed: 'Wednesday',
            thu: 'Thursday',
            fri: 'Friday',
            sat: 'Saturday',
            sun: 'Sunday'
        };

        // id from route (/center-request-details/{id}) or ?id=
        const bladeId = "{{ $id ?? '' }}";
        const fieldId = (bladeId && bladeId !== '') ? bladeId : (() => {
            const m = location.pathname.match(/center-request-details\/([^\/?#]+)/);
            if (m && m[1]) return decodeURIComponent(m[1]);
            const qs = new URLSearchParams(location.search);
            return qs.get('id') || '';
        })();

        // loader toggle
        function toggleLoader(show) {
            document.getElementById('pageLoader').classList.toggle('show', !!show);
        }

        // map
        function initCentreMap(lat, lng) {
            const mapDiv = document.getElementById('centreMap');
            const ph = document.getElementById('mapPlaceholder');
            try {
                mapDiv.style.display = 'block';
                if (ph) ph.style.display = 'none';

                const map = L.map(mapDiv, {
                    zoomControl: true,
                    attributionControl: true
                }).setView([lat, lng], 14);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);
                L.marker([lat, lng]).addTo(map);
                setTimeout(() => map.invalidateSize(), 50);
            } catch (e) {
                // if anything fails, keep placeholder
                if (ph) ph.style.display = 'flex';
                mapDiv.style.display = 'none';
                console.warn('Map init failed:', e);
            }
        }

        (async function load() {
            if (!fieldId) {
                alert('No centre id provided.');
                window.location.href = "{{ url('/center-request') }}";
                return;
            }

            toggleLoader(true);
            const approveBtn = document.getElementById('approveBtn');
            const rejectBtn = document.getElementById('rejectBtn');

            try {
                const snap = await db.collection('fields').doc(fieldId).get();
                if (!snap.exists) {
                    alert('Centre not found.');
                    window.location.href = "{{ url('/center-request') }}";
                    return;
                }
                const d = snap.data();

                // header
                const title = d.name || d.location?.name || 'Centre Request Details';
                document.getElementById('pageTitle').textContent = title;
                const s = statusClass(d.status);
                const statusAlert = document.getElementById('statusAlert');
                statusAlert.className = `alert ${s.cls} d-flex justify-content-between align-items-center`;
                document.getElementById('statusText').textContent = s.label;
                document.getElementById('submittedAt').textContent = `Submitted on ${fmtDate(d.createdAt)}`;

                // basic info
                document.getElementById('centreName').textContent = title;
                document.getElementById('docIdBadge').textContent = `ID: ${snap.id}`;
                document.getElementById('centreDesc').textContent = d.description || '—';

                // sports badges
                const sports = Array.isArray(d.sports) ? d.sports : [];
                document.getElementById('sportsBadges').innerHTML = sports.map((sp, i) => {
                    if (i === 0) return `<span class="badge bg-primary me-1">${esc(sp)}</span>`;
                    if (i === 1)
                    return `<span class="badge text-white me-1" style="background-color:#8b5cf6;">${esc(sp)}</span>`;
                    return `<span class="badge bg-success me-1">${esc(sp)}</span>`;
                }).join('') || '';

                // facilities
                const fac = Array.isArray(d.facilities) ? d.facilities : [];
                document.getElementById('facilitiesList').innerHTML = fac.length ?
                    fac.map(f =>
                        `<li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>${esc(f)}</li>`)
                    .join('') :
                    '<li class="text-muted">No facilities listed</li>';

                // timings
                const tb = document.getElementById('timingsBody');
                const t = d.timings || {};
                let rows = '';
                ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'].forEach(k => {
                    if (!t[k]) return;
                    rows +=
                        `<tr><td>${DAY_LABEL[k]||k}</td><td>${esc(t[k].open||'-')} – ${esc(t[k].close||'-')}</td></tr>`;
                });
                tb.innerHTML = rows || '<tr><td colspan="2" class="text-muted">No hours provided</td></tr>';

                // address
                document.getElementById('centreAddress').textContent = d.location?.address || '—';

                // map
                const lat = Number(d.location?.latitude);
                const lng = Number(d.location?.longitude);
                const coordsValid = Number.isFinite(lat) && Number.isFinite(lng) && !(lat === 0 && lng === 0);
                if (coordsValid) initCentreMap(lat, lng);

                // submitter
                document.getElementById('submitterId').textContent = `UID: ${d.uid || '—'}`;
                if (d.uid) {
                    const uSnap = await db.collection('users').where('uid', '==', d.uid).limit(1).get();
                    if (!uSnap.empty) {
                        const u = uSnap.docs[0].data();
                        const name = u.fullName || 'Unknown User';
                        document.getElementById('userName').textContent = name;
                        document.getElementById('userAvatar').textContent = initials(name);
                        if (u.about) document.getElementById('userAbout').textContent = u.about;
                        if (u.email) document.getElementById('userEmail').textContent = u.email;
                        if (u.mobile) document.getElementById('userPhone').textContent = u.mobile;
                    }
                }

                // actions
                approveBtn.addEventListener('click', async () => {
                    if (!confirm('Approve this centre?')) return;
                    approveBtn.disabled = true;
                    rejectBtn.disabled = true;
                    try {
                        await db.collection('fields').doc(fieldId).update({
                            status: 'approved',
                            updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                        });
                        const st = statusClass('approved');
                        statusAlert.className =
                            `alert ${st.cls} d-flex justify-content-between align-items-center`;
                        document.getElementById('statusText').textContent = st.label;
                        alert('Centre approved.');
                    } catch (e) {
                        alert(e?.message || 'Failed to approve.');
                    } finally {
                        approveBtn.disabled = false;
                        rejectBtn.disabled = false;
                    }
                });

                rejectBtn.addEventListener('click', async () => {
                    if (!confirm('Reject this centre?')) return;
                    approveBtn.disabled = true;
                    rejectBtn.disabled = true;
                    try {
                        await db.collection('fields').doc(fieldId).update({
                            status: 'rejected',
                            updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                        });
                        const st = statusClass('rejected');
                        statusAlert.className =
                            `alert ${st.cls} d-flex justify-content-between align-items-center`;
                        document.getElementById('statusText').textContent = st.label;
                        alert('Centre rejected.');
                    } catch (e) {
                        alert(e?.message || 'Failed to reject.');
                    } finally {
                        approveBtn.disabled = false;
                        rejectBtn.disabled = false;
                    }
                });

            } catch (err) {
                console.error(err);
                alert(err?.message || 'Failed to load centre.');
                window.location.href = "{{ url('/center-request') }}";
            } finally {
                toggleLoader(false); // ✅ hide loader when done (success or error)
            }
        })();
    </script>
</body>

</html>






{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Centre Request Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" href="{{ url('public/assets/dist/img/favicon.png') }}" type="image/x-icon">

    <!-- Firebase (compat) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed bg-light">
    <div class="wrapper">

        <!-- Header -->
        @include('inc.header')

        <!-- Sidebar -->
        @include('inc.aside')

        <!-- Content Wrapper -->
        <div class="content-wrapper p-4 bg-light">
            <!-- Page Header -->
            <div class="mb-4">
                <h3 id="pageTitle" class="fw-bold">Centre Request Details</h3>
                <p id="pageSubtitle" class="text-muted">Review submission and make approval decision</p>
                <div id="statusAlert" class="alert alert-warning d-flex justify-content-between align-items-center">
                    <span><strong>Status:</strong> <span id="statusText">Pending Review</span></span>
                    <small id="submittedAt" class="text-muted">Submitted on March 20, 2025 at 2:30 PM</small>
                </div>
            </div>

            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Centre Information -->
                    <div class="card p-4">
                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                            <div class="d-flex align-items-start">
                                <div class="bg-secondary rounded-2 text-white d-flex justify-content-center align-items-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="fas fa-image fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 id="centreName" class="mb-1 fw-bold">Downtown Sports Arena</h5>
                                    <!-- badges container (we will replace its content) -->
                                    <div id="sportsBadges">
                                        <span class="badge bg-primary me-1">Football</span>
                                        <span class="badge text-white me-1" style="background-color:#8b5cf6;">Indoor
                                            Tennis</span>
                                        <span class="badge bg-success">Volleyball</span>
                                    </div>
                                </div>
                            </div>
                            <span id="docIdBadge" class="badge bg-light text-muted mt-2 mt-lg-0">ID:
                                #EVT–2025–0322</span>
                        </div>

                        <div class="mt-4">
                            <h6 class="fw-bold">Centre Description</h6>
                            <p id="centreDesc" class="text-muted mb-0">
                                A state-of-the-art multi-sport facility located in the heart of downtown Manhattan.
                                Features modern equipment, professional-grade courts, and comprehensive amenities for
                                athletes of all
                                levels. The arena hosts various sports including football, basketball, volleyball, and
                                indoor tennis.
                            </p>
                        </div>
                    </div>

                    <!-- Sports & Facilities -->
                    <div class="card p-4 mt-4">
                        <h6 class="fw-bold mb-3">Sports & Facilities</h6>
                        <ul id="facilitiesList" class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Professional
                                changing rooms with lockers</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> On-site parking for
                                200+ vehicles</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Cafeteria and
                                refreshment area</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> First aid station
                                with trained staff</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Equipment rental service</li>
                        </ul>
                    </div>

                    <!-- Operating Hours -->
                    <div class="card p-4 mt-4">
                        <h6 class="fw-bold mb-3">Operating Hours</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Day</th>
                                        <th>Hours</th>
                                    </tr>
                                </thead>
                                <tbody id="timingsBody">
                                    <tr>
                                        <td>Monday – Friday</td>
                                        <td>6:00 AM – 11:00 PM</td>
                                    </tr>
                                    <tr>
                                        <td>Saturday</td>
                                        <td>7:00 AM – 10:00 PM</td>
                                    </tr>
                                    <tr>
                                        <td>Sunday</td>
                                        <td>8:00 AM – 9:00 PM</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Submission Details -->
                    <div class="card p-4 mb-4">
                        <h6 class="fw-bold mb-3">Submission Details</h6>
                        <div class="d-flex align-items-start mb-3">
                            <div id="userAvatar"
                                class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; font-weight: bold;">AH</div>
                            <div class="ms-3">
                                <h6 id="userName" class="mb-1 fw-bold">Alex Harper</h6>
                                <p id="userAbout" class="mb-1 text-muted" style="font-size: 14px;">Centre Creator &
                                    Sports Enthusiast</p>
                                <div class="text-warning" style="font-size: 16px;">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i><i class="far fa-star"></i>
                                    <span class="text-muted small ms-2">4.8 (24 reviews)</span>
                                </div>
                            </div>
                        </div>
                        <ul class="list-unstyled small text-dark mb-0">
                            <li class="mb-2"><i class="fas fa-envelope me-2 text-secondary"></i> <span
                                    id="userEmail">alex.harper@email.com</span></li>
                            <li class="mb-2"><i class="fas fa-phone me-2 text-secondary"></i> <span id="userPhone">+1
                                    (555) 123–4567</span></li>
                            <li><i class="fas fa-id-badge me-2 text-secondary"></i> <span id="submitterId">UID: —</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Centre Location -->
                    <div class="card p-4 mb-4">
                        <h6 class="fw-bold mb-3">Centre Location</h6>
                        <div class="bg-light d-flex justify-content-center align-items-center rounded mb-3"
                            style="height: 150px;">
                            <i class="fas fa-map-marker-alt fa-2x text-secondary"></i>
                        </div>
                        <p id="centreAddress" class="small text-dark mb-0">
                            123 Sports Avenue, Manhattan, NY 10001
                        </p>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card p-4">
                        <h6 class="fw-bold mb-4">Quick Actions</h6>
                        <div class="d-grid">
                            <button id="approveBtn" type="button" class="btn btn-success btn-lg w-100 mb-3">
                                <i class="fas fa-check me-2"></i> Approve Centre
                            </button>
                            <button id="rejectBtn" type="button" class="btn btn-danger btn-lg w-100">
                                <i class="fas fa-times me-2"></i> Reject Centre
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        @include('inc.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>

    <script>
        // ---- Firebase init ----
        const firebaseConfig = {
            apiKey: "AIzaSyDtnVhbX6cczcCydCB1DLxrAJVQWU2H8S0",
            authDomain: "time2play-ed370.firebaseapp.com",
            projectId: "time2play-ed370",
            messagingSenderId: "988354704853",
            appId: "1:988354704853:web:YOUR_WEB_APP_ID"
        };
        if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore();

        // ---- helpers (no design changes; just data fill) ----
        const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [c]));

        function fmtDate(ts) {
            if (!ts) return '—';
            const d = ts.toDate ? ts.toDate() : new Date(ts);
            return d.toLocaleString();
        }

        function initials(name) {
            const parts = String(name || '').trim().split(/\s+/);
            const a = (parts[0] || ' ').charAt(0);
            const b = (parts[1] || ' ').charAt(0);
            return (a + b).toUpperCase();
        }

        function statusToClass(status) {
            const s = String(status || '').toLowerCase();
            if (s === 'approved') return {
                label: 'Approved',
                cls: 'alert-success'
            };
            if (s === 'rejected') return {
                label: 'Rejected',
                cls: 'alert-danger'
            };
            if (s === 'pending') return {
                label: 'Pending Review',
                cls: 'alert-warning'
            };
            return {
                label: s || '—',
                cls: 'alert-secondary'
            };
        }
        const DAY_LABEL = {
            mon: 'Monday',
            tue: 'Tuesday',
            wed: 'Wednesday',
            thu: 'Thursday',
            fri: 'Friday',
            sat: 'Saturday',
            sun: 'Sunday'
        };

        // ---- get id (route param first, then URL) ----
        const bladeId = "{{ $id ?? '' }}";
        const fieldId = (bladeId && bladeId !== '') ? bladeId : (() => {
            const m = location.pathname.match(/center-request-details\/([^\/?#]+)/);
            if (m && m[1]) return decodeURIComponent(m[1]);
            const qs = new URLSearchParams(location.search);
            return qs.get('id') || '';
        })();

        (async function load() {
            if (!fieldId) {
                alert('No centre id provided.');
                window.location.href = "{{ url('/center-request') }}";
                return;
            }

            try {
                // 1) fetch field doc
                const snap = await db.collection('fields').doc(fieldId).get();
                if (!snap.exists) {
                    alert('Centre not found.');
                    window.location.href = "{{ url('/center-request') }}";
                    return;
                }
                const d = snap.data();

                // Top header
                const title = d.name || d.location?.name || 'Centre Request Details';
                document.getElementById('pageTitle').textContent = title;
                document.getElementById('pageSubtitle').textContent =
                    'Review submission and make approval decision';

                const sObj = statusToClass(d.status);
                const statusAlert = document.getElementById('statusAlert');
                statusAlert.className = `alert ${sObj.cls} d-flex justify-content-between align-items-center`;
                document.getElementById('statusText').textContent = sObj.label;
                document.getElementById('submittedAt').textContent = `Submitted on ${fmtDate(d.createdAt)}`;

                // Centre card
                document.getElementById('centreName').textContent = title;
                document.getElementById('docIdBadge').textContent = `ID: ${snap.id}`;
                document.getElementById('centreDesc').textContent = d.description || document.getElementById(
                    'centreDesc').textContent;

                // Sports badges
                const sportsWrap = document.getElementById('sportsBadges');
                const sports = Array.isArray(d.sports) ? d.sports : [];
                if (sports.length) {
                    sportsWrap.innerHTML = sports.map((s, i) => {
                        // keep original color vibe: first primary, second purple, others success
                        if (i === 0) return `<span class="badge bg-primary me-1">${esc(s)}</span>`;
                        if (i === 1)
                            return `<span class="badge text-white me-1" style="background-color:#8b5cf6;">${esc(s)}</span>`;
                        return `<span class="badge bg-success me-1">${esc(s)}</span>`;
                    }).join('');
                }

                // Facilities
                const facWrap = document.getElementById('facilitiesList');
                const fac = Array.isArray(d.facilities) ? d.facilities : [];
                if (fac.length) {
                    facWrap.innerHTML = fac.map(f => `
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>${esc(f)}</li>
                    `).join('');
                }

                // Timings -> keep 2 columns (Day / Hours) to preserve design
                const tBody = document.getElementById('timingsBody');
                const t = d.timings || {};
                const order = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
                let rows = '';
                for (const key of order) {
                    if (!t[key]) continue;
                    const open = t[key].open || '-';
                    const close = t[key].close || '-';
                    rows += `
                        <tr>
                            <td>${DAY_LABEL[key] || key}</td>
                            <td>${esc(open)} – ${esc(close)}</td>
                        </tr>
                    `;
                }
                if (rows) tBody.innerHTML = rows;

                // Location
                document.getElementById('centreAddress').textContent = d.location?.address || document
                    .getElementById('centreAddress').textContent;

                // 2) fetch submitter (users where uid == d.uid)
                document.getElementById('submitterId').textContent = `UID: ${d.uid || '—'}`;
                if (d.uid) {
                    const uSnap = await db.collection('users').where('uid', '==', d.uid).limit(1).get();
                    if (!uSnap.empty) {
                        const u = uSnap.docs[0].data();
                        const name = u.fullName || 'Unknown User';
                        document.getElementById('userName').textContent = name;
                        document.getElementById('userAvatar').textContent = initials(name);
                        document.getElementById('userAbout').textContent = u.about || document.getElementById(
                            'userAbout').textContent;
                        document.getElementById('userEmail').textContent = u.email || document.getElementById(
                            'userEmail').textContent;
                        document.getElementById('userPhone').textContent = u.mobile || document.getElementById(
                            'userPhone').textContent;
                    }
                }

                // Actions
                const approveBtn = document.getElementById('approveBtn');
                const rejectBtn = document.getElementById('rejectBtn');

                approveBtn.addEventListener('click', async () => {
                    if (!confirm('Approve this centre?')) return;
                    approveBtn.disabled = true;
                    rejectBtn.disabled = true;
                    try {
                        await db.collection('fields').doc(fieldId).update({
                            status: 'approved',
                            updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                        });
                        const s = statusToClass('approved');
                        statusAlert.className =
                            `alert ${s.cls} d-flex justify-content-between align-items-center`;
                        document.getElementById('statusText').textContent = s.label;
                        alert('Centre approved.');
                    } catch (e) {
                        alert(e?.message || 'Failed to approve.');
                    } finally {
                        approveBtn.disabled = false;
                        rejectBtn.disabled = false;
                    }
                });

                rejectBtn.addEventListener('click', async () => {
                    if (!confirm('Reject this centre?')) return;
                    approveBtn.disabled = true;
                    rejectBtn.disabled = true;
                    try {
                        await db.collection('fields').doc(fieldId).update({
                            status: 'rejected',
                            updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                        });
                        const s = statusToClass('rejected');
                        statusAlert.className =
                            `alert ${s.cls} d-flex justify-content-between align-items-center`;
                        document.getElementById('statusText').textContent = s.label;
                        alert('Centre rejected.');
                    } catch (e) {
                        alert(e?.message || 'Failed to reject.');
                    } finally {
                        approveBtn.disabled = false;
                        rejectBtn.disabled = false;
                    }
                });

            } catch (err) {
                console.error(err);
                alert(err?.message || 'Failed to load centre.');
                window.location.href = "{{ url('/center-request') }}";
            }
        })();
    </script>
</body>

</html> --}}









{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Centre Request Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" href="{{ url('public/assets/dist/img/favicon.png') }}" type="image/x-icon">
</head>

<body class="hold-transition sidebar-mini layout-fixed bg-light">
    <div class="wrapper">

        <!-- Header -->
        @include('inc.header')

        <!-- Sidebar -->
        @include('inc.aside')

        <!-- Content Wrapper -->
        <div class="content-wrapper p-4 bg-light">
            <!-- Page Header -->
            <div class="mb-4">
                <h3 class="fw-bold">Centre Request Details</h3>
                <p class="text-muted">Review submission and make approval decision</p>
                <div class="alert alert-warning d-flex justify-content-between align-items-center">
                    <span><strong>Status:</strong> Pending Review</span>
                    <small class="text-muted">Submitted on March 20, 2025 at 2:30 PM</small>
                </div>
            </div>

            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Centre Information -->
                    <div class="card p-4">
                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                            <div class="d-flex align-items-start">
                                <div class="bg-secondary rounded-2 text-white d-flex justify-content-center align-items-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="fas fa-image fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1 fw-bold">Downtown Sports Arena</h5>
                                    <span class="badge bg-primary me-1">Football</span>
                                    <span class="badge text-white me-1" style="background-color:#8b5cf6;">Indoor
                                        Tennis</span>
                                    <span class="badge bg-success">Volleyball</span>
                                </div>
                            </div>
                            <span class="badge bg-light text-muted mt-2 mt-lg-0">ID: #EVT–2025–0322</span>
                        </div>

                        <div class="mt-4">
                            <h6 class="fw-bold">Centre Description</h6>
                            <p class="text-muted mb-0">
                                A state-of-the-art multi-sport facility located in the heart of downtown Manhattan.
                                Features modern
                                equipment, professional-grade courts, and comprehensive amenities for athletes of all
                                levels.
                                The arena hosts various sports including football, basketball, volleyball, and indoor
                                tennis.
                            </p>
                        </div>
                    </div>

                    <!-- Sports & Facilities -->
                    <div class="card p-4 mt-4">
                        <h6 class="fw-bold mb-3">Sports & Facilities</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Professional
                                changing rooms with lockers</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> On-site parking for
                                200+ vehicles</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Cafeteria and
                                refreshment area</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> First aid station
                                with trained staff</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Equipment rental service</li>
                        </ul>
                    </div>

                    <!-- Operating Hours -->
                    <div class="card p-4 mt-4">
                        <h6 class="fw-bold mb-3">Operating Hours</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Day</th>
                                        <th>Hours</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Monday – Friday</td>
                                        <td>6:00 AM – 11:00 PM</td>
                                    </tr>
                                    <tr>
                                        <td>Saturday</td>
                                        <td>7:00 AM – 10:00 PM</td>
                                    </tr>
                                    <tr>
                                        <td>Sunday</td>
                                        <td>8:00 AM – 9:00 PM</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Submission Details -->
                    <div class="card p-4 mb-4">
                        <h6 class="fw-bold mb-3">Submission Details</h6>
                        <div class="d-flex align-items-start mb-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; font-weight: bold;">AH</div>
                            <div class="ms-3">
                                <h6 class="mb-1 fw-bold">Alex Harper</h6>
                                <p class="mb-1 text-muted" style="font-size: 14px;">Centre Creator & Sports Enthusiast
                                </p>
                                <div class="text-warning" style="font-size: 16px;">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                        class="fas fa-star"></i><i class="far fa-star"></i>
                                    <span class="text-muted small ms-2">4.8 (24 reviews)</span>
                                </div>
                            </div>
                        </div>
                        <ul class="list-unstyled small text-dark mb-0">
                            <li class="mb-2"><i class="fas fa-envelope me-2 text-secondary"></i> alex.harper@email.com
                            </li>
                            <li class="mb-2"><i class="fas fa-phone me-2 text-secondary"></i> +1 (555) 123–4567</li>
                            <li><i class="fas fa-map-marker-alt me-2 text-secondary"></i> New York, NY</li>
                        </ul>
                    </div>

                    <!-- Centre Location -->
                    <div class="card p-4 mb-4">
                        <h6 class="fw-bold mb-3">Centre Location</h6>
                        <div class="bg-light d-flex justify-content-center align-items-center rounded mb-3"
                            style="height: 150px;">
                            <i class="fas fa-map-marker-alt fa-2x text-secondary"></i>
                        </div>
                        <p class="small text-dark mb-0">
                            123 Sports Avenue, Manhattan, NY 10001
                        </p>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card p-4">
                        <h6 class="fw-bold mb-4">Quick Actions</h6>
                        <div class="d-grid">
                            <button type="button" class="btn btn-success btn-lg w-100 mb-3">
                                <i class="fas fa-check me-2"></i> Approve Centre
                            </button>
                            <button type="button" class="btn btn-danger btn-lg w-100">
                                <i class="fas fa-times me-2"></i> Reject Centre
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        @include('inc.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html> --}}

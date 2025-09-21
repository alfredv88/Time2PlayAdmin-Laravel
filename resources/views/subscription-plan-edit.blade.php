<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Edit Subscription Plan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" href="{{ url('public/assets/dist/img/favicon.png') }}" type="image/x-icon">

    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <div class="content-wrapper p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold">Edit Plan: {{ strtoupper($type) }}</h3>
                <a href="{{ route('subscription.control') }}" class="btn btn-outline-secondary">Back</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form id="planForm">
                        <input type="hidden" id="planType" value="{{ $type }}">

                        <div class="form-group">
                            <label class="font-weight-bold">Status</label>
                            <select id="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="block">Block</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Features</label>
                            <div class="input-group mb-2">
                                <input id="newFeature" type="text" class="form-control" placeholder="Add a feature">
                                <div class="input-group-append">
                                    <button id="addFeatureBtn" class="btn btn-primary" type="button"><i
                                            class="fas fa-plus"></i> Add</button>
                                </div>
                            </div>

                            <ul id="featureList" class="list-group">
                                <!-- populated by JS -->
                            </ul>
                        </div>

                        <div class="text-right">
                            <button id="saveBtn" class="btn btn-primary"><i class="fas fa-save"></i> Save
                                Changes</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        @include('inc.footer')
    </div>

    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>

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

        const DEFAULT_FEATURES = {
            free: [
                "Join Free Events",
                "Browse Local Sports Feed",
                "Access Public Groups"
            ],
            pro: [
                "Join Premium & Paid Events",
                "Create & Host Events",
                "View Event Stats & Insights",
                "Access Private Sports Groups",
                "Priority Support Access"
            ]
        };

        const type = document.getElementById('planType').value;
        const statusEl = document.getElementById('status');
        const listEl = document.getElementById('featureList');
        const newInput = document.getElementById('newFeature');

        let features = [];

        function renderFeatures() {
            listEl.innerHTML = features.map((f, idx) => `
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <span>${f}</span>
        <button type="button" class="btn btn-sm btn-outline-danger" data-remove="${idx}">
          <i class="fas fa-trash"></i>
        </button>
      </li>
    `).join('') || '<li class="list-group-item text-muted">No features</li>';
        }

        document.getElementById('addFeatureBtn').addEventListener('click', () => {
            const v = newInput.value.trim();
            if (!v) return;
            features.push(v);
            newInput.value = '';
            renderFeatures();
        });

        listEl.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-remove]');
            if (!btn) return;
            const idx = parseInt(btn.getAttribute('data-remove'), 10);
            features.splice(idx, 1);
            renderFeatures();
        });

        async function loadPlan() {
            const ref = db.collection('subscriptionPlan').doc(type);
            const snap = await ref.get();
            if (!snap.exists) {
                await ref.set({
                    type,
                    status: 'active',
                    features: DEFAULT_FEATURES[type] || []
                });
                return loadPlan();
            }
            const d = snap.data();
            statusEl.value = d.status || 'active';
            features = Array.isArray(d.features) ? d.features.slice() : [];
            renderFeatures();
        }

        document.getElementById('saveBtn').addEventListener('click', async (e) => {
            e.preventDefault();
            await db.collection('subscriptionPlan').doc(type).set({
                type,
                status: statusEl.value,
                features,
                updatedAt: firebase.firestore.FieldValue.serverTimestamp()
            }, {
                merge: true
            });
            alert('Saved.');
            window.location.href = "{{ route('subscription.control') }}";
        });

        loadPlan();
    </script>
</body>

</html>

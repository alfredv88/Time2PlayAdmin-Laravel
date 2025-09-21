<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Help Requests</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" type="image/png" href="{{ url('public/assets/dist/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">

    <!-- Firebase (compat) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>

    <style>
        .request-item:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .active-request {
            background-color: #e9ecef;
        }

        @media (max-width: 767px) {
            .userdetails-card {
                margin-top: 15px;
            }
        }

        .minh-120 {
            min-height: 120px;
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
                    <h1 class="font-weight-bold mb-2">Help Requests</h1>
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Left: list -->
                        <div class="col-md-5">
                            <ul id="reqList" class="list-group">
                                <!-- filled by JS -->
                                <li id="listLoader" class="list-group-item text-muted">
                                    <i class="fas fa-spinner fa-spin me-2"></i> Loading requests…
                                </li>
                            </ul>
                        </div>

                        <!-- Right: details -->
                        <div class="col-md-7 userdetails-card">
                            <div id="detailCard" class="card p-4 shadow-sm border-0 minh-120">
                                <p id="emptyMsg" class="text-muted m-0">Select a request to view details.</p>

                                <div id="detailWrap" style="display:none;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="fw-bold mb-3">Person Details</h5>
                                            <p class="mb-1"><strong>First Name:</strong> <span id="dName">—</span>
                                            </p>
                                            <p class="mb-1">
                                                <strong>Status:</strong>
                                                <span id="dStatus" class="text-primary">—</span>
                                            </p>
                                            <p class="mb-1"><strong>Subject:</strong> Help me</p>
                                            <p class="mb-1">
                                                <strong>Email Address:</strong>
                                                <a id="dEmail" href="#" target="_blank">—</a>
                                            </p>
                                            <p class="mb-1">
                                                <strong>Phone:</strong> <span id="dPhone">—</span>
                                            </p>
                                        </div>
                                        <img id="dAvatar" src="https://i.pravatar.cc/100?u=placeholder" alt="Profile"
                                            class="rounded-circle" width="70" height="70">
                                    </div>

                                    <div class="mb-3">
                                        <p class="mb-2"><strong>Feedback:</strong></p>
                                        <p id="dMessage" class="text-muted" style="font-size: 14px;">—</p>
                                    </div>

                                    <!-- Existing reply -->
                                    <div id="replyView" class="mb-2" style="display:none;">
                                        <p class="mb-2"><strong>Reply:</strong></p>
                                        <p id="dReply">—</p>
                                    </div>

                                    <!-- Reply form (only if no reply yet) -->
                                    <form id="replyForm" action="{{ route('help.requests.reply', ['id' => 0]) }}"
                                        method="POST" style="display:none;">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label class="form-label"><strong>Reply:</strong></label>
                                            <textarea id="replyText" name="reply" class="form-control" rows="4" required placeholder="Write reply here..."></textarea>
                                        </div>
                                        <div class="text-center">
                                            <div class="mt-3">
                                                <button id="replyBtn" type="submit" class="btn btn-primary w-100 py-3"
                                                    style="background-color: #0d3d70; border: none;">
                                                    Send Reply
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div> <!-- /detailWrap -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('inc.footer')
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

        // state
        let requests = []; // array of {id, ...data}
        let selectedId = (new URLSearchParams(location.search)).get('id') || null;

        // helpers
        const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [c]));

        function statusText(doc) {
            return doc.reply ? 'Read' : (doc.status || 'Unread');
        }

        function statusClass(doc) {
            const s = statusText(doc).toLowerCase();
            return (s === 'replied') ? 'text-danger' : 'text-primary'; // keep your original color logic
        }

        function goTo(id) {
            const u = new URL(location.href);
            u.searchParams.set('id', id);
            history.replaceState(null, '', u.toString());
            selectedId = id;
            renderList();
            renderDetail();
        }

        // render left list
        function renderList() {
            const ul = document.getElementById('reqList');
            const loader = document.getElementById('listLoader');
            if (loader) loader.remove();

            if (!requests.length) {
                ul.innerHTML = '<li class="list-group-item text-muted">No requests found.</li>';
                return;
            }

            ul.innerHTML = requests.map(r => {
                const name = r.userName || r.name || 'No Name';
                const email = r.userEmail || r.email || '';
                const active = (r.id === selectedId) ? 'active-request' : '';
                return `
                <li class="list-group-item request-item ${active}" onclick="goTo('${esc(r.id)}')">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="d-flex align-items-center">
                            <img src="https://i.pravatar.cc/150?u=${encodeURIComponent(r.userId || r.email || r.id)}"
                                 alt="Avatar" class="rounded-circle mr-3" width="40" height="40">
                            <div>
                                <strong>${esc(name)}</strong><br>
                                <small>${esc(email)}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="${statusClass(r)} mr-2">${esc(statusText(r)).charAt(0).toUpperCase()+esc(statusText(r)).slice(1)}</span>
                            <i class="fas fa-chevron-right text-secondary"></i>
                        </div>
                    </div>
                </li>`;
            }).join('');
        }

        // render right detail
        function renderDetail() {
            const wrap = document.getElementById('detailWrap');
            const empty = document.getElementById('emptyMsg');
            const form = document.getElementById('replyForm');
            const view = document.getElementById('replyView');

            if (!selectedId) {
                wrap.style.display = 'none';
                empty.style.display = 'block';
                return;
            }

            const doc = requests.find(x => x.id === selectedId);
            if (!doc) {
                wrap.style.display = 'none';
                empty.textContent = 'Request not found.';
                empty.style.display = 'block';
                return;
            }

            // fill fields
            const name = doc.userName || doc.name || 'No Name';
            const email = doc.userEmail || doc.email || '';
            const phone = doc.contact || '';
            const message = doc.message || '';

            document.getElementById('dName').textContent = name;
            document.getElementById('dStatus').textContent = statusText(doc).charAt(0).toUpperCase() + statusText(doc)
                .slice(1);
            document.getElementById('dEmail').textContent = email || '—';
            document.getElementById('dEmail').href = email ? `mailto:${email}` : '#';
            document.getElementById('dPhone').textContent = phone || '—';
            document.getElementById('dMessage').textContent = message;
            document.getElementById('dAvatar').src =
                `https://i.pravatar.cc/100?u=${encodeURIComponent(doc.userId || doc.email || doc.id)}`;

            // reply area
            if (doc.reply) {
                // already replied
                document.getElementById('dReply').textContent = doc.reply;
                view.style.display = 'block';
                form.style.display = 'none';
            } else {
                // no reply yet
                view.style.display = 'none';
                form.style.display = 'block';
                form.setAttribute('data-id', doc.id); // to know which doc to update
            }

            empty.style.display = 'none';
            wrap.style.display = 'block';
        }

        // load data from Firestore
        (async function init() {
            try {
                const snap = await db.collection('help_support').orderBy('timestamp', 'desc').get();
                requests = snap.docs.map(d => ({
                    id: d.id,
                    ...d.data()
                }));

                // if there is no selected id yet, select the first
                if (!selectedId && requests.length) {
                    selectedId = requests[0].id;
                }

                renderList();
                renderDetail();
            } catch (e) {
                console.error(e);
                const ul = document.getElementById('reqList');
                ul.innerHTML = `<li class="list-group-item text-danger">Failed to load requests.</li>`;
            }
        })();

        // intercept reply form to write to Firestore
        document.getElementById('replyForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const txt = document.getElementById('replyText').value.trim();
            if (!id) return alert('No request selected.');
            if (!txt) return alert('Please write a reply.');

            const btn = document.getElementById('replyBtn');
            btn.disabled = true;

            try {
                await db.collection('help_support').doc(id).set({
                    reply: txt,
                    status: 'replied',
                    repliedAt: firebase.firestore.FieldValue.serverTimestamp()
                }, {
                    merge: true
                });

                // reflect in local state
                const it = requests.find(x => x.id === id);
                if (it) {
                    it.reply = txt;
                    it.status = 'replied';
                }

                // re-render UI
                renderList();
                renderDetail();
            } catch (e) {
                console.error(e);
                alert(e?.message || 'Failed to send reply.');
            } finally {
                btn.disabled = false;
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
    <title>Help Requests</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" type="image/png" href="{{ url('public/assets/dist/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">
    <style>
        .request-item:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .active-request {
            background-color: #e9ecef;
        }

        @media (max-width: 767px) {
            .userdetails-card {
                margin-top: 15px;
                /* Adjust value as needed */
            }
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
                    <h1 class="font-weight-bold mb-2">Help Requests</h1>
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    @php
                        $helpRequests = [
                            [
                                'id' => 1,
                                'uid' => 'user1',
                                'name' => 'Ali Raza',
                                'email' => 'ali@example.com',
                                'phoneNo' => '03123456789',
                                'status' => 'unread',
                                'feedback' => 'I need help with my account login.',
                                'reply' => null,
                                'user' => ['name' => 'Ali Raza'],
                            ],
                            [
                                'id' => 2,
                                'uid' => 'user2',
                                'name' => 'Fatima Khan',
                                'email' => 'fatima@example.com',
                                'phoneNo' => '03219876543',
                                'status' => 'read',
                                'feedback' => 'Can you explain the refund policy?',
                                'reply' => 'Yes, we will process it within 3-5 working days.',
                                'user' => ['name' => 'Fatima Khan'],
                            ],
                            [
                                'id' => 3,
                                'uid' => 'user3',
                                'name' => 'Usman Ahmed',
                                'email' => 'usman@example.com',
                                'phoneNo' => null,
                                'status' => 'unread',
                                'feedback' => 'How do I update my shipping address?',
                                'reply' => null,
                                'user' => ['name' => 'Usman Ahmed'],
                            ],
                        ];
                    @endphp

                    <div class="row">
                        <div class="col-md-5">
                            <ul class="list-group">
                                @foreach ($helpRequests as $req)
                                    <li class="list-group-item request-item {{ request('id') == $req['id'] ? 'active-request' : '' }}"
                                        onclick="window.location='?id={{ $req['id'] }}'">

                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <!-- Left: Avatar + Name + Email -->
                                            <div class="d-flex align-items-center">
                                                <img src="https://i.pravatar.cc/150?u={{ $req['uid'] }}"
                                                    alt="Avatar" class="rounded-circle mr-3" width="40"
                                                    height="40">
                                                <div>
                                                    <strong>{{ $req['user']['name'] ?? ($req['name'] ?? 'No Name') }}</strong><br>
                                                    <small>{{ $req['email'] }}</small>
                                                </div>
                                            </div>

                                            <!-- Right: Status + Arrow -->
                                            @php
                                                $statusColor =
                                                    strtolower($req['status']) === 'read'
                                                        ? 'text-danger'
                                                        : 'text-primary';
                                            @endphp
                                            <div class="d-flex align-items-center gap-2">
                                                <span
                                                    class="{{ $statusColor }} mr-2">{{ ucfirst($req['status']) }}</span>
                                                <i class="fas fa-chevron-right text-secondary"></i>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="col-md-7 userdetails-card">
                            @if (request('id'))
                                @php
                                    $selected = collect($helpRequests)->firstWhere('id', request('id'));
                                @endphp
                                @if ($selected)
                                    <div class="card p-4 shadow-sm border-0">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h5 class="fw-bold mb-3">Person Details</h5>
                                                <p class="mb-1"><strong>First Name:</strong>
                                                    {{ $selected['user']['name'] ?? $selected['name'] }}</p>
                                                <p class="mb-1">
                                                    <strong>Status:</strong>
                                                    <span
                                                        class="text-primary">{{ ucfirst($selected['status']) }}</span>
                                                </p>
                                                <p class="mb-1"><strong>Subject:</strong> Help me</p>
                                                <p class="mb-1">
                                                    <strong>Email Address:</strong>
                                                    <a
                                                        href="mailto:{{ $selected['email'] }}">{{ $selected['email'] }}</a>
                                                </p>
                                            </div>
                                            <img src="https://i.pravatar.cc/100?u={{ $selected['uid'] }}"
                                                alt="Profile" class="rounded-circle" width="70" height="70">
                                        </div>

                                        <div class="mb-3">
                                            <p class="mb-2"><strong>Feedback:</strong></p>
                                            <p class="text-muted" style="font-size: 14px;">
                                                {{ $selected['feedback'] }}
                                            </p>
                                        </div>

                                        @if (isset($selected['reply']))
                                            <div class="mb-2">
                                                <p class="mb-2"><strong>Reply:</strong></p>
                                                <p>{{ $selected['reply'] }}</p>
                                            </div>
                                        @else
                                            <form action="{{ route('help.requests.reply', $selected['id']) }}"
                                                method="POST">
                                                @csrf
                                                <div class="form-group mb-3">
                                                    <label class="form-label"><strong>Reply:</strong></label>
                                                    <textarea name="reply" class="form-control" rows="4" required placeholder="Write reply here..."></textarea>
                                                </div>
                                                <div class="text-center">
                                                    <div class="mt-3">
                                                        <button type="submit" class="btn btn-primary w-100 py-3"
                                                            style="background-color: #0d3d70; border: none;">
                                                            Send Reply
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <p class="text-muted">Select a request to view details.</p>
                            @endif
                        </div>

                    </div>
                </div>
            </section>
        </div>
        @include('inc.footer')
    </div>
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html> --}}

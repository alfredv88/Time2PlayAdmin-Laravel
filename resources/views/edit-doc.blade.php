<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts / Styles -->
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
        .spinner-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .6);
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
                            <h1>Update Document</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid position-relative">
                    <div id="cardSpinner" class="spinner-overlay">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header" style="background: var(--buttons-primary-color);">
                                    <h3 class="card-title">Update Document</h3>
                                </div>

                                <form id="editDocForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <!-- incoming Firestore doc id -->
                                    <input type="hidden" id="docId" value="{{ $docId }}">
                                    <!-- we keep type disabled in UI, but submit via this hidden field -->
                                    <input type="hidden" name="type" id="typeHidden">
                                    <!-- keep existing pdf url if user doesn't upload -->
                                    <input type="hidden" id="pdfUrlExisting">

                                    <div class="card-body">
                                        <!-- Title -->
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input id="titleInput" type="text" name="title" class="form-control"
                                                placeholder="Enter title" required>
                                        </div>

                                        <!-- Type (disabled select, but visible) -->
                                        <div class="form-group">
                                            <label>Type</label>
                                            <select id="typeSelect" class="form-control" disabled></select>
                                            <small class="text-muted">Type is fixed. Create a new document for a
                                                different type.</small>
                                        </div>

                                        <!-- Upload New PDF -->
                                        <div class="form-group">
                                            <label>Upload New PDF (optional)</label>
                                            <input id="pdfInput" type="file" name="policy_pdf"
                                                accept="application/pdf" class="form-control">
                                        </div>

                                        <!-- Current Preview -->
                                        <div class="form-group mt-3">
                                            <label>Current Preview</label><br>
                                            <embed id="pdfPreview" src="" type="application/pdf" width="20%"
                                                height="150">
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <a href="{{ url('/policies') }}"
                                            class="btn btn-outline-secondary mr-2">Cancel</a>
                                        <button id="updateBtn" type="submit" class="btn btn-primary border-0"
                                            style="background: var(--buttons-primary-color);">
                                            Update
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div> <!-- /.container-fluid -->
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

        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const camelToTitle = (s = '') =>
            s.replace(/([A-Z])/g, ' $1').replace(/^./, m => m.toUpperCase()).trim();

        const cardSpinner = document.getElementById('cardSpinner');
        const showCardSpinner = (on) => {
            cardSpinner.style.display = on ? 'flex' : 'none';
        };

        async function buildTypeSelectAndPrefill(docId) {
            const select = document.getElementById('typeSelect');
            const hiddenType = document.getElementById('typeHidden');

            // preload with common types
            const base = new Set(['privacyPolicy', 'termsOfService', 'refundPolicy']);
            try {
                const list = await db.collection('policyDoc').get();
                list.forEach(d => base.add(d.id || (d.data()?.type || '')));
            } catch (e) {
                console.warn('Could not fetch types from policyDoc', e);
            }

            const types = Array.from(base).filter(Boolean)
                .sort((a, b) => camelToTitle(a).localeCompare(camelToTitle(b)));

            select.innerHTML = types.map(t => `<option value="${t}">${camelToTitle(t)}</option>`).join('');

            // now load current doc to pick the right option + fill fields
            const snap = await db.collection('policyDoc').doc(docId).get();
            if (!snap.exists) throw new Error('Document not found');

            const d = snap.data();
            const currentType = d.type || snap.id;
            const currentTitle = d.title || camelToTitle(currentType);
            const pdfUrl = d.pdfUrl || d.url || '';

            // if current type isn't in options for any reason, add it
            if (![...types].includes(currentType)) {
                const opt = document.createElement('option');
                opt.value = currentType;
                opt.textContent = camelToTitle(currentType);
                select.appendChild(opt);
            }
            select.value = currentType;
            hiddenType.value = currentType;

            // fill other fields
            document.getElementById('titleInput').value = currentTitle;
            document.getElementById('pdfUrlExisting').value = pdfUrl;
            document.getElementById('pdfPreview').src = pdfUrl || '';
        }

        async function uploadPdfIfNeeded(file) {
            if (!file) return document.getElementById('pdfUrlExisting').value || '';
            const fd = new FormData();
            fd.append('policy_pdf', file);

            const res = await fetch("{{ route('policies.pdf.upload') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf
                },
                body: fd
            });
            const out = await res.json().catch(() => ({}));
            if (!res.ok || !out?.success || !out?.url) {
                throw new Error(out?.message || 'Upload failed');
            }
            return out.url; // absolute URL returned by Laravel
        }

        // --- Init (load + fill) ---
        (async function init() {
            try {
                showCardSpinner(true);
                const docId = document.getElementById('docId').value;
                await buildTypeSelectAndPrefill(docId);
            } catch (e) {
                console.error(e);
                alert(e.message || 'Failed to load document');
                window.location.href = "{{ url('/policies') }}";
            } finally {
                showCardSpinner(false);
            }
        })();

        // --- Submit update ---
        document.getElementById('editDocForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('updateBtn');
            const old = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Updating…';
            showCardSpinner(true);

            try {
                const docId = document.getElementById('docId').value;
                const title = document.getElementById('titleInput').value.trim();
                const type = document.getElementById('typeHidden').value;
                const file = document.getElementById('pdfInput').files[0] || null;
                if (!title) throw new Error('Title is required');

                // optional upload (or keep existing)
                const pdfUrl = await uploadPdfIfNeeded(file);

                // update Firestore doc
                await db.collection('policyDoc').doc(docId).set({
                    title,
                    type,
                    pdfUrl, // keep same if not replaced
                    updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                }, {
                    merge: true
                });

                // go back to list
                window.location.href = "{{ url('/policies') }}";
            } catch (err) {
                console.error(err);
                alert(err?.message || 'Update failed');
                btn.disabled = false;
                btn.innerText = old;
                showCardSpinner(false);
            }
        });
    </script>
</body>

</html>

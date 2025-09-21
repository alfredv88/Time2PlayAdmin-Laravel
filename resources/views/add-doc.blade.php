{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Document</title>

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
                            <h1>Update Documents</h1>
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
                                    <h3 class="card-title">Update Document</h3>
                                </div>
                                <form action="{{ url('update-doc/' . $doc['id']) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Upload New PDF for <strong>{{ $doc['title'] }}</strong></label>
                                            <input type="file" name="privacy_pdf" accept="application/pdf"
                                                class="form-control" required>
                                            @error('privacy_pdf')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mt-3">
                                            <label>Current Preview</label><br>
                                            <embed src="{{ $doc['url'] }}" type="application/pdf" width="20%"
                                                height="150">
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary border-0"
                                            style="background: var(--buttons-primary-color);;">Update</button>
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
    <title>Add Policy Document</title>

    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" type="image/png" href="{{ url('public/assets/dist/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Firebase -->
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
                            <h1>Add Policy Document</h1>
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
                                    <h3 class="card-title">Add Document</h3>
                                </div>

                                <form id="docForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Type</label>
                                            <select id="typeSelect" name="type" class="form-control" required>
                                                <option value="privacyPolicy">Privacy Policy</option>
                                                <option value="termsOfService">Terms of Service</option>
                                                <option value="refundPolicy">Refund Policy</option>
                                                <option value="custom">Custom…</option>
                                            </select>
                                        </div>

                                        <div id="customTypeWrap" class="form-group" style="display:none;">
                                            <label>Custom Type (e.g., Terms and condition)</label>
                                            <input type="text" id="customTypeInput" class="form-control"
                                                placeholder="Will be saved in camelCase">
                                            <small class="text-muted">Example input “Terms and condition” → saved as
                                                <code>termsAndCondition</code></small>
                                        </div>

                                        <div class="form-group">
                                            <label>Title (optional)</label>
                                            <input type="text" name="title" class="form-control"
                                                placeholder="Defaults to a title from the type">
                                        </div>

                                        <div class="form-group">
                                            <label>PDF File</label>
                                            <input type="file" name="pdf" accept="application/pdf"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="card-footer d-flex justify-content-end">
                                        <a href="{{ route('policies') }}"
                                            class="btn btn-outline-secondary mr-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary border-0"
                                            style="background: var(--buttons-primary-color);">Create</button>
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

        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const toCamel = (str = '') =>
            str.replace(/[^a-zA-Z0-9 ]+/g, ' ')
            .trim()
            .split(/\s+/)
            .map((w, i) => i === 0 ? w.toLowerCase() : (w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()))
            .join('');

        const camelToTitle = (s = '') =>
            s.replace(/([A-Z])/g, ' $1').replace(/^./, m => m.toUpperCase()).trim();

        const typeSelect = document.getElementById('typeSelect');
        const customWrap = document.getElementById('customTypeWrap');
        const customInput = document.getElementById('customTypeInput');

        typeSelect.addEventListener('change', () => {
            customWrap.style.display = (typeSelect.value === 'custom') ? 'block' : 'none';
        });

        document.getElementById('docForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.submitter;
            const old = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Saving…';

            try {
                const chosen = typeSelect.value;
                const finalType = (chosen === 'custom') ? toCamel(customInput.value) : chosen;
                if (!finalType) {
                    alert('Please enter a custom type');
                    return;
                }

                const titleInput = e.target.title.value.trim();
                const title = titleInput || camelToTitle(finalType);

                const file = e.target.pdf.files[0];
                if (!file) throw new Error('Please choose a PDF');

                // 1) Check uniqueness using doc id == type
                const ref = db.collection('policyDoc').doc(finalType);
                const exists = await ref.get();
                if (exists.exists) {
                    alert(`The "${finalType}" policy already exists.`);
                    return;
                }

                // 2) Upload PDF
                const fd = new FormData();
                fd.append('pdf', file);
                const res = await fetch("{{ route('policies.pdf.upload') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    body: fd
                });
                const out = await res.json();
                if (!res.ok || !out.success) throw new Error(out?.message || 'Upload failed');

                // 3) Save doc with doc id = finalType (prevents duplicates)
                await ref.set({
                    type: finalType,
                    title,
                    url: out.url,
                    createdAt: firebase.firestore.FieldValue.serverTimestamp()
                });

                // 4) Back to list
                window.location.href = "{{ route('policies') }}";
            } catch (err) {
                alert(err.message || err);
            } finally {
                btn.disabled = false;
                btn.innerText = old;
            }
        });
    </script>

</body>

</html>

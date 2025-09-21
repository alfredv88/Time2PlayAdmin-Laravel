{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Policy Documents</title>
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
    <style>
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--buttons-primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: all 0.3s;
        }

        .floating-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            color: white;
            text-decoration: none;
        }

        .floating-btn i {
            font-size: 24px;
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
                            <h1 class="font-weight-bold">Policy Documents</h1>
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

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        @php
                            $policies = [
                                [
                                    'id' => 1,
                                    'title' => 'Privacy Policy',
                                    'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                                ],
                                [
                                    'id' => 2,
                                    'title' => 'Terms of Service',
                                    'url' => 'https://www.orimi.com/pdf-test.pdf',
                                ],
                                [
                                    'id' => 3,
                                    'title' => 'Refund Policy',
                                    'url' => 'https://www.hq.nasa.gov/alsj/a17/A17_FlightPlan.pdf',
                                ],
                            ];
                        @endphp
                        @foreach ($policies as $doc)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                        <div style="height: 300px; overflow: hidden;">
                                            <object data="{{ $doc['url'] }}" type="application/pdf" width="100%"
                                                height="500">
                                                <p>Your browser does not support PDF preview.
                                                    <a href="{{ $doc['url'] }}" target="_blank">Download PDF</a>.
                                                </p>
                                            </object>
                                        </div>
                                    </div>

                                    <div
                                        class="card-footer bg-white border-top d-flex align-items-center justify-content-between">
                                        <strong class="text-truncate"
                                            style="color: var(--buttons-primary-color); width: 100%;">
                                            {{ $doc['title'] }}
                                        </strong>
                                        <a href="{{ url('edit-doc/' . $doc['id']) }}" class="ms-2 text-decoration-none"
                                            title="Edit {{ $doc['title'] }}">
                                            <i class="fas fa-pencil-alt"
                                                style="color: var(--buttons-primary-color);"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>

        <!-- Floating Add Button -->
        <a href="#" class="floating-btn" title="Add New Document">
            <i class="fas fa-plus"></i>
        </a>

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
    <title>Policy Documents</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" type="image/png" href="{{ url('public/assets/dist/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>

    <style>
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--buttons-primary-color);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, .2);
            z-index: 1000;
            transition: all .3s;
        }

        .floating-btn:hover {
            transform: scale(1.1);
            color: #fff;
            text-decoration: none;
        }

        .card-preview {
            height: 300px;
            overflow: hidden;
        }

        .grid-loading {
            display: none;
            align-items: center;
            justify-content: center;
            padding: 40px;
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
                            <h1 class="font-weight-bold">Policy Documents</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">

                    <!-- Loader for whole grid -->
                    <div id="gridLoading" class="text-center my-3 d-none">
                        <div class="spinner-border text-primary" role="status" aria-label="Loading"></div>
                        <div class="mt-2">Loading documents…</div>
                    </div>

                    <div id="policiesGrid" class="row"></div>

                    <!-- Empty state -->
                    <div id="emptyState" class="text-center text-muted d-none">
                        <i class="far fa-file-pdf fa-2x mb-2"></i>
                        <div>No policy documents found.</div>
                    </div>

                </div>
            </section>
        </div>

        <!-- Floating Add Button -->
        <a href="{{ route('policies.add') }}" class="floating-btn" title="Add New Document">
            <i class="fas fa-plus"></i>
        </a>

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

        const camelToTitle = (s = '') =>
            s.replace(/([A-Z])/g, ' $1').replace(/^./, m => m.toUpperCase()).trim();

        const showGridLoader = (show = true) => {
            const el = document.getElementById('gridLoading');
            if (show) el.classList.remove('d-none');
            else el.classList.add('d-none');
        };
        const showEmpty = (show = true) => {
            const el = document.getElementById('emptyState');
            if (show) el.classList.remove('d-none');
            else el.classList.add('d-none');
        };

        const cardHtml = (doc) => {
            const d = doc.data();
            const type = d.type || '';
            const title = d.title?.trim() || camelToTitle(type || 'policy');
            const url = d.url || '';
            return `
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <div class="card-preview" style="height:300px; overflow:hidden;">
              ${url ? `
                    <object data="${url}" type="application/pdf" width="100%" height="500">
                      <p>Your browser does not support PDF preview.
                        <a href="${url}" target="_blank">Download PDF</a>.
                      </p>
                    </object>` : `
                    <div class="text-center text-muted py-5">
                      <i class="far fa-file-pdf fa-2x"></i><br>No PDF uploaded
                    </div>`}
            </div>
          </div>
          <div class="card-footer bg-white border-top d-flex align-items-center justify-content-between">
            <strong class="text-truncate" style="color: var(--buttons-primary-color); width: 100%;">${title}</strong>
            <a href="{{ url('edit-doc') }}/${doc.id}" class="ms-2 text-decoration-none" title="Edit ${title}">
              <i class="fas fa-pencil-alt" style="color: var(--buttons-primary-color);"></i>
            </a>
          </div>
        </div>
      </div>`;
        };

        async function renderGrid() {
            const grid = document.getElementById('policiesGrid');
            grid.innerHTML = '';
            showEmpty(false);
            showGridLoader(true);
            try {
                let snap;
                try {
                    snap = await db.collection('policyDoc').orderBy('type').get();
                } catch {
                    snap = await db.collection('policyDoc').get();
                }

                if (snap.empty) {
                    showEmpty(true);
                } else {
                    grid.innerHTML = snap.docs.map(cardHtml).join('');
                }
            } catch (e) {
                console.error(e);
                grid.innerHTML = '<div class="col-12 text-danger">Failed to load documents.</div>';
            } finally {
                showGridLoader(false);
            }
        }

        (async () => {
            await renderGrid();
        })();
    </script>

</body>

</html>

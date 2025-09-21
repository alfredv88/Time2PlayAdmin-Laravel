{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Version Control</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('public/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ url('public/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
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
                            <h1 class="font-weight-bold">Version Control</h1>
                        </div>
                        @if (session('message'))
                            <div id="flashMessageSuccess" class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div id="flashMessageError" class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <script>
                            setTimeout(function() {
                                const flashMessageSuccess = document.getElementById('flashMessageSuccess');
                                const flashMessageError = document.getElementById('flashMessageError');

                                if (flashMessageSuccess) flashMessageSuccess.style.display = 'none';
                                if (flashMessageError) flashMessageError.style.display = 'none';
                            }, 5000); // 5000 milliseconds = 5 seconds
                        </script>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs mb-3" id="versionTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="android-tab" data-toggle="tab"
                                                href="#android" role="tab"
                                                style="color: var(--buttons-primary-color);">Android
                                                Version</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="ios-tab" data-toggle="tab" href="#ios"
                                                role="tab" style="color: var(--buttons-primary-color);">iOS
                                                Version</a>
                                        </li>
                                    </ul>

                                    <!-- Add New Version Button -->
                                    <div class="mb-3">
                                        <a href="{{ url('version/add') }}" class="btn btn-primary"
                                            style="background:var(--buttons-primary-color);;">
                                            <i class="fas fa-plus"></i> Add New Version
                                        </a>
                                    </div>

                                    <!-- Tabs Content -->
                                    <div class="tab-content" id="versionTabContent">

                                        <!-- Android Table -->
                                        <div class="tab-pane fade show active" id="android" role="tabpanel">
                                            <table id="androidTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Version Number</th>
                                                        <th>Description</th>
                                                        <th>Release Date</th>
                                                        <th>Version Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1.5.0</td>
                                                        <td>Security improvements</td>
                                                        <td>2025-07-01</td>
                                                        <td class="text-center">
                                                            <form method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $version['id'] ?? 1 }}">
                                                                @php
                                                                    $status = strtolower(
                                                                        $version['version_status'] ?? 'latest',
                                                                    );
                                                                @endphp
                                                                <select name="version_status"
                                                                    class="form-control font-weight-bold"
                                                                    onchange="this.form.submit()"
                                                                    style="width: 140px;
                                                                        height: 42px;
                                                                        font-size: 15px;
                                                                        padding: 8px 10px;
                                                                        color: {{ $status === 'latest' ? '#155724' : ($status === 'outdated' ? '#721c24' : ($status === 'stable' ? '#856404' : '#0c5460')) }};
                                                                        background-color: {{ $status === 'latest' ? '#d4edda' : ($status === 'outdated' ? '#f8d7da' : ($status === 'stable' ? '#fff3cd' : '#d1ecf1')) }};
                                                                        border: 1px solid #ced4da;
                                                                        border-radius: 5px;">
                                                                    <option value="latest"
                                                                        {{ $status === 'latest' ? 'selected' : '' }}>
                                                                        Latest</option>
                                                                    <option value="outdated"
                                                                        {{ $status === 'outdated' ? 'selected' : '' }}>
                                                                        Outdated</option>
                                                                    <option value="stable"
                                                                        {{ $status === 'stable' ? 'selected' : '' }}>
                                                                        Stable</option>
                                                                    <option value="beta"
                                                                        {{ $status === 'beta' ? 'selected' : '' }}>Beta
                                                                    </option>
                                                                </select>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>1.4.2</td>
                                                        <td>Minor bug fixes</td>
                                                        <td>2025-06-15</td>
                                                        <td class="text-center">
                                                            <form method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $version['id'] ?? 1 }}">
                                                                @php
                                                                    $status = strtolower(
                                                                        $version['version_status'] ?? 'outdated',
                                                                    );
                                                                @endphp
                                                                <select name="version_status"
                                                                    class="form-control font-weight-bold"
                                                                    onchange="this.form.submit()"
                                                                    style="width: 140px;
                                                                        height: 42px;
                                                                        font-size: 15px;
                                                                        padding: 8px 10px;
                                                                        color: {{ $status === 'latest' ? '#155724' : ($status === 'outdated' ? '#721c24' : ($status === 'stable' ? '#856404' : '#0c5460')) }};
                                                                        background-color: {{ $status === 'latest' ? '#d4edda' : ($status === 'outdated' ? '#f8d7da' : ($status === 'stable' ? '#fff3cd' : '#d1ecf1')) }};
                                                                        border: 1px solid #ced4da;
                                                                        border-radius: 5px;">
                                                                    <option value="latest"
                                                                        {{ $status === 'latest' ? 'selected' : '' }}>
                                                                        Latest</option>
                                                                    <option value="outdated"
                                                                        {{ $status === 'outdated' ? 'selected' : '' }}>
                                                                        Outdated</option>
                                                                    <option value="stable"
                                                                        {{ $status === 'stable' ? 'selected' : '' }}>
                                                                        Stable</option>
                                                                    <option value="beta"
                                                                        {{ $status === 'beta' ? 'selected' : '' }}>Beta
                                                                    </option>
                                                                </select>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>1.3.9</td>
                                                        <td>New settings page</td>
                                                        <td>2025-06-01</td>
                                                        <td class="text-center">
                                                            <form method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $version['id'] ?? 1 }}">
                                                                @php
                                                                    $status = strtolower(
                                                                        $version['version_status'] ?? 'stable',
                                                                    );
                                                                @endphp
                                                                <select name="version_status"
                                                                    class="form-control font-weight-bold"
                                                                    onchange="this.form.submit()"
                                                                    style="width: 140px;
                                                                        height: 42px;
                                                                        font-size: 15px;
                                                                        padding: 8px 10px;
                                                                        color: {{ $status === 'latest' ? '#155724' : ($status === 'outdated' ? '#721c24' : ($status === 'stable' ? '#856404' : '#0c5460')) }};
                                                                        background-color: {{ $status === 'latest' ? '#d4edda' : ($status === 'outdated' ? '#f8d7da' : ($status === 'stable' ? '#fff3cd' : '#d1ecf1')) }};
                                                                        border: 1px solid #ced4da;
                                                                        border-radius: 5px;">
                                                                    <option value="latest"
                                                                        {{ $status === 'latest' ? 'selected' : '' }}>
                                                                        Latest</option>
                                                                    <option value="outdated"
                                                                        {{ $status === 'outdated' ? 'selected' : '' }}>
                                                                        Outdated</option>
                                                                    <option value="stable"
                                                                        {{ $status === 'stable' ? 'selected' : '' }}>
                                                                        Stable</option>
                                                                    <option value="beta"
                                                                        {{ $status === 'beta' ? 'selected' : '' }}>Beta
                                                                    </option>
                                                                </select>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>1.3.0</td>
                                                        <td>Testing performance</td>
                                                        <td>2025-05-15</td>
                                                        <td class="text-center">
                                                            <form method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $version['id'] ?? 1 }}">
                                                                @php
                                                                    $status = strtolower(
                                                                        $version['version_status'] ?? 'latest',
                                                                    );
                                                                @endphp
                                                                <select name="version_status"
                                                                    class="form-control font-weight-bold"
                                                                    onchange="this.form.submit()"
                                                                    style="width: 140px;
                                                                        height: 42px;
                                                                        font-size: 15px;
                                                                        padding: 8px 10px;
                                                                        color: {{ $status === 'latest' ? '#155724' : ($status === 'outdated' ? '#721c24' : ($status === 'stable' ? '#856404' : '#0c5460')) }};
                                                                        background-color: {{ $status === 'latest' ? '#d4edda' : ($status === 'outdated' ? '#f8d7da' : ($status === 'stable' ? '#fff3cd' : '#d1ecf1')) }};
                                                                        border: 1px solid #ced4da;
                                                                        border-radius: 5px;">
                                                                    <option value="latest"
                                                                        {{ $status === 'latest' ? 'selected' : '' }}>
                                                                        Latest</option>
                                                                    <option value="outdated"
                                                                        {{ $status === 'outdated' ? 'selected' : '' }}>
                                                                        Outdated</option>
                                                                    <option value="stable"
                                                                        {{ $status === 'stable' ? 'selected' : '' }}>
                                                                        Stable</option>
                                                                    <option value="beta"
                                                                        {{ $status === 'beta' ? 'selected' : '' }}>Beta
                                                                    </option>
                                                                </select>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- iOS Table -->
                                        <div class="tab-pane fade" id="ios" role="tabpanel">
                                            <table id="iosTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Version Number</th>
                                                        <th>Description</th>
                                                        <th>Release Date</th>
                                                        <th>Version Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1.3.9</td>
                                                        <td>New</td>
                                                        <td>2025-06-01</td>
                                                        <td class="text-center">
                                                            <form method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $version['id'] ?? 1 }}">
                                                                @php
                                                                    $status = strtolower(
                                                                        $version['version_status'] ?? 'latest',
                                                                    );
                                                                @endphp
                                                                <select name="version_status"
                                                                    class="form-control font-weight-bold"
                                                                    onchange="this.form.submit()"
                                                                    style="width: 140px;
                                                                        height: 42px;
                                                                        font-size: 15px;
                                                                        padding: 8px 10px;
                                                                        color: {{ $status === 'latest' ? '#155724' : ($status === 'outdated' ? '#721c24' : ($status === 'stable' ? '#856404' : '#0c5460')) }};
                                                                        background-color: {{ $status === 'latest' ? '#d4edda' : ($status === 'outdated' ? '#f8d7da' : ($status === 'stable' ? '#fff3cd' : '#d1ecf1')) }};
                                                                        border: 1px solid #ced4da;
                                                                        border-radius: 5px;">
                                                                    <option value="latest"
                                                                        {{ $status === 'latest' ? 'selected' : '' }}>
                                                                        Latest</option>
                                                                    <option value="outdated"
                                                                        {{ $status === 'outdated' ? 'selected' : '' }}>
                                                                        Outdated</option>
                                                                    <option value="stable"
                                                                        {{ $status === 'stable' ? 'selected' : '' }}>
                                                                        Stable</option>
                                                                    <option value="beta"
                                                                        {{ $status === 'beta' ? 'selected' : '' }}>Beta
                                                                    </option>
                                                                </select>
                                                            </form>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>1.5.0</td>
                                                        <td>Security improvements</td>
                                                        <td>2025-07-01</td>
                                                        <td class="text-center">
                                                            <form method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $version['id'] ?? 1 }}">
                                                                @php
                                                                    $status = strtolower(
                                                                        $version['version_status'] ?? 'beta',
                                                                    );
                                                                @endphp
                                                                <select name="version_status"
                                                                    class="form-control font-weight-bold"
                                                                    onchange="this.form.submit()"
                                                                    style="width: 140px;
                                                                        height: 42px;
                                                                        font-size: 15px;
                                                                        padding: 8px 10px;
                                                                        color: {{ $status === 'latest' ? '#155724' : ($status === 'outdated' ? '#721c24' : ($status === 'stable' ? '#856404' : '#0c5460')) }};
                                                                        background-color: {{ $status === 'latest' ? '#d4edda' : ($status === 'outdated' ? '#f8d7da' : ($status === 'stable' ? '#fff3cd' : '#d1ecf1')) }};
                                                                        border: 1px solid #ced4da;
                                                                        border-radius: 5px;">
                                                                    <option value="latest"
                                                                        {{ $status === 'latest' ? 'selected' : '' }}>
                                                                        Latest</option>
                                                                    <option value="outdated"
                                                                        {{ $status === 'outdated' ? 'selected' : '' }}>
                                                                        Outdated</option>
                                                                    <option value="stable"
                                                                        {{ $status === 'stable' ? 'selected' : '' }}>
                                                                        Stable</option>
                                                                    <option value="beta"
                                                                        {{ $status === 'beta' ? 'selected' : '' }}>Beta
                                                                    </option>
                                                                </select>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>1.4.2</td>
                                                        <td>Minor bug fixes</td>
                                                        <td>2025-06-15</td>
                                                        <td class="text-center">
                                                            <form method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $version['id'] ?? 1 }}">
                                                                @php
                                                                    $status = strtolower(
                                                                        $version['version_status'] ?? 'stable',
                                                                    );
                                                                @endphp
                                                                <select name="version_status"
                                                                    class="form-control font-weight-bold"
                                                                    onchange="this.form.submit()"
                                                                    style="width: 140px;
                                                                        height: 42px;
                                                                        font-size: 15px;
                                                                        padding: 8px 10px;
                                                                        color: {{ $status === 'latest' ? '#155724' : ($status === 'outdated' ? '#721c24' : ($status === 'stable' ? '#856404' : '#0c5460')) }};
                                                                        background-color: {{ $status === 'latest' ? '#d4edda' : ($status === 'outdated' ? '#f8d7da' : ($status === 'stable' ? '#fff3cd' : '#d1ecf1')) }};
                                                                        border: 1px solid #ced4da;
                                                                        border-radius: 5px;">
                                                                    <option value="latest"
                                                                        {{ $status === 'latest' ? 'selected' : '' }}>
                                                                        Latest</option>
                                                                    <option value="outdated"
                                                                        {{ $status === 'outdated' ? 'selected' : '' }}>
                                                                        Outdated</option>
                                                                    <option value="stable"
                                                                        {{ $status === 'stable' ? 'selected' : '' }}>
                                                                        Stable</option>
                                                                    <option value="beta"
                                                                        {{ $status === 'beta' ? 'selected' : '' }}>Beta
                                                                    </option>
                                                                </select>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>1.3.0</td>
                                                        <td>Testing performance</td>
                                                        <td>2025-05-15</td>

                                                        <td class="text-center">
                                                            <form method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $version['id'] ?? 1 }}">
                                                                @php
                                                                    $status = strtolower(
                                                                        $version['version_status'] ?? 'outdated',
                                                                    );
                                                                @endphp
                                                                <select name="version_status"
                                                                    class="form-control font-weight-bold"
                                                                    onchange="this.form.submit()"
                                                                    style="width: 140px;
                                                                        height: 42px;
                                                                        font-size: 15px;
                                                                        padding: 8px 10px;
                                                                        color: {{ $status === 'latest' ? '#155724' : ($status === 'outdated' ? '#721c24' : ($status === 'stable' ? '#856404' : '#0c5460')) }};
                                                                        background-color: {{ $status === 'latest' ? '#d4edda' : ($status === 'outdated' ? '#f8d7da' : ($status === 'stable' ? '#fff3cd' : '#d1ecf1')) }};
                                                                        border: 1px solid #ced4da;
                                                                        border-radius: 5px;">
                                                                    <option value="latest"
                                                                        {{ $status === 'latest' ? 'selected' : '' }}>
                                                                        Latest</option>
                                                                    <option value="outdated"
                                                                        {{ $status === 'outdated' ? 'selected' : '' }}>
                                                                        Outdated</option>
                                                                    <option value="stable"
                                                                        {{ $status === 'stable' ? 'selected' : '' }}>
                                                                        Stable</option>
                                                                    <option value="beta"
                                                                        {{ $status === 'beta' ? 'selected' : '' }}>Beta
                                                                    </option>
                                                                </select>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        @include('inc.footer')
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>

    <!-- jQuery -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ url('public/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>
    <script>
        $(function() {
            // Init Android table on page load
            $('#androidTable').DataTable({
                responsive: true,
                autoWidth: false
            });

            // Init iOS table only when tab is shown
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                let target = $(e.target).attr("href"); // e.g. #ios
                if (target === '#ios' && !$.fn.DataTable.isDataTable('#iosTable')) {
                    $('#iosTable').DataTable({
                        responsive: true,
                        autoWidth: false
                    });
                }
            });
        });
    </script>

</body>

</html> --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Version Control</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('public/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ url('public/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" type="image/png" href="{{ url('public/assets/dist/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/varibales.css') }}">

    <!-- Firebase (compat) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
    <style>
        .table-wrap {
            position: relative;
        }

        .table-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.75);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .table-overlay .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <div class="content-wrapper">
            <!-- Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-6">
                            <h1 class="font-weight-bold">Version Control</h1>
                        </div>

                        @if (session('message'))
                            <div id="flashMessageSuccess" class="alert alert-success mt-2">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div id="flashMessageError" class="alert alert-danger mt-2">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </div>

                <script>
                    setTimeout(function() {
                        const s = document.getElementById('flashMessageSuccess');
                        const e = document.getElementById('flashMessageError');
                        if (s) s.style.display = 'none';
                        if (e) e.style.display = 'none';
                    }, 5000);
                </script>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">

                            <!-- Tabs -->
                            <ul class="nav nav-tabs mb-3" id="versionTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="android-tab" data-toggle="tab" href="#android"
                                        role="tab" style="color: var(--buttons-primary-color);">Android Version</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="ios-tab" data-toggle="tab" href="#ios" role="tab"
                                        style="color: var(--buttons-primary-color);">iOS Version</a>
                                </li>
                            </ul>

                            <!-- Add New Version -->
                            <div class="mb-3">
                                <a href="{{ url('version/add') }}" class="btn btn-primary"
                                    style="background: var(--buttons-primary-color);">
                                    <i class="fas fa-plus"></i> Add New Version
                                </a>
                            </div>

                            <!-- Tabs content -->
                            <div class="tab-content" id="versionTabContent">

                                <!-- Android Table -->
                                <div class="tab-pane fade show active" id="android" role="tabpanel"
                                    aria-labelledby="android-tab">
                                    <div class="table-wrap">
                                        <div id="androidLoading" class="table-overlay">
                                            <div class="spinner-border text-primary" role="status"
                                                aria-label="Loading"></div>
                                        </div>
                                        <table id="androidTable" class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Version Number</th>
                                                    <th>Description</th>
                                                    <th>Release Date</th>
                                                    <th>Version Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="androidTbody"></tbody>
                                        </table>
                                    </div>
                                </div>


                                <!-- iOS Table -->
                                <div class="tab-pane fade" id="ios" role="tabpanel" aria-labelledby="ios-tab">
                                    <div class="table-wrap">
                                        <div id="iosLoading" class="table-overlay">
                                            <div class="spinner-border text-primary" role="status"
                                                aria-label="Loading"></div>
                                        </div>
                                        <table id="iosTable" class="table table-bordered table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Version Number</th>
                                                    <th>Description</th>
                                                    <th>Release Date</th>
                                                    <th>Version Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="iosTbody"></tbody>
                                        </table>
                                    </div>
                                </div>


                            </div><!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div><!-- /.container-fluid -->
            </section><!-- /.content -->
        </div>

        @include('inc.footer')
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <!-- jQuery -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables & Plugins -->
    <script src="{{ url('public/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
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

        // --- helpers ---
        const fmtDate = (v) => {
            if (!v) return '-';
            let d;
            if (v.toDate) d = v.toDate(); // Firestore Timestamp
            else if (typeof v === 'string') d = new Date(v + 'T00:00:00');
            else d = new Date(v);
            return d.toLocaleDateString(undefined, {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        };

        const statusStyle = (status) => {
            status = String(status || '').toLowerCase();
            const color = (status === 'latest') ? '#155724' :
                (status === 'outdated') ? '#721c24' :
                (status === 'stable') ? '#856404' :
                '#0c5460';
            const bg = (status === 'latest') ? '#d4edda' :
                (status === 'outdated') ? '#f8d7da' :
                (status === 'stable') ? '#fff3cd' :
                '#d1ecf1';
            return `width:140px;height:42px;font-size:15px;padding:8px 10px;color:${color};background-color:${bg};border:1px solid #ced4da;border-radius:5px;`;
        };

        const buildRowHtml = (doc) => {
            const d = doc.data();
            const id = doc.id;
            const version = d.version_number || '-';
            const desc = d.description || '-';
            const date = fmtDate(d.release_date);
            const status = (d.version_status || 'latest').toLowerCase();
            return `
      <tr data-id="${id}">
        <td>${version}</td>
        <td>${desc}</td>
        <td>${date}</td>
        <td class="text-center">
          <select class="form-control font-weight-bold version-status-select"
                  data-id="${id}" style="${statusStyle(status)}">
            <option value="latest"  ${status==='latest'  ? 'selected' : ''}>Latest</option>
            <option value="outdated"${status==='outdated'? 'selected' : ''}>Outdated</option>
            <option value="stable"  ${status==='stable'  ? 'selected' : ''}>Stable</option>
            <option value="beta"    ${status==='beta'    ? 'selected' : ''}>Beta</option>
          </select>
        </td>
      </tr>`;
        };

        let androidDT = null;
        let iosDT = null;

        function showTableLoading(device, show) {
            const el = document.getElementById(device === 'android' ? 'androidLoading' : 'iosLoading');
            if (el) el.style.display = show ? 'flex' : 'none';
        }

        async function queryVersions(device) {
            const base = db.collection('versions').where('device', '==', device);
            try {
                // Try indexed query first
                return await base.orderBy('release_date', 'desc').get();
            } catch (e) {
                // Fallback if index required: fetch without orderBy and sort in JS
                const snap = await base.get();
                // Simulate the same .docs API but sorted
                const sortedDocs = [...snap.docs].sort((a, b) => {
                    const ad = a.data().release_date?.toDate?.() ?? new Date(0);
                    const bd = b.data().release_date?.toDate?.() ?? new Date(0);
                    return bd - ad; // desc
                });
                return {
                    docs: sortedDocs
                };
            }
        }

        async function renderTable(device) {
            showTableLoading(device, true);
            try {
                const snap = await queryVersions(device);
                const tbodyId = (device === 'android') ? 'androidTbody' : 'iosTbody';
                document.getElementById(tbodyId).innerHTML = snap.docs.map(buildRowHtml).join('');

                if (device === 'android') {
                    if (!$.fn.DataTable.isDataTable('#androidTable')) {
                        androidDT = $('#androidTable').DataTable({
                            responsive: true,
                            autoWidth: false
                        });
                    } else {
                        androidDT.rows().invalidate().draw(false);
                    }
                } else {
                    if (!$.fn.DataTable.isDataTable('#iosTable')) {
                        iosDT = $('#iosTable').DataTable({
                            responsive: true,
                            autoWidth: false
                        });
                    } else {
                        iosDT.rows().invalidate().draw(false);
                    }
                }
            } finally {
                showTableLoading(device, false);
            }
        }

        // Update status on change
        document.addEventListener('change', async (e) => {
            const sel = e.target.closest('.version-status-select');
            if (!sel) return;
            const docId = sel.getAttribute('data-id');
            const newStatus = sel.value;
            const prevStyle = sel.getAttribute('style');
            sel.disabled = true;

            try {
                await db.collection('versions').doc(docId).set({
                    version_status: newStatus,
                    updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                }, {
                    merge: true
                });
                sel.setAttribute('style', statusStyle(newStatus));
            } catch (err) {
                alert(err?.message || 'Update failed');
                sel.setAttribute('style', prevStyle);
            } finally {
                sel.disabled = false;
            }
        });

        (async function init() {
            // Optional flash after redirect
            const u = new URLSearchParams(location.search);
            if (u.get('added') === '1') {
                const s = document.createElement('div');
                s.className = 'alert alert-success';
                s.textContent = 'Version added successfully.';
                document.querySelector('.content-header .container-fluid .row').appendChild(s);
                setTimeout(() => s.remove(), 5000);
            }

            await renderTable('android');
            await renderTable('ios');
        })();
    </script>

</body>

</html>

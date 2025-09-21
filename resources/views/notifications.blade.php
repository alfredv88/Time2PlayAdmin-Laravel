<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifications</title>

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
    <style>
        table thead th {
            background-color: #f9f9f9;
            font-weight: 600;
            font-size: 14px;
        }

        table tbody td {
            font-size: 14px;
            vertical-align: middle;
        }

        .btn-danger {
            background-color: #FF3D3D !important;
            border: none;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('inc.header')
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        @include('inc.aside')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="font-weight-bold">Notifications</h1>
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
                            <!-- /.card -->

                            <div class="card">
                                <div class="card-header">
                                    {{-- <h3 class="card-title">DataTable with default features</h3> --}}
                                    <a href="{{ url('noti/add') }}">
                                        <button class="btn btn-primary border-0"
                                            style="background: var(--buttons-primary-color);">Add New
                                            Notification +</button>
                                    </a>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Notification Title</th>
                                                <th>Description</th>
                                                <th>Type</th>
                                                <th>Release Date/Time</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($notifications ?? [] as $n)
                                                @php
                                                    $count = is_array($n['recipients'] ?? null)
                                                        ? count($n['recipients'])
                                                        : 0;
                                                    $typeText =
                                                        $n['userType'] === 'allUsers'
                                                            ? 'All Users'
                                                            : (in_array($n['userType'], ['25%', '50%', '75%'])
                                                                ? $count . ' Users'
                                                                : ($n['userType'] === 'specificUsers'
                                                                    ? $count . ' Users'
                                                                    : $n['userType']));
                                                    $dt = '';
                                                    if (!empty($n['releaseDate']) && !empty($n['releaseTime'])) {
                                                        try {
                                                            $dt = \Carbon\Carbon::parse(
                                                                $n['releaseDate'] . ' ' . $n['releaseTime'],
                                                            )->format('M d, Y h:i A');
                                                        } catch (\Throwable $e) {
                                                            $dt = $n['releaseDate'] . ' ' . $n['releaseTime'];
                                                        }
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $n['title'] ?? '—' }}</td>
                                                    <td>{{ $n['body'] ?? '—' }}</td>
                                                    <td>{{ $typeText }}</td>
                                                    <td>{{ $dt }}</td>
                                                    <td>
                                                        <form action="{{ url('noti/delete/' . ($n['id'] ?? '')) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Delete this notification?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-danger btn-sm">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No notifications
                                                        found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        @include('inc.footer')
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

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
    <!-- AdminLTE for demo purposes -->
    {{-- <script src="{{ url('public/assets/dist/js/demo.js') }}"></script> --}}
    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                //   "responsive": true, "lengthChange": false, "autoWidth": false,
                //   "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
</body>

</html>

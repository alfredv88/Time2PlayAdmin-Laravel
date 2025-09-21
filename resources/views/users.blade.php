<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ session('userscreentype') ?? 'Users' }}</title>

    <!-- Google Font -->
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

    <style>
        .blocked-users-css {
            position: relative;
            z-index: 9999;
            pointer-events: none;
        }

        .blocked-users-btn {
            right: 26%;
            bottom: 0;
            background: #10416C;
            cursor: pointer;
            pointer-events: auto;
            position: absolute;
        }

        @media (max-width:1210px) {
            .blocked-users-btn {
                right: 28%;
            }
        }

        @media (max-width:766px) {
            .blocked-users-btn {
                right: 45%;
            }
        }

        @media (max-width:435px) {
            .blocked-users-btn {
                right: 20%;
            }
        }
    </style>

    <script>
        // provide scope from controller: all | active | blocked
        window.USERS_SCOPE = "{{ $scope ?? 'all' }}";
    </script>
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
                            <h1 class="font-weight-bold">{{ session('userscreentype') ?? 'Users Listing' }}</h1>
                        </div>

                        @if (session('message'))
                            <div id="flashMessageSuccess" class="alert alert-success">{{ session('message') }}</div>
                        @endif
                        @if (session('error'))
                            <div id="flashMessageError" class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <script>
                            setTimeout(function() {
                                const s = document.getElementById('flashMessageSuccess');
                                const e = document.getElementById('flashMessageError');
                                if (s) s.style.display = 'none';
                                if (e) e.style.display = 'none';
                            }, 5000);
                        </script>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    {{-- Top Right Button --}}
                                    @if (session('userscreentype') == 'Users Listing')
                                        <div class="position-relative blocked-users-css"
                                            style="height:30px; margin-bottom:-3%">
                                            <a class="btn btn-success border-0 blocked-users-btn"
                                                href="{{ url('users/blocked') }}">
                                                Blocked Users
                                            </a>
                                        </div>
                                    @elseif (session('userscreentype') == 'Blocked Users')
                                        <div class="position-relative blocked-users-css"
                                            style="height:30px; margin-bottom:-3%">
                                            <a class="btn btn-primary border-0 blocked-users-btn"
                                                href="{{ url('/users') }}">
                                                All Users
                                            </a>
                                        </div>
                                    @elseif (session('userscreentype') == 'Active Users')
                                        <div class="position-relative blocked-users-css"
                                            style="height:30px; margin-bottom:-3%">
                                            <a class="btn btn-primary border-0 blocked-users-btn"
                                                href="{{ url('/users') }}">
                                                All Users
                                            </a>
                                        </div>
                                    @endif

                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Full name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Location</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- DataTables (server-side) will populate rows --}}
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('inc.footer')

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <!-- Bootstrap 5 bundle (dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 (AdminLTE) -->
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables -->
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
    <!-- AdminLTE -->
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>

    <script>
        $(function() {
            const ajaxUrl = "{{ route('users.data') }}" + "?scope=" + encodeURIComponent(window.USERS_SCOPE ||
                'all');

            $("#example1").DataTable({
                // processing: true,
                serverSide: true,
                responsive: true,
                ajax: ajaxUrl,
                order: [],
                pageLength: 25,
                columns: [{
                        data: 0,
                        orderable: false
                    }, // Full name + badge + avatar (HTML)
                    {
                        data: 1
                    }, // Email
                    {
                        data: 2
                    }, // Phone
                    {
                        data: 3
                    }, // Location
                    {
                        data: 4,
                        orderable: false,
                        searchable: false
                    } // Action menu (HTML)
                ],
                deferRender: true
            });
        });
    </script>
</body>

</html>






{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ session('userscreentype') ?? 'Users' }}</title>

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
        @media (max-width: 1210px) {
            .blocked-users-css {
                right: 28%;
                bottom: 0;
            }
        }

        @media (max-width: 766px) {
            .blocked-users-css {
                right: 45%;
                bottom: 2px;
            }
        }

        @media (max-width: 435px) {
            .blocked-users-css {
                right: 20%;
                bottom: 13px;
            }
        }
    </style>
    <style>
        .blocked-users-css {
            position: relative;
            z-index: 9999;
            /* bring above table */
            pointer-events: none;
            /* container ignores clicks */
        }

        .blocked-users-btn {
            right: 26%;
            bottom: 0;
            background: #10416C;
            cursor: pointer;
            pointer-events: auto;
            /* button is clickable */
        }

        @media (max-width:1210px) {
            .blocked-users-btn {
                right: 28%;
            }
        }

        @media (max-width: 766px) {
            .blocked-users-btn {
                right: 45%;
            }
        }

        @media (max-width: 435px) {
            .blocked-users-btn {
                right: 20%;
            }
        }
    </style>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('inc.header')
        <!-- /.navbar -->
        @include('inc.aside')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="font-weight-bold">{{ session('userscreentype') ?? 'Users Listing' }}</h1>
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
                                    @if (session('userscreentype') == 'Users Listing')
                                        <div class="position-relative blocked-users-css"
                                            style="height:30px; margin-bottom:-3%">
                                            <a class="btn btn-success border-0 position-absolute blocked-users-btn"
                                                href="{{ url('users/blocked') }}">
                                                Blocked Users
                                            </a>
                                        </div>
                                    @endif
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Full name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Location</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($users ?? [] as $u)
                                                <tr>
                                                    <td class="d-flex align-items-center gap-2">
                                                        <img src="{{ $u['avatar'] }}"
                                                            onerror="this.onerror=null;this.src='https://edenchristianacademy.co.nz/wp-content/uploads/2013/11/dummy-image-square.jpg';"
                                                            class="rounded-circle mr-2"
                                                            style="width: 45px; height: 45px; object-fit: cover;">

                                                        <span>{{ $u['full_name'] }}</span>
                                                        @if ($u['status'])
                                                            <span class="badge badge-success ml-2">Active</span>
                                                        @else
                                                            <span class="badge badge-danger ml-2">Blocked</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $u['email'] }}</td>
                                                    <td>{{ $u['phone'] }}</td>
                                                    <td>{{ $u['location'] }}</td>
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-light" type="button"
                                                                data-bs-toggle="dropdown">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                @if ($u['status'])
                                                                    <li>
                                                                        <form method="POST"
                                                                            action="{{ route('users.toggle', $u['id']) }}">
                                                                            @csrf
                                                                            <input type="hidden" name="to"
                                                                                value="block">
                                                                            <button type="submit"
                                                                                class="dropdown-item text-danger">Block
                                                                                User</button>
                                                                        </form>
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        <form method="POST"
                                                                            action="{{ route('users.toggle', $u['id']) }}">
                                                                            @csrf
                                                                            <input type="hidden" name="to"
                                                                                value="unblock">
                                                                            <button type="submit"
                                                                                class="dropdown-item text-success">Unblock
                                                                                User</button>
                                                                        </form>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No users found.
                                                    </td>
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
    <!-- Bootstrap & Font Awesome (in your Blade layout or <head>) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

</html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Requests</title>

    <!-- Google Fonts -->
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

    <script>
        // Provided by controller: all | pending | approved | rejected
        window.EVENTS_SCOPE = "{{ $scope ?? 'all' }}";
    </script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="mb-4">
                        <!-- Title + Export Button -->
                        <div class="row align-items-start">
                            <div class="col-12 col-md-8">
                                <h3 class="font-weight-bold mb-1">Event Requests</h3>
                                <p class="text-muted mb-2 mb-md-0">Review and manage pending event submissions</p>
                            </div>
                            <div class="col-12 col-md-4 d-flex justify-content-md-end mb-3 mb-md-0">
                                <button id="exportCsv" class="btn btn-primary" style="border-radius: 10px;">
                                    <i class="fas fa-download me-1"></i> Export CSV
                                </button>
                            </div>
                        </div>

                        <!-- Search + Status Boxes -->
                        <div class="row align-items-center mt-3">
                            <!-- Search box -->
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <input id="eventSearch" type="text" class="form-control"
                                    placeholder="🔍 Search events...">
                            </div>

                            <!-- Summary boxes -->
                            <div
                                class="col-12 col-md-6 d-flex flex-wrap gap-2 justify-content-start justify-content-md-end">
                                <!-- Pending -->
                                <div id="boxPending" class="mb-2 text-center px-4 py-2 rounded"
                                    style="background-color: #FFF5CC; cursor:pointer;">
                                    <div id="countPending" class="font-weight-bold"
                                        style="color: #B76E00; font-size: 18px;">0</div>
                                    <small style="color: #B76E00;">Pending</small>
                                </div>

                                <!-- Approved -->
                                <div id="boxApproved" class="mb-2 mx-2 text-center px-4 py-2 rounded"
                                    style="background-color: #E3FBEB; cursor:pointer;">
                                    <div id="countApproved" class="font-weight-bold"
                                        style="color: #127947; font-size: 18px;">0</div>
                                    <small style="color: #127947;">Approved</small>
                                </div>

                                <!-- Rejected -->
                                <div id="boxRejected" class="mb-2 text-center px-4 py-2 rounded"
                                    style="background-color: #FDE6E6; cursor:pointer;">
                                    <div id="countRejected" class="font-weight-bold"
                                        style="color: #E63946; font-size: 18px;">0</div>
                                    <small style="color: #E63946;">Rejected</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Events Table -->
                    <div class="card">
                        <div class="card-body table-responsive p-3">
                            <table id="eventsTable" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Event Details</th>
                                        <th>Schedule</th>
                                        <th>Participants</th>
                                        <th>Entry Fee</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </section>
        </div>

        @include('inc.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>

    <script>
        $(function() {
            const scope = window.EVENTS_SCOPE || 'all';
            const dataUrl = "{{ route('events.data') }}" + "?scope=" + encodeURIComponent(scope);
            const statsUrl = "{{ route('events.stats') }}";

            // DataTable
            const dt = $("#eventsTable").DataTable({
                // processing: true,
                serverSide: true,
                responsive: true,
                ajax: dataUrl,
                order: [],
                pageLength: 25,
                columns: [{
                        data: 0,
                        orderable: false
                    },
                    {
                        data: 1
                    },
                    {
                        data: 2
                    },
                    {
                        data: 3
                    },
                    {
                        data: 4,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 5,
                        orderable: false,
                        searchable: false
                    }
                ],
                deferRender: true,
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'csvHtml5',
                    title: 'events_export',
                    text: 'Export CSV',
                    className: 'd-none', // we’ll trigger via custom button
                }]
            });

            // Hook custom search box to DataTables search
            $("#eventSearch").on('keyup', function() {
                dt.search(this.value).draw();
            });

            // Hook custom export button
            $("#exportCsv").on('click', function() {
                dt.button(0).trigger();
            });

            // Counters
            function loadStats() {
                $.getJSON(statsUrl, function(json) {
                    $("#countPending").text(json.pending ?? 0);
                    $("#countApproved").text(json.approved ?? 0);
                    $("#countRejected").text(json.rejected ?? 0);
                });
            }
            loadStats();

            // Clickable status boxes to filter quickly
            $("#boxPending").on('click', () => window.location.href = "{{ route('events.index') }}?scope=pending");
            $("#boxApproved").on('click', () => window.location.href =
                "{{ route('events.index') }}?scope=approved");
            $("#boxRejected").on('click', () => window.location.href =
                "{{ route('events.index') }}?scope=rejected");
        });
    </script>
</body>

</html>








{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Requests</title>

    <!-- Google Fonts -->
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
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="mb-4">
                        <!-- Title + Export Button -->
                        <div class="row align-items-start">
                            <div class="col-12 col-md-8">
                                <h3 class="font-weight-bold mb-1">Event Requests</h3>
                                <p class="text-muted mb-2 mb-md-0">Review and manage pending event submissions</p>
                            </div>
                            <div class="col-12 col-md-4 d-flex justify-content-md-end mb-3 mb-md-0">
                                <a href="#" class="btn btn-primary" style="border-radius: 10px;">
                                    <i class="fas fa-download me-1"></i> Export
                                </a>
                            </div>

                        </div>

                        <!-- Search + Status Boxes -->
                        <div class="row align-items-center mt-3">
                            <!-- Search box -->
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <input type="text" class="form-control" placeholder="🔍 Search events...">
                            </div>

                            <!-- Summary boxes -->
                            <div
                                class="col-12 col-md-6 d-flex flex-wrap gap-2 justify-content-start justify-content-md-end">
                                <!-- Pending -->
                                <div class="mb-2 text-center px-4 py-2 rounded" style="background-color: #FFF5CC;">
                                    <div class="font-weight-bold" style="color: #B76E00; font-size: 18px;">24</div>
                                    <small style="color: #B76E00;">Pending</small>
                                </div>

                                <!-- Approved -->
                                <div class="mb-2 mx-2 ext-center px-4 py-2 rounded" style="background-color: #E3FBEB;">
                                    <div class="font-weight-bold" style="color: #127947; font-size: 18px;">156</div>
                                    <small style="color: #127947;">Approved</small>
                                </div>

                                <!-- Rejected -->
                                <div class="mb-2 text-center px-4 py-2 rounded" style="background-color: #FDE6E6;">
                                    <div class="font-weight-bold" style="color: #E63946; font-size: 18px;">8</div>
                                    <small style="color: #E63946;">Rejected</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Events Table -->
                    <div class="card">
                        <div class="card-body table-responsive p-3">
                            <table id="eventsTable" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Event Details</th>
                                        <th>Schedule</th>
                                        <th>Participants</th>
                                        <th>Entry Fee</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary text-white rounded d-flex justify-content-center align-items-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="fw-bold">Downtown Sports Arena</div>
                                                    <span class="badge bg-primary me-1">Football</span>
                                                    <span class="badge text-white"
                                                        style="background-color:#8b5cf6;">Basketball</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            March 22, 2025<br>
                                            7:00 PM – 9:00 PM<br>
                                            <small>Central Park Field</small>
                                        </td>
                                        <td>0/30<br><small>Max Players</small></td>
                                        <td><span class="text-success">$15.00</span><br><small>Per Person</small></td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ url('view-event') }}">View</a>
                                            <button class="btn btn-sm btn-success">Approve</button>
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary text-white rounded d-flex justify-content-center align-items-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="fw-bold">Downtown Sports Arena</div>
                                                    <span class="badge bg-primary me-1">Football</span>
                                                    <span class="badge text-white"
                                                        style="background-color:#8b5cf6;">Basketball</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            March 22, 2025<br>
                                            7:00 PM – 9:00 PM<br>
                                            <small>Central Park Field</small>
                                        </td>
                                        <td>0/30<br><small>Max Players</small></td>
                                        <td><span class="text-success">$15.00</span><br><small>Per Person</small></td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ url('view-event') }}">View</a>
                                            <button class="btn btn-sm btn-success">Approve</button>
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary text-white rounded d-flex justify-content-center align-items-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="fw-bold">Downtown Sports Arena</div>
                                                    <span class="badge bg-primary me-1">Football</span>
                                                    <span class="badge text-white"
                                                        style="background-color:#8b5cf6;">Basketball</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            March 22, 2025<br>
                                            7:00 PM – 9:00 PM<br>
                                            <small>Central Park Field</small>
                                        </td>
                                        <td>0/30<br><small>Max Players</small></td>
                                        <td><span class="text-success">$15.00</span><br><small>Per Person</small></td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ url('view-event') }}">View</a>
                                            <button class="btn btn-sm btn-success">Approve</button>
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary text-white rounded d-flex justify-content-center align-items-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="fw-bold">Downtown Sports Arena</div>
                                                    <span class="badge bg-primary me-1">Football</span>
                                                    <span class="badge text-white"
                                                        style="background-color:#8b5cf6;">Basketball</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            March 22, 2025<br>
                                            7:00 PM – 9:00 PM<br>
                                            <small>Central Park Field</small>
                                        </td>
                                        <td>0/30<br><small>Max Players</small></td>
                                        <td><span class="text-success">$15.00</span><br><small>Per Person</small></td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ url('view-event') }}">View</a>
                                            <button class="btn btn-sm btn-success">Approve</button>
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary text-white rounded d-flex justify-content-center align-items-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="fw-bold">Downtown Sports Arena</div>
                                                    <span class="badge bg-primary me-1">Football</span>
                                                    <span class="badge text-white"
                                                        style="background-color:#8b5cf6;">Basketball</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            March 22, 2025<br>
                                            7:00 PM – 9:00 PM<br>
                                            <small>Central Park Field</small>
                                        </td>
                                        <td>0/30<br><small>Max Players</small></td>
                                        <td><span class="text-success">$15.00</span><br><small>Per Person</small></td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ url('view-event') }}">View</a>
                                            <button class="btn btn-sm btn-success">Approve</button>
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary text-white rounded d-flex justify-content-center align-items-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="fw-bold">Downtown Sports Arena</div>
                                                    <span class="badge bg-primary me-1">Football</span>
                                                    <span class="badge text-white"
                                                        style="background-color:#8b5cf6;">Basketball</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            March 22, 2025<br>
                                            7:00 PM – 9:00 PM<br>
                                            <small>Central Park Field</small>
                                        </td>
                                        <td>0/30<br><small>Max Players</small></td>
                                        <td><span class="text-success">$15.00</span><br><small>Per Person</small></td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ url('view-event') }}">View</a>
                                            <button class="btn btn-sm btn-success">Approve</button>
                                            <button class="btn btn-sm btn-danger">Reject</button>
                                        </td>
                                    </tr>
                                    <!-- Add more hardcoded rows here if needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @include('inc.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>

    <script>
        $(function() {
            $('#eventsTable').DataTable({
                responsive: true,
                autoWidth: false,
                searching: false, // 🔴 hides the top-right search box
                lengthChange: false // 🔴 hides the top-left record length dropdown
            });
        });
    </script>
</body>

</html> --}}

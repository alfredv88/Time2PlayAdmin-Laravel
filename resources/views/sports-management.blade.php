{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sports Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts & Styles -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ url('public/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" href="{{ url('public/assets/dist/img/favicon.png') }}" type="image/x-icon">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <!-- Sport Availability UI Start -->
        <div class="content-wrapper p-4">
            <!-- Alert Section -->
            <div class="alert d-flex align-items-start" role="alert"
                style="border: 2px solid #facc15; background-color: #FEF3C7;">
                <div class="mr-2"><i class="fas fa-exclamation-triangle mt-1"></i></div>
                <div>
                    <strong>Important: Sport Availability Control</strong><br>
                    Only sports enabled here will be available for users when creating events or selecting centre types.
                    Disabled sports will be hidden from all user interfaces.
                </div>
            </div>

            <!-- Header & Button -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                <div>
                    <h3 class="font-weight-bold mb-1">Sport Availability Management</h3>
                    <p class="text-muted mb-0">Control which sports are available in the app for events and centres</p>
                </div>
                <a href="{{ url('add-sport') }}" style="background: var(--buttons-primary-color);"
                    class="btn btn-primary border-0 d-flex align-items-center">
                    <i class="fas fa-plus mr-2"></i> Add New Sport
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-12 col-md-4 mb-3">
                    <div class="bg-white border rounded px-4 py-3 d-flex flex-column justify-content-between position-relative h-100"
                        style="border-color: #E5E7EB;">

                        <!-- Status Icon - top-right -->
                        <div class="position-absolute" style="top: 16px; right: 16px;">
                            <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle"
                                style="width: 32px; height: 32px;">
                                <i class="fas fa-check" style="font-size: 14px;"></i>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="mb-2" style="margin-top: 8px;">
                            <div class="text-muted" style="font-weight: 600; font-size: 16px;">Available to Users</div>
                            <div class="text-success font-weight-bold" style="font-size: 32px;">8</div>
                            <div class="text-success" style="font-size: 14px;">Sports enabled in app</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <div class="bg-white border rounded px-4 py-3 d-flex flex-column justify-content-between position-relative h-100"
                        style="border-color: #E5E7EB;">

                        <!-- Status Icon - top-right -->
                        <div class="position-absolute" style="top: 16px; right: 16px;">
                            <div class="d-flex align-items-center justify-content-center bg-danger text-white rounded-circle"
                                style="width: 32px; height: 32px;">
                                <i class="fas fa-times-circle text-white"></i>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="mb-2" style="margin-top: 8px;">
                            <div class="text-muted" style="font-weight: 600; font-size: 16px;">Disabled Sports</div>
                            <div class="text-danger font-weight-bold" style="font-size: 32px;">2</div>
                            <div class="text-danger" style="font-size: 14px;">Sports hidden from users</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <div class="bg-white border rounded px-4 py-3 d-flex flex-column justify-content-between position-relative h-100"
                        style="border-color: #E5E7EB;">

                        <!-- Status Icon - top-right -->
                        <div class="position-absolute" style="top: 16px; right: 16px;">
                            <div class="d-flex align-items-center justify-content-center text-white rounded-circle"
                                style="width: 32px; height: 32px;">
                                <i class="fas fa-tag text-secondary fa-lg"></i>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="mb-2" style="margin-top: 8px;">
                            <div class="text-muted" style="font-weight: 600; font-size: 16px;">Total Sports</div>
                            <div class="text-dark font-weight-bold" style="font-size: 32px;">2</div>
                            <div class="text-dark" style="font-size: 14px;">Sports in database</div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Sport Availability Controls Card -->
            <div class="card shadow-sm rounded mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 font-weight-bold">Sport Availability Controls</h5>
                        <div>
                            <button class="btn btn-success btn-sm mr-2 mb-2"><i class="fas fa-check-circle mr-1"></i>
                                Enable
                                All</button>
                            <button class="btn btn-danger btn-sm mb-2"><i class="fas fa-times-circle mr-1"></i> Disable
                                All</button>
                        </div>
                    </div>

                    <!-- Available Sports -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="mr-2" style="color: #10b981; font-size: 24px;">|</span>
                            <h6 class="text-success mb-0 font-weight-bold">Available Sports (Visible to Users)</h6>
                        </div>

                        <!-- Sport Availability Cards (Styled to Match Image) -->
                        <div class="card shadow-sm rounded mb-4">
                            <div class="card-body">
                                <!-- Available Sports List -->
                                <div class="mb-3">
                                    <!-- Football -->
                                    <div style="background: #F0FDF4"
                                        class="bg-opacity-10 p-3 rounded mb-2 border border-success d-flex align-items-center justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded mr-3"
                                                style="background-color:#e0e7ff; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                                                <i class="fas fa-wave-square text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark">Football</div>
                                                <div class="text-muted small">American football and soccer events • 245
                                                    active events</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center ml-auto text-right">
                                            <div class="mr-4">
                                                <div class="font-weight-bold">245</div>
                                                <small class="text-muted">Events</small>
                                            </div>
                                            <div class="mr-4">
                                                <div class="font-weight-bold">89</div>
                                                <small class="text-muted">Centres</small>
                                            </div>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-light border"><i
                                                        class="fas fa-edit"></i></button>
                                                <button style="background: #FEE2E2"
                                                    class="btn btn-sm btn-light border bg-opacity-25"><i
                                                        class="fas fa-eye-slash text-danger"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Basketball -->
                                    <div style="background: #F0FDF4"
                                        class="bg-opacity-10 p-3 rounded mb-2 border border-success d-flex align-items-center justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded mr-3"
                                                style="background-color:#fef3c7; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                                                <i class="fas fa-circle text-warning"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark">Basketball</div>
                                                <div class="text-muted small">Indoor and outdoor basketball games • 189
                                                    active events</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center ml-auto text-right">
                                            <div class="mr-4">
                                                <div class="font-weight-bold">189</div>
                                                <small class="text-muted">Events</small>
                                            </div>
                                            <div class="mr-4">
                                                <div class="font-weight-bold">67</div>
                                                <small class="text-muted">Centres</small>
                                            </div>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-light border"><i
                                                        class="fas fa-edit"></i></button>
                                                <button style="background: #FEE2E2"
                                                    class="btn btn-sm btn-light border bg-opacity-25"><i
                                                        class="fas fa-eye-slash text-danger"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tennis -->
                                    <div style="background: #F0FDF4"
                                        class="bg-opacity-10 p-3 rounded border border-success d-flex align-items-center justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded mr-3"
                                                style="background-color:#d1fae5; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                                                <i class="fas fa-bullseye text-success"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark">Tennis</div>
                                                <div class="text-muted small">Singles and doubles tennis matches • 156
                                                    active events</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center ml-auto text-right">
                                            <div class="mr-4">
                                                <div class="font-weight-bold">156</div>
                                                <small class="text-muted">Events</small>
                                            </div>
                                            <div class="mr-4">
                                                <div class="font-weight-bold">45</div>
                                                <small class="text-muted">Centres</small>
                                            </div>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-light border"><i
                                                        class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-light border bg-opacity-25"
                                                    style="background: #FEE2E2"><i
                                                        class="fas fa-eye-slash text-danger"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Disabled Sports -->
                    <div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="mr-2" style="color: #ef4444; font-size: 24px;">|</span>
                            <h6 class="text-danger mb-0 font-weight-bold">Disabled Sports (Hidden from Users)</h6>
                        </div>

                        <!-- Disabled Sports Cards Styled to Match Image -->
                        <div class="card shadow-sm rounded mb-4">
                            <div class="card-body">
                                <!-- Disabled Sports List -->
                                <div class="mb-3">
                                    <!-- Boxing -->
                                    <div style="background: #FEF2F2"
                                        class="bg-opacity-10 p-3 rounded mb-2 border border-danger d-flex align-items-center justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded mr-3"
                                                style="background-color:#e9d5ff; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                                                <i class="fas fa-bolt" style="color: #7c3aed;"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark">Boxing</div>
                                                <div class="text-muted small">Combat sport and fitness boxing •
                                                    Currently disabled</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center ml-auto text-right">
                                            <div class="mr-4">
                                                <div class="font-weight-bold">0</div>
                                                <small class="text-muted">Events</small>
                                            </div>
                                            <div class="mr-4">
                                                <div class="font-weight-bold">12</div>
                                                <small class="text-muted">Centres</small>
                                            </div>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-light border"><i
                                                        class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-light border"
                                                    style="background-color: #d1fae5;"><i
                                                        class="fas fa-eye text-success"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Gymnastics -->
                                    <div style="background: #FEF2F2"
                                        class="bg-opacity-10 p-3 rounded border border-danger d-flex align-items-center justify-content-between flex-wrap">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded mr-3"
                                                style="background-color:#fee2e2; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                                                <i class="fas fa-user-circle text-danger"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark">Gymnastics</div>
                                                <div class="text-muted small">Artistic and rhythmic gymnastics •
                                                    Currently disabled</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center ml-auto text-right">
                                            <div class="mr-4">
                                                <div class="font-weight-bold">0</div>
                                                <small class="text-muted">Events</small>
                                            </div>
                                            <div class="mr-4">
                                                <div class="font-weight-bold">8</div>
                                                <small class="text-muted">Centres</small>
                                            </div>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-light border"><i
                                                        class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-light border"
                                                    style="background-color: #d1fae5;"><i
                                                        class="fas fa-eye text-success"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Empty Message -->
                    <div class="text-center text-muted mt-4">
                        <i class="fas fa-eye-slash fa-2x"></i>
                        <p class="mb-0">No More Disabled Sports</p>
                        <small>All other sports are currently available to users</small>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sport Availability UI End -->

        @include('inc.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
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
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>

    <script>
        $(function() {
            $('#centreTable').DataTable({
                responsive: true,
                autoWidth: false,
                searching: false, // 🔴 hides the top-right search box
                lengthChange: false // 🔴 hides the top-left record length dropdown
            });
        });
    </script>


</body>

</html> --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sports Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts & Styles -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ url('public/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <link rel="icon" href="{{ url('public/assets/dist/img/favicon.png') }}" type="image/x-icon">

    <!-- Firebase (compat) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <!-- Sport Availability UI Start -->
        <div class="content-wrapper p-4">

            <!-- Alert Section -->
            <div class="alert d-flex align-items-start" role="alert"
                style="border: 2px solid #facc15; background-color: #FEF3C7;">
                <div class="mr-2"><i class="fas fa-exclamation-triangle mt-1"></i></div>
                <div>
                    <strong>Important: Sport Availability Control</strong><br>
                    Only sports enabled here will be available for users when creating events or selecting centre types.
                    Disabled sports will be hidden from all user interfaces.
                </div>
            </div>

            <!-- Header & Button -->
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                <div>
                    <h3 class="font-weight-bold mb-1">Sport Availability Management</h3>
                    <p class="text-muted mb-0">Control which sports are available in the app for events and centres</p>
                </div>
                <a href="{{ url('add-sport') }}" style="background: var(--buttons-primary-color);"
                    class="btn btn-primary border-0 d-flex align-items-center">
                    <i class="fas fa-plus mr-2"></i> Add New Sport
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <!-- Available to Users -->
                <div class="col-12 col-md-4 mb-3">
                    <div class="bg-white border rounded px-4 py-3 d-flex flex-column justify-content-between position-relative h-100"
                        style="border-color: #E5E7EB;">
                        <div class="position-absolute" style="top: 16px; right: 16px;">
                            <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle"
                                style="width: 32px; height: 32px;">
                                <i class="fas fa-check" style="font-size: 14px;"></i>
                            </div>
                        </div>
                        <div class="mb-2" style="margin-top: 8px;">
                            <div class="text-muted" style="font-weight: 600; font-size: 16px;">Available to Users</div>
                            <div class="text-success font-weight-bold" style="font-size: 32px;" id="availableCount">0
                            </div>
                            <div class="text-success" style="font-size: 14px;">Sports enabled in app</div>
                        </div>
                    </div>
                </div>

                <!-- Disabled -->
                <div class="col-12 col-md-4 mb-3">
                    <div class="bg-white border rounded px-4 py-3 d-flex flex-column justify-content-between position-relative h-100"
                        style="border-color: #E5E7EB;">
                        <div class="position-absolute" style="top: 16px; right: 16px;">
                            <div class="d-flex align-items-center justify-content-center bg-danger text-white rounded-circle"
                                style="width: 32px; height: 32px;">
                                <i class="fas fa-times-circle text-white"></i>
                            </div>
                        </div>
                        <div class="mb-2" style="margin-top: 8px;">
                            <div class="text-muted" style="font-weight: 600; font-size: 16px;">Disabled Sports</div>
                            <div class="text-danger font-weight-bold" style="font-size: 32px;" id="disabledCount">0
                            </div>
                            <div class="text-danger" style="font-size: 14px;">Sports hidden from users</div>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="col-12 col-md-4 mb-3">
                    <div class="bg-white border rounded px-4 py-3 d-flex flex-column justify-content-between position-relative h-100"
                        style="border-color: #E5E7EB;">
                        <div class="position-absolute" style="top: 16px; right: 16px;">
                            <div class="d-flex align-items-center justify-content-center text-white rounded-circle"
                                style="width: 32px; height: 32px;">
                                <i class="fas fa-tag text-secondary fa-lg"></i>
                            </div>
                        </div>
                        <div class="mb-2" style="margin-top: 8px;">
                            <div class="text-muted" style="font-weight: 600; font-size: 16px;">Total Sports</div>
                            <div class="text-dark font-weight-bold" style="font-size: 32px;" id="totalCount">0</div>
                            <div class="text-dark" style="font-size: 14px;">Sports in database</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sport Availability Controls Card -->
            <div class="card shadow-sm rounded mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 font-weight-bold">Sport Availability Controls</h5>
                        <div>
                            <button class="btn btn-success btn-sm mr-2 mb-2" id="enableAllBtn"><i
                                    class="fas fa-check-circle mr-1"></i> Enable All</button>
                            <button class="btn btn-danger btn-sm mb-2" id="disableAllBtn"><i
                                    class="fas fa-times-circle mr-1"></i> Disable All</button>
                        </div>
                    </div>

                    <!-- Available Sports -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="mr-2" style="color: #10b981; font-size: 24px;">|</span>
                            <h6 class="text-success mb-0 font-weight-bold">Available Sports (Visible to Users)</h6>
                        </div>

                        <div class="card shadow-sm rounded mb-4">
                            <div class="card-body">
                                <!-- will be filled by JS -->
                                <div class="mb-3" id="availableList"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Disabled Sports -->
                    <div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="mr-2" style="color: #ef4444; font-size: 24px;">|</span>
                            <h6 class="text-danger mb-0 font-weight-bold">Disabled Sports (Hidden from Users)</h6>
                        </div>

                        <div class="card shadow-sm rounded mb-4">
                            <div class="card-body">
                                <!-- will be filled by JS -->
                                <div class="mb-3" id="disabledList"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty Message -->
                    <div class="text-center text-muted mt-4" id="emptyDisabledMsg" style="display:none;">
                        <i class="fas fa-eye-slash fa-2x"></i>
                        <p class="mb-0">No More Disabled Sports</p>
                        <small>All other sports are currently available to users</small>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sport Availability UI End -->

        @include('inc.footer')
    </div>

    <!-- Vendor Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
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
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>

    <!-- Firebase init for this page -->
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
    </script>

    <!-- Render + actions -->
    <script>
        const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [c]));

        const rowEnabled = (doc) => {
            const d = doc.data();
            const leftIcon = d.iconUrl ?
                `
      <div class="rounded mr-3"
           style="background-color:#e0e7ff; width:40px; height:40px; display:flex; align-items:center; justify-content:center; overflow:hidden;">
        <img src="${esc(d.iconUrl)}" alt="${esc(d.name)} icon" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
      </div>` :
                `
      <div class="rounded mr-3"
           style="background-color:#e0e7ff; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
        <i class="fas fa-wave-square text-primary"></i>
      </div>`;

            return `
    <div style="background: #F0FDF4"
      class="bg-opacity-10 p-3 rounded mb-2 border border-success d-flex align-items-center justify-content-between flex-wrap">
      <div class="d-flex align-items-center">
        ${leftIcon}
        <div>
          <div class="font-weight-bold text-dark">${esc(d.name)}</div>
          <div class="text-muted small">${esc(d.description || 'Enabled sport')} • ${Number(d.eventsCount||0)} active events</div>
        </div>
      </div>
      <div class="d-flex align-items-center ml-auto text-right">
        <div class="mr-4">
          <div class="font-weight-bold">${Number(d.eventsCount||0)}</div>
          <small class="text-muted">Events</small>
        </div>
        <div class="mr-4">
          <div class="font-weight-bold">${Number(d.centresCount||0)}</div>
          <small class="text-muted">Centres</small>
        </div>
        <div class="btn-group">
          <a href="{{ url('edit-sport') }}/${doc.id}" class="btn btn-sm btn-light border">
            <i class="fas fa-edit"></i>
          </a>
          <button style="background: #FEE2E2" class="btn btn-sm btn-light border bg-opacity-25"
                  data-action="disable-one" data-id="${doc.id}">
            <i class="fas fa-eye-slash text-danger"></i>
          </button>
        </div>
      </div>
    </div>`;
        };


        const rowDisabled = (doc) => {
            const d = doc.data();
            const leftIcon = d.iconUrl ?
                `
      <div class="rounded mr-3"
           style="background-color:#fee2e2; width:40px; height:40px; display:flex; align-items:center; justify-content:center; overflow:hidden;">
        <img src="${esc(d.iconUrl)}" alt="${esc(d.name)} icon" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
      </div>` :
                `
      <div class="rounded mr-3"
           style="background-color:#fee2e2; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
        <i class="fas fa-user-circle text-danger"></i>
      </div>`;

            return `
    <div style="background: #FEF2F2"
      class="bg-opacity-10 p-3 rounded mb-2 border border-danger d-flex align-items-center justify-content-between flex-wrap">
      <div class="d-flex align-items-center">
        ${leftIcon}
        <div>
          <div class="font-weight-bold text-dark">${esc(d.name)}</div>
          <div class="text-muted small">${esc(d.description || 'Currently disabled')}</div>
        </div>
      </div>
      <div class="d-flex align-items-center ml-auto text-right">
        <div class="mr-4">
          <div class="font-weight-bold">${Number(d.eventsCount||0)}</div>
          <small class="text-muted">Events</small>
        </div>
        <div class="mr-4">
          <div class="font-weight-bold">${Number(d.centresCount||0)}</div>
          <small class="text-muted">Centres</small>
        </div>
        <div class="btn-group">
          <a href="{{ url('edit-sport') }}/${doc.id}" class="btn btn-sm btn-light border">
            <i class="fas fa-edit"></i>
          </a>
          <button class="btn btn-sm btn-light border" style="background-color: #d1fae5;"
                  data-action="enable-one" data-id="${doc.id}">
            <i class="fas fa-eye text-success"></i>
          </button>
        </div>
      </div>
    </div>`;
        };


        async function render() {
            const snap = await db.collection('sportsManagement').orderBy('name').get();
            const docs = snap.docs;

            const enabled = docs.filter(d => (d.data().status || 'enabled') === 'enabled');
            const disabled = docs.filter(d => (d.data().status || 'enabled') === 'disabled');

            document.getElementById('availableCount').innerText = enabled.length;
            document.getElementById('disabledCount').innerText = disabled.length;
            document.getElementById('totalCount').innerText = docs.length;

            document.getElementById('availableList').innerHTML = enabled.map(rowEnabled).join('');
            document.getElementById('disabledList').innerHTML = disabled.map(rowDisabled).join('');

            document.getElementById('emptyDisabledMsg').style.display = disabled.length ? 'none' : 'block';
        }

        async function bulkSetStatus(status) {
            const go = confirm(status === 'enabled' ?
                'Enable ALL sports for users?' :
                'Disable ALL sports (hide from users)?');
            if (!go) return;

            const snap = await db.collection('sportsManagement').get();
            const docs = snap.docs;

            // batch in chunks ≤ 400
            let batch = db.batch();
            let count = 0;
            for (const d of docs) {
                batch.update(d.ref, {
                    status
                });
                count++;
                if (count % 400 === 0) {
                    await batch.commit();
                    batch = db.batch();
                }
            }
            await batch.commit();

            alert(status === 'enabled' ? 'All sports enabled.' : 'All sports disabled.');
            render();
        }

        document.getElementById('enableAllBtn').addEventListener('click', () => bulkSetStatus('enabled'));
        document.getElementById('disableAllBtn').addEventListener('click', () => bulkSetStatus('disabled'));

        // Single toggle buttons
        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('button[data-action]');
            if (!btn) return;
            const id = btn.getAttribute('data-id');
            const action = btn.getAttribute('data-action');

            if (action === 'disable-one') {
                if (!confirm('Disable this sport?')) return;
                await db.collection('sportsManagement').doc(id).update({
                    status: 'disabled'
                });
                render();
            } else if (action === 'enable-one') {
                if (!confirm('Enable this sport?')) return;
                await db.collection('sportsManagement').doc(id).update({
                    status: 'enabled'
                });
                render();
            }
        });

        // first load
        render();
    </script>

</body>

</html>

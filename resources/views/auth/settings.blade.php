<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setting</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('public/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('public/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/admin/css/main.css') }}">
    <link rel="stylesheet" href="{{ url('public/admin/css/pdf.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ url('public/frontend/images/logo.png') }}">
    <link href='https://fonts.googleapis.com/css?family=Viga' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        #logo {
            width: 75%;
            height: auto;
            border-radius: 26.32px 0px 26.32px 0px;
            background: rgba(255, 255, 255, 1);
            box-shadow: 1.7543859481811523px 7.017543792724609px 7.017543792724609px 0px rgba(0, 0, 0, 0.2) inset;
            box-shadow: -1.7543859481811523px -3.5087718963623047px 7.017543792724609px 0px rgba(0, 0, 0, 0.2) inset;
        }

        .nav-link {
            color: black !important;
        }

        /* .nav-link:hover {
        background-color: rgba(0, 0, 0, 0.5) !important;
        color: white !important;
    } */
        .nav-link.active {
            background-color: black !important;
            color: white !important;
        }

        .search {
            width: 100%;
            border: none;
            border-radius: 4px;
            background: rgba(255, 255, 255, 1);
            box-shadow: 0px 2px 10px 0px rgba(0, 0, 0, 0.25);
            outline: none;
            /* Remove the outline when the input is focused */
        }

        .search:focus {
            border: none;
            /* Remove the border when the input is focused */
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('inc.nav')

        @include('inc.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <div class="pt-4">
                        <h1 style="color: black;">Settings</h1>
                    </div>

                    @if (session('success'))
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-12">
                                <div class="alert alert-success alert-dismissible" id="success-alert">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    {{ session('success') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Display Validation Error Messages -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible" id="danger-alert">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    {{ session('error') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Table with User Data -->
                    <div class="row pt-3">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card text-center">
                                <div class="card-body">
                                    <!-- Lock icon -->
                                    <div class="mb-4">
                                        <img src="{{ url('public/frontend/images/lock.png') }}" style="width: 8%" alt="">
                                    </div>
                                    <!-- Title -->
                                    <h3 class="mb-4" style="font-weight: bold;">Change Password</h3>

                                    <form action="{{ url('settings') }}" method="post">
                                        @csrf
                                        <!-- Current Password -->
                                        <div class="mb-3 d-flex justify-content-center">
                                            <div class="input-group" style="width: 50%;">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="bi bi-lock-fill" style="color: #2CB71B"></i>
                                                </span>
                                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Current Password" id="currentPassword">
                                                <span class="input-group-text">
                                                    <i class="bi bi-eye-fill" style="color: #2CB71B" onclick="togglePassword('currentPassword')"></i>
                                                </span>
                                                @error('current_password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- New Password -->
                                        <div class="mb-3 d-flex justify-content-center">
                                            <div class="input-group" style="width: 50%;">
                                                <span class="input-group-text" id="basic-addon2">
                                                    <i class="bi bi-lock-fill" style="color: #2CB71B"></i>
                                                </span>
                                                <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="New Password" id="newPassword">
                                                <span class="input-group-text">
                                                    <i class="bi bi-eye-fill" style="color: #2CB71B" onclick="togglePassword('newPassword')"></i>
                                                </span>
                                                @error('new_password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="mb-3 d-flex justify-content-center">
                                            <div class="input-group" style="width: 50%;">
                                                <span class="input-group-text" id="basic-addon3">
                                                    <i class="bi bi-lock-fill" style="color: #2CB71B"></i>
                                                </span>
                                                <input type="password" name="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirm Password" id="confirmPassword">
                                                <span class="input-group-text">
                                                    <i class="bi bi-eye-fill" style="color: #2CB71B" onclick="togglePassword('confirmPassword')"></i>
                                                </span>
                                                @error('confirm_password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Update Button -->
                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-lg btn-primary border-0" style="background: #2CB71B; width: 40%;">
                                                <strong>Update</strong>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        function togglePassword(inputId) {
                            var passwordInput = document.getElementById(inputId);
                            var eyeIcon = passwordInput.nextElementSibling.querySelector('i'); // Get the eye icon

                            if (passwordInput.type === "password") {
                                passwordInput.type = "text"; // Show password
                                eyeIcon.classList.remove('bi-eye-fill'); // Remove the visible eye icon class
                                eyeIcon.classList.add('bi-eye-slash-fill'); // Add the invisible eye icon class
                            } else {
                                passwordInput.type = "password"; // Hide password
                                eyeIcon.classList.remove('bi-eye-slash-fill'); // Remove the invisible eye icon class
                                eyeIcon.classList.add('bi-eye-fill'); // Add the visible eye icon class
                            }
                        }
                    </script>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            // Hide the success message after 5 seconds
                            setTimeout(function() {
                                $("#success-alert").fadeTo(500, 0).slideUp(500, function() {
                                    $(this).remove();
                                });
                            }, 5000);

                            // Hide the error messages after 5 seconds
                            setTimeout(function() {
                                $(".alert-danger").fadeTo(500, 0).slideUp(500, function() {
                                    $(this).remove();
                                });
                            }, 5000);
                        });
                    </script>
                </div>
            </section>
        </div>
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $("#success-alert").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>

    <script>
        function confirmAction() {
            return confirm('Are you sure you want to approve the request?');
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $("#danger-alert").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.status-button').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Stop the form from submitting
                    var userId = this.getAttribute('data-user-id');
                    var userStatus = this.classList.contains('btn-success') ? 'Active' : 'Blocked';
                    if (confirm('Are you sure you want to change the status of user ' + userId +
                            ' to ' + userStatus + '?')) {
                        document.getElementById('statusForm-' + userId).submit();
                    }
                });
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('userSearch');

            searchInput.addEventListener('keyup', function(e) {
                const searchText = e.target.value.toLowerCase();
                const tableRows = document.querySelectorAll('#example2 tbody tr');

                tableRows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const textContent = cells[1].textContent.toLowerCase() + ' ' + cells[2]
                        .textContent.toLowerCase();
                    if (textContent.includes(searchText)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery -->
    <script src="{{ url('public/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('public/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ url('public/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ url('public/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ url('public/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ url('public/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ url('public/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('public/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ url('public/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('public/dist/js/adminlte.min.js') }}"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
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

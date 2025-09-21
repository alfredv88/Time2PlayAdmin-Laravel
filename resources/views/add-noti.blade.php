<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Notification</title>

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
                            <h1>Custom Notification</h1>
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
                                    <h3 class="card-title">Add New Notification</h3>
                                </div>
                                <form action="{{ url('store-noti') }}" method="POST" id="notificationForm">
                                    @csrf
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label>Notification Title</label>
                                            <input required type="text" name="title" class="form-control"
                                                placeholder="Enter Title">
                                        </div>

                                        <div class="form-group">
                                            <label>Type</label>
                                            <select name="type" class="form-control" id="typeSelector">
                                                <option value="all">All Users</option>
                                                <option value="25">25%</option>
                                                <option value="50">50%</option>
                                                <option value="75">75%</option>
                                                <option value="specific">Specific Users</option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="specificUsersSelector" style="display:none;">
                                            <label>Users</label>
                                            <button type="button" class="btn btn-outline-secondary w-100"
                                                data-toggle="modal" data-target="#usersModal">
                                                Select Users to Send Notification
                                            </button>
                                            <input type="hidden" name="user_ids" id="user_ids">
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Release Date</label>
                                                <input required type="date" name="release_date" id="release_date"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Release Time</label>
                                                <input required type="time" name="release_time" id="release_time"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea required name="description" class="form-control" placeholder="Enter Notification Description"></textarea>
                                        </div>

                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary border-0"
                                            style="background: var(--buttons-primary-color);">Send</button>
                                    </div>
                                </form>

                                {{-- Modal --}}
                                <div class="modal fade" id="usersModal" tabindex="-1" aria-labelledby="usersModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Select users to send notification</h5>
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                {{-- <input type="text" class="form-control mb-3" id="userSearch" placeholder="Search users here..."> --}}
                                                @php($users = $users ?? [])
                                                <ul class="list-group" id="userList">
                                                    @foreach ($users as $u)
                                                        <li class="list-group-item d-flex align-items-center">
                                                            <img src="{{ $u['image'] ?? 'https://i.pravatar.cc/40?u=' . $u['id'] }}"
                                                                class="rounded-circle mr-2" width="35"
                                                                height="35" alt="">
                                                            <span
                                                                class="flex-grow-1">{{ $u['name'] ?? 'Unknown' }}</span>
                                                            <input type="checkbox" class="user-checkbox"
                                                                value="{{ $u['id'] }}">
                                                        </li>
                                                    @endforeach
                                                </ul>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary w-100"
                                                    id="doneSelectingUsers"
                                                    style="background: var(--buttons-primary-color);">Done</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
    <script>
        document.getElementById('typeSelector').addEventListener('change', function() {
            const selected = this.value;
            const specificSelector = document.getElementById('specificUsersSelector');

            if (selected === 'specific') {
                specificSelector.style.display = 'block';
                $('#usersModal').modal('show'); // Auto open modal
            } else {
                specificSelector.style.display = 'none';
            }
        });

        document.getElementById('doneSelectingUsers').addEventListener('click', function() {
            const selectedUserIds = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb
                .value);
            document.getElementById('user_ids').value = selectedUserIds.join(',');
            $('#usersModal').modal('hide');
        });

        document.getElementById('userSearch').addEventListener('input', function() {
            const searchVal = this.value.toLowerCase();
            document.querySelectorAll('#userList li').forEach(item => {
                const name = item.querySelector('span').innerText.toLowerCase();
                item.style.display = name.includes(searchVal) ? 'flex' : 'none';
            });
        });
    </script>

    <!-- Optionally include Moment.js with Timezone support -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.43/moment-timezone-with-data.min.js"></script>

    <script>
        // When user submits or selects, convert to America/New_York timezone
        function getNewYorkReleaseDateTime() {
            const date = document.getElementById('release_date').value;
            const time = document.getElementById('release_time').value;

            if (!date || !time) return null;

            // Combine date and time into one
            const localDateTime = moment(`${date}T${time}`);

            // Convert to America/New_York timezone
            const newYorkTime = localDateTime.tz("America/New_York");

            console.log("New York Date-Time:", newYorkTime.format()); // ISO format
            console.log("New York Date:", newYorkTime.format('YYYY-MM-DD'));
            console.log("New York Time:", newYorkTime.format('HH:mm'));

            return {
                date: newYorkTime.format('YYYY-MM-DD'),
                time: newYorkTime.format('HH:mm')
            };
        }

        // Example: capture on form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const releaseDateTime = getNewYorkReleaseDateTime();
            if (releaseDateTime) {
                // Optionally, you can set hidden fields here if you want to send New York time instead
                console.log('Submitting:', releaseDateTime);
            }
            // Otherwise continue submitting normally
        });
    </script>

    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ url('public/assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html>

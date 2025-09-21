<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Request Details</title>

    <!-- Google Font & Bootstrap -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <style>
        .badge-custom {
            padding: 5px 10px;
            font-size: 12px;
            margin-right: 5px;
        }

        .event-box {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fff;
        }

        .event-title {
            font-weight: bold;
            font-size: 18px;
        }

        .status-box {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .quick-actions .btn {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }

        .text-label {
            font-weight: 600;
            color: #6c757d;
        }

        .stat-box {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
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
                    <h2>Event Request Details</h2>
                    <p class="text-muted">Review and manage this event submission</p>
                    <div class="status-box">
                        <strong>Status:</strong>
                        @php $slug = $event['statusSlug'] ?? 'pending'; @endphp
                        @if ($slug === 'approved')
                            Approved
                        @elseif($slug === 'rejected')
                            Rejected
                        @else
                            Pending Review
                        @endif
                        @if (!empty($event['createdAtDisp']))
                            <span class="float-right">Submitted on {{ $event['createdAtDisp'] }}</span>
                        @endif
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Event Overview -->
                        <div class="col-md-8">
                            <style>
                                .event-box {
                                    background: #fff;
                                    border: 1px solid #e0e0e0;
                                    border-radius: 12px;
                                    padding: 24px;
                                    margin-bottom: 20px;
                                    font-family: 'Segoe UI', sans-serif;
                                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
                                }

                                .event-title {
                                    font-size: 20px;
                                    font-weight: 700;
                                    color: #111827;
                                }

                                .badge-custom {
                                    font-size: 13px;
                                    padding: 6px 12px;
                                    border-radius: 20px;
                                    margin-right: 6px;
                                    display: inline-flex;
                                    align-items: center;
                                    font-weight: 500;
                                }

                                .badge-football {
                                    background-color: #e0f2fe;
                                    color: #0284c7;
                                }

                                .badge-tournament {
                                    background-color: #ede9fe;
                                    color: #7c3aed;
                                }

                                .badge-public {
                                    background-color: #d1fae5;
                                    color: #059669;
                                }

                                .event-label {
                                    color: #6b7280;
                                    font-weight: 500;
                                    font-size: 14px;
                                    margin-bottom: 2px;
                                }

                                .event-value {
                                    font-size: 15px;
                                    color: #111827;
                                    margin-bottom: 10px;
                                    font-weight: 500;
                                }

                                .event-id {
                                    font-size: 13px;
                                    color: #6b7280;
                                    background: #f3f4f6;
                                    padding: 6px 12px;
                                    border-radius: 6px;
                                    font-weight: 500;
                                }

                                .event-icon {
                                    width: 48px;
                                    height: 48px;
                                    background: #e5e7eb;
                                    border-radius: 8px;
                                    display: inline-flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 20px;
                                    margin-right: 15px;
                                    color: #9ca3af;
                                }

                                @media (max-width: 768px) {
                                    .event-box .row>div {
                                        margin-bottom: 16px;
                                    }
                                }
                            </style>

                            <div class="event-box">
                                <div class="d-flex justify-content-between align-items-start flex-wrap">
                                    <div class="d-flex align-items-start">
                                        <div class="event-icon">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <div>
                                            <div class="event-title mb-2">{{ $event['name'] ?? 'Untitled Event' }}</div>
                                            <div>
                                                <span class="badge badge-custom badge-football"><i
                                                        class="fas fa-bolt mr-1"></i>
                                                    {{ $event['sportType'] ?? 'Football' }}</span>
                                                <span class="badge badge-custom badge-tournament"><i
                                                        class="fas fa-users mr-1"></i>
                                                    {{ $event['eventFormat'] ?? 'Tournament' }}</span>
                                                <span class="badge badge-custom badge-public"><i
                                                        class="fas fa-globe mr-1"></i>
                                                    {{ $event['eventType'] ?? 'Public' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="event-id mt-2 mt-md-0">ID: {{ $event['eventId'] ?? $event['docId'] }}
                                    </div> --}}
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="event-label">Event Date</div>
                                        <div class="event-value">
                                            {{ $event['eventDateDisp'] ?? 'Friday, March 22, 2025' }}</div>

                                        <div class="event-label">Location</div>
                                        <div class="event-value">
                                            {{ $event['location'] ?? 'Central Park Football Field, Downtown' }}</div>

                                        <div class="event-label">Max Participants</div>
                                        <div class="event-value">{{ (int) ($event['maxParticipants'] ?? 30) }} players
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="event-label">Time</div>
                                        <div class="event-value">
                                            {{ ($event['startTime'] ?? '7:00 PM') . (isset($event['endTime']) && $event['endTime'] ? ' – ' . $event['endTime'] : '') }}
                                        </div>

                                        <div class="event-label">Duration</div>
                                        <div class="event-value">{{ $event['durationHuman'] ?? '0 hours' }}</div>

                                        <div class="event-label">Entry Fee</div>
                                        <div class="event-value text-success">
                                            @if (($event['entryType'] ?? 'Free') === 'Free' || (float) ($event['price'] ?? 0) == 0)
                                                Free
                                            @else
                                                ${{ number_format((float) $event['price'], 2) }} per person
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="event-box">
                                <h5 class="mb-3">Event Description</h5>
                                <p>{{ $event['description'] ?? 'Nothing' }}</p>
                                {{-- <p><strong>What to expect:</strong></p>
                                <ul>
                                    <li>Professional referees</li>
                                    <li>Quality equipment provided</li>
                                    <li>Post-game refreshments</li>
                                    <li>Prizes for winning teams</li>
                                    <li>Great networking opportunities</li>
                                </ul>
                                <p>Please bring your own cleats and water bottle. Team jerseys will be provided on-site.
                                </p> --}}
                            </div>
                        </div>

                        <!-- Right Sidebar -->
                        <div class="col-md-4">
                            <!-- Organizer -->
                            <div class="card shadow-sm border-0 rounded-3 p-4 mb-4">
                                <h5 class="mb-3">Event Organizer</h5>
                                <div class="d-flex align-items-start mb-3">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                        style="width: 60px; height: 60px; font-size: 20px; font-weight: bold;">
                                        AH
                                    </div>
                                    <div class="ms-3" style="margin-left: 2%">
                                        <h6 class="mb-1 fw-bold">Alex Harper</h6>
                                        <p class="mb-1 text-muted" style="font-size: 14px;">Event Creator & Sports
                                            Enthusiast</p>
                                        <div class="text-warning" style="font-size: 16px;">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <span class="text-muted small ms-2">4.8 (24 reviews)</span>
                                        </div>
                                    </div>
                                </div>

                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="fas fa-envelope text-secondary me-2"></i>
                                        <span class="text-dark">alex.harper@email.com</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-phone text-secondary me-2"></i>
                                        <span class="text-dark">+1 (555) 123–4567</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-map-marker-alt text-secondary me-2"></i>
                                        <span class="text-dark">New York, NY</span>
                                    </li>
                                </ul>

                                <button class="btn btn-outline-secondary w-100">
                                    <i class="far fa-comment-alt me-1"></i> Contact Organizer
                                </button>
                            </div>

                            <!-- Statistics -->
                            <div class="stat-box">
                                <h6 class="mb-3">Event Statistics</h6>
                                <p><strong>Current Registrations:</strong>
                                    {{ (int) ($event['currentParticipants'] ?? 0) }} /
                                    {{ (int) ($event['maxParticipants'] ?? 0) }}</p>
                                <p><strong>Registration Rate:</strong> {{ (int) ($event['registrationRate'] ?? 0) }}%
                                </p>
                                @if (!is_null($event['daysUntil'] ?? null))
                                    <p><strong>Days Until Event:</strong>
                                        @php $d = (int)$event['daysUntil']; @endphp
                                        @if ($d >= 0)
                                            <span class="{{ $d <= 2 ? 'text-danger' : '' }}">{{ $d }}
                                                day{{ $d == 1 ? '' : 's' }}</span>
                                        @else
                                            <span class="text-muted">Event date passed</span>
                                        @endif
                                    </p>
                                @endif
                                @if (($event['expectedRevenue'] ?? 0) > 0)
                                    <p><strong>Expected Revenue:</strong> <span
                                            class="text-success">${{ number_format((float) $event['expectedRevenue'], 2) }}</span>
                                    </p>
                                @endif
                            </div>

                            <div class="card shadow-sm border-0 rounded-3 p-4">
                                <h5 class="mb-4">Quick Actions</h5>

                                <div class="d-grid">
                                    <button type="button" class="btn btn-success btn-lg w-100 mb-3">
                                        <i class="fas fa-check me-2"></i> Approve Event
                                    </button>

                                    <button type="button" class="btn btn-danger btn-lg w-100">
                                        <i class="fas fa-times me-2"></i> Reject Event
                                    </button>
                                </div>
                            </div>

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
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>

    <script>
        // minimal JS: submit POST without adding forms in your HTML
        (function() {
            const UPDATE_URL = "{{ route('events.updateStatus', $event['docId']) }}";
            const CSRF = "{{ csrf_token() }}";

            function submitStatus(status) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = UPDATE_URL;

                const tok = document.createElement('input');
                tok.type = 'hidden';
                tok.name = '_token';
                tok.value = CSRF;
                const st = document.createElement('input');
                st.type = 'hidden';
                st.name = 'status';
                st.value = status;

                form.appendChild(tok);
                form.appendChild(st);
                document.body.appendChild(form);
                form.submit();
            }

            const approveBtn = document.querySelector('.btn.btn-success.btn-lg.w-100.mb-3');
            const rejectBtn = document.querySelector('.btn.btn-danger.btn-lg.w-100');

            if (approveBtn) approveBtn.addEventListener('click', () => submitStatus('approved'));
            if (rejectBtn) rejectBtn.addEventListener('click', () => submitStatus('rejected'));
        })();
    </script>
</body>

</html>













{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Request Details</title>

    <!-- Google Font & Bootstrap -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
    <link rel="stylesheet" href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/dist/css/adminlte.min.css') }}">
    <style>
        .badge-custom {
            padding: 5px 10px;
            font-size: 12px;
            margin-right: 5px;
        }

        .event-box {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fff;
        }

        .event-title {
            font-weight: bold;
            font-size: 18px;
        }

        .status-box {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .quick-actions .btn {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }

        .text-label {
            font-weight: 600;
            color: #6c757d;
        }

        .stat-box {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
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
                    <h2>Event Request Details</h2>
                    <p class="text-muted">Review and manage this event submission</p>
                    <div class="status-box">
                        <strong>Status:</strong> Pending Review
                        <span class="float-right">Submitted on March 20, 2025 at 2:30 PM</span>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Event Overview -->
                        <div class="col-md-8">
                            <style>
                                .event-box {
                                    background: #fff;
                                    border: 1px solid #e0e0e0;
                                    border-radius: 12px;
                                    padding: 24px;
                                    margin-bottom: 20px;
                                    font-family: 'Segoe UI', sans-serif;
                                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
                                }

                                .event-title {
                                    font-size: 20px;
                                    font-weight: 700;
                                    color: #111827;
                                }

                                .badge-custom {
                                    font-size: 13px;
                                    padding: 6px 12px;
                                    border-radius: 20px;
                                    margin-right: 6px;
                                    display: inline-flex;
                                    align-items: center;
                                    font-weight: 500;
                                }

                                .badge-football {
                                    background-color: #e0f2fe;
                                    color: #0284c7;
                                }

                                .badge-tournament {
                                    background-color: #ede9fe;
                                    color: #7c3aed;
                                }

                                .badge-public {
                                    background-color: #d1fae5;
                                    color: #059669;
                                }

                                .event-label {
                                    color: #6b7280;
                                    font-weight: 500;
                                    font-size: 14px;
                                    margin-bottom: 2px;
                                }

                                .event-value {
                                    font-size: 15px;
                                    color: #111827;
                                    margin-bottom: 10px;
                                    font-weight: 500;
                                }

                                .event-id {
                                    font-size: 13px;
                                    color: #6b7280;
                                    background: #f3f4f6;
                                    padding: 6px 12px;
                                    border-radius: 6px;
                                    font-weight: 500;
                                }

                                .event-icon {
                                    width: 48px;
                                    height: 48px;
                                    background: #e5e7eb;
                                    border-radius: 8px;
                                    display: inline-flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 20px;
                                    margin-right: 15px;
                                    color: #9ca3af;
                                }

                                @media (max-width: 768px) {
                                    .event-box .row>div {
                                        margin-bottom: 16px;
                                    }
                                }
                            </style>

                            <div class="event-box">
                                <div class="d-flex justify-content-between align-items-start flex-wrap">
                                    <div class="d-flex align-items-start">
                                        <div class="event-icon">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <div>
                                            <div class="event-title mb-2">Friday Night Football League</div>
                                            <div>
                                                <span class="badge badge-custom badge-football"><i
                                                        class="fas fa-bolt mr-1"></i> Football</span>
                                                <span class="badge badge-custom badge-tournament"><i
                                                        class="fas fa-users mr-1"></i> Tournament</span>
                                                <span class="badge badge-custom badge-public"><i
                                                        class="fas fa-globe mr-1"></i> Public</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="event-id mt-2 mt-md-0">ID: #EVT–2025–0322</div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="event-label">Event Date</div>
                                        <div class="event-value">Friday, March 22, 2025</div>

                                        <div class="event-label">Location</div>
                                        <div class="event-value">Central Park Football Field, Downtown</div>

                                        <div class="event-label">Max Participants</div>
                                        <div class="event-value">30 players</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="event-label">Time</div>
                                        <div class="event-value">7:00 PM – 9:00 PM</div>

                                        <div class="event-label">Duration</div>
                                        <div class="event-value">2 hours</div>

                                        <div class="event-label">Entry Fee</div>
                                        <div class="event-value text-success">$15.00 per person</div>
                                    </div>
                                </div>
                            </div>


                            <div class="event-box">
                                <h5 class="mb-3">Event Description</h5>
                                <p>
                                    Join us for an exciting Friday night football tournament! This weekly league brings
                                    together football enthusiasts from across the city for competitive matches in a
                                    friendly environment.
                                    Whether you’re a seasoned player or just starting out, this event welcomes all skill
                                    levels.
                                </p>
                                <p><strong>What to expect:</strong></p>
                                <ul>
                                    <li>Professional referees</li>
                                    <li>Quality equipment provided</li>
                                    <li>Post-game refreshments</li>
                                    <li>Prizes for winning teams</li>
                                    <li>Great networking opportunities</li>
                                </ul>
                                <p>Please bring your own cleats and water bottle. Team jerseys will be provided on-site.
                                </p>
                            </div>
                        </div>

                        <!-- Right Sidebar -->
                        <div class="col-md-4">
                            <!-- Organizer -->
                            <div class="card shadow-sm border-0 rounded-3 p-4 mb-4">
                                <h5 class="mb-3">Event Organizer</h5>
                                <div class="d-flex align-items-start mb-3">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                        style="width: 60px; height: 60px; font-size: 20px; font-weight: bold;">
                                        AH
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1 fw-bold">Alex Harper</h6>
                                        <p class="mb-1 text-muted" style="font-size: 14px;">Event Creator & Sports
                                            Enthusiast</p>
                                        <div class="text-warning" style="font-size: 16px;">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <span class="text-muted small ms-2">4.8 (24 reviews)</span>
                                        </div>
                                    </div>
                                </div>

                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="fas fa-envelope text-secondary me-2"></i>
                                        <span class="text-dark">alex.harper@email.com</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-phone text-secondary me-2"></i>
                                        <span class="text-dark">+1 (555) 123–4567</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-map-marker-alt text-secondary me-2"></i>
                                        <span class="text-dark">New York, NY</span>
                                    </li>
                                </ul>

                                <button class="btn btn-outline-secondary w-100">
                                    <i class="far fa-comment-alt me-1"></i> Contact Organizer
                                </button>
                            </div>


                            <!-- Statistics -->
                            <div class="stat-box">
                                <h6 class="mb-3">Event Statistics</h6>
                                <p><strong>Current Registrations:</strong> 22 / 30</p>
                                <p><strong>Registration Rate:</strong> 73%</p>
                                <p><strong>Days Until Event:</strong> <span class="text-danger">2 days</span></p>
                                <p><strong>Expected Revenue:</strong> <span class="text-success">$450.00</span></p>
                            </div>

                            <div class="card shadow-sm border-0 rounded-3 p-4">
                                <h5 class="mb-4">Quick Actions</h5>

                                <div class="d-grid">
                                    <button type="button" class="btn btn-success btn-lg w-100 mb-3">
                                        <i class="fas fa-check me-2"></i> Approve Event
                                    </button>

                                    <button type="button" class="btn btn-danger btn-lg w-100">
                                        <i class="fas fa-times me-2"></i> Reject Event
                                    </button>
                                </div>
                            </div>


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
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html> --}}

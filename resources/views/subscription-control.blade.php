{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Subscription Control</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ url('public/assets/dist/css/adminlte.min.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ url('public/assets/dist/img/favicon.png') }}" type="image/x-icon">

    <style>
        .toggle-switch {
            width: 45px;
            height: 24px;
        }

        .box-highlight {
            border: 2px solid #0d6efd;
            background: #f0f8ff;
        }

        .badge-circle {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <div class="content-wrapper p-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold">Subscription Control</h3>
                    <p class="text-muted">Manage subscription plans, pricing, and payment methods</p>
                </div>
                <button class="btn btn-primary"><i class="fas fa-save me-2"></i> Save All Changes</button>
            </div>
            <!-- Stats -->
            <div class="row g-3 mb-4">
                <!-- Active Subscriptions -->
                <div class="col-md-4">
                    <div class="p-4 rounded-3 border border-primary"
                        style="background-color: #eef4ff; position: relative;">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="text-primary fw-bold">Active Subscriptions</h6>
                            <i class="fas fa-users text-primary"></i>
                        </div>
                        <h3 class="fw-bold text-primary mt-3">1,247</h3>
                        <div class="text-primary small">+12% from last month</div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="col-md-4">
                    <div class="p-4 rounded-3 border border-success"
                        style="background-color: #e6f9f0; position: relative;">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="text-success fw-bold">Monthly Revenue</h6>
                            <i class="fas fa-dollar-sign text-success"></i>
                        </div>
                        <h3 class="fw-bold text-success mt-3">$18,450</h3>
                        <div class="text-success small">+8% from last month</div>
                    </div>
                </div>

                <!-- Pro Plan Uptake -->
                <div class="col-md-4">
                    <div class="p-4 rounded-3 border border-warning"
                        style="background-color: #fff5d1; position: relative;">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="text-warning fw-bold">Pro Plan Uptake</h6>
                            <i class="fas fa-chart-line text-warning"></i>
                        </div>
                        <h3 class="fw-bold text-warning mt-3">23%</h3>
                        <div class="text-warning small">Free: 77% | Pro: 23%</div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Subscription Plan Settings -->
                <div class="col-lg-8">
                    <div class="bg-white border rounded p-4 mb-4">
                        <h5 class="fw-bold mb-3">Subscription Plan Settings</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-bold text-success"><i class="fas fa-leaf me-1"></i> Free Plan
                                        </div>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                    <p class="text-muted small">Basic access for all users</p>
                                    <ul class="small text-muted">
                                        <li>Join Free Events</li>
                                        <li>Browse Local Sports Feed</li>
                                        <li>Access Public Groups</li>
                                    </ul>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                        <button class="btn btn-sm btn-outline-primary">+ Add</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100 box-highlight">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-bold text-primary"><i class="fas fa-crown me-1"></i> Pro Plan
                                        </div>
                                        <span class="badge bg-primary">Active</span>
                                    </div>
                                    <p class="text-muted small">Premium features & benefits</p>
                                    <ul class="small text-muted">
                                        <li>Join Premium & Paid Events</li>
                                        <li>Create & Host Events</li>
                                        <li>View Event Stats & Insights</li>
                                        <li>Access Private Sports Groups</li>
                                        <li>Priority Support Access</li>
                                    </ul>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                        <button class="btn btn-sm btn-outline-primary">+ Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Pricing -->
                    <div class="bg-white border rounded p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold">Subscription Price Management</h5>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    USD
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">USD</a></li>
                                    <li><a class="dropdown-item" href="#">PKR</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Monthly Subscription -->
                            <div class="col-md-6">
                                <div class="border rounded-3 p-4 bg-white position-relative shadow-sm">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="fw-bold mb-0">Monthly Subscription</h5>
                                        <span class="badge bg-success">Active</span>
                                    </div>

                                    <p class="text-muted small mb-2">Set Monthly Price</p>

                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-white border-end-0"><i
                                                class="fas fa-dollar-sign"></i></span>
                                        <input type="text"
                                            class="form-control border-start-0 border-end-0 text-center fw-semibold"
                                            value="5.99">
                                        <span class="input-group-text bg-white border-start-0">/ month</span>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary w-100"><i class="fas fa-check me-1"></i>
                                            Update</button>
                                        <button class="btn btn-outline-secondary w-100"><i
                                                class="fas fa-rotate-left me-1"></i> Reset</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Annual Subscription -->
                            <div class="col-md-6">
                                <div class="border border-success rounded-3 p-4 bg-light position-relative shadow-sm">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="fw-bold mb-0 text-success">Annual Subscription</h5>
                                        <span class="badge bg-success">Active</span>
                                    </div>

                                    <p class="text-muted small mb-2">Set Annual Price</p>

                                    <div class="input-group mb-2">
                                        <span class="input-group-text bg-white border-end-0"><i
                                                class="fas fa-dollar-sign"></i></span>
                                        <input type="text"
                                            class="form-control border-start-0 border-end-0 text-center fw-semibold"
                                            value="59.99">
                                        <span class="input-group-text bg-white border-start-0">/ year</span>
                                    </div>

                                    <div class="text-success small mb-3">Save $11.89 compared to monthly</div>

                                    <div class="d-flex gap-2">
                                        <button class="btn btn-success w-100"><i class="fas fa-check me-1"></i>
                                            Update</button>
                                        <button class="btn btn-outline-secondary w-100"><i
                                                class="fas fa-rotate-left me-1"></i> Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Payment Method Control -->
                <div class="col-lg-4">
                    <div class="bg-white border rounded-4 p-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Payment Method Control</h5>
                            <span class="badge bg-success">4 Active</span>
                        </div>

                        @php
                            $methods = [
                                [
                                    'icon' => 'fab fa-google',
                                    'name' => 'Google Pay',
                                    'desc' => 'Digital wallet payment',
                                    'fee' => '2.9%',
                                    'active' => true,
                                ],
                                [
                                    'icon' => 'fab fa-apple',
                                    'name' => 'Apple Pay',
                                    'desc' => 'iOS secure payment',
                                    'fee' => '2.9%',
                                    'active' => true,
                                ],
                                [
                                    'icon' => 'fas fa-credit-card',
                                    'name' => 'Credit/Debit Card',
                                    'desc' => 'Visa, Mastercard, Amex',
                                    'fee' => '3.4%',
                                    'active' => true,
                                ],
                                [
                                    'icon' => 'fas fa-qrcode',
                                    'name' => 'QR Code Payment',
                                    'desc' => 'Scan to pay system',
                                    'fee' => '1.5%',
                                    'active' => true,
                                ],
                                [
                                    'icon' => 'fas fa-wallet',
                                    'name' => 'Time2Play Wallet',
                                    'desc' => 'In-app digital wallet',
                                    'fee' => '0% (Internal)',
                                    'active' => false,
                                ],
                            ];
                        @endphp

                        @foreach ($methods as $method)
                            <div
                                class="rounded-3 p-3 mb-3 border {{ $method['name'] === 'QR Code Payment' ? 'border-primary bg-light-subtle' : 'bg-light' }}">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="fw-semibold mb-1">
                                            <i
                                                class="{{ $method['icon'] }} me-2 {{ $method['name'] === 'QR Code Payment' ? 'text-primary' : 'text-dark' }}"></i>
                                            {{ $method['name'] }}
                                        </div>
                                        <div class="text-muted small">{{ $method['desc'] }}</div>
                                        <div class="text-muted small">Processing Fee: {{ $method['fee'] }}</div>
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-link btn-sm text-secondary px-1">⚙️ Settings</button>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                {{ $method['active'] ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                @if ($method['name'] === 'QR Code Payment')
                                    <hr>
                                    <div class="text-primary fw-semibold mb-1">QR Code Configuration</div>
                                    <div class="small text-muted mb-2">
                                        Bank: Chase Bank<br>
                                        Account: ****1234<br>
                                        Name: Time2Play Inc.
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i> Upload New
                                        </button>
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fas fa-pen me-1"></i> Update Details
                                        </button>
                                    </div>
                                    <div class="small text-success mt-2">Processing Fee: 1.5% (Lowest)</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            @include('inc.footer')
        </div>

        <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html> --}}



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Subscription Control</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap & FontAwesome & AdminLTE -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('public/assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ url('public/assets/dist/css/adminlte.min.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ url('public/assets/dist/img/favicon.png') }}" type="image/x-icon">

    <style>
        .toggle-switch {
            width: 45px;
            height: 24px;
        }

        .box-highlight {
            border: 2px solid #0d6efd;
            background: #f0f8ff;
        }

        .badge-circle {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    </style>
    <style>
        .card-loading-overlay {
            position: absolute;
            inset: 0;
            z-index: 5;
            background: rgba(255, 255, 255, .7);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: .5rem;
        }

        .card-loading-overlay .spinner-border {
            width: 2.5rem;
            height: 2.5rem;
        }
    </style>

    <!-- Firebase (compat) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('inc.header')
        @include('inc.aside')

        <div class="content-wrapper p-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold">Subscription Control</h3>
                    <p class="text-muted">Manage subscription plans, pricing, and payment methods</p>
                </div>
                <button id="saveAllBtn" class="btn btn-primary"><i class="fas fa-save me-2"></i> Save All
                    Changes</button>
            </div>

            <!-- Stats (static UI as provided) -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="p-4 rounded-3 border border-primary" style="background-color:#eef4ff;">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="text-primary fw-bold">Active Subscriptions</h6>
                            <i class="fas fa-users text-primary"></i>
                        </div>
                        <h3 class="fw-bold text-primary mt-3">1,247</h3>
                        <div class="text-primary small">+12% from last month</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-3 border border-success" style="background-color:#e6f9f0;">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="text-success fw-bold">Monthly Revenue</h6>
                            <i class="fas fa-dollar-sign text-success"></i>
                        </div>
                        <h3 class="fw-bold text-success mt-3">$18,450</h3>
                        <div class="text-success small">+8% from last month</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-3 border border-warning" style="background-color:#fff5d1;">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="text-warning fw-bold">Pro Plan Uptake</h6>
                            <i class="fas fa-chart-line text-warning"></i>
                        </div>
                        <h3 class="fw-bold text-warning mt-3">23%</h3>
                        <div class="text-warning small">Free: 77% | Pro: 23%</div>
                    </div>
                </div>
            </div>
            <script>
                /* Pre-hydrate tiles: set 0 values and show a tiny spinner ASAP (no DOMContentLoaded wait) */
                (function() {
                    function prep(title, mainText) {
                        const h6 = Array.from(document.querySelectorAll('.row.g-3.mb-4 h6'))
                            .find(el => el.textContent.trim() === title);
                        if (!h6) return;
                        const card = h6.closest('.p-4');
                        const h3 = card?.querySelector('h3');
                        const small = card?.querySelector('div.small');

                        if (h3) {
                            h3.textContent = mainText;
                            // add a small spinner next to the number
                            const sp = document.createElement('span');
                            sp.className = 'spinner-border spinner-border-sm text-secondary ms-2';
                            sp.setAttribute('role', 'status');
                            sp.setAttribute('data-tile-spinner', title);
                            h3.after(sp);
                        }
                        if (small) small.textContent = 'Loading…';
                    }

                    prep('Active Subscriptions', '0');
                    prep('Monthly Revenue', '$0.00');
                    prep('Pro Plan Uptake', '0%');
                })();
            </script>

            <div class="row g-4">
                <!-- Subscription Plan Settings -->
                <div class="col-lg-8">
                    <div id="plansCard" class="bg-white border rounded p-4 mb-4 position-relative">
                        <!-- loader -->
                        <div id="plansLoader" class="card-loading-overlay d-none">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <div class="mt-2 small text-primary" id="plansLoaderMsg">Loading plans…</div>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-3">Subscription Plan Settings</h5>
                        <div class="row g-3">
                            <!-- Free plan -->
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-bold text-success"><i class="fas fa-leaf me-1"></i> Free Plan
                                        </div>
                                        <span id="freeStatusBadge" class="badge bg-success">Active</span>
                                    </div>
                                    <p class="text-muted small">Basic access for all users</p>
                                    <ul id="freeFeatures" class="small text-muted mb-3">
                                        <!-- filled by JS from Firestore -->
                                    </ul>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('subscription.plan.edit', ['type' => 'free']) }}"
                                            class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <a href="{{ route('subscription.plan.edit', ['type' => 'free']) }}"
                                            class="btn btn-sm btn-outline-primary">+ Add</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Pro plan -->
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100 box-highlight">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-bold text-primary"><i class="fas fa-crown me-1"></i> Pro Plan
                                        </div>
                                        <span id="proStatusBadge" class="badge bg-primary">Active</span>
                                    </div>
                                    <p class="text-muted small">Premium features & benefits</p>
                                    <ul id="proFeatures" class="small text-muted mb-3">
                                        <!-- filled by JS from Firestore -->
                                    </ul>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('subscription.plan.edit', ['type' => 'pro']) }}"
                                            class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <a href="{{ route('subscription.plan.edit', ['type' => 'pro']) }}"
                                            class="btn btn-sm btn-outline-primary">+ Add</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Price Management -->
                    <div id="pricesCard" class="bg-white border rounded p-4 position-relative">
                        <!-- loader -->
                        <div id="pricesLoader" class="card-loading-overlay d-none">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <div class="mt-2 small text-primary" id="pricesLoaderMsg">Loading prices…</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold">Subscription Price Management</h5>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">USD</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">USD</a></li>
                                    <li><a class="dropdown-item" href="#">PKR</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Monthly -->
                            <div class="col-md-6">
                                <div class="border rounded-3 p-4 bg-white position-relative shadow-sm">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="fw-bold mb-0">Monthly Subscription</h5>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                    <p class="text-muted small mb-2">Set Monthly Price</p>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-white border-end-0"><i
                                                class="fas fa-dollar-sign"></i></span>
                                        <input id="monthlyPriceInput" type="text"
                                            class="form-control border-start-0 border-end-0 text-center fw-semibold"
                                            value="5.99">
                                        <span class="input-group-text bg-white border-start-0">/ month</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button id="monthlyUpdateBtn" class="btn btn-primary w-100"><i
                                                class="fas fa-check me-1"></i> Update</button>
                                        <button id="monthlyResetBtn" class="btn btn-outline-secondary w-100"><i
                                                class="fas fa-rotate-left me-1"></i> Reset</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Yearly -->
                            <div class="col-md-6">
                                <div class="border border-success rounded-3 p-4 bg-light position-relative shadow-sm">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="fw-bold mb-0 text-success">Annual Subscription</h5>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                    <p class="text-muted small mb-2">Set Annual Price</p>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text bg-white border-end-0"><i
                                                class="fas fa-dollar-sign"></i></span>
                                        <input id="yearlyPriceInput" type="text"
                                            class="form-control border-start-0 border-end-0 text-center fw-semibold"
                                            value="59.99">
                                        <span class="input-group-text bg-white border-start-0">/ year</span>
                                    </div>
                                    <div class="text-success small mb-3">Save $11.89 compared to monthly</div>
                                    <div class="d-flex gap-2">
                                        <button id="yearlyUpdateBtn" class="btn btn-success w-100"><i
                                                class="fas fa-check me-1"></i> Update</button>
                                        <button id="yearlyResetBtn" class="btn btn-outline-secondary w-100"><i
                                                class="fas fa-rotate-left me-1"></i> Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Payment Method Control -->
                <div class="col-lg-4">
                    <div id="paymentCard" class="bg-white border rounded-4 p-4 shadow-sm position-relative">
                        <!-- loader -->
                        <div id="paymentLoader" class="card-loading-overlay d-none">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status"></div>
                                <div class="mt-2 small text-primary" id="paymentLoaderMsg">Loading methods…</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Payment Method Control</h5>
                            <span id="activeMethodsBadge" class="badge bg-success">0 Active</span>
                        </div>

                        @php
                            $methods = [
                                [
                                    'type' => 'googlePay',
                                    'icon' => 'fab fa-google',
                                    'name' => 'Google Pay',
                                    'desc' => 'Digital wallet payment',
                                    'fee' => '2.9%',
                                    'active' => true,
                                ],
                                [
                                    'type' => 'applePay',
                                    'icon' => 'fab fa-apple',
                                    'name' => 'Apple Pay',
                                    'desc' => 'iOS secure payment',
                                    'fee' => '2.9%',
                                    'active' => true,
                                ],
                                [
                                    'type' => 'creditCard',
                                    'icon' => 'fas fa-credit-card',
                                    'name' => 'Credit/Debit Card',
                                    'desc' => 'Visa, Mastercard, Amex',
                                    'fee' => '3.4%',
                                    'active' => true,
                                ],
                                [
                                    'type' => 'qrCode',
                                    'icon' => 'fas fa-qrcode',
                                    'name' => 'QR Code Payment',
                                    'desc' => 'Scan to pay system',
                                    'fee' => '1.5%',
                                    'active' => true,
                                ],
                                [
                                    'type' => 'time2PlayWallet',
                                    'icon' => 'fas fa-wallet',
                                    'name' => 'Time2Play Wallet',
                                    'desc' => 'In-app digital wallet',
                                    'fee' => '0% (Internal)',
                                    'active' => false,
                                ],
                            ];
                        @endphp

                        @foreach ($methods as $method)
                            <div
                                class="rounded-3 p-3 mb-3 border {{ $method['name'] === 'QR Code Payment' ? 'border-primary bg-light-subtle' : 'bg-light' }}">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="fw-semibold mb-1">
                                            <i
                                                class="{{ $method['icon'] }} me-2 {{ $method['name'] === 'QR Code Payment' ? 'text-primary' : 'text-dark' }}"></i>
                                            {{ $method['name'] }}
                                        </div>
                                        <div class="text-muted small">{{ $method['desc'] }}</div>
                                        <div class="text-muted small">Processing Fee: {{ $method['fee'] }}</div>
                                    </div>
                                    <div class="text-end">
                                        <!-- settings button removed as requested -->
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                data-method-type="{{ $method['type'] }}"
                                                {{ $method['active'] ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                @if ($method['name'] === 'QR Code Payment')
                                    <hr>
                                    <div class="text-primary fw-semibold mb-1">QR Code Configuration</div>
                                    <div id="qrMeta" class="small text-muted mb-2">Bank: Chase Bank<br>Account:
                                        ****1234<br>Name: Time2Play Inc.</div>

                                    <div class="d-flex gap-2 align-items-center">
                                        <button id="qrUploadBtn" class="btn btn-outline-primary btn-sm"
                                            type="button">
                                            <i class="fas fa-upload me-1"></i> Upload New
                                        </button>
                                        <button id="qrUpdateBtn" class="btn btn-primary btn-sm" type="button">
                                            <i class="fas fa-pen me-1"></i> Update Details
                                        </button>
                                        <input id="qrUploadInput" type="file" accept="image/*"
                                            style="display:none">
                                    </div>

                                    <div id="qrPreviewWrap" class="mt-2" style="display:none;">
                                        <img id="qrPreview" src="" alt="QR Code" class="img-thumbnail"
                                            style="max-height:100px;">
                                        <small id="qrPreviewNote" class="text-muted d-block mt-1"></small>
                                    </div>

                                    <div id="qrSavedNote" class="small text-success mt-2" style="display:none;">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @include('inc.footer')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('public/assets/dist/js/adminlte.min.js') }}"></script>
    <!-- Global page loader -->
    <div id="pageLoader" class="position-fixed top-0 start-0 w-100 h-100 d-none"
        style="background:rgba(255,255,255,.65); z-index:2000;">
        <div class="position-absolute top-50 start-50 translate-middle text-center">
            <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;"></div>
            <div id="pageLoaderMsg" class="mt-2 text-primary small">Loading…</div>
        </div>
    </div>

    <!-- Toast (auto dismiss) -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:2000;">
        <div id="actionToast" class="toast text-white bg-success border-0" role="alert" aria-live="assertive"
            aria-atomic="true" data-bs-delay="3000">
            <div class="d-flex">
                <div id="actionToastBody" class="toast-body">Saved</div>
                {{-- <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button> --}}
            </div>
        </div>
    </div>
    <script>
        // --- card loader helpers ---
        function showCardLoader(which, msg) {
            const map = {
                plans: ['plansLoader', 'plansLoaderMsg'],
                prices: ['pricesLoader', 'pricesLoaderMsg'],
                pay: ['paymentLoader', 'paymentLoaderMsg'],
            };
            const ids = map[which];
            if (!ids) return;
            const [loaderId, msgId] = ids;
            const el = document.getElementById(loaderId);
            const msgEl = document.getElementById(msgId);
            if (msg && msgEl) msgEl.textContent = msg;
            el?.classList.remove('d-none');
        }

        function hideCardLoader(which) {
            const map = {
                plans: 'plansLoader',
                prices: 'pricesLoader',
                pay: 'paymentLoader'
            };
            const el = document.getElementById(map[which]);
            el?.classList.add('d-none');
        }
    </script>

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

        // ---------- Helpers: loader + toast + button spinner ----------
        function showPageLoader(msg = 'Loading…') {
            document.getElementById('pageLoaderMsg').textContent = msg;
            document.getElementById('pageLoader').classList.remove('d-none');
        }

        function hidePageLoader() {
            document.getElementById('pageLoader').classList.add('d-none');
        }

        function showToast(msg = 'Saved', type = 'success') {
            const el = document.getElementById('actionToast');
            const body = document.getElementById('actionToastBody');
            body.textContent = msg;
            el.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info');
            el.classList.add(type === 'error' ? 'bg-danger' : 'bg-success');
            const t = new bootstrap.Toast(el);
            t.show();
        }
        async function withBtnSpinner(btn, doingText, fn) {
            const original = btn.innerHTML;
            const wasDisabled = btn.disabled;
            btn.disabled = true;
            btn.innerHTML =
                `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${doingText}`;
            try {
                await fn();
            } finally {
                btn.disabled = wasDisabled;
                btn.innerHTML = original;
            }
        }

        // ---------- Defaults ----------
        const DEFAULT_FEATURES = {
            free: ["Join Free Events", "Browse Local Sports Feed", "Access Public Groups"],
            pro: ["Join Premium & Paid Events", "Create & Host Events", "View Event Stats & Insights",
                "Access Private Sports Groups", "Priority Support Access"
            ]
        };
        const DEFAULT_PRICES = {
            monthly: 5.99,
            yearly: 59.99
        };
        const CURRENCY = "USD";
        const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [c]));

        // ---------- Ensure docs exist ----------
        async function ensurePlanDoc(type) {
            const ref = db.collection('subscriptionPlan').doc(type);
            const snap = await ref.get();
            if (!snap.exists) {
                await ref.set({
                    type,
                    status: 'active',
                    features: DEFAULT_FEATURES[type] || [],
                    createdAt: firebase.firestore.FieldValue.serverTimestamp()
                }, {
                    merge: true
                });
            }
            return ref.get();
        }
        async function ensurePriceDoc(type) {
            const ref = db.collection('subscriptionPriceManagement').doc(type);
            const snap = await ref.get();
            if (!snap.exists) {
                await ref.set({
                    type,
                    price: DEFAULT_PRICES[type],
                    currency: CURRENCY,
                    createdAt: firebase.firestore.FieldValue.serverTimestamp()
                }, {
                    merge: true
                });
            }
            return ref.get();
        }
        async function ensurePaymentDoc(type, activeDefault) {
            const ref = db.collection('paymentMethodControl').doc(type);
            const snap = await ref.get();
            if (!snap.exists) {
                await ref.set({
                    type,
                    status: activeDefault ? 'active' : 'block',
                    qrCodeImage: type === 'qrCode' ? '' : '',
                    createdAt: firebase.firestore.FieldValue.serverTimestamp()
                }, {
                    merge: true
                });
            }
            return ref.get();
        }

        // ---------- Plans UI ----------
        async function loadPlans() {
            const free = await ensurePlanDoc('free');
            const pro = await ensurePlanDoc('pro');
            const freeData = free.data();
            const proData = pro.data();

            const freeBadge = document.getElementById('freeStatusBadge');
            const proBadge = document.getElementById('proStatusBadge');
            if (freeData.status === 'block') {
                freeBadge.classList.remove('bg-success');
                freeBadge.classList.add('bg-secondary');
                freeBadge.textContent = 'Blocked';
            } else {
                freeBadge.classList.add('bg-success');
                freeBadge.classList.remove('bg-secondary');
                freeBadge.textContent = 'Active';
            }
            if (proData.status === 'block') {
                proBadge.classList.remove('bg-primary');
                proBadge.classList.add('bg-secondary');
                proBadge.textContent = 'Blocked';
            } else {
                proBadge.classList.add('bg-primary');
                proBadge.classList.remove('bg-secondary');
                proBadge.textContent = 'Active';
            }

            const freeList = document.getElementById('freeFeatures');
            const proList = document.getElementById('proFeatures');
            freeList.innerHTML = (freeData.features || []).map(f => `<li>${esc(f)}</li>`).join('') ||
                '<li class="text-muted">No features</li>';
            proList.innerHTML = (proData.features || []).map(f => `<li>${esc(f)}</li>`).join('') ||
                '<li class="text-muted">No features</li>';
        }

        // ---------- Price Management ----------
        async function loadPrices() {
            const monthly = await ensurePriceDoc('monthly');
            const yearly = await ensurePriceDoc('yearly');
            document.getElementById('monthlyPriceInput').value = Number(monthly.data().price || DEFAULT_PRICES.monthly)
                .toFixed(2);
            document.getElementById('yearlyPriceInput').value = Number(yearly.data().price || DEFAULT_PRICES.yearly)
                .toFixed(2);
        }
        async function updatePrice(type, value) {
            const price = parseFloat(String(value).replace(/[^\d.]/g, ''));
            if (isNaN(price) || price <= 0) {
                showToast('Enter a valid price', 'error');
                return;
            }
            await db.collection('subscriptionPriceManagement').doc(type).set({
                type,
                price,
                currency: CURRENCY,
                updatedAt: firebase.firestore.FieldValue.serverTimestamp()
            }, {
                merge: true
            });
            showToast('Price updated.');
        }
        async function resetPrice(type) {
            await db.collection('subscriptionPriceManagement').doc(type).set({
                type,
                price: DEFAULT_PRICES[type],
                currency: CURRENCY,
                updatedAt: firebase.firestore.FieldValue.serverTimestamp()
            }, {
                merge: true
            });
            document.getElementById(type + 'PriceInput').value = DEFAULT_PRICES[type].toFixed(2);
            showToast('Price reset.');
        }

        // ---------- Payment Methods ----------
        async function loadPaymentMethods() {
            const defaults = [
                ['googlePay', true],
                ['applePay', true],
                ['creditCard', true],
                ['qrCode', true],
                ['time2PlayWallet', false],
            ];
            const states = {};
            for (const [t, def] of defaults) {
                const snap = await ensurePaymentDoc(t, def);
                states[t] = snap.data();
                const el = document.querySelector(`.form-check-input[data-method-type="${t}"]`);
                if (el) el.checked = (states[t].status === 'active');
            }
            const activeCount = Object.values(states).filter(s => s.status === 'active').length;
            document.getElementById('activeMethodsBadge').textContent = `${activeCount} Active`;

            const qr = states['qrCode'];
            if (qr && qr.qrCodeImage) {
                const wrap = document.getElementById('qrPreviewWrap');
                const img = document.getElementById('qrPreview');
                img.src = qr.qrCodeImage;
                document.getElementById('qrPreviewNote').textContent = 'Saved QR code image';
                wrap.style.display = 'block';
                document.getElementById('qrSavedNote').style.display = 'none';
            }
        }

        // Toggle handler (show global loader while updating)
        document.addEventListener('change', async (e) => {
            const sw = e.target.closest('.form-check-input[data-method-type]');
            if (!sw) return;
            const type = sw.getAttribute('data-method-type');
            const status = sw.checked ? 'active' : 'block';
            const revertTo = !sw.checked;

            // OLD: showPageLoader('Updating…');
            showCardLoader('pay', 'Updating…'); // NEW
            sw.disabled = true;
            try {
                await db.collection('paymentMethodControl').doc(type).set({
                    type,
                    status,
                    updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                }, {
                    merge: true
                });
                const snap = await db.collection('paymentMethodControl').get();
                const active = snap.docs.map(d => d.data()).filter(d => d.status === 'active').length;
                document.getElementById('activeMethodsBadge').textContent = `${active} Active`;
                showToast('Updated.');
            } catch (err) {
                sw.checked = revertTo;
                showToast(err?.message || 'Update failed', 'error');
            } finally {
                sw.disabled = false;
                hideCardLoader('pay'); // NEW
            }
        });


        // QR upload & update (with button spinner)
        let qrSelectedFile = null;
        const qrInput = document.getElementById('qrUploadInput');
        const qrUploadBtn = document.getElementById('qrUploadBtn');
        const qrUpdateBtn = document.getElementById('qrUpdateBtn');

        if (qrUploadBtn) {
            qrUploadBtn.addEventListener('click', () => qrInput.click());
            qrInput?.addEventListener('change', (e) => {
                const f = e.target.files && e.target.files[0];
                qrSelectedFile = f || null;
                if (qrSelectedFile) {
                    const wrap = document.getElementById('qrPreviewWrap');
                    const img = document.getElementById('qrPreview');
                    img.src = URL.createObjectURL(qrSelectedFile);
                    document.getElementById('qrPreviewNote').textContent = 'New image (not saved yet)';
                    wrap.style.display = 'block';
                }
            });
        }
        if (qrUpdateBtn) {
            qrUpdateBtn.addEventListener('click', async () => {
                if (!qrSelectedFile) {
                    showToast('Please choose an image first.', 'error');
                    return;
                }
                await withBtnSpinner(qrUpdateBtn, 'Saving…', async () => {
                    showCardLoader('pay', 'Saving QR…'); // NEW
                    try {
                        const fd = new FormData();
                        fd.append('icon', qrSelectedFile);
                        const res = await fetch("{{ route('sports.icon.upload') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf
                            },
                            body: fd
                        });
                        const out = await res.json();
                        if (!res.ok || !out.success) throw new Error(out?.message ||
                            'Upload failed');

                        const url = out.url;
                        await db.collection('paymentMethodControl').doc('qrCode').set({
                            type: 'qrCode',
                            qrCodeImage: url,
                            updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                        }, {
                            merge: true
                        });

                        const wrap = document.getElementById('qrPreviewWrap');
                        const img = document.getElementById('qrPreview');
                        img.src = url;
                        document.getElementById('qrPreviewNote').textContent =
                            'Saved QR code image';
                        wrap.style.display = 'block';
                        const saved = document.getElementById('qrSavedNote');
                        saved.textContent = 'QR Code image saved.';
                        saved.style.display = 'block';
                        qrSelectedFile = null;
                        qrInput.value = '';
                        showToast('QR updated.');
                    } finally {
                        hideCardLoader('pay'); // NEW
                    }
                });
            });
        }

        // Price buttons (with button spinner)
        const monthlyUpdateBtn = document.getElementById('monthlyUpdateBtn');
        const yearlyUpdateBtn = document.getElementById('yearlyUpdateBtn');
        const monthlyResetBtn = document.getElementById('monthlyResetBtn');
        const yearlyResetBtn = document.getElementById('yearlyResetBtn');

        monthlyUpdateBtn.addEventListener('click', () =>
            withBtnSpinner(monthlyUpdateBtn, 'Updating…', async () => {
                showCardLoader('prices', 'Updating price…');
                try {
                    await updatePrice('monthly', document.getElementById('monthlyPriceInput').value);
                } finally {
                    hideCardLoader('prices');
                }
            })
        );

        yearlyUpdateBtn.addEventListener('click', () =>
            withBtnSpinner(yearlyUpdateBtn, 'Updating…', async () => {
                showCardLoader('prices', 'Updating price…');
                try {
                    await updatePrice('yearly', document.getElementById('yearlyPriceInput').value);
                } finally {
                    hideCardLoader('prices');
                }
            })
        );

        monthlyResetBtn.addEventListener('click', () =>
            withBtnSpinner(monthlyResetBtn, 'Resetting…', async () => {
                showCardLoader('prices', 'Resetting…');
                try {
                    await resetPrice('monthly');
                } finally {
                    hideCardLoader('prices');
                }
            })
        );

        yearlyResetBtn.addEventListener('click', () =>
            withBtnSpinner(yearlyResetBtn, 'Resetting…', async () => {
                showCardLoader('prices', 'Resetting…');
                try {
                    await resetPrice('yearly');
                } finally {
                    hideCardLoader('prices');
                }
            })
        );

        // Save All Changes -> show toast, then reload after 3s
        document.getElementById('saveAllBtn').addEventListener('click', () => {
            showToast('Saved');
            // setTimeout(() => location.reload(), 3000);
        });

        // First load with global loader
        (async function init() {
            // show all three while their sections load
            showCardLoader('plans', 'Loading plans…');
            showCardLoader('prices', 'Loading prices…');
            showCardLoader('pay', 'Loading methods…');
            try {
                await loadPlans();
                hideCardLoader('plans');
                await loadPrices();
                hideCardLoader('prices');
                await loadPaymentMethods();
                hideCardLoader('pay');
            } catch (e) {
                // still hide if any error
                hideCardLoader('plans');
                hideCardLoader('prices');
                hideCardLoader('pay');
            }
        })();
    </script>
    <script>
        // Reuse existing Firestore instance
        const _db = (typeof db !== 'undefined' ? db : firebase.firestore());

        // --- helpers to find tiles by their <h6> title ---
        function getTile(title) {
            const h6 = Array.from(document.querySelectorAll('.row.g-3.mb-4 h6'))
                .find(el => el.textContent.trim() === title);
            if (!h6) return null;
            const card = h6.closest('.p-4');
            return {
                card,
                h3: card.querySelector('h3'),
                small: card.querySelector('div.small'),
                title
            };
        }

        function clearSpinner(tile) {
            if (!tile?.h3) return;
            const sib = tile.h3.nextElementSibling;
            if (sib && sib.classList.contains('spinner-border')) sib.remove();
        }

        // month range in UTC (consistent)
        function monthRangeUTC(date) {
            const y = date.getUTCFullYear(),
                m = date.getUTCMonth();
            const from = new Date(Date.UTC(y, m, 1));
            const to = new Date(Date.UTC(y, m + 1, 1));
            const prevFrom = new Date(Date.UTC(y, m - 1, 1));
            const prevTo = from;
            return {
                from,
                to,
                prevFrom,
                prevTo
            };
        }

        // Two single-field timestamp queries (avoids composite indices)
        async function getSubsInRangeUTC(from, to) {
            const [a, b] = await Promise.all([
                _db.collection('subscriptions')
                .where('subscribedAt', '>=', from).where('subscribedAt', '<', to).get(),
                _db.collection('subscriptions')
                .where('startDate', '>=', from).where('startDate', '<', to).get()
            ]);
            const seen = new Set(),
                out = [];
            a.forEach(doc => {
                if (!seen.has(doc.id)) {
                    seen.add(doc.id);
                    out.push(doc.data());
                }
            });
            b.forEach(doc => {
                if (!seen.has(doc.id)) {
                    seen.add(doc.id);
                    out.push(doc.data());
                }
            });
            return out;
        }

        function sumRevenue(rows) {
            let s = 0;
            for (const d of rows) {
                const n = Number(d.totalAmount ?? d.proPackageAmount ?? 0);
                if (Number.isFinite(n)) s += n;
            }
            return s;
        }

        function isPro(d) {
            const n = Number(d.totalAmount ?? d.proPackageAmount ?? 0);
            return (Number.isFinite(n) && n > 0) || !!(d.subscriptionType || d.proPackageType);
        }

        function fmtMoney(n) {
            return Number(n || 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function fmtPct(cur, prev) {
            if (!isFinite(prev) || prev <= 0) return '—';
            const p = ((cur - prev) / prev) * 100;
            return (p >= 0 ? '+' : '') + p.toFixed(1) + '%';
        }

        async function refreshSubTiles() {
            const tileActive = getTile('Active Subscriptions');
            const tileRevenue = getTile('Monthly Revenue');
            const tilePro = getTile('Pro Plan Uptake');

            const {
                from,
                to,
                prevFrom,
                prevTo
            } = monthRangeUTC(new Date());

            // Active subs (simple equality)
            const actSnap = await _db.collection('subscriptions').where('isActive', '==', true).get();
            const activeDocs = actSnap.docs.map(d => d.data());
            const activeCount = activeDocs.length;

            // Current vs previous month
            const [curRows, prevRows] = await Promise.all([
                getSubsInRangeUTC(from, to),
                getSubsInRangeUTC(prevFrom, prevTo)
            ]);

            // deltas for "Active Subscriptions"
            const curNewActives = curRows.filter(d => d.isActive === true).length;
            const prevNewActives = prevRows.filter(d => d.isActive === true).length;
            const activeDelta = fmtPct(curNewActives, prevNewActives);

            // revenue
            const curRevenue = sumRevenue(curRows);
            const prevRevenue = sumRevenue(prevRows);
            const revenueDelta = fmtPct(curRevenue, prevRevenue);

            // pro uptake among actives
            const proActive = activeDocs.filter(isPro).length;
            const proPct = activeCount > 0 ? Math.round(proActive / activeCount * 100) : 0;
            const freePct = 100 - proPct;

            // paint values; remove the tiny spinner we added in step 1
            if (tileActive) {
                tileActive.h3.textContent = activeCount.toLocaleString();
                tileActive.small.textContent = `${activeDelta} from last month`;
                clearSpinner(tileActive);
            }
            if (tileRevenue) {
                tileRevenue.h3.textContent = `$${fmtMoney(curRevenue)}`;
                tileRevenue.small.textContent = `${revenueDelta} from last month`;
                clearSpinner(tileRevenue);
            }
            if (tilePro) {
                tilePro.h3.textContent = `${proPct}%`;
                tilePro.small.textContent = `Free: ${freePct}% | Pro: ${proPct}%`;
                clearSpinner(tilePro);
            }
        }

        // fetch after the page finishes parsing
        document.addEventListener('DOMContentLoaded', () => {
            refreshSubTiles().catch(console.error);
        });
    </script>

</body>

</html>

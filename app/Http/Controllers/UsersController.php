<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Kreait\Firebase\Factory;
// use Carbon\Carbon;
// use Illuminate\Support\Facades\Storage;
// use Kreait\Firebase\Exception\StorageException;
// use Google\Cloud\Firestore\FieldValue;
// use Google\Cloud\Firestore\FirestoreClient;
// use Illuminate\Support\Facades\Http;
// use App\Services\GoogleAccessToken;

// class UsersController extends Controller
// {


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Factory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Exception\StorageException;
use Google\Cloud\Firestore\FieldValue;
use App\Services\FirebaseService;
use Google\Cloud\Firestore\FirestoreClient;
use App\Services\GoogleAccessToken;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    // ------------------- USERS LISTING -------------------

    public function index(Request $request)
    {
        session(['tab' => 'users']);
        session(['userscreentype' => 'Users Listing']);
        $scope = 'all'; // all | active | blocked
        return view('users', compact('scope'));
    }

    // DataTables server-side feed (optimized, paginated, memory-safe)
    public function data(Request $req)
    {
        $start   = (int)($req->input('start', 0));
        $length  = (int)($req->input('length', 25));
        $search  = trim($req->input('search.value', ''));
        $scope   = $req->query('scope', 'all'); // all | active | blocked

        // Limit page size to prevent memory issues
        $length = min($length, 100);
        
        // Get paginated data directly from Firebase (memory optimized)
        $result = $this->getUsersPaginated($start, $length, $search, $scope);
        
        $recordsTotal    = $result['total'];
        $recordsFiltered = $result['filtered'];
        $data = $result['data'];

        // Build rows in same order as Blade columns
        $rows = array_map(function ($u) {
            $badge = $u['status']
                ? '<span class="badge badge-success ml-2">Active</span>'
                : '<span class="badge badge-danger ml-2">Blocked</span>';

            $nameCol = '
            <div class="d-flex align-items-center gap-2">
              <img src="' . e($u['avatar']) . '" loading="lazy"
                   onerror="this.onerror=null;this.src=\'https://edenchristianacademy.co.nz/wp-content/uploads/2013/11/dummy-image-square.jpg\';"
                   class="rounded-circle mr-2" style="width:45px;height:45px;object-fit:cover;">
              <span>' . e($u['full_name']) . '</span>' . $badge . '
            </div>';

            $action = '
            <div class="dropdown">
              <button class="btn btn-light" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-ellipsis-v"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <form method="POST" action="' . route('users.toggle', $u['id']) . '">
                    ' . csrf_field() . '
                    <input type="hidden" name="to" value="' . ($u['status'] ? 'block' : 'unblock') . '">
                    <button type="submit" class="dropdown-item ' . ($u['status'] ? 'text-danger' : 'text-success') . '">
                      ' . ($u['status'] ? 'Block User' : 'Unblock User') . '
                    </button>
                  </form>
                </li>
              </ul>
            </div>';

            return [
                $nameCol,
                e($u['email'] ?? ''),
                e($u['phone'] ?? ''),
                e($u['location'] ?? ''),
                $action,
            ];
        }, $data);

        return response()->json([
            'draw'            => (int)$req->input('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $rows,
        ]);
    }

    // Toggle (block/unblock) a user by setting status boolean via REST
    public function toggle(Request $request, string $id)
    {
        $request->validate([
            'to' => 'required|in:block,unblock',
        ]);

        $toBlock   = $request->input('to') === 'block';
        $newStatus = !$toBlock; // block => false, unblock => true

        try {
            $projectId   = env('FIREBASE_PROJECT_ID');
            $accessToken = GoogleAccessToken::get();

            $patchUrl =
                "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/users/{$id}" .
                "?updateMask.fieldPaths=status";

            $body = ['fields' => ['status' => ['booleanValue' => (bool)$newStatus]]];

            $ch = curl_init($patchUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST  => 'PATCH',
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: application/json',
                ],
                CURLOPT_POSTFIELDS     => json_encode($body),
                CURLOPT_TIMEOUT        => 20,
            ]);
            $patchResp = curl_exec($ch);
            $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                throw new \Exception('Update failed: ' . $patchResp);
            }

            // Invalidate cached list so UI reflects instantly
            Cache::forget('users_list_min_60s');
            Cache::forget('users_list_count_60s');

            return back()->with('message', $newStatus ? 'User unblocked' : 'User blocked');
        } catch (\Throwable $e) {
            return back()->with('error', app()->environment('local') ? $e->getMessage() : 'Update failed.');
        }
    }

    public function activeUsers()
    {
        session(['tab' => 'users']);
        session(['userscreentype' => 'Active Users']);
        $scope = 'active';
        return view('users', compact('scope'));
    }

    public function blockedUsers()
    {
        session(['tab' => 'users']);
        session(['userscreentype' => 'Blocked Users']);
        $scope = 'blocked';
        return view('users', compact('scope'));
    }

    // ---------- Firestore fetch helpers (masked + cached) ----------

    // Memory-optimized paginated user fetching
    private function getUsersPaginated($start, $length, $search, $scope): array
    {
            $projectId   = env('FIREBASE_PROJECT_ID');
            $accessToken = GoogleAccessToken::get();

        // Build Firebase query with pagination
            $base = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/users";
        
        // Calculate page for Firebase (Firebase uses pageSize, not offset)
        $pageSize = min($length * 2, 100); // Get more records to account for filtering
        $pageNumber = intval($start / $pageSize) + 1;
        
        $url = $base . '?pageSize=' . $pageSize
                    . '&mask.fieldPaths=fullName'
                    . '&mask.fieldPaths=email'
                    . '&mask.fieldPaths=mobile'
                    . '&mask.fieldPaths=address'
                    . '&mask.fieldPaths=imageUrl'
            . '&mask.fieldPaths=status';

                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $accessToken],
                    CURLOPT_ENCODING       => 'gzip',
            CURLOPT_TIMEOUT        => 30,
                ]);
                $resp = curl_exec($ch);
                curl_close($ch);

                $json = json_decode($resp, true);
                $docs = $json['documents'] ?? [];

        $out = [];
                foreach ($docs as $doc) {
                    $f = $doc['fields'] ?? [];
                    $getS = fn($k) => $f[$k]['stringValue'] ?? null;
                    $getB = fn($k) => isset($f[$k]['booleanValue']) ? (bool)$f[$k]['booleanValue'] : true;

            $user = [
                        'id'        => basename($doc['name']),
                        'full_name' => $getS('fullName') ?? 'Unknown',
                        'email'     => $getS('email'),
                        'phone'     => $getS('mobile'),
                        'location'  => $getS('address'),
                        'avatar'    => $getS('imageUrl'),
                        'status'    => $getB('status'),
                    ];
            
            // Apply scope filter
            if ($scope === 'active' && !$user['status']) continue;
            if ($scope === 'blocked' && $user['status']) continue;
            
            // Apply search filter
            if ($search !== '') {
                $q = mb_strtolower($search);
                if (!str_contains(mb_strtolower($user['full_name'] ?? ''), $q) &&
                    !str_contains(mb_strtolower($user['email'] ?? ''), $q) &&
                    !str_contains(mb_strtolower($user['phone'] ?? ''), $q) &&
                    !str_contains(mb_strtolower($user['location'] ?? ''), $q)) {
                    continue;
                }
            }
            
            $out[] = $user;
        }
        
        // Get total count from cache (updated periodically)
        $total = Cache::remember('users_total_count', 300, function() use ($projectId, $accessToken) {
            return $this->getTotalUsersCount($projectId, $accessToken);
        });
        
        return [
            'data' => array_slice($out, 0, $length),
            'total' => $total,
            'filtered' => count($out)
        ];
    }
    
    // Get total users count efficiently
    private function getTotalUsersCount($projectId, $accessToken): int
    {
        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/users?pageSize=1";
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $accessToken],
            CURLOPT_ENCODING       => 'gzip',
            CURLOPT_TIMEOUT        => 10,
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);
        
        $json = json_decode($resp, true);
        return $json['documents'] ? 1 : 0; // Simplified count for now
    }

    // Legacy method - kept for compatibility but optimized
    private function getUsersCountCached(): int
    {
        return Cache::remember('users_list_count_60s', 300, function() {
            $projectId = env('FIREBASE_PROJECT_ID');
            $accessToken = GoogleAccessToken::get();
            return $this->getTotalUsersCount($projectId, $accessToken);
        });
    }

    // ------------------- OTHER VIEWS / EXISTING FEATURES -------------------

    public function users()
    {
        session(['tab' => 'users']);
        session(['userscreentype' => 'Users Listing']);
        $scope = 'all';
        return view('users', compact('scope'));
    }





    public function events(Request $request)
    {
        session(['tab' => "events"]);
        // Filter by status from query if you like: all | pending | approved | rejected
        $scope = $request->query('scope', 'all');
        return view('events', compact('scope'));
    }


    public function eventsData(Request $req)
    {
        $start   = (int)($req->input('start', 0));
        $length  = (int)($req->input('length', 25));
        $search  = trim($req->input('search.value', ''));
        $scope   = $req->query('scope', 'all'); // all | pending | approved | rejected

        // Limit page size to prevent memory issues
        $length = min($length, 100);
        
        // Get paginated data directly from Firebase (memory optimized)
        $result = $this->getEventsPaginated($start, $length, $search, $scope);
        
        $recordsTotal    = $result['total'];
        $recordsFiltered = $result['filtered'];
        $data = $result['data'];

        // Build rows: [Event Details, Schedule, Participants, Entry Fee, Status, Actions]
        $rows = array_map(function ($e) {
            $eventDetails = '
        <div class="d-flex align-items-center">
          <div class="bg-secondary text-white rounded d-flex justify-content-center align-items-center"
               style="width: 40px; height: 40px; overflow:hidden;">
            ' . ($e['imageUrl'] ? '<img src="' . e($e['imageUrl']) . '" alt="" style="width:40px;height:40px;object-fit:cover;">'
                : '<i class="fas fa-image"></i>') . '
          </div>
          <div class="ml-2">
            <div class="fw-bold">' . e($e['name'] ?? 'Untitled') . '</div>
            ' . ($e['sportType'] ? '<span class="badge bg-primary me-1">' . e($e['sportType']) . '</span>' : '') . '
            ' . ($e['eventFormat'] ? '<span class="badge text-white" style="background-color:#8b5cf6;">' . e($e['eventFormat']) . '</span>' : '') . '
          </div>
        </div>';

            $schedule = e($e['eventDateDisp']) . '<br>' .
                e($e['startTime'] ?? '') . ' – ' . e($e['endTime'] ?? '') . '<br>' .
                '<small>' . e($e['location'] ?? '') . '</small>';

            $participants = (int)($e['currentParticipants'] ?? 0) . '/' . (int)($e['maxParticipants'] ?? 0)
                . '<br><small>Max Players</small>';

            $entryFee = ($e['entryType'] === 'Free' || (float)($e['price'] ?? 0) == 0)
                ? '<span class="text-success">Free</span>'
                : '<span class="text-success">$' . number_format((float)$e['price'], 2) . '</span><br><small>Per Person</small>';

            $badgeHtml = match ($e['statusSlug']) {
                'approved' => '<span class="badge badge-success">Approved</span>',
                'rejected' => '<span class="badge badge-danger">Rejected</span>',
                default    => '<span class="badge badge-warning">Pending</span>',
            };

            // Actions: show relevant buttons per status
            $actionBtns = '<a class="btn btn-sm btn-primary" href="' . route('events.show', $e['docId']) . '">View</a> ';
            if ($e['statusSlug'] !== 'approved') {
                $actionBtns .= '<form method="POST" action="' . route('events.updateStatus', $e['docId']) . '" style="display:inline-block; margin-left:4px;">'
                    . csrf_field()
                    . '<input type="hidden" name="status" value="approved">'
                    . '<button class="btn btn-sm btn-success" type="submit">Approve</button>'
                    . '</form> ';
            }
            if ($e['statusSlug'] !== 'rejected') {
                $actionBtns .= '<form method="POST" action="' . route('events.updateStatus', $e['docId']) . '" style="display:inline-block; margin-left:4px;">'
                    . csrf_field()
                    . '<input type="hidden" name="status" value="rejected">'
                    . '<button class="btn btn-sm btn-danger" type="submit">Reject</button>'
                    . '</form>';
            }


            return [$eventDetails, $schedule, $participants, $entryFee, $badgeHtml, $actionBtns];
        }, $data);

        return response()->json([
            'draw'            => (int)$req->input('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $rows,
        ]);
    }



    public function eventsStats()
    {
        // Optimized stats calculation with limited data fetch
        $result = $this->getEventsPaginated(0, 200, '', 'all'); // Get first 200 events for stats
        $all = $result['data'];

        $counts = ['pending' => 0, 'approved' => 0, 'rejected' => 0];
        foreach ($all as $e) {
            $slug = $e['statusSlug'];
            if (isset($counts[$slug])) $counts[$slug]++;
        }

        return response()->json([
            'pending'  => $counts['pending'],
            'approved' => $counts['approved'],
            'rejected' => $counts['rejected'],
        ]);
    }



    public function updateEventStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending'
        ]);

        try {
            $projectId   = env('FIREBASE_PROJECT_ID');
            $accessToken = GoogleAccessToken::get();

            $patchUrl =
                "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/events/{$id}" .
                "?updateMask.fieldPaths=status";

            $body = ['fields' => ['status' => ['stringValue' => $request->input('status')]]];

            $ch = curl_init($patchUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST  => 'PATCH',
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: application/json',
                ],
                CURLOPT_POSTFIELDS     => json_encode($body),
                CURLOPT_TIMEOUT        => 20,
            ]);
            $patchResp = curl_exec($ch);
            $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                throw new \Exception('Update failed: ' . $patchResp);
            }

            // Invalidate events caches
            Cache::forget('events_list_min_60s');
            Cache::forget('events_list_count_60s');
            Cache::forget('events_status_counts_60s');

            return back()->with('message', 'Event status updated.');
        } catch (\Throwable $e) {
            return back()->with('error', app()->environment('local') ? $e->getMessage() : 'Failed to update event.');
        }
    }



    // Memory-optimized paginated events fetching
    private function getEventsPaginated($start, $length, $search, $scope): array
    {
            $projectId   = env('FIREBASE_PROJECT_ID');
            $accessToken = GoogleAccessToken::get();

        // Build Firebase query with pagination
            $base = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/events";
        
        // Calculate page for Firebase (Firebase uses pageSize, not offset)
        $pageSize = min($length * 2, 100); // Get more records to account for filtering
        
        $url = $base . '?pageSize=' . $pageSize
                    . '&mask.fieldPaths=name'
                    . '&mask.fieldPaths=sportType'
                    . '&mask.fieldPaths=eventFormat'
                    . '&mask.fieldPaths=eventDate'
                    . '&mask.fieldPaths=startTime'
                    . '&mask.fieldPaths=endTime'
                    . '&mask.fieldPaths=location'
                    . '&mask.fieldPaths=maxParticipants'
                    . '&mask.fieldPaths=currentParticipants'
                    . '&mask.fieldPaths=price'
                    . '&mask.fieldPaths=entryType'
                    . '&mask.fieldPaths=status'
                    . '&mask.fieldPaths=imageUrl'
                    . '&mask.fieldPaths=joinedParticipants'
            . '&mask.fieldPaths=eventId';

                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $accessToken],
                    CURLOPT_ENCODING       => 'gzip',
            CURLOPT_TIMEOUT        => 30,
                ]);
                $resp = curl_exec($ch);
                curl_close($ch);

                $json = json_decode($resp, true);
                $docs = $json['documents'] ?? [];

        $out = [];
                foreach ($docs as $doc) {
                    $f = $doc['fields'] ?? [];

                    $getS = fn($k) => $f[$k]['stringValue'] ?? null;
                    $getN = function ($k) use ($f) {
                        if (!isset($f[$k])) return null;
                        if (isset($f[$k]['integerValue'])) return (int)$f[$k]['integerValue'];
                        if (isset($f[$k]['doubleValue']))  return (float)$f[$k]['doubleValue'];
                        return isset($f[$k]['numberValue']) ? (float)$f[$k]['numberValue'] : null;
                    };
                    $getArr = function ($k) use ($f) {
                        if (!isset($f[$k]['arrayValue']['values'])) return [];
                        $vals = $f[$k]['arrayValue']['values'];
                        return array_map(function ($v) {
                            return $v['stringValue'] ?? null;
                        }, $vals);
                    };

                    $docId = basename($doc['name']);
                    $statusRaw = trim((string)($getS('status') ?? 'pending'));
                    $statusSlug = strtolower($statusRaw);
                    if (!in_array($statusSlug, ['approved', 'rejected', 'pending'])) {
                        $statusSlug = 'pending';
                    }

            $eventDate = $getS('eventDate');
                    $eventDateDisp = $eventDate ? date('F j, Y', strtotime($eventDate)) : '';

                    $current = $getN('currentParticipants');
                    if ($current === null) {
                        $current = count(array_filter($getArr('joinedParticipants')));
                    }

            $event = [
                        'docId'               => $docId,
                        'eventId'             => $getS('eventId'),
                        'name'                => $getS('name'),
                        'sportType'           => $getS('sportType'),
                        'eventFormat'         => $getS('eventFormat'),
                        'eventDate'           => $eventDate,
                        'eventDateDisp'       => $eventDateDisp,
                        'startTime'           => $getS('startTime'),
                        'endTime'             => $getS('endTime'),
                        'location'            => $getS('location'),
                        'maxParticipants'     => $getN('maxParticipants') ?? 0,
                        'currentParticipants' => $current ?? 0,
                        'price'               => $getN('price') ?? 0,
                        'entryType'           => $getS('entryType') ?? 'Free',
                        'status'              => $statusRaw,
                        'statusSlug'          => $statusSlug,
                        'imageUrl'            => $getS('imageUrl'),
                    ];
            
            // Apply scope filter
            if (in_array($scope, ['pending', 'approved', 'rejected']) && $event['statusSlug'] !== $scope) {
                continue;
            }
            
            // Apply search filter
            if ($search !== '') {
                $q = mb_strtolower($search);
                if (!str_contains(mb_strtolower($event['name'] ?? ''), $q) &&
                    !str_contains(mb_strtolower($event['sportType'] ?? ''), $q) &&
                    !str_contains(mb_strtolower($event['location'] ?? ''), $q) &&
                    !str_contains(mb_strtolower($event['eventFormat'] ?? ''), $q)) {
                    continue;
                }
            }
            
            $out[] = $event;
        }
        
        // Get total count from cache (updated periodically)
        $total = Cache::remember('events_total_count', 300, function() use ($projectId, $accessToken) {
            return $this->getTotalEventsCount($projectId, $accessToken);
        });
        
        return [
            'data' => array_slice($out, 0, $length),
            'total' => $total,
            'filtered' => count($out)
        ];
    }
    
    // Get total events count efficiently
    private function getTotalEventsCount($projectId, $accessToken): int
    {
        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/events?pageSize=1";
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $accessToken],
            CURLOPT_ENCODING       => 'gzip',
            CURLOPT_TIMEOUT        => 10,
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);
        
        $json = json_decode($resp, true);
        return $json['documents'] ? 1 : 0; // Simplified count for now
    }

    // Legacy method - kept for compatibility but optimized
    private function getEventsCountCached(): int
    {
        return Cache::remember('events_list_count_60s', 300, function() {
            $projectId = env('FIREBASE_PROJECT_ID');
            $accessToken = GoogleAccessToken::get();
            return $this->getTotalEventsCount($projectId, $accessToken);
        });
    }

    public function viewEvent(string $id)
    {
        session(['tab' => "events"]);

        try {
            $event = $this->getEventById($id);
            if (!$event) {
                return redirect()->route('events.index')->with('error', 'Event not found.');
            }
            // You can also load organizer/user here if needed (by $event['userId'])
            return view('view-event', compact('event'));
        } catch (\Throwable $e) {
            return redirect()->route('events.index')
                ->with('error', app()->environment('local') ? $e->getMessage() : 'Unable to load event.');
        }
    }


    private function getEventById(string $id): ?array
    {
        $projectId   = env('FIREBASE_PROJECT_ID');
        $accessToken = \App\Services\GoogleAccessToken::get();

        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/events/{$id}"
            . "?mask.fieldPaths=name"
            . "&mask.fieldPaths=sportType"
            . "&mask.fieldPaths=eventFormat"
            . "&mask.fieldPaths=eventType"
            . "&mask.fieldPaths=eventDate"
            . "&mask.fieldPaths=startTime"
            . "&mask.fieldPaths=endTime"
            . "&mask.fieldPaths=location"
            . "&mask.fieldPaths=maxParticipants"
            . "&mask.fieldPaths=currentParticipants"
            . "&mask.fieldPaths=joinedParticipants"
            . "&mask.fieldPaths=price"
            . "&mask.fieldPaths=entryType"
            . "&mask.fieldPaths=status"
            . "&mask.fieldPaths=imageUrl"
            . "&mask.fieldPaths=description"
            . "&mask.fieldPaths=userId"
            . "&mask.fieldPaths=createdAt"
            . "&mask.fieldPaths=eventId";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $accessToken],
            CURLOPT_ENCODING       => 'gzip',
            CURLOPT_TIMEOUT        => 20,
        ]);
        $resp = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code !== 200) {
            return null;
        }

        $doc = json_decode($resp, true);
        if (!is_array($doc)) return null;

        $f = $doc['fields'] ?? [];

        $getS = fn($k) => $f[$k]['stringValue'] ?? null;
        $getN = function ($k) use ($f) {
            if (!isset($f[$k])) return null;
            if (isset($f[$k]['integerValue'])) return (int)$f[$k]['integerValue'];
            if (isset($f[$k]['doubleValue']))  return (float)$f[$k]['doubleValue'];
            return isset($f[$k]['numberValue']) ? (float)$f[$k]['numberValue'] : null;
        };
        $getArr = function ($k) use ($f) {
            if (!isset($f[$k]['arrayValue']['values'])) return [];
            $vals = $f[$k]['arrayValue']['values'];
            return array_map(fn($v) => $v['stringValue'] ?? null, $vals);
        };
        $getTS = fn($k) => $f[$k]['timestampValue'] ?? null;

        $docId = basename($doc['name'] ?? $id);
        $statusRaw  = trim((string)($getS('status') ?? 'pending'));
        $statusSlug = strtolower($statusRaw);
        if (!in_array($statusSlug, ['approved', 'rejected', 'pending'])) $statusSlug = 'pending';

        $eventDate = $getS('eventDate');
        $eventDateDisp = $eventDate ? date('l, F j, Y', strtotime($eventDate)) : '';
        $createdAtIso  = $getTS('createdAt');
        $createdAtDisp = $createdAtIso ? date('F j, Y \a\t g:i A', strtotime($createdAtIso)) : null;

        $current = $getN('currentParticipants');
        if ($current === null) {
            $current = count(array_filter($getArr('joinedParticipants')));
        }

        return [
            'docId'               => $docId,
            'eventId'             => $getS('eventId'),
            'name'                => $getS('name'),
            'sportType'           => $getS('sportType'),
            'eventFormat'         => $getS('eventFormat'),
            'eventType'           => $getS('eventType'),
            'eventDate'           => $eventDate,
            'eventDateDisp'       => $eventDateDisp,
            'startTime'           => $getS('startTime'),
            'endTime'             => $getS('endTime'),
            'location'            => $getS('location'),
            'maxParticipants'     => $getN('maxParticipants') ?? 0,
            'currentParticipants' => $current ?? 0,
            'price'               => $getN('price') ?? 0,
            'entryType'           => $getS('entryType') ?? 'Free',
            'status'              => $statusRaw,
            'statusSlug'          => $statusSlug,
            'imageUrl'            => $getS('imageUrl'),
            'description'         => $getS('description'),
            'userId'              => $getS('userId'),
            'createdAtIso'        => $createdAtIso,
            'createdAtDisp'       => $createdAtDisp,
        ];
    }


    public function centerRequest()
    {
        session(['tab' => "center-request"]);
        return view('center-request');
    }
    public function sportsManagement()
    {
        session(['tab' => "sports-management"]);
        return view('sports-management');
    }


    // app/Http/Controllers/UsersController.php
    public function editSport($id)
    {
        // we only pass the Firestore doc id to the Blade
        return view('edit-sport', ['docId' => $id]);
    }


    public function addSport()
    {
        return view('add-sport');
    }



    // app/Http/Controllers/SportUploadController.php
    // app/Http/Controllers/SportUploadController.php
    public function upload(Request $request)
    {
        $request->validate([
            'icon' => 'required|file|mimes:png,jpg,jpeg,webp,svg|max:4096',
        ]);

        $file = $request->file('icon');

        $dir = public_path('uploads/sports/icons');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext  = strtolower($file->getClientOriginalExtension());
        $name = now()->format('Ymd_His') . '_' . \Illuminate\Support\Str::random(6) . '.' . $ext;

        $file->move($dir, $name);

        // ✅ Build a full URL that includes the sub-folder base (e.g. /Projects/Local/time2play)
        // Using request()->getBaseUrl() captures the app's subdirectory correctly.
        $base = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/');
        $url  = $base . '/public/uploads/sports/icons/' . $name;

        // (Optional) also return a relative path without a leading slash
        $path = 'uploads/sports/icons/' . $name;

        return response()->json([
            'success' => true,
            'url'     => $url,   // <-- use this in JS
            'path'    => $path,  // optional
        ]);
    }


    public function subscriptionControl()
    {
        session(['tab' => "subscription-control"]);
        return view('subscription-control');
    }


    public function editPlan(string $type)
    {
        // $type is "free" or "pro"
        return view('subscription-plan-edit', ['type' => $type]); // blade from section #3
    }



    // app/Http/Controllers/UsersController.php
    public function centerRequestDetails(string $id)
    {
        return view('center-request-details', ['id' => $id]);
    }

    // public function viewEvent()
    // {
    //     return view('view-event');
    // }

    // Legacy toggle using Kreait (kept as-is)
    public function toggleStatus($userId, $newStatus)
    {
        try {
            $factory = (new Factory)->withServiceAccount(base_path('bandmates.json'));
            $database = $factory->createFirestore()->database();
            $doctorRef = $database->collection('users')->document($userId);
            $doctorRef->set(['status' => (int)$newStatus], ['merge' => true]);

            Cache::forget('users_list_min_60s');
            Cache::forget('users_list_count_60s');

            return redirect()->back()->with('message', 'User status updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    public function notifications()
    {
        session(['tab' => "notifications"]);

        $project = env('FIREBASE_PROJECT_ID');
        $token   = GoogleAccessToken::get();

        $base = "https://firestore.googleapis.com/v1/projects/$project/databases/(default)/documents/notifications";
        $rows = [];
        $page = null;

        do {
            $res = Http::withToken($token)->acceptJson()->get($base, [
                'pageSize' => 1000,
                'pageToken' => $page,
            ])->json();

            foreach (($res['documents'] ?? []) as $doc) {
                $f = $doc['fields'] ?? [];
                $getS = fn($k) => $f[$k]['stringValue'] ?? null;
                $getArr = fn($k) => array_map(fn($v) => $v['stringValue'] ?? null, $f[$k]['arrayValue']['values'] ?? []);
                $rows[] = [
                    'id'           => basename($doc['name']),
                    'title'        => $getS('title'),
                    'body'         => $getS('body'),
                    'userType'     => $getS('userType'),
                    'releaseDate'  => $getS('releaseDate'),
                    'releaseTime'  => $getS('releaseTime'),
                    'recipients'   => $getArr('recipients'),
                    'createTime'   => $doc['createTime'] ?? null,
                ];
            }
            $page = $res['nextPageToken'] ?? null;
        } while ($page);

        // newest first
        usort($rows, fn($a, $b) => strcmp($b['createTime'] ?? '', $a['createTime'] ?? ''));

        return view('notifications', ['notifications' => $rows]);
    }



    public function store(Request $r)
    {
        $r->validate([
            'title'        => 'required|string|max:200',
            'description'  => 'required|string|max:5000',
            'type'         => 'required|in:all,25,50,75,specific',
            'release_date' => 'required|date',
            'release_time' => 'required',
            'user_ids'     => 'nullable|string',
        ]);

        $project = env('FIREBASE_PROJECT_ID');
        $token   = GoogleAccessToken::get();

        // choose recipients
        $uids = [];
        if ($r->type === 'all') {
            $uids = $this->activeUserIds($project, $token);
        } elseif (in_array($r->type, ['25', '50', '75'], true)) {
            $all = $this->activeUserIds($project, $token);
            shuffle($all);
            $uids = array_slice($all, 0, max(1, (int)ceil(count($all) * ((int)$r->type) / 100)));
        } else { // specific
            $uids = array_values(array_filter(array_map('trim', explode(',', (string)$r->user_ids))));
        }

        $userType = [
            'all' => 'allUsers',
            '25' => '25%',
            '50' => '50%',
            '75' => '75%',
            'specific' => 'specificUsers'
        ][$r->type];

        // Firestore fields
        $fields = [
            'title'       => ['stringValue' => $r->title],
            'body'        => ['stringValue' => $r->description],
            'timestamp'   => ['timestampValue' => now()->toIso8601String()], // server TS optional; good enough
            'userType'    => ['stringValue' => $userType],
            'releaseDate' => ['stringValue' => $r->release_date],
            'releaseTime' => ['stringValue' => $r->release_time],
            'notiType'    => ['stringValue' => 'admin'],
            'recipients'  => ['arrayValue' => ['values' => array_map(fn($u) => ['stringValue' => $u], $uids)]],
            'recipientsReadStatus' => [
                'mapValue' => ['fields' => array_reduce($uids, function ($a, $u) {
                    $a[$u] = ['booleanValue' => false];
                    return $a;
                }, [])]
            ],
        ];

        Http::withToken($token)->post(
            "https://firestore.googleapis.com/v1/projects/$project/databases/(default)/documents/notifications",
            ['fields' => $fields]
        );

        return redirect('notifications')->with('message', 'Notification saved!');
    }



    public function delete($id)
    {
        try {
            $project = env('FIREBASE_PROJECT_ID');
            $token   = GoogleAccessToken::get();

            Http::withToken($token)->delete(
                "https://firestore.googleapis.com/v1/projects/$project/databases/(default)/documents/notifications/$id"
            );

            return back()->with('message', 'Notification deleted successfully!');
        } catch (\Throwable $e) {
            return back()->with('error', app()->environment('local') ? $e->getMessage() : 'Failed to delete.');
        }
    }


    private function activeUserIds(string $project, string $token): array
    {
        $base = "https://firestore.googleapis.com/v1/projects/$project/databases/(default)/documents/users";
        $uids = [];
        $page = null;

        do {
            $res = Http::withToken($token)->acceptJson()->get($base, [
                'pageSize' => 1000,
                'pageToken' => $page,
            ])->json();

            foreach (($res['documents'] ?? []) as $doc) {
                $f = $doc['fields'] ?? [];
                $status = $f['status']['booleanValue'] ?? true;
                if ($status === false) continue;
                $uid = $f['uid']['stringValue'] ?? basename($doc['name']);
                if ($uid) $uids[] = $uid;
            }
            $page = $res['nextPageToken'] ?? null;
        } while ($page);

        return array_values(array_unique($uids));
    }



    public function addNoti()
    {
        $project = env('FIREBASE_PROJECT_ID');
        $token   = GoogleAccessToken::get();

        $base = "https://firestore.googleapis.com/v1/projects/$project/databases/(default)/documents/users";
        $users = [];
        $page = null;
        do {
            $res = Http::withToken($token)->acceptJson()->get($base, ['pageSize' => 500, 'pageToken' => $page])->json();
            foreach (($res['documents'] ?? []) as $doc) {
                $f = $doc['fields'] ?? [];
                $status = $f['status']['booleanValue'] ?? true;
                if ($status === false) continue;
                $users[] = [
                    'id'   => ($f['uid']['stringValue'] ?? basename($doc['name'])),
                    'name' => ($f['name']['stringValue'] ?? ($f['fullName']['stringValue'] ?? 'Unknown')),
                    'image' => ($f['imageURL']['stringValue'] ?? ($f['imageUrl']['stringValue'] ?? null)),
                ];
            }
            $page = $res['nextPageToken'] ?? null;
        } while ($page);

        return view('add-noti', compact('users'));
    }


    public function addVersion()
    {
        return view('add-version');
    }

    public function changePassword()
    {
        session(['tab' => "password"]);
        return view('auth/change-pass');
    }
    public function policyDoc()
    {
        session(['tab' => "policies"]);
        return view('policy-doc');   // (blade below)
    }

    public function addDoc()
    {
        return view('add-doc');      // (blade below)
    }

    public function editDoc($id)
    {
        return view('edit-doc', ['docId' => $id]);
    }

    /**
     * Upload a PDF to public/uploads/policies and return absolute URL.
     */
    public function uploadPolicyPdf(Request $request)
    {
        // Accept either "pdf" or "file" name to be flexible
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:20480', // 20 MB
        ]);

        $file = $request->file('pdf');

        $dir = public_path('uploads/policies');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $name = now()->format('Ymd_His') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);

        $url = url('uploads/policies/' . $name); // absolute URL that works anywhere in your app

        return response()->json(['success' => true, 'url' => $url]);
    }

    public function helpRequestsView()
    {
        session(['tab' => "help"]);
        return view('requests');
    }

    public function reply(Request $request, $id)
    {
        $request->validate(['reply' => 'required|string|max:5000']);

        $firestore = (new Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore();
        $db = $firestore->database();

        $doc = $db->collection('helpRequests')->document($id)->snapshot();
        if (!$doc->exists()) {
            return redirect()->back()->with('error', 'Help request not found.');
        }

        $data = $doc->data();
        $email = $data['email'] ?? null;
        $name  = $data['name'] ?? 'User';
        if (!$email) {
            return redirect()->back()->with('error', 'No email found for this request.');
        }

        $db->collection('helpRequests')->document($id)->update([
            ['path' => 'reply', 'value' => $request->input('reply')],
            ['path' => 'status', 'value' => 'read'],
        ]);

        $subject = 'Bandmates Reply to Your Help Request';
        $message = "<p>Dear {$name},</p><p>Here is our reply to your request:</p><p><strong>{$request->input('reply')}</strong></p><p>Regards,<br>Support Team Bandmates</p>";

        $response = Http::asForm()->post('https://Apis.appistaan.com/mailapi/index.php?key=sk286292djd926d', [
            'to' => $email,
            'subject' => $subject,
            'message' => $message,
        ]);

        if (($response->json('success') ?? null) !== "1") {
            return redirect()->back()->with('error', 'Reply saved, but failed to send email: ' . ($response->json('message') ?? ''));
        }

        return redirect('help-requests')->with('status', 'Reply sent successfully.');
    }

    // public function addDoc()
    // {
    //     return view('add-doc');
    // }

    public function versions()
    {
        session(['tab' => 'versions']);
        return view('versions');
    }

    public function updateVersionStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'version_status' => 'required|in:latest,stable,beta,outdated'
        ]);

        try {
            $firestore = (new Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore();
            $document = $firestore->database()->collection('app_versions')->document($request->id);
            $document->update([['path' => 'version_status', 'value' => $request->version_status]]);
            return redirect()->back()->with('message', 'Version status updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Failed to update version status.');
        }
    }

    public function requests()
    {
        session(['tab' => "help"]);
        return view('requests');
    }

    public function storeAndroidVersion(Request $request)
    {
        $request->validate([
            'version_number' => 'required|string',
            'release_date' => 'required',
            'device' => 'required|string|in:android,ios',
            'version_status' => 'required',
            'description' => 'nullable|string',
        ]);

        $firestore = (new Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore()->database();
        $versions = $firestore->collection('app_versions');

        $versions->add([
            'version_number' => $request->version_number,
            'release_date' => $request->release_date,
            'device' => $request->device,
            'version_status' => $request->version_status,
            'description' => $request->description,
            'created_at' => now()->toDateTimeString(),
        ]);

        return redirect('versions')->with('status', 'Version added successfully!');
    }

    // public function users()
    // {
    //     session(['tab' => "users"]);
    //     session(['userscreentype' => "Users Listing"]);
    //     // try {
    //     //     $factory = (new Factory)->withServiceAccount(__DIR__.'/bandmates.json');
    //     //     $database = $factory->createFirestore()->database();
    //     //     $users = $database->collection('users')->documents();
    //     // return view('users', compact('users'));
    //     return view('users');
    //     // } catch (\Throwable $e) {
    //     //     return response()->json(['error' => $e->getMessage()], 500);
    //     // }
    // }






    // // List users
    // public function index(Request $request)
    // {
    //     session(['tab' => 'users']);
    //     session(['userscreentype' => 'Users Listing']);

    //     try {
    //         $projectId = env('FIREBASE_PROJECT_ID');
    //         $saPath    = base_path(env('FIREBASE_CREDENTIALS'));

    //         // --- service account
    //         $sa = json_decode(file_get_contents($saPath), true);
    //         if (!$sa || empty($sa['client_email']) || empty($sa['private_key'])) {
    //             throw new \Exception('Invalid service account JSON');
    //         }

    //         // --- get access token
    //         $jwtHeader = ['alg' => 'RS256', 'typ' => 'JWT'];
    //         $jwtClaim  = [
    //             'iss'   => $sa['client_email'],
    //             'scope' => 'https://www.googleapis.com/auth/datastore',
    //             'aud'   => 'https://oauth2.googleapis.com/token',
    //             'exp'   => time() + 3600,
    //             'iat'   => time(),
    //         ];
    //         $b64 = fn($d) => rtrim(strtr(base64_encode(json_encode($d)), '+/', '-_'), '=');
    //         $input = $b64($jwtHeader) . '.' . $b64($jwtClaim);
    //         openssl_sign($input, $sig, $sa['private_key'], 'sha256WithRSAEncryption');
    //         $jwt = $input . '.' . rtrim(strtr(base64_encode($sig), '+/', '-_'), '=');

    //         $ch = curl_init('https://oauth2.googleapis.com/token');
    //         curl_setopt_array($ch, [
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_POST           => true,
    //             CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
    //             CURLOPT_POSTFIELDS     => http_build_query([
    //                 'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    //                 'assertion'  => $jwt,
    //             ]),
    //         ]);
    //         $tokenResp = curl_exec($ch);
    //         curl_close($ch);
    //         $tokenJson   = json_decode($tokenResp, true);
    //         $accessToken = $tokenJson['access_token'] ?? null;
    //         if (!$accessToken) {
    //             throw new \Exception('Failed to fetch access token: ' . $tokenResp);
    //         }

    //         // --- LIST documents in top-level collection "users"
    //         // If your collection is "Users" (capital U), change it here.
    //         $baseUrl = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/users";
    //         $pageToken = null;
    //         $users = [];

    //         do {
    //             $url = $baseUrl . '?pageSize=1000' . ($pageToken ? '&pageToken=' . urlencode($pageToken) : '');
    //             $ch = curl_init($url);
    //             curl_setopt_array($ch, [
    //                 CURLOPT_RETURNTRANSFER => true,
    //                 CURLOPT_HTTPHEADER     => [
    //                     'Authorization: Bearer ' . $accessToken,
    //                 ],
    //             ]);
    //             $resp = curl_exec($ch);
    //             curl_close($ch);

    //             $json = json_decode($resp, true);
    //             if (!is_array($json)) {
    //                 throw new \Exception('Invalid Firestore response: ' . $resp);
    //             }

    //             $docs = $json['documents'] ?? [];
    //             foreach ($docs as $doc) {
    //                 $docId  = basename($doc['name']);
    //                 $f      = $doc['fields'] ?? [];

    //                 // helpers
    //                 $getS = fn($k) => isset($f[$k]['stringValue'])  ? $f[$k]['stringValue']  : null;
    //                 $getB = fn($k) => isset($f[$k]['booleanValue']) ? (bool)$f[$k]['booleanValue'] : null;

    //                 // map to what your Blade expects, using the real field names from your screenshot
    //                 $users[] = [
    //                     'id'        => $docId,
    //                     'full_name' => $getS('fullName')    ?? 'Unknown',
    //                     'email'     => $getS('email'),
    //                     'phone'     => $getS('mobile'),
    //                     'location'  => $getS('address'),
    //                     'avatar'    => $getS('imageUrl'),
    //                     'status'    => $getB('status') ?? true, // true = active; false = blocked
    //                 ];
    //             }

    //             $pageToken = $json['nextPageToken'] ?? null;
    //         } while ($pageToken);

    //         return view('users', compact('users'));
    //     } catch (\Throwable $e) {
    //         return back()->with('error', app()->environment('local') ? $e->getMessage() : 'Unable to load users.');
    //     }
    // }


    // // Toggle (block/unblock) a user by setting status boolean
    // public function toggle(Request $request, string $id)
    // {
    //     $request->validate([
    //         'to' => 'required|in:block,unblock',
    //     ]);

    //     $toBlock = $request->input('to') === 'block' ? true : false;
    //     $newStatus = !$toBlock; // block => status=false, unblock => status=true

    //     try {
    //         $projectId = env('FIREBASE_PROJECT_ID');
    //         $saPath    = base_path(env('FIREBASE_CREDENTIALS'));

    //         $sa = json_decode(file_get_contents($saPath), true);
    //         if (!$sa || empty($sa['client_email']) || empty($sa['private_key'])) {
    //             throw new \Exception('Invalid service account JSON');
    //         }

    //         // Access token
    //         $jwtHeader = ['alg' => 'RS256', 'typ' => 'JWT'];
    //         $jwtClaim  = [
    //             'iss'   => $sa['client_email'],
    //             'scope' => 'https://www.googleapis.com/auth/datastore',
    //             'aud'   => 'https://oauth2.googleapis.com/token',
    //             'exp'   => time() + 3600,
    //             'iat'   => time(),
    //         ];
    //         $b64 = fn($d) => rtrim(strtr(base64_encode(json_encode($d)), '+/', '-_'), '=');
    //         $input = $b64($jwtHeader) . '.' . $b64($jwtClaim);
    //         openssl_sign($input, $sig, $sa['private_key'], 'sha256WithRSAEncryption');
    //         $jwt = $input . '.' . rtrim(strtr(base64_encode($sig), '+/', '-_'), '=');

    //         $ch = curl_init('https://oauth2.googleapis.com/token');
    //         curl_setopt_array($ch, [
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_POST           => true,
    //             CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
    //             CURLOPT_POSTFIELDS     => http_build_query([
    //                 'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    //                 'assertion'  => $jwt,
    //             ]),
    //         ]);
    //         $tokenResp = curl_exec($ch);
    //         curl_close($ch);
    //         $tokenJson   = json_decode($tokenResp, true);
    //         $accessToken = $tokenJson['access_token'] ?? null;
    //         if (!$accessToken) {
    //             throw new \Exception('Fail to fetch access token: ' . $tokenResp);
    //         }

    //         // PATCH status
    //         $patchUrl =
    //             "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/users/{$id}" .
    //             "?updateMask.fieldPaths=status";

    //         $body = [
    //             'fields' => [
    //                 'status' => ['booleanValue' => (bool)$newStatus],
    //             ],
    //         ];

    //         $ch = curl_init($patchUrl);
    //         curl_setopt_array($ch, [
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_CUSTOMREQUEST  => 'PATCH',
    //             CURLOPT_HTTPHEADER     => [
    //                 'Authorization: Bearer ' . $accessToken,
    //                 'Content-Type: application/json',
    //             ],
    //             CURLOPT_POSTFIELDS     => json_encode($body),
    //         ]);
    //         $patchResp = curl_exec($ch);
    //         $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //         curl_close($ch);

    //         if ($httpCode !== 200) {
    //             throw new \Exception('Update failed: ' . $patchResp);
    //         }

    //         return redirect()->route('users.index')->with('message', $newStatus ? 'User unblocked' : 'User blocked');
    //     } catch (\Throwable $e) {
    //         return redirect()->route('users.index')
    //             ->with('error', app()->environment('local') ? $e->getMessage() : 'Update failed.');
    //     }
    // }



    // public function events()
    // {
    //     session(['tab' => "events"]);
    //     return view('events');
    // }



    // public function centerRequest()
    // {
    //     session(['tab' => "center-request"]);
    //     return view('center-request');
    // }


    // public function sportsManagement()
    // {
    //     session(['tab' => "sports-management"]);
    //     return view('sports-management');
    // }


    // public function addSport()
    // {
    //     return view('add-sport');
    // }


    // public function subscriptionControl()
    // {
    //     session(['tab' => "subscription-control"]);
    //     return view('subscription-control');
    // }


    // public function centerRequestDetails()
    // {
    //     return view('center-request-details');
    // }


    // public function viewEvent()
    // {
    //     return view('view-event');
    // }


    // public function toggleStatus($userId, $newStatus)
    // {
    //     try {
    //         $factory = (new Factory)->withServiceAccount(__DIR__ . '/bandmates.json');
    //         $database = $factory->createFirestore()->database();

    //         // Get the doctor's document reference
    //         $doctorRef = $database->collection('users')->document($userId);

    //         // Update the doctor's email with merge set to true
    //         $doctorRef->set([
    //             'status' => (int)$newStatus
    //         ], ['merge' => true]);

    //         return redirect()->back()->with('message', 'User status updated successfully.');
    //     } catch (\Throwable $e) {
    //         return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
    //     }
    // }


    // public function activeUsers()
    // {
    //     session(['userscreentype' => "Active Users"]);
    //     try {
    //         $allUsers = $this->fetchUsersFromFirestore(); // reuse your list code
    //         $users = array_filter($allUsers, fn($u) => $u['status'] === true);

    //         return view('users', compact('users'));
    //     } catch (\Throwable $e) {
    //         return back()->with('error', app()->environment('local') ? $e->getMessage() : 'Unable to load blocked users.');
    //     }
    // }

    // public function blockedUsers()
    // {
    //     session(['userscreentype' => "Blocked Users"]);

    //     try {
    //         $allUsers = $this->fetchUsersFromFirestore(); // reuse your list code
    //         $users = array_filter($allUsers, fn($u) => $u['status'] === false);

    //         return view('users', compact('users'));
    //     } catch (\Throwable $e) {
    //         return back()->with('error', app()->environment('local') ? $e->getMessage() : 'Unable to load blocked users.');
    //     }
    // }

    // // ---------- Helpers ----------

    // private function fetchUsersFromFirestore(): array
    // {
    //     [$accessToken, $projectId] = $this->getTokenAndProject();

    //     $baseUrl   = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/users";
    //     $pageToken = null;
    //     $users     = [];

    //     do {
    //         $url = $baseUrl . '?pageSize=1000' . ($pageToken ? '&pageToken=' . urlencode($pageToken) : '');
    //         $ch = curl_init($url);
    //         curl_setopt_array($ch, [
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $accessToken],
    //         ]);
    //         $resp = curl_exec($ch);
    //         curl_close($ch);

    //         $json = json_decode($resp, true);
    //         if (!is_array($json)) throw new \Exception('Invalid Firestore response: ' . $resp);

    //         foreach ($json['documents'] ?? [] as $doc) {
    //             $users[] = $this->mapDoc($doc);
    //         }
    //         $pageToken = $json['nextPageToken'] ?? null;
    //     } while ($pageToken);

    //     return $users;
    // }

    // private function mapDoc(array $doc): array
    // {
    //     $id = basename($doc['name']);
    //     $f  = $doc['fields'] ?? [];

    //     $getS = fn($k) => isset($f[$k]['stringValue'])  ? $f[$k]['stringValue']  : null;
    //     $getB = fn($k) => isset($f[$k]['booleanValue']) ? (bool)$f[$k]['booleanValue'] : null;

    //     return [
    //         'id'        => $id,
    //         'full_name' => $getS('fullName') ?? 'Unknown',
    //         'email'     => $getS('email'),
    //         'phone'     => $getS('mobile'),
    //         'location'  => $getS('address'),
    //         'avatar'    => $getS('imageUrl'),
    //         'status'    => $getB('status') ?? true, // default active
    //         'uid'       => $getS('uid'),            // if you need it later
    //     ];
    // }

    // private function getTokenAndProject(): array
    // {
    //     $projectId = env('FIREBASE_PROJECT_ID');
    //     $saPath    = base_path(env('FIREBASE_CREDENTIALS'));
    //     $sa        = json_decode(file_get_contents($saPath), true);

    //     if (!$sa || empty($sa['client_email']) || empty($sa['private_key'])) {
    //         throw new \Exception('Invalid service account JSON');
    //     }

    //     $jwtHeader = ['alg' => 'RS256', 'typ' => 'JWT'];
    //     $jwtClaim  = [
    //         'iss'   => $sa['client_email'],
    //         'scope' => 'https://www.googleapis.com/auth/datastore',
    //         'aud'   => 'https://oauth2.googleapis.com/token',
    //         'exp'   => time() + 3600,
    //         'iat'   => time(),
    //     ];
    //     $b64 = fn($d) => rtrim(strtr(base64_encode(json_encode($d)), '+/', '-_'), '=');
    //     $input = $b64($jwtHeader) . '.' . $b64($jwtClaim);
    //     openssl_sign($input, $sig, $sa['private_key'], 'sha256WithRSAEncryption');
    //     $jwt = $input . '.' . rtrim(strtr(base64_encode($sig), '+/', '-_'), '=');

    //     $ch = curl_init('https://oauth2.googleapis.com/token');
    //     curl_setopt_array($ch, [
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_POST           => true,
    //         CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
    //         CURLOPT_POSTFIELDS     => http_build_query([
    //             'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    //             'assertion'  => $jwt,
    //         ]),
    //     ]);
    //     $tokenResp  = curl_exec($ch);
    //     curl_close($ch);
    //     $tokenJson  = json_decode($tokenResp, true);
    //     $accessToken = $tokenJson['access_token'] ?? null;

    //     if (!$accessToken) throw new \Exception('Failed to fetch access token: ' . $tokenResp);

    //     return [$accessToken, $projectId];
    // }


    // public function notifications()
    // {
    //     session(['tab' => "notifications"]);

    //     // $firestore = (new Factory)->withServiceAccount(__DIR__ . '/bandmates.json')->createFirestore();
    //     // $collection = $firestore->database()->collection('notifications');

    //     // // Filter: only get documents where notiType == "admin"
    //     // $query = $collection->where('notiType', '=', 'admin')->documents();

    //     // return view('notifications', ['notifications' => $query]);
    //     return view('notifications');
    // }


    // public function delete($id)
    // {
    //     try {
    //         $firestore = (new Factory)->withServiceAccount(__DIR__ . '/bandmates.json')->createFirestore();
    //         $firestore->database()->collection('notifications')->document($id)->delete();

    //         return redirect()->back()->with('message', 'Notification deleted successfully!');
    //     } catch (\Throwable $e) {
    //         return redirect()->back()->with('error', 'Failed to delete: ' . $e->getMessage());
    //     }
    // }


    // public function addNoti()
    // {
    //     // $firestore = (new Factory)->withServiceAccount(__DIR__ . '/bandmates.json')->createFirestore();
    //     // $users = $firestore->database()->collection('users')->where('status', '=', 1)->documents();
    //     // return view('add-noti', compact('users'));
    //     return view('add-noti');
    // }


    // public function store(Request $request)
    // {
    //     $firestore = (new Factory)->withServiceAccount(__DIR__ . '/bandmates.json')->createFirestore();
    //     $usersCollection = $firestore->database()->collection('users')->where('status', '=', 1)->documents();

    //     $allUserIds = [];
    //     foreach ($usersCollection as $user) {
    //         $allUserIds[] = $user->id();
    //     }

    //     $type = $request->input('type');
    //     $selectedUserIds = [];

    //     if ($type === 'specific') {
    //         // From modal selection
    //         $selectedUserIds = explode(',', $request->input('user_ids', ''));
    //     } elseif (in_array($type, ['25', '50', '75'])) {
    //         // Random X% of users
    //         $percentage = (int)$type;
    //         $countToPick = (int) ceil((count($allUserIds) * $percentage) / 100);
    //         shuffle($allUserIds); // Randomize order
    //         $selectedUserIds = array_slice($allUserIds, 0, $countToPick);
    //     } else {
    //         // All users
    //         $selectedUserIds = $allUserIds;
    //     }

    //     // Final payload
    //     $data = [
    //         'title' => $request->title,
    //         'description' => $request->description,
    //         'release_date' => $request->release_date,
    //         'release_time' => $request->release_time,
    //         'type' => $type,
    //         "notiType" => "admin",
    //         'user_ids' => $selectedUserIds,
    //         'created_at' => now()->toDateTimeString(),
    //     ];

    //     // Store into Firestore
    //     $firestore->database()->collection('notifications')->add($data);

    //     return redirect('notifications')->with('message', 'Notification created successfully!');
    // }

    // public function addversion()
    // {
    //     return view('add-version');
    // }


    // public function changePassword()
    // {
    //     session(['tab' => "password"]);
    //     return view('auth/change-pass');
    // }


    // public function policyDoc()
    // {
    //     session(['tab' => "policies"]);
    //     // $firestore = (new \Kreait\Firebase\Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore();
    //     // $documents = $firestore->database()->collection('policyDocuments')->documents();

    //     // $policies = [];

    //     // foreach ($documents as $doc) {
    //     //     $data = $doc->data();
    //     //     $data['id'] = $doc->id();
    //     //     $policies[] = $data;
    //     // }

    //     // return view('policy-doc', compact('policies'));
    //     return view('policy-doc');
    // }

    // public function helpRequestsView()
    // {
    //     session(['tab' => "help"]);
    //     // $firestore = (new Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore();
    //     // $db = $firestore->database();

    //     // // Get all help requests
    //     // $helpRequestss = $db->collection('helpRequests')->documents();

    //     // $helpRequests = [];

    //     // foreach ($helpRequestss as $helpDoc) {
    //     //     $data = $helpDoc->data();
    //     //     $data['id'] = $helpDoc->id();

    //     //     // Get matching user info from 'users' collection using uid
    //     //     if (!empty($data['uid'])) {
    //     //         $userSnap = $db->collection('users')->document($data['uid'])->snapshot();

    //     //         if ($userSnap->exists()) {
    //     //             $data['user'] = $userSnap->data(); // attach user info
    //     //         } else {
    //     //             $data['user'] = null;
    //     //         }
    //     //     }

    //     //     $helpRequests[] = $data;
    //     // }

    //     // return view('requests', compact('helpRequests'));
    //     return view('requests');
    // }



    // public function reply(Request $request, $id)
    // {
    //     $request->validate([
    //         'reply' => 'required|string|max:5000',
    //     ]);

    //     $firestore = (new \Kreait\Firebase\Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore();
    //     $db = $firestore->database();

    //     // Get the help request document
    //     $doc = $db->collection('helpRequests')->document($id)->snapshot();
    //     if (!$doc->exists()) {
    //         return redirect()->back()->with('error', 'Help request not found.');
    //     }

    //     $data = $doc->data();
    //     $email = $data['email'] ?? null;
    //     $name  = $data['name'] ?? 'User';

    //     if (!$email) {
    //         return redirect()->back()->with('error', 'No email found for this request.');
    //     }

    //     // Update Firestore
    //     $db->collection('helpRequests')->document($id)->update([
    //         ['path' => 'reply', 'value' => $request->input('reply')],
    //         ['path' => 'status', 'value' => 'read'],
    //     ]);

    //     // Send Email via external API
    //     $subject = 'Bandmates Reply to Your Help Request';
    //     $message = "<p>Dear {$name},</p><p>Here is our reply to your request:</p><p><strong>{$request->input('reply')}</strong></p><p>Regards,<br>Support Team Bandmates</p>";

    //     $response = Http::asForm()->post('https://Apis.appistaan.com/mailapi/index.php?key=sk286292djd926d', [
    //         'to' => $email,
    //         'subject' => $subject,
    //         'message' => $message,
    //     ]);

    //     // Optional: check if sending succeeded
    //     if ($response->json('success') !== "1") {
    //         return redirect()->back()->with('error', 'Reply saved, but failed to send email: ' . $response->json('message'));
    //     }

    //     return redirect('help-requests')->with('status', 'Reply sent successfully.');
    // }


    // public function addDoc()
    // {
    //     return view('add-doc');
    // }


    // public function versions()
    // {
    //     session(['tab' => 'versions']);

    //     // $firestore = (new Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore();
    //     // $versionsCollection = $firestore->database()->collection('app_versions')->documents();

    //     // $androidVersions = collect();
    //     // $iosVersions = collect();

    //     // foreach ($versionsCollection as $version) {
    //     //     $data = $version->data();
    //     //     $data['id'] = $version->id(); // In case needed later

    //     //     if (isset($data['device']) && $data['device'] === 'android') {
    //     //         $androidVersions->push($data);
    //     //     } elseif (isset($data['device']) && $data['device'] === 'ios') {
    //     //         $iosVersions->push($data);
    //     //     }
    //     // }

    //     // return view('versions', [
    //     //     'androidVersions' => $androidVersions,
    //     //     'iosVersions' => $iosVersions
    //     // ]);
    //     return view('versions');
    // }


    // public function updateVersionStatus(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|string',
    //         'version_status' => 'required|in:latest,stable,beta,outdated'
    //     ]);

    //     try {
    //         $firestore = (new Factory)->withServiceAccount(base_path('bandmates.json'))->createFirestore();
    //         $document = $firestore->database()->collection('app_versions')->document($request->id);

    //         $document->update([
    //             ['path' => 'version_status', 'value' => $request->version_status]
    //         ]);

    //         return redirect()->back()->with('message', 'Version status updated successfully.');
    //     } catch (\Throwable $e) {
    //         return redirect()->back()->with('error', 'Failed to update version status.');
    //     }
    // }


    // public function requests()
    // {
    //     session(['tab' => "help"]);
    //     return view('requests');
    // }



    // public function storeAndroidVersion(Request $request)
    // {
    //     $request->validate([
    //         'version_number' => 'required|string',
    //         'release_date' => 'required',
    //         'device' => 'required|string|in:android,ios',
    //         'version_status' => 'required',
    //         'description' => 'nullable|string',
    //     ]);

    //     $firestore = (new Factory)
    //         ->withServiceAccount(base_path('bandmates.json'))
    //         ->createFirestore()
    //         ->database();

    //     $versions = $firestore->collection('app_versions');

    //     $versions->add([
    //         'version_number' => $request->version_number,
    //         'release_date' => $request->release_date,
    //         'device' => $request->device,
    //         'version_status' => $request->version_status,
    //         'description' => $request->description,
    //         'created_at' => now()->toDateTimeString(),
    //     ]);

    //     return redirect('versions')->with('status', 'Version added successfully!');
    // }
}

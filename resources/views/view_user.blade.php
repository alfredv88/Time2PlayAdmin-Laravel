<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Profile</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ url('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ url('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ url('assets/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .scrollable-card {
        max-height: 750px;  /* Adjust this value to your preference */
        overflow-y: auto;
    }
    /* Styles for the custom scrollbar */
    .scrollable-card::-webkit-scrollbar {
        width: 8px; /* Adjust the width of the scrollbar */
    }

    .scrollable-card::-webkit-scrollbar-thumb {
        background-color: #888; /* Color of the draggable scroll bar */
        border-radius: 4px; /* Make the edges round */
    }

    .scrollable-card::-webkit-scrollbar-thumb:hover {
        background-color: #555; /* Color when hovering over the draggable scrollbar */
    }

    .scrollable-card::-webkit-scrollbar-track {
        background-color: #f1f1f1; /* Background of the scrollbar track */
        border-radius: 4px; /* Make the edges round */
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
            <h1>User Profile Details</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Profile Image Section -->
                <div class="col-xl-5 col-lg-4 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($user->profileImg)
                            <img class="img-circle img-fluid"
                                 src="{{ url('public/Courseimages/'.$user->profileImg) }}"
                                 style="max-height: 200px; width: 200px;" alt="">
                            @else
                            <img class="img-circle img-fluid"
                                 src="https://alxgroup.com.au/wp-content/uploads/2016/04/dummy-post-horisontal.jpg"
                                 style="max-height: 200px; width: auto;" alt="">
                            @endif
                        </div>
                        <div class="text-start ml-2">
                            <h4 class="font-weight-bold">User Details</h4>
                        </div>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>Full Name:</th>
                                    <td>{{ $user->fName." ".$user->lName }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>
                                        @if($user->phone)
                                            {{ $user->phone }}
                                        @else
                                            Null
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td>
                                        @if($user->gender)
                                            {{ $user->gender }}
                                        @else
                                            Null
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date Of Birth:</th>
                                    <td>
                                        @if($user->dob)
                                            {{ $user->dob }}
                                        @else
                                            Null
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>
                                        @if($user->address)
                                            {{ $user->address }}
                                        @else
                                            Null
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>About:</th>
                                    <td>
                                        @if($user->about)
                                            {{ $user->about }}
                                        @else
                                            Null
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-around">
                            <div>
                                <a href="{{ url('block_user/'.$user->uid) }}">
                                    @if($user->status == 1)
                                        <button class="btn btn-large text-light" style="background: #1D3943" onclick="return confirm('Are you sure you want to block this user?');">Block User</button>
                                    @else
                                        <button class="btn btn-large text-light bg-warning" onclick="return confirm('Are you sure you want to Unblock this user?');">Unblock User</button>
                                    @endif
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('users.edit',$user->uid) }}" class="btn btn-large text-light bg-success">Edit User</a>
                            </div>
                        </div>
                        <div class="text-center p-4">
                            {{-- <a class="btn btn-large btn-danger" onclick="return confirm('Are you sure you want to delete this user?');"
                             href="{{ url('deleteUser/'.$user->uid) }}">Delete User</a> --}}
                             <a class="btn btn-large btn-danger"
                                onclick="return confirm('If you proceed with this deletion, the following data related to the user will be permanently removed:\n\n- Comments and chats from our external database.\n- User\'s authentication account.\n- User\'s balances, boards, liked comments, favorites, feelings, friends, goals, group memberships, guides, horses, horse teams, likes, notifications, posts, rides, ride check-ins, round-ups, safety contacts, stats, studies, and subscriptions from our internal database.\n\nPlease note that this operation is irreversible. Once deleted, there\'s no way to recover this data.');"
                                href="{{ url('deleteUser/'.$user->uid) }}">Delete User</a>
                        </div>
                    </div>
                </div>

                <!-- Profile Details Section -->
                <div class="col-xl-7 col-lg-8 col-md-6 col-sm-12 scrollable-card">
                    {{-- @foreach ($posts as $post)
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-lg-8 col-md-9 col-sm-11 col-11">
                                        <div class="d-flex p-1">
                                            <div class="">
                                                <img src="{{ url('public/Courseimages/'.$user['profileImg']) }}" height="60px" width="60px" class="rounded-circle mx-auto d-block" onerror="this.onerror=null; this.src='http://www.listercarterhomes.com/wp-content/uploads/2013/11/dummy-image-square.jpg'" alt="">
                                            </div>
                                            <div class="d-flex align-items-center pl-2">
                                                <div>
                                                    <div class="">
                                                        {{ $user['fName']. " ".$user['lName'] }}
                                                    </div>
                                                    <div class="">
                                                        @if(isset($post['group_data']['name']))
                                                            {{ $post['group_data']['name'] }}
                                                        @endif
                                                        <span>{{ \Carbon\Carbon::parse($post['created_at'])->format('d M Y H:i') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-3 col-sm-1 col-1 d-flex align-items-center justify-content-end">
                                        <div class="d-flex justify-content-end">
                                            <div class="btn-group">
                                                <div data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </div>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{ route('users.show',$user->uid) }}">Delete Post</a>
                                                    <a class="dropdown-item" href="{{ route('users.edit',$user->uid) }}">Block User</a>
                                                    <a class="dropdown-item" onclick="return confirm('Are you sure you want to delete this user?');" href="{{ url('deleteUser/'.$user->uid) }}">Delete User</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-weight-bold mb-n3">
                                        <p>{{ $post['text'] }}</p>
                                    </div>
                                    <div class="row row-cols-2">
                                        @php
                                            $imageArray = explode(',', $post['images']);
                                            $additionalImagesCount = count($imageArray) - 3;
                                        @endphp

                                        @foreach($imageArray as $index => $image)
                                            @if($index < 3)
                                                <div class="col p-1">
                                                    <img src="{{ url('public/Courseimages/'.trim($image)) }}" class="img-fluid h-100 w-100" onerror="this.onerror=null; this.src='http://www.listercarterhomes.com/wp-content/uploads/2013/11/dummy-image-square.jpg'" alt="http://www.listercarterhomes.com/wp-content/uploads/2013/11/dummy-image-square.jpg">
                                                </div>
                                            @elseif($index == 3)
                                                <div class="col p-1 d-flex justify-content-center align-items-center">
                                                    <img src="{{ url('public/Courseimages/'.trim($image)) }}" class="img-fluid h-100 w-100" onerror="this.onerror=null; this.src='http://www.listercarterhomes.com/wp-content/uploads/2013/11/dummy-image-square.jpg'" alt="http://www.listercarterhomes.com/wp-content/uploads/2013/11/dummy-image-square.jpg">
                                                    @if($additionalImagesCount > 0)
                                                        <button class="btn btn-dark position-absolute top-50 start-50 translate-middle" style="opacity: 0.7; height: 95%; width: 97%; font-size: 30px" data-toggle="modal" data-target="#exampleModalCenter-{{ $post['id'] }}">+{{ $additionalImagesCount }}</button>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModalCenter-{{ $post['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Post Details</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        @foreach($imageArray as $image)
                                                            <div class="col-md-4 p-1">
                                                                <img src="{{ url('public/Courseimages/'.trim($image)) }}" class="img-fluid h-100 w-100" onerror="this.onerror=null; this.src='http://www.listercarterhomes.com/wp-content/uploads/2013/11/dummy-image-square.jpg'" alt="http://www.listercarterhomes.com/wp-content/uploads/2013/11/dummy-image-square.jpg">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-3">
                                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            <span class="text-wrap">{{ $post['likes_count'] }} Like</span>
                                        </div>
                                        <div class="text-center col-lg-4 col-md-4 col-sm-6 col-6">
                                            <i class="fa fa-comments-o" aria-hidden="true"></i>
                                            <span class="text-wrap">{{ $post['comments_count'] }} Comments</span>
                                        </div>
                                        <div class="d-flex justify-content-end align-items-center col-lg-4 col-md-4 col-sm-3 col-3">
                                            <i class="fa fa-share-alt" aria-hidden="true"></i>
                                            <span class="ml-1 text-wrap">Share</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach --}}
                    @foreach ($posts as $post)
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-lg-8 col-md-9 col-sm-11 col-11">
                                        <div class="d-flex p-1">
                                            <div class="">
                                                <img src="{{ url('public/Courseimages/'.$user['profileImg']) }}" height="60px" width="60px" class="rounded-circle mx-auto d-block" onerror="this.onerror=null; this.src='http://www.listercarterhomes.com/wp-content/uploads/2013/11/dummy-image-square.jpg'" alt="">
                                            </div>
                                            <div class="d-flex align-items-center pl-2">
                                                <div>
                                                    <div class="">
                                                        {{ $user['fName']. " ".$user['lName'] }}
                                                    </div>
                                                    <div class="">
                                                        @if(isset($post['group_data']['name']))
                                                            {{ $post['group_data']['name'] }}
                                                        @endif
                                                        <span>{{ \Carbon\Carbon::parse($post['created_at'])->format('d M Y H:i') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-3 col-sm-1 col-1 d-flex align-items-center justify-content-end">
                                        <div class="d-flex justify-content-end">
                                            <div class="btn-group">
                                                <div data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </div>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{ url('delete_post/'.$post->id) }}">Delete Post</a>
                                                    @if($user->status == 1)
                                                        <a class="dropdown-item" href="{{ url('block_user/'.$user->uid) }}" onclick="return confirm('Are you sure you want to block this user?');">Block User</a>
                                                    @else
                                                        <a class="dropdown-item" href="{{ url('block_user/'.$user->uid) }}" onclick="return confirm('Are you sure you want to Unblock this user?');">Unblock User</a>
                                                    @endif
                                                    {{-- <a class="dropdown-item" onclick="return confirm('Are you sure you want to delete this user?');" href="{{ url('deleteUser/'.$user->uid) }}">Delete User</a> --}}
                                                    <a class="dropdown-item" onclick="return confirm('If you proceed with this deletion, the following data related to the user will be permanently removed:\n\n- Comments and chats from our external database.\n- User\'s authentication account.\n- User\'s balances, boards, liked comments, favorites, feelings, friends, goals, group memberships, guides, horses, horse teams, likes, notifications, posts, rides, ride check-ins, round-ups, safety contacts, stats, studies, and subscriptions from our internal database.\n\nPlease note that this operation is irreversible. Once deleted, there\'s no way to recover this data.');" href="{{ url('deleteUser/'.$user->uid) }}">Delete User</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-weight-bold mb-n3">
                                        <p>{{ $post['text'] }}</p>
                                    </div>
                                    <div class="row row-cols-2">
                                        @php
                                            $imageArray = explode(',', $post['images']);
                                            $totalMedia = count($imageArray) + (isset($post['video']) && !empty($post['video']) ? 1 : 0);
                                            $additionalImagesCount = $totalMedia - 3;
                                        @endphp
                                        @for($index = 0; $index < min($totalMedia, 3); $index++)
                                            @if($index != 2)
                                                <div class="col p-1">
                                                    <img src="{{ url('public/Courseimages/'.trim($imageArray[$index])) }}" class="img-fluid" style="height: 285px; width: 285px" onerror="this.onerror=null; this.src='http://www.listercarterhomes.com/wp-content/uploads/2013/11/dummy-image-square.jpg'" alt="">
                                                </div>
                                            @elseif(isset($post['video']) && !empty($post['video']))
                                                <div class="col p-1">
                                                    <iframe width="285" height="285" src="https://www.youtube.com/embed/{{ last(explode("/", $post['video'])) }}" frameborder="0" allowfullscreen></iframe>
                                                </div>
                                            @else
                                                <div class="col p-1">
                                                    <img src="{{ url('public/Courseimages/'.trim($imageArray[$index])) }}" class="img-fluid" style="height: 285px; width: 285px" onerror="this.onerror=null; this.src='http://www.listercarterhomes.com/wp-content/uploads/2013/11/dummy-image-square.jpg'" alt="">
                                                </div>
                                            @endif
                                        @endfor

                                        @if($additionalImagesCount > 0 && $index >= 3)
                                            <div class="col p-1 d-flex justify-content-center align-items-center">
                                                <img src="{{ url('public/Courseimages/'.trim($imageArray[$index])) }}" class="img-fluid" style="height: 285px; width: 285px" onerror="this.onerror=null; this.src='http://www.listercarterhomes.com/wp-content/uploads/2013/11/dummy-image-square.jpg'" alt="">
                                                <button class="btn btn-dark position-absolute top-50 start-50 translate-middle" style="opacity: 0.7; height: 95%; width: 97%; font-size: 30px" data-toggle="modal" data-target="#exampleModalCenter-{{ $post['id'] }}">+{{ $additionalImagesCount }}</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <!-- Modal for Images and Video -->
                                <div class="modal fade" id="exampleModalCenter-{{ $post['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Post Details</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    @foreach($imageArray as $image)
                                                        <div class="col-md-4 p-1">
                                                            <img src="{{ url('public/Courseimages/'.trim($image)) }}" class="img-fluid h-100 w-100" onerror="this.onerror=null; this.src='http://www.listercarterhomes.com/wp-content/uploads/2013/11/dummy-image-square.jpg'" alt="http://www.listercarterhomes.com/wp-content/uploads/2013/11/dummy-image-square.jpg">
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if(isset($post['video']) && !empty($post['video']))
                                                    <div class="mt-2">
                                                        <iframe width="100%" height="315" src="https://www.youtube.com/embed/{{ last(explode("/", $post['video'])) }}" frameborder="0" allowfullscreen></iframe>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-3 col-3">
                                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            <span class="text-wrap">{{ $post['likes_count'] }} Like</span>
                                        </div>
                                        <div class="text-center col-lg-4 col-md-4 col-sm-6 col-6">
                                            <i class="fa fa-comments-o" aria-hidden="true"></i>
                                            <span class="text-wrap">{{ $post['count_comments'] }} Comments</span>
                                        </div>
                                        <div class="d-flex justify-content-end align-items-center col-lg-4 col-md-4 col-sm-3 col-3">
                                            <i class="fa fa-share-alt" aria-hidden="true"></i>
                                            <span class="ml-1 text-wrap">Share</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-4">
            <div class="card">
              <div class="card-body text-center">
                @if($user->profileImg)
                    <img class="img-circle img-fluid" src="{{ url('public/Courseimages/'.$user->profileImg) }}" style="height: 200px; width: 200px" alt="">
                @else
                    <img class="img-circle img-fluid" src="https://alxgroup.com.au/wp-content/uploads/2016/04/dummy-post-horisontal.jpg" style="height: 200px; width: 200px" alt="">
                @endif
              </div>
              <div class="text-center">
                  <h4>{{ $user->fName." ".$user->lName }}</h4>
              </div>
            </div>
          </div>
          <div class="col-8">
            <div class="card">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>Full Name:</th>
                                <td>{{ $user->fName." ".$user->lName }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>
                                    @if($user->phone)
                                        {{ $user->phone }}
                                    @else
                                        Null
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Gender:</th>
                                <td>
                                    @if($user->gender)
                                        {{ $user->gender }}
                                    @else
                                        Null
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Date Of Birth:</th>
                                <td>
                                    @if($user->dob)
                                        {{ $user->dob }}
                                    @else
                                        Null
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>
                                    @if($user->address)
                                        {{ $user->address }}
                                    @else
                                        Null
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>About:</th>
                                <td>
                                    @if($user->about)
                                        {{ $user->about }}
                                    @else
                                        Null
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
      </div>
    </section> --}}
  </div>
@include('inc.footer')
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ url('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ url('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ url('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ url('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('assets/dist/js/adminlte.min.js') }}"></script>
<script>
  $(function () {
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

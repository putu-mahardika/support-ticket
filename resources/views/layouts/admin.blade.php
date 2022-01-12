@php
    $userNotifications = auth()->user()->notifications;
    $maxDisplayNotificationPopup = 3;
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="icon" href="{{ asset('theme/img/headset.png') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        .disabledContainer {
            pointer-events: none;
            opacity: 0.4;
        }
    </style>
    @yield('styles')
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        @include('partials.menu')
        <!-- End Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    @if (request()->route()->getName() == 'admin.home')
                        <form id="formMonthFilter" class="form-inline d-none d-sm-block">
                            <input type="month" id="monthFilter" name="monthFilter" class="form-control mr-2" style="width: 13rem; font-size: .8rem;" value="{{ now()->format('Y-m') }}">
                            <button type="submit" id="btnFormMonthFilter" class="btn btn-primary" style="font-size: .8rem;">
                                Apply
                            </button>
                        </form>
                    @endif

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Alerts -->
                        @if (request()->route()->getName() == 'admin.home')
                            <li class="nav-item d-block d-sm-none" data-toggle="modal" data-target="#modalFilterMonth">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-calendar"></i>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a id="toggleNotifications" class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Notifications
                                </h6>
                                <div id="notificationsList"></div>
                                @if ($userNotifications->count() > $maxDisplayNotificationPopup)
                                    <a class="dropdown-item text-center small text-gray-500" href="{{ route('admin.notif')}}">
                                        Show All Notification
                                    </a>
                                @endif
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        @if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('agent'))
                            <!-- Nav Item - Project Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-700 small">
                                        {{ auth()->user()->projects()->first()->name ?? '' }}
                                    </span>
                                </a>
                            </li>
                            <div class="topbar-divider d-none d-sm-block"></div>
                        @endif

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    Hallo {{ auth()->user()->name ?? '(null)' }}
                                </span>
                                <img class="img-profile rounded-circle" src="{{ empty(auth()->user()->photo) ? Avatar::create(auth()->user()->name)->toBase64() : asset(auth()->user()->photo->getUrl('thumb')) }}">
                                <i class="fas fa-caret-down p-2"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item text-gray-700" href="{{ route('admin.profile.index')}}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>
                </nav>
                <!-- End of Topbar -->

                {{-- Konten disini --}}
                <div class="container-fluid">

                    <!-- Content Row -->
                    @yield('content')

                </div>
                <!-- End Main Content -->

            </div>
            <!-- End Content Wrapper -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; {{ config('app.name') }} {{ now()->year }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>

        {{-- Logout Form --}}
        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
        <!-- Page Wrapper End-->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalFilterMonth" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formModalFilterMonth" class="form-group">
                        <input type="month" id="monthFilterModal" name="monthFilterModal" class="form-control mr-2" value="{{ now()->format('Y-m') }}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="formModalFilterMonth">Apply</button>
                </div>
            </div>
        </div>
    </div>

    @include('partials.photoswipe')

    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        $(document).ready(() => {
            mqttUserKey = "{{ md5(auth()->user()->email) }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            reloadNotification();
            onLoadParent;
        });

        window.reloadNotification = () => {
            $.get("{{ route('admin.notif') }}", function (res) {
                if (res.hasUnread) {
                    $('#toggleNotifications').html(`
                        <span class="badge badge-danger p-2">
                            <i class="fas fa-bell mr-1"></i>
                            ${res.label}
                        </span>
                    `);
                }
                else {
                    $('#toggleNotifications').html(`
                        <i class="fas fa-bell fa-lg"></i>
                    `);
                }
                $('#notificationsList').html(res.html);
            });
        }

        window.playNotifSound = () => {
            let audio = new Audio("{{ asset('sound/notif-sound-2.mp3') }}");
            audio.play();
            let promise = audio.play();
            if (promise !== undefined) {
                promise.then().catch();
            }
        }

        const getInitials = (name) => {
            let initials = name.split(' ');
            if (initials.length > 1) {
                initials = initials.shift().charAt(0) + initials.pop().charAt(0);
            } else {
                initials = name.substring(0, 2);
            }
            return initials.toUpperCase();
        }

        let lists = document.querySelectorAll('ul.navbar-nav li.nav-item');
        let collapseLists = document.querySelectorAll('ul.navbar-nav li.nav-item .collapse .collapse-inner a');

        lists.forEach(list => {
            if (list.childNodes[1].href == location.href) {
                list.classList.add('active');
            }
            else {
                list.classList.remove('active');
            }
        });

        collapseLists.forEach(collapseList => {
            if (collapseList.href == location.href) {
                collapseList.classList.add('active');
                collapseList.closest('.nav-item').classList.add('active');
            }
            else {
                collapseList.classList.remove('active');
            }
        });

        window.onLoadParent = () => {}
    </script>
    @yield('scripts')
</body>

</html>

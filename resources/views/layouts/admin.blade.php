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
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .disabledContainer {
            pointer-events: none;
            opacity: 0.4;
        }
    </style>
    @yield('styles')

    {{-- template SB-ADMIN-2 --}}

    <!-- Custom fonts for this template-->
    {{-- <link href="{{ asset('theme/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet"> --}}
    <!-- Custom styles for this template-->
    {{-- <link href="{{ asset('theme/css/sb-admin-2.min.css') }}" rel="stylesheet"> --}}
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
                            <li class="nav-item d-block d-sm-none">
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
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
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
                                <img class="img-profile rounded-circle fa-2x" src="{{ asset('theme/img/undraw_profile.svg') }}">
                            </a>
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

    {{-- Template bawaan --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            timer: 5000,
            position: 'top-end',
            timerProgressBar: true,
            showConfirmButton: false,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        // window.reloadNotification = () => {
        //     $.get("{{ route('admin.notif') }}", function (res) {
        //         if (res.hasUnread) {
        //             $('#toggleNotifications').html(`
        //                 <span class="badge badge-danger p-2">
        //                     <i class="fas fa-bell mr-1"></i>
        //                     ${res.label}
        //                 </span>
        //             `);
        //         }
        //         else {
        //             $('#toggleNotifications').html(`
        //                 <i class="fas fa-bell fa-lg"></i>
        //             `);
        //         }
        //         $('#notificationsList').html(res.html);
        //     });
        // }
        window.playNotifSound = () => {
            let audio = new Audio("{{ asset('sound/notif-sound-2.mp3') }}");
            audio.play();
            let promise = audio.play();
            if (promise !== undefined) {
                promise.then().catch();
            }
        }
    </script>
    {{-- <script src="{{ asset('js/main.js') }}"></script> --}}
    <script>
        $(document).ready(() => {
            mqttUserKey = "{{ md5(auth()->user()->email) }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            reloadNotification();
        });
        $(function () {
            let copyButtonTrans = "{{ trans('global.datatables.copy') }}";
            let csvButtonTrans = "{{ trans('global.datatables.csv') }}";
            let excelButtonTrans = "{{ trans('global.datatables.excel') }}";
            let pdfButtonTrans = "{{ trans('global.datatables.pdf') }}";
            let printButtonTrans = "{{ trans('global.datatables.print') }}";
            let colvisButtonTrans = "{{ trans('global.datatables.colvis') }}";

            let languages = {
                // 'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
                'en': '{{ asset('dt.json') }}'
            };

            $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, {
                className: 'btn'
            });
            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                    url: languages['{{ app()->getLocale() }}']
                },
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }, {
                    orderable: false,
                    searchable: false,
                    targets: -1
                }],
                select: {
                    style: 'multi+shift',
                    selector: 'td:first-child'
                },
                order: [],
                scrollX: true,
                pageLength: 100,
                dom: 'lBfrtip<"actions">',
                buttons: [{
                        extend: 'copy',
                        className: 'btn-default',
                        text: copyButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'btn-default',
                        text: csvButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn-default',
                        text: excelButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn-default',
                        text: pdfButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn-default',
                        text: printButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'colvis',
                        className: 'btn-default',
                        text: colvisButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            });

            $.fn.dataTable.ext.classes.sPageButton = '';
        });

        const getInitials = (name) => {
            let initials = name.split(' ');
            if (initials.length > 1) {
                initials = initials.shift().charAt(0) + initials.pop().charAt(0);
            } else {
                initials = name.substring(0, 2);
            }
            return initials.toUpperCase();
        }

        // List Menu Active
        let lists = document.querySelectorAll('ul.navbar-nav li.nav-item');

        lists.forEach(list => {
        console.log(list.childNodes[1].href);
        if (location.href == list.childNodes[1].href) {
                list.classList.add('active');
            }
            else {
                list.classList.remove('active');
            }
        });
    </script>
    <!-- Bootstrap core JavaScript-->
    {{-- <script src="{{ asset('theme/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}

    <!-- Core plugin JavaScript-->
    {{-- <script src="{{ asset('theme/vendor/jquery-easing/jquery.easing.min.js') }}"></script> --}}

    <!-- Custom scripts for all pages-->
    {{-- <script src="{{ asset('theme/js/sb-admin-2.min.js') }}"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.3.0-beta.4/lightgallery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.3.0-beta.4/plugins/thumbnail/lg-thumbnail.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.3.0-beta.4/plugins/zoom/lg-zoom.min.js"></script> --}}
    <script>
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
    </script>
    @yield('scripts')

</body>

</html>

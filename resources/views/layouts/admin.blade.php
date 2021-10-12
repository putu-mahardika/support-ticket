<!DOCTYPE html>
<html>
<head>
    <title>Help Desk</title>
    <link rel="icon" href="{{ asset('theme/img/headset.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- template bawaaan --}}
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    {{-- <link href="https://unpkg.com/@coreui/coreui@2.1.16/dist/css/coreui.min.css" rel="stylesheet" /> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/21.1.5/css/dx.common.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/21.1.5/css/dx.light.css" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/21.1.5/css/dx.common.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/21.1.5/css/dx.light.css" />
    @yield('styles')

    {{-- template SB-ADMIN-2 --}}

    <!-- Custom fonts for this template-->
    <link href="{{ asset('theme/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{ asset('theme/css/sb-admin-2.min.css') }}" rel="stylesheet">


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
                        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                                <i class="fa fa-bars"></i>
                            </button>
                            </button>

                            <!-- Topbar Navbar -->
                            <ul class="navbar-nav ml-auto">

                                <!-- Nav Item - Alerts -->
                                <li class="nav-item dropdown no-arrow mx-1">
                                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">    
                                                <i class="fas fa-bell fa-lg "></i>
                                        <!-- Counter - Alerts -->
                                        <span class="badge badge-danger badge-counter">3+</span>
                                    </a>
                                    <!-- Dropdown - Alerts -->
                                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                        aria-labelledby="alertsDropdown">
                                        <h6 class="dropdown-header">
                                            Notifikasi
                                        </h6>
                                        <a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-primary">
                                                    <i class="fas fa-file-alt text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">December 12, 2019</div>
                                                <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-success">
                                                    <i class="fas fa-donate text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">December 7, 2019</div>
                                                $290.29 has been deposited into your account!
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-warning">
                                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">December 2, 2019</div>
                                                Spending Alert: We've noticed unusually high spending for your account.
                                            </div>
                                        </a>
                                        <a class="dropdown-item text-center small text-gray-500" href="{{ route('admin.notif')}}">Show All Notification</a>
                                    </div>
                                </li>

                                <div class="topbar-divider d-none d-sm-block"></div>

                                 <!-- Nav Item - Project Information -->
                                <li class="nav-item dropdown no-arrow">
                                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->projects()->first()->name ?? '' }}</span>
                                    </a>
                                </li>

                                <div class="topbar-divider d-none d-sm-block"></div>

                                <!-- Nav Item - User Information -->
                                <li class="nav-item dropdown no-arrow">
                                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">Hallo {{ auth()->user()->name ?? '(null)' }}</span>
                                        <img class="img-profile rounded-circle  fa-2x" src="{{ asset('theme/img/undraw_profile.svg') }}">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://unpkg.com/@coreui/coreui@2.1.16/dist/js/coreui.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        $(function () {
            let copyButtonTrans   = "{{ trans('global.datatables.copy') }}";
            let csvButtonTrans    = "{{ trans('global.datatables.csv') }}";
            let excelButtonTrans  = "{{ trans('global.datatables.excel') }}";
            let pdfButtonTrans    = "{{ trans('global.datatables.pdf') }}";
            let printButtonTrans  = "{{ trans('global.datatables.print') }}";
            let colvisButtonTrans = "{{ trans('global.datatables.colvis') }}";

            let languages = {
                // 'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
                'en': 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/id.json'
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
                    targets  : 0
                }, {
                    orderable : false,
                    searchable: false,
                    targets   : -1
                }],
                select: {
                    style   : 'multi+shift',
                    selector: 'td:first-child'
                },
                order     : [],
                scrollX   : true,
                pageLength: 100,
                dom       : 'lBfrtip<"actions">',
                buttons   : [{
                        extend       : 'copy',
                        className    : 'btn-default',
                        text         : copyButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend       : 'csv',
                        className    : 'btn-default',
                        text         : csvButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend       : 'excel',
                        className    : 'btn-default',
                        text         : excelButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend       : 'pdf',
                        className    : 'btn-default',
                        text         : pdfButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend       : 'print',
                        className    : 'btn-default',
                        text         : printButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend       : 'colvis',
                        className    : 'btn-default',
                        text         : colvisButtonTrans,
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
    </script>
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('theme/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('theme/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('theme/js/sb-admin-2.min.js') }}"></script>
    @yield('scripts')
</body>
</html>

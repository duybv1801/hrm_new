<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trans('auth.nal_lg') }}</title>
    {{-- select 2 --}}

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" fade
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- iCheck -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.5/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css">
    <!-- daterangepicker -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css">
    <!-- tempusdominus -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">

    {{-- zoom_img --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                {{-- Notifications Dropdown Menu --}}
                @include('layouts.notifi')
                {{-- full screen --}}

                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-toggle="tooltip"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        title="Log" data-original-title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            @php
                use Illuminate\Support\Facades\Blade;
            @endphp
            <a href="{!! route('users.index') !!}" class="brand-link">
                <div class="text-center">
                    @if (Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="User Avatar"
                            class="brand-image img-circle elevation-3" style="max-width: 45px; opacity: .8">
                    @else
                        <img src="https://ron.nal.vn/api/files/avatar_tungts_human.png" alt="User Avatar"
                            class="brand-image img-circle elevation-3" style="max-width: 45px; opacity: .8">
                    @endif
                </div>
                <span class="brand-text font-weight-light">{{ Auth::user()->code }}</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        @include('layouts.menu')
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="content-wrapper">
            @yield('content')
        </div>
        <div class="col-4 link-wrap">
            <a href="" class="link" data-toggle="tooltip"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Log"
                data-original-title="Logout"><i class="fas fa-sign-out-alt"></i>{{ trans('passwords.sign_out') }}</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
        <footer class="main-footer">
            <footer class="footer"> {{ trans('auth.nal') }}<a href="#">
            </footer>
    </div>

    <!-- JavaScript Bootstrap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
    <!-- ./wrapper -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Sparkline -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
    <!-- JQVMap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jquery.vmap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/3.1.0/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <!-- Summernote -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/js/jquery.overlayScrollbars.min.js">
    </script>
    <!-- AdminLTE App -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.5/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.5/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.5/js/pages/dashboard.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js"></script>

    <!-- Tempusdominus Bootstrap 4 -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js">
    </script>
    {{-- Calendar --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    {{-- fancybox --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    {{-- select 2 --}}
    <!-- lightbox2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">

    <!-- lightbox2 JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    {{-- zoom_img --}}
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>


    <script>
        $(function() {
            $('.nav-link[data-widget="fullscreen"]').on('click', function() {
                if (
                    !document.fullscreenElement && // alternative standard method
                    !document.mozFullScreenElement &&
                    !document.webkitFullscreenElement &&
                    !document.msFullscreenElement
                ) {
                    if (document.documentElement.requestFullscreen) {
                        document.documentElement.requestFullscreen();
                    } else if (document.documentElement.mozRequestFullScreen) { // Firefox
                        document.documentElement.mozRequestFullScreen();
                    } else if (document.documentElement
                        .webkitRequestFullscreen) { // Chrome, Safari and Opera
                        document.documentElement.webkitRequestFullscreen();
                    } else if (document.documentElement.msRequestFullscreen) { // IE/Edge
                        document.documentElement.msRequestFullscreen();
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.mozCancelFullScreen) { // Firefox
                        document.mozCancelFullScreen();
                    } else if (document.webkitExitFullscreen) { // Chrome, Safari and Opera
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) { // IE/Edge
                        document.msExitFullscreen();
                    }
                }
            });
        });
    </script>
    <script>
        $(function() {
            // Date picker
            $('.reservationdate').datetimepicker({
                format: 'DD/MM/yyyy',
            });


            //Date and time picker
            $('.reservationdatetime').datetimepicker({
                format: 'DD/MM/YYYY HH:mm',
                icons: {
                    time: 'far fa-clock'
                },
                stepping: {{ $settings['block'] ?? 15 }},
            });

            //Date range picker
            $('.reservation').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - ',
                }
            })

            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'DD/MM/YYYY hh:ii'
                }
            })
            //Date range as a button
            $('#daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            }, function(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'))
            });

            //Timepicker        
            $('.timepicker').datetimepicker({
                format: 'HH:mm',
                stepping: {{ $settings['block'] ?? 15 }},
            });
            $('#reservationdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
            $('#reservation').daterangepicker({
                format: 'DD/MM/YYYY',
            })
        });
    </script>


    {{-- delete arlert modal --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Delete 1 --}}
    <script>
        function confirmDelete(event) {
            event.preventDefault();

            Swal.fire({
                title: "{{ trans('Are you sure you want to delete?') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: "{{ trans('Yes, Delete it!') }}",
                cancelButtonText: "{{ trans('Cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }
    </script>
    <script>
        function confirmCancel(event) {
            event.preventDefault();

            Swal.fire({
                title: "{{ trans('Are you sure you want to cancel?') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: "{{ trans('Yes, Cancel it!') }}",
                cancelButtonText: "{{ trans('Cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }
    </script>


    {{-- show modal edit holiday --}}
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('click', '#edit_holiday', function() {
                var id = $(this).data('id');

                $.get('/holidays' + '/' + id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Post");
                    $('#editModal').modal('show');
                    var editForm = $('#editModal').find('form');
                    editForm.attr('action', editForm.attr('action').replace('__id__', id));
                    $('#titleHoliday').val(data.title);
                    var formattedDate = moment(data.date, 'YYYY-MM-DD').format('DD/MM/YYYY');
                    $('#dateHoliday').val(formattedDate);
                });
            });
        });
    </script>
    <script>
        function previewAvatar(event) {
            var input = event.target;

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var preview = document.getElementById('avatar-preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datetimepicker@4.17.47/build/js/bootstrap-datetimepicker.min.js">
    </script>

    <script>
        $(document).ready(function() {
            $('.datetime_24h').datetimepicker({
                format: 'DD/MM/YYYY HH:mm',
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-calendar-check',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                },
                stepping: {{ $settings['block'] }},
            });
        });
    </script>
    {{-- total caculator --}}

    <script>
        function calculateTotalHours() {
            var from_datetime = document.getElementById('from_datetimenew').value;
            var to_datetime = document.getElementById('to_datetimenew').value;

            var fromDate = moment(from_datetime, 'DD/MM/YYYY HH:mm');
            var toDate = moment(to_datetime, 'DD/MM/YYYY HH:mm');

            // Calculate the total working time
            var totalDuration = moment.duration(toDate.diff(fromDate));


            // Subtract the lunch break time    
            var lunchBreakStart = moment('{{ $settings['lunch_time_start'] }}', 'HH:mm');
            var lunchBreakEnd = moment('{{ $settings['lunch_time_end'] }}', 'HH:mm');
            var lunchBreakDuration = moment.duration(lunchBreakEnd.diff(lunchBreakStart));

            // Check if the start date and end date are the same day

            if (fromDate.isSame(toDate, 'day')) {
                // If the same day, only subtract the lunch break time if any
                if (fromDate.isBefore(lunchBreakStart) && toDate.isAfter(lunchBreakEnd)) {
                    totalDuration.subtract(lunchBreakDuration);
                } else if (fromDate.isBetween(lunchBreakStart, lunchBreakEnd) || toDate.isBetween(lunchBreakStart,
                        lunchBreakEnd)) {
                    var overlapStart = moment.max(fromDate, lunchBreakStart);
                    var overlapEnd = moment.min(toDate, lunchBreakEnd);
                    var overlapDuration = moment.duration(overlapEnd.diff(overlapStart));
                    totalDuration.subtract(overlapDuration);
                }
            } else {
                // If different, calculate the number of days difference and multiply by the lunch break time for each day
                var daysDiff = toDate.diff(fromDate, 'DD/MM/YYYY');
                var lunchBreakTotal = lunchBreakDuration.clone().multiply(daysDiff);
                totalDuration.subtract(lunchBreakTotal);
            }

            // Format the result
            var totalHours = totalDuration.asHours().toFixed(2);

            document.getElementById('total').value = totalHours;
        }
    </script>


    {{-- search fast --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get all the forms on the page
            const forms = document.querySelectorAll("form");

            // Add an event listener to each form for keydown events
            forms.forEach(function(form) {
                form.addEventListener("keydown", function(event) {
                    if (event.key === "Enter") {
                        event.preventDefault();
                        form.submit();
                    }
                });
            });
        });
    </script>
    {{-- multy choice cc --}} <!-- Include the Select2 library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#cc').select2();
            $('#user').select2();
            $('#user_export').select2();
        });
    </script>
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
            color: black;
            list-style: none;
        }
    </style>
    {{--  modal cancel --}}
    <script>
        $(document).ready(function() {
            $('#cancelModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var remoteId = button.data('id');

                var modal = $(this);
                modal.find('.modal-title').text('{{ trans('Confirm cancellation!') }} ');

                var form = modal.find('form');
                var action = form.attr('action');
                action = action.replace(/\/\d+$/, '/' + remoteId);
                form.attr('action', action);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#cancelModal').on('hidden.bs.modal', function() {
                var form = $(this).find('form');
                form[0].reset();

                var commentInput = form.find('#comment');
                commentInput.val('');
            })
        });
    </script>
    {{-- change label name when import file --}}
    <script>
        document.getElementById('csv_file').addEventListener('change', function(event) {
            const fileInput = event.target;
            const fileName = fileInput.files[0].name;
            const label = fileInput.nextElementSibling;
            label.innerText = fileName;
        });
    </script>

    {{-- change label name when import file --}}
    <script>
        document.getElementById('csv_file').addEventListener('change', function(event) {
            const fileInput = event.target;
            const fileName = fileInput.files[0].name;
            const label = fileInput.nextElementSibling;
            label.innerText = fileName;
        });
    </script>
    {{-- export timesheet modal --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const exportButton = document.getElementById("exportButton");
            const closeButton = document.querySelector("#exportTimesheet .close");
            exportButton.addEventListener("click", function() {
                const exportTimesheetModal = document.getElementById("exportTimesheet");
                const closeEvent = new MouseEvent("click", {
                    bubbles: true,
                    cancelable: true,
                    view: window
                });
                closeButton.dispatchEvent(closeEvent);
            });
        });
    </script>
</body>

</html>

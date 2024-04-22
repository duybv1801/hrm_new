@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ trans('holiday.calendar') }}</h1>
            </div>
    </section>
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-body p-1">
                        <!-- THE CALENDAR -->
                        <div id="calendar" class="mx-auto col-md-9"></div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var holidays = <?php echo json_encode($events); ?>;
            var events = [];
            holidays.forEach(function(eventData) {
                var event = {
                    title: eventData.title,
                    date: eventData.date,
                };
                events.push(event);
            });
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'vi',
                customButtons: {
                    today: {
                        text: "{{ trans('Today') }}",
                        click: function() {
                            var currentDate = new Date();
                            calendar.gotoDate(currentDate);
                        }
                    },
                },

                events: events
            });
            calendar.render();
        });
    </script>
@endsection

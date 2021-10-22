@extends('layouts.admin')
@section('content')
    <div class="row mb-5">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    Create Work Clock
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.workclock.store') }}" method="POST">
                        @csrf
                        <div class="form-group {{ $errors->has('day') ? 'has-error' : '' }}">
                            <label for="day">Day*</label>
                            <select name="day" id="day" class="form-control" required autofocus>
                                @foreach (Carbon\Carbon::getDays() as $day)
                                    <option @if(old('day') == $day) selected @endif value="{{ $day }}">{{ $day }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('day'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('day') }}
                                </em>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('time_start') ? 'has-error' : '' }}">
                            <label for="time_start">Time Start*</label>
                            <input type="time" name="time_start" id="time_start" class="form-control" required onchange="getTimeEnd();" value="{{ old('time_start') }}">
                            @if($errors->has('time_start'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('time_start') }}
                                </em>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('duration') ? 'has-error' : '' }}">
                            <label for="duration">Duration*</label>
                            <input type="number" name="duration" id="duration" class="form-control" min="1" max="24" step="1" required onchange="getTimeEnd();" value="{{ old('duration') }}">
                            @if($errors->has('duration'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('duration') }}
                                </em>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('time_end') ? 'has-error' : '' }}">
                            <label for="time_end">Time End</label>
                            <input type="time" name="time_end" id="time_end" class="form-control" disabled>
                            @if($errors->has('time_end'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('time_end') }}
                                </em>
                            @endif
                        </div>

                        <div>
                            <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(() => {
            getTimeEnd();
        });

        function getTimeEnd() {
            let timeStart = $('#time_start');
            let timeEnd = $('#time_end');
            let duration = $('#duration');

            if (timeStart.val() !== "" && duration.val() !== "") {
                timeEnd.val(
                    moment(timeStart.val(), 'hh:mm').add(duration.val(), 'h').format('HH:mm')
                );
            }
        }

    </script>
@endsection

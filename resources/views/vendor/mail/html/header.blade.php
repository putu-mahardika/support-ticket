<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
{{-- <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo"> --}}
<<<<<<< HEAD
=======
<img src="{{ asset('/images/logo-group-1.png') }}" class="logo" alt="Laravel Logo">
>>>>>>> f4f167cd8a10c0963ae35d53ec5ff0c8799d012c
@else
{{ $slot }}
@endif
</a>
</td>
</tr>

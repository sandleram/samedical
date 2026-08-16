@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="row src_iframe">
    @if (empty($url))
        <div class="alert alert-warning">URL de BI não configurada para o tenant atual.</div>
    @endif
</div>

@push('scripts')
<script>
$(document).ready(function () {
    @if (! empty($url))
        var iframe = '<iframe src="' + @json($url) + '" width="100%" height="600" style="border:none;"></iframe>';
        var parentHeight = $(window).height() - 100;
        var iframe2 = iframe.replace('height="600"', 'height="' + parentHeight + '"');
        setTimeout(function () {
            $("body").removeClass("minified");
            $('.minifyme').click();
        }, 1000);
        setTimeout(function () { $('.src_iframe').html(iframe2); }, 1500);
    @endif
});
</script>
@endpush
@endsection

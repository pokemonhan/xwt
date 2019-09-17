<!--begin:: Global Mandatory Vendors -->
@section('mandatory-endscripts')
    @include('common.mandatory.mandatory-endscripts')
@show

<!--end:: Global Mandatory Vendors -->

<!--begin:: Global Optional Vendors -->
@section('optional-endscripts')
    @include('common.optional.optional-endscripts')
@show

<!--end:: Global Optional Vendors -->

<!--begin::Global Theme Bundle -->
<script src="{{ asset('assets/demo/base/scripts.bundle.js') }}" type="text/javascript"></script>

<!--end::Global Theme Bundle -->

<!--begin::Page Vendors -->
<script src="{{ asset('assets/vendors/custom/fullcalendar/fullcalendar.bundle.js') }}" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Scripts -->
<script src="{{ asset('assets/app/js/dashboard.js') }}" type="text/javascript"></script>

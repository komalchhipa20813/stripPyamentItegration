<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script>
    $('[data-toggle="tooltip"]').tooltip();
</script>
<script type="text/javascript">
    var aurl = {!! json_encode(url('/')) !!}
    /* Ajax Set Up */
    $.ajaxSetup({
         headers: {
             "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
         },
     });
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

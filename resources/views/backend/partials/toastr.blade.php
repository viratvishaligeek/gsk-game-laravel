<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    $(function () {
        if (!window.toastr) {
            return;
        }

        window.toastr.options = {
            closeButton: true,
            debug: false,
            newestOnTop: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            preventDuplicates: false,
            onclick: null,
            showDuration: '300',
            hideDuration: '1000',
            timeOut: '4000',
            extendedTimeOut: '1000',
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut'
        };

        @if (session('success'))
            window.toastr.success(@json(session('success')), 'Success!');
        @endif

        @if (session('error'))
            window.toastr.error(@json(session('error')), 'Error!');
        @endif

        @if (session('warning'))
            window.toastr.warning(@json(session('warning')), 'Warning!');
        @endif

        @if (session('info'))
            window.toastr.info(@json(session('info')), 'Info!');
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                window.toastr.error(@json($error), 'Validation Error');
            @endforeach
        @endif
    });
</script>

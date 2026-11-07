@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function () {
            if (window.feather) {
                window.feather.replace();
            }

            $(document).on('click.adminWalletRequests', '.view-proof-btn', function () {
                var imageUrl = $(this).data('image');
                var $image = $('#proofImage');

                $image.off('error.adminWalletRequests').on('error.adminWalletRequests', function () {
                    var fallbackSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="640" height="360"><rect width="100%" height="100%" fill="#f1f5f9"/><text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" fill="#64748b" font-family="Arial" font-size="22">Image unavailable</text></svg>';
                    $(this).attr('src', 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(fallbackSvg));
                }).attr('src', imageUrl || '');
            });

            $(document).on('click.adminWalletRequests', '.approve-btn', function () {
                var $button = $(this);
                var form = $button.closest('form').get(0);
                var name = $button.data('name') || 'User';
                var amount = $button.data('amount') || '0';
                var safeName = $('<div>').text(name).html();
                var safeAmount = $('<div>').text(amount).html();

                if (!form || !window.Swal || typeof window.Swal.fire !== 'function') {
                    if (form && window.confirm('Approve Wallet Top-up?')) {
                        form.submit();
                    }
                    return;
                }

                window.Swal.fire({
                    title: 'Approve Wallet Top-up?',
                    html: '<div class="text-center"><p class="mb-1">User: <strong>' + safeName + '</strong></p><p>Amount: <strong class="text-success">₹' + safeAmount + '</strong></p><small class="text-muted">This amount will be credited to the user&#39;s wallet.</small></div>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1cc88a',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Yes, Approve',
                    cancelButtonText: 'Cancel'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            $(document).on('click.adminWalletRequests', '.reject-btn', function () {
                var $button = $(this);
                $('#rejectUserName').text($button.data('name') || '');
                $('#rejectForm').attr('action', $button.data('action') || '');
            });
        });
    </script>
@endsection
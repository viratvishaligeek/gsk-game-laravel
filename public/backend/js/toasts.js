$(function () {
    if (!window.bootstrap) {
        return;
    }

    var $basicToast = $('#toastBasic');
    var $noAutohideToast = $('#toastNoAutohide');
    var $basicTrigger = $('#toastBasicTrigger');
    var $noAutohideTrigger = $('#toastNoAutohideTrigger');

    var basicToast = $basicToast.length
        ? window.bootstrap.Toast.getOrCreateInstance($basicToast.get(0))
        : null;
    var noAutohideToast = $noAutohideToast.length
        ? window.bootstrap.Toast.getOrCreateInstance($noAutohideToast.get(0))
        : null;

    $basicTrigger.on('click.adminToasts', function () {
        if (basicToast) {
            basicToast.show();
        }
    });

    $noAutohideTrigger.on('click.adminToasts', function () {
        if (noAutohideToast) {
            noAutohideToast.show();
        }
    });
});

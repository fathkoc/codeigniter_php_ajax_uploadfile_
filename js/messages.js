(function($) {
    $(".delete-message").jConfirm().on('confirm', function(e){
        var id = $(this).data("id");
        var data = [
            {
                name: 'csrf',
                value: Cookies.get('csrf_token')
            },{
                name : 'id',
                value : id
            }]

        $.ajax({
            method: 'post',
            dataType: 'json',
            data: data,
            url: url + 'Messages/delete_message',
            success: function(result) {
                if (result.status === true) {
                    $('#delete'+id).remove();
                    toastr.success(" Silindi.");

                } else {
                    toastr.warning(result.error);
                }

            },
            error: function() {
                toastr.error("Bağlantı Hatası Tekrar Deneyin");
            }
        });
    });

    $(".seen-button").click(function(){
        var id = $(this).data("id");

        var data = [
            {
                name: 'csrf',
                value: Cookies.get('csrf_token')
            },{
                name : 'status',
                value : status
            },{
                name : 'id',
                value : id
            }]
        $.ajax({
            method: 'post',
            dataType: 'json',
            data: data,
            url: url + 'Messages/seen_message',
            success: function(result) {
                if (result.status === true) {
                    $(".seen-button"+id).removeClass("btn-success");
                    $(".seen-button"+id).addClass("btn-secondary");
                }
            }
        });
    });


})(jQuery);

(function($) {
    $("#city_box").change(function(){
        console.log($(this).val());
        var city_id = $(this).val();

        var data = [
            {
                name: 'csrf',
                value: Cookies.get('csrf_token')
            },{
                name: 'city_id',
                value: city_id
        }]

        $.ajax({
            method: 'post',
            dataType: 'json',
            data: data,
            url: url + 'Ajax/get_counties',
            success: function(result) {
                if (result.status === true) {
                    console.log(result.counties);
                    $("#counties_box").empty();
                    var option = '';
                    $.each( result.counties, function( key, val ){
                        option += '<option value="'+val.id+'">'+val.name+'</option>';
                    });
                    $("#counties_box").append(option);
                } else {
                    toastr.warning(result.error);
                }
            },
            error: function() {
                toastr.error("Bağlantı Hatası Tekrar Deneyin");
            }
        });
    })

    $(".remove-images").jConfirm().on('confirm', function(e){

        var data = [
            {
                name: 'csrf',
                value: Cookies.get('csrf_token')
            }]

            $.ajax({
                method: 'post',
                dataType: 'json',
                data: data,
                url: url + 'Ajax/remove_images',
                success: function(result) {
                    if (result.status === true) {
                        toastr.success("Bellekteki Resimler Kaldırıldı");
                    } else {
                        toastr.warning(result.error);
                    }
                },
                error: function() {
                    toastr.error("Bağlantı Hatası Tekrar Deneyin");
                }
            });
    });

    $(".delete-archive-image").jConfirm().on('confirm', function(e){

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
            url: url + 'Ajax/delete_archive_image',
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
})(jQuery);


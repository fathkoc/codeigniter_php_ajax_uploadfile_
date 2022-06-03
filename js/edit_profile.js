(function($) {
    $(document).on("submit", "#infoEditProfileGeneralForm", function(event){
        event.preventDefault();
        var serialized = $(this).serializeArray();
        serialized.push({
            name: 'csrf',
            value: Cookies.get('csrf_token')
        });

        console.log(serialized);
        $.ajax({
            method: 'post',
            dataType: 'json',
            data: serialized,
            url: url + 'Edit_profile/update_account_genaral',
            success: function(result) {
                if (result.status === true) {
                    toastr.success("Giriş Bilgileriniz Güncellendi");
                    setTimeout(
                        function(){
                            window.location.reload();
                        }, 1000);
                } else {
                    toastr.warning(result.error);
                }

            },
            error: function() {
                toastr.error("Bağlantı Hatası Tekrar Deneyin");
            }
        });
    });

    $(document).on("submit", "#infoEditPassForm", function(event){
        event.preventDefault();
        var serialized = $(this).serializeArray();
        serialized.push({
            name: 'csrf',
            value: Cookies.get('csrf_token')
        });
        $.ajax({
            method: 'post',
            dataType: 'json',
            data: serialized,
            url: url + 'Edit_profile/update_pass_account',
            success: function(result) {
                if (result.status === true) {
                    toastr.success("Giriş Bilgileriniz Güncellendi");
                    setTimeout(
                        function(){
                            window.location.reload();
                        }, 1000);
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

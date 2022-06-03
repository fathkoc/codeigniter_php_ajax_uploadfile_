(function($) {
		$(document).on("submit", "#infoForm", function(event){
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
            url: url + 'Login/login',
            success: function(result) {
                if (result.status === true) {
                    toastr.success("Giriş Başarılı Yönlendiriliyorsunuz");
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
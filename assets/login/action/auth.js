$(function(){
    $('#loginform').submit(function(e){
        e.preventDefault();
        var uname = $('#uname').val();
        var pass = $('#pass').val();
        if (uname != "" && pass != "") {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:{uname:uname,pass:pass},
                url:'action_login',
                success:function(results){
                    if (results.condition == 2) {
                        let timerInterval
                        Swal.fire({
                            icon:'success',
                            title:'Sukses Login !',
                            text:results.pesan,
                            html: 'Mohon Tunggu, Sedang mengalihkan ',
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location.href=results.url;
                            }
                        })
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed !',
                            text: results.pesan
                        });
                    }
                }
            })
        }else{
            Swal.fire({
                icon: 'Warning',
                title: 'Peringatan !',
                text: 'Field Tidak boleh kosong !'
            })
        }
    })
})
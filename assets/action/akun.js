$(function(){
    $('#li-kelola').addClass('active submenu');
    $('#base').addClass('show');
    $('#sub-akun').addClass('active');

    $('#table-akun').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"akunLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $(document).on('click','.setStatus',function(){
        var id = $(this).attr('id');
        var status = $(this).attr('data-status');
        swal({
            title: 'Yakin mengganti Status Akun?',
            text: "Akun akan terganti menjadi statusnya menjadi aktif !",
            icon: 'warning',
            buttons:{
                cancel: {
                    visible: true,
                    text : 'cancel!',
                    className: 'btn btn-danger'
                },        			
                confirm: {
                    text : 'Ya, Yakin !',
                    className : 'btn btn-success'
                }
            }
        }).then((willSet) => {
            if (willSet) {
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{status:status,id:id},
                    url:'setStatus',
                    success:function(data){
                        if (data.cond == "1") {
                            swal("Berhasil ", {
                                icon: 'success',
                                buttons: false,
                                timer: 2000,
                            }).then((result)=>{
                                $('#table-akun').DataTable().ajax.reload();
                            });
                        }else{
                            swal("Gagal", {
                                icon: 'error',
                                buttons: false,
                                timer: 2000,
                            }).then((result)=>{
                                $('#table-akun').DataTable().ajax.reload();
                            });
                        }
                    }
                })
            }
        })
    })

    $(document).on('click','.resetPass',function(){
        var id = $(this).attr('id');
        var induk = $(this).attr('data-induk');
        swal({
            title: 'Yakin Reset password akun ini ?',
            text: "Password akan ter-reset kembali ke nomor induk !",
            icon: 'warning',
            buttons:{
                cancel: {
                    visible: true,
                    text : 'cancel!',
                    className: 'btn btn-danger'
                },        			
                confirm: {
                    text : 'Ya, Yakin !',
                    className : 'btn btn-success'
                }
            }
        }).then((willSet) => {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:{id:id,induk:induk},
                url:'resetPass',
                success:function(data){
                    if (data.cond == "1") {
                        swal('Berhasil melakukan reset Password !',{
                            icon:'success',
                            button:'false',
                            timer:2000
                        }).then((result)=>{
                            $('#table-akun').DataTable().ajax.reload();
                        })
                    }else{
                        swal('Gagal Melakukan Reset Password !',{
                            icon:'error',
                            button:'false',
                            timer:2000
                        }).then((result)=>{
                            $('#table-akun').DataTable().ajax.reload();
                        })
                    }
                }
            })
        });
    })

})
$(function(){
    $('#li-kelola').addClass('active submenu');
    $('#base').addClass('show');
    $('#sub-walimurid').addClass('active');

    $('#table-walimurid').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"walimuridLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-tambah-walimurid').click(function(){
        $('#nama_walimurid').val('');
        $('#induk_siswa_walimurid').val('');
        $('#telp_walimurid').val('');
        $('#modal-walimurid').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Tambah Kelas');
        $('#operation').val('tambah');
        // $('#warning').html('<i> Akun akan otomatis terbuat dengan username dan password sesuai dengan nomor induk !');
    })

    $(document).on('click','.editWalimurid',function(){
        var id_walimurid = $(this).attr('id');
        $('#modal-walimurid').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Edit Walimurid');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id_walimurid:id_walimurid},
            url:'walimuridById',
            success:function(result){
                $('#nama_walimurid').val(result.nama_walimurid);
                $('#induk_siswa_walimurid').val(result.induk_siswa_walimurid);
                $('#telp_walimurid').val(result.telp_walimurid);
                $('#id_walimurid').val(result.id_walimurid);
                $('#operation').val('edit');
                // $('#warning').html('<i> Mengganti Nomor Induk akan mengganti nomor induk di akun juga ! </i>');
            }
        })
    })

    $('#form-walimurid').submit(function(e){
        e.preventDefault();   
        $.ajax({
            url: 'doWalimurid',
            data: new FormData(this),
            processData: false,
            contentType: false,
            method: 'POST',
            dataType:'JSON',
            success: function(data){
                if (data.cond == "1") {
                    swal("Berhasil !", {
                        icon: 'success',
                        buttons: false,
                        timer: 3000,
                    });
                    $('#modal-walimurid').modal('hide');
                    $('#table-walimurid').DataTable().ajax.reload();
                    $('#nama_walimurid').val();
                    $('#induk_siswa_walimurid').val();
                    $('#telp_walimurid').val();
                    $('#id_walimurid').val();
                    $('#operation').val();
                }else if(data.cond == "0"){
                    swal("Gagal", {
                        icon: 'error',
                        buttons: false,
                        timer: 3000,
                    });
                }
            }
        });
    })

    $(document).on('click','.deleteWalimurid',function(){
        swal({
            title: 'Yakin menghapus data ?',
            text: "Walimurid akan terhapus !",
            icon: 'warning',
            buttons:{
                cancel: {
                    visible: true,
                    text : 'Tidak, cancel!',
                    className: 'btn btn-danger'
                },        			
                confirm: {
                    text : 'Ya, Hapus Saja!',
                    className : 'btn btn-success'
                }
            }
        }).then((willdelete) => {
            if (willdelete) {
                var id_walimurid = $(this).attr('id');
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{id_walimurid:id_walimurid},
                    url:'deleteWalimurid',
                    success:function(result){
                        swal('Berhasil !',{
                            icon:'success',
                            button:false,
                            timer:2000
                        }).then((result)=>{
                            $('#table-walimurid').DataTable().ajax.reload();
                        })
                    }
                })
            }
        })
    })
})
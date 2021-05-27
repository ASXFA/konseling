$(function(){
    // $('#li-user').attr('class','nav-item submenu');
    // $('#base').attr('class','collapse show');
    // $('#sub-guru').attr('class','active');

    $('#table-sanksi').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"sanksiLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-tambah-sanksi').click(function(){
        $('#nama_sanksi').val('');
        $('#jumlah_poin_sanksi').val('');
        $('#modal-sanksi').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Tambah Sanksi');
        $('#operation').val('tambah');
        // $('#warning').html('<i> Akun akan otomatis terbuat dengan username dan password sesuai dengan nomor induk !');
    })

    $(document).on('click','.editSanksi',function(){
        var id_sanksi = $(this).attr('id');
        $('#modal-sanksi').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Edit Sanksi');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id_sanksi:id_sanksi},
            url:'sanksiById',
            success:function(result){
                $('#nama_sanksi').val(result.nama_sanksi);
                $('#jumlah_poin_sanksi').val(result.jumlah_poin_sanksi);
                $('#id_sanksi').val(result.id_sanksi);
                $('#operation').val('edit');
                // $('#warning').html('<i> Mengganti Nomor Induk akan mengganti nomor induk di akun juga ! </i>');
            }
        })
    })

    $('#form-sanksi').submit(function(e){
        e.preventDefault();   
        $.ajax({
            url: 'doSanksi',
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
                    $('#modal-sanksi').modal('hide');
                    $('#table-sanksi').DataTable().ajax.reload();
                    $('#nama_sanksi').val();
                    $('#jumlah_poin_sanksi').val();
                    $('#id_sanksi').val();
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

    $(document).on('click','.deleteSanksi',function(){
        swal({
            title: 'Yakin menghapus data ?',
            text: "Jenis Pelanggaran akan terhapus !",
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
                var id_sanksi = $(this).attr('id');
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{id_sanksi:id_sanksi},
                    url:'deleteSanksi',
                    success:function(result){
                        swal('Berhasil !',{
                            icon:'success',
                            button:false,
                            timer:2000
                        }).then((result)=>{
                            $('#table-sanksi').DataTable().ajax.reload();
                        })
                    }
                })
            }
        })
    })
})
$(function(){
    // $('#li-user').attr('class','nav-item submenu');
    // $('#base').attr('class','collapse show');
    // $('#sub-guru').attr('class','active');

    $('#table-siswa').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"siswaLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-tambah-siswa').click(function(){
        $('#induk_siswa').val('');
        $('#old_induk').val('');
        $('#nama_siswa').val('');
        $('#jabatan_siswa').val('');
        $('#alamat_siswa').val('');
        $('#jk_siswa').val('');
        $('#ortu_siswa').val('');
        $('#id_siswa').val('');
        $('#operation').val('');
        $('#modal-siswa').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Tambah Siswa');
        $('#operation').val('tambah');
        $('#warning').html('<i> Akun akan otomatis terbuat dengan username dan password sesuai dengan nomor induk !');
    })

    $(document).on('click','.editSiswa',function(){
        var induk = $(this).attr('id');
        $('#modal-siswa').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Edit Siswa');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{induk:induk},
            url:'siswaByInduk',
            success:function(result){
                $('#induk_siswa').val(result.induk_siswa);
                $('#old_induk').val(result.induk_siswa);
                $('#nama_siswa').val(result.nama_siswa);
                $('#jabatan_siswa').val(3);
                $('#alamat_siswa').val(result.alamat_siswa);
                $('#jk_siswa').val(result.jk_siswa);
                $('#ortu_siswa').val(result.ortu_siswa);
                $('#id_siswa').val(result.id_siswa);
                $('#operation').val('edit');
                $('#warning').html('<i> Mengganti Nomor Induk akan mengganti nomor induk di akun juga ! </i>');
            }
        })
    })

    $('#form-siswa').submit(function(e){
        e.preventDefault();   
        $.ajax({
            url: 'doSiswa',
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
                    $('#modal-siswa').modal('hide');
                    $('#table-siswa').DataTable().ajax.reload();
                    $('#induk_siswa').val();
                    $('#old_induk').val();
                    $('#nama_siswa').val();
                    $('#jabatan_siswa').val();
                    $('#alamat_siswa').val();
                    $('#jk_siswa').val();
                    $('#ortu_siswa').val();
                    $('#id_siswa').val();
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

    $(document).on('click','.deleteSiswa',function(){
        swal({
            title: 'Yakin menghapus data ?',
            text: "Akun dengan induk tersebut akan terhapus juga !",
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
                var induk = $(this).attr('id');
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{induk:induk},
                    url:'deleteSiswa',
                    success:function(result){
                        swal({
                            icon: 'success',
                            title: 'Berhasil dihapus !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-siswa').DataTable().ajax.reload();
                            }else if(results.isConfirmed){
                                $('#table-siswa').DataTable().ajax.reload();
                            }
                        })
                    }
                })
            }
        })
    })
})
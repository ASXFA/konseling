$(function(){
    $('#li-kelola').addClass('active submenu');
    $('#base').addClass('show');
    $('#sub-kelas').addClass('active');

    $('#table-kelas').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"kelasLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-tambah-kelas').click(function(){
        $('#kode_kelas').val('');
        $('#nama_kelas').val('');
        $('#keterangan_kelas').val('');
        $('#id_guru_kelas').val('');
        $('#modal-kelas').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Tambah Kelas');
        $('#operation').val('tambah');
        // $('#warning').html('<i> Akun akan otomatis terbuat dengan username dan password sesuai dengan nomor induk !');
    })

    $(document).on('click','.editKelas',function(){
        var id_kelas = $(this).attr('id');
        $('#modal-kelas').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Edit Kelas');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id_kelas:id_kelas},
            url:'kelasById',
            success:function(result){
                $('#kode_kelas').val(result.kode_kelas);
                $('#nama_kelas').val(result.nama_kelas);
                $('#keterangan_kelas').val(result.keterangan_kelas);
                $('#id_guru_kelas').val(result.id_guru_kelas);
                $('#id_kelas').val(result.id_kelas);
                $('#operation').val('edit');
                $('#warning').html('<i> Mengganti Nomor Induk akan mengganti nomor induk di akun juga ! </i>');
            }
        })
    })

    $('#form-kelas').submit(function(e){
        e.preventDefault();   
        $.ajax({
            url: 'doKelas',
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
                    $('#modal-kelas').modal('hide');
                    $('#table-kelas').DataTable().ajax.reload();
                    $('#kode_kelas').val();
                    $('#nama_kelas').val();
                    $('#keterangan_kelas').val();
                    $('#id_guru_kelas').val();
                    $('#id_kelas').val();
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

    $(document).on('click','.deleteKelas',function(){
        swal({
            title: 'Yakin menghapus data ?',
            text: "Kelas akan terhapus !",
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
                var id_kelas = $(this).attr('id');
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{id_kelas:id_kelas},
                    url:'deleteKelas',
                    success:function(result){
                        swal('Berhasil !',{
                            icon:'success',
                            button:false,
                            timer:2000
                        }).then((result)=>{
                            $('#table-kelas').DataTable().ajax.reload();
                        })
                    }
                })
            }
        })
    })
})
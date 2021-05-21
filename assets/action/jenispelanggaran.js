$(function(){
    // $('#li-user').attr('class','nav-item submenu');
    // $('#base').attr('class','collapse show');
    // $('#sub-guru').attr('class','active');

    $('#table-jenispelanggaran').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"jenispelanggaranLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-tambah-jenispelanggaran').click(function(){
        $('#kode_jenis_pelanggaran').val('');
        $('#nama_jenis_pelanggaran').val('');
        $('#kategori_jenis_pelanggaran').val('');
        $('#poin_jenis_pelanggaran').val('');
        $('#modal-jenispelanggaran').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Tambah Jenis Pelanggaran');
        $('#operation').val('tambah');
        // $('#warning').html('<i> Akun akan otomatis terbuat dengan username dan password sesuai dengan nomor induk !');
    })

    $(document).on('click','.editJenispelanggaran',function(){
        var id_jenis_pelanggaran = $(this).attr('id');
        $('#modal-jenispelanggaran').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Edit Jenis Pelanggaran');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id_jenis_pelanggaran:id_jenis_pelanggaran},
            url:'jenispelanggaranById',
            success:function(result){
                $('#kode_jenis_pelanggaran').val(result.kode_jenis_pelanggaran);
                $('#nama_jenis_pelanggaran').val(result.nama_jenis_pelanggaran);
                $('#kategori_jenis_pelanggaran').val(result.kategori_jenis_pelanggaran);
                $('#poin_jenis_pelanggaran').val(result.poin_jenis_pelanggaran);
                $('#id_jenis_pelanggaran').val(result.id_jenis_pelanggaran);
                $('#operation').val('edit');
                // $('#warning').html('<i> Mengganti Nomor Induk akan mengganti nomor induk di akun juga ! </i>');
            }
        })
    })

    $('#form-jenispelanggaran').submit(function(e){
        e.preventDefault();   
        $.ajax({
            url: 'doJenispelanggaran',
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
                    $('#modal-jenispelanggaran').modal('hide');
                    $('#table-jenispelanggaran').DataTable().ajax.reload();
                    $('#kode_jenis_pelanggaran').val();
                    $('#nama_jenis_pelanggaran').val();
                    $('#kategori_jenis_pelanggaran').val();
                    $('#poin_jenis_pelanggaran').val();
                    $('#id_jenis_pelanggaran').val();
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

    $(document).on('click','.deleteJenispelanggaran',function(){
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
                var id_jenis_pelanggaran = $(this).attr('id');
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{id_jenis_pelanggaran:id_jenis_pelanggaran},
                    url:'deleteJenispelanggaran',
                    success:function(result){
                        swal('Berhasil !',{
                            icon:'success',
                            button:false,
                            timer:2000
                        }).then((result)=>{
                            $('#table-jenispelanggaran').DataTable().ajax.reload();
                        })
                    }
                })
            }
        })
    })
})
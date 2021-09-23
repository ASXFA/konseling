$(function(){
    $('#li-kelola').addClass('active submenu');
    $('#base').addClass('show');
    $('#sub-guru').addClass('active');

    $('#table-guru').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"guruLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-tambah-guru').click(function(){
        $('#modal-guru').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Tambah Guru');
        $('#induk_guru').val();
        $('#old_induk').val();
        $('#nama_guru').val();
        $('#jabatan_guru').val();
        $('#telp_guru').val();
        $('#id_guru').val();
        $('#operation').val();
        $('#operation').val('tambah');
        $('#warning').html('<i> Akun akan otomatis terbuat dengan username dan password sesuai dengan nomor induk !');
    })

    $(document).on('click','.editGuru',function(){
        var induk = $(this).attr('id');
        $('#modal-guru').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Edit Guru');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{induk:induk},
            url:'guruByInduk',
            success:function(result){
                $('#induk_guru').val(result.induk_guru);
                $('#old_induk').val(result.induk_guru);
                $('#nama_guru').val(result.nama_guru);
                $('#jabatan_guru').val(result.jabatan_guru);
                $('#telp_guru').val(result.telp_guru);
                $('#id_guru').val(result.id_guru);
                $('#operation').val('edit');
                $('#warning').html('<i> Mengganti Nomor Induk akan mengganti nomor induk di akun juga ! </i>');
            }
        })
    })

    $('#form-guru').submit(function(e){
        e.preventDefault();   
        $.ajax({
            url: 'doGuru',
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
                    $('#modal-guru').modal('hide');
                    $('#table-guru').DataTable().ajax.reload();
                    $('#induk_guru').val();
                    $('#old_induk').val();
                    $('#nama_guru').val();
                    $('#jabatan_guru').val();
                    $('#telp_guru').val();
                    $('#id_guru').val();
                    $('#operation').val();
                }else if(data.cond == "0"){
                    swal("Gagal", {
                        icon: 'error',
                        buttons: false,
                        timer: 3000,
                    });
                    $('#modal-guru').modal('hide');
                    $('#table-guru').DataTable().ajax.reload();
                    $('#induk_guru').val();
                    $('#old_induk').val();
                    $('#nama_guru').val();
                    $('#jabatan_guru').val();
                    $('#telp_guru').val();
                    $('#id_guru').val();
                    $('#operation').val();
                }
            }
        });
    })

    $(document).on('click','.deleteGuru',function(){
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
                    url:'deleteGuru',
                    success:function(result){
                        swal({
                            icon: 'success',
                            title: 'Berhasil dihapus !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-guru').DataTable().ajax.reload();
                            }else if(results.isConfirmed){
                                $('#table-guru').DataTable().ajax.reload();
                            }
                        })
                    }
                })
            }
        })
    })

    $(document).on('click','.detailGuru',function(){
        var induk = $(this).attr('id');
        $('#modal-detail-guru').modal({backdrop:'static',show:true});
        $('#exampleModalLabelDetail').html('Detail Guru');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{induk:induk},
            url:'guruByInduk',
            success:function(result){
                var html = "";
                html +="<table class='table table-borderd'>";
                html += "<tr>";
                html += "<td rowspan='8'><img class='img-thumbnail' src='"+base_url+"assets/img-profil/guru/"+result.foto_guru+"'></td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Induk guru </td>";
                html += "<td>"+result.induk_guru+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Nama guru </td>";
                html += "<td>"+result.nama_guru+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Jabatan Guru </td>";
                html += "<td>"+result.jabatan_guru+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Telpon</td>";
                html += "<td>"+result.telp_guru+"</td>";
                html += "</tr>";
                html += "</table>";

                $('#table-detail-guru').html(html);
            }
        })
    })

})
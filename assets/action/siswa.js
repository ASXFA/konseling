$(function(){
    $('#li-kelola').addClass('active submenu');
    $('#base').addClass('show');
    $('#sub-siswa').addClass('active');

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
        $('#id_kelas_siswa').val('');
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
                $('#id_kelas_siswa').val(result.ortu_siswa);
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
                    $('#id_kelas_siswa').val();
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
                        swal('Berhasil !',{
                            icon:'success',
                            button:false,
                            timer:2000
                        }).then((result)=>{
                            $('#table-siswa').DataTable().ajax.reload();
                        })
                    }
                })
            }
        })
    })
    $(document).on('click','.resetPoin',function(){
        swal({
            title: 'Yakin Reset Poin Siswa ?',
            text: "Poin akan kembali menjadi 0 !",
            icon: 'warning',
            buttons:{
                cancel: {
                    visible: true,
                    text : 'Tidak, cancel!',
                    className: 'btn btn-danger'
                },        			
                confirm: {
                    text : 'Ya, Reset!',
                    className : 'btn btn-success'
                }
            }
        }).then((willreset) => {
            if (willreset) {
                var induk = $(this).attr('id');
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{induk:induk},
                    url:'resetPoin',
                    success:function(result){
                        swal('Berhasil !',{
                            icon:'success',
                            button:false,
                            timer:2000
                        }).then((result)=>{
                            $('#table-siswa').DataTable().ajax.reload();
                        })
                    }
                })
            }
        })
    })
    $(document).on('click','.detailSiswa',function(){
        var induk = $(this).attr('id');
        $('#modal-detail-siswa').modal({backdrop:'static',show:true});
        $('#exampleModalLabelDetail').html('Detail Siswa');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{induk:induk},
            url:'detailSiswa',
            success:function(result){
                var html = "";
                html +="<table class='table table-borderd'>";
                html += "<tr>";
                html += "<td rowspan='8'><img class='img-thumbnail' src='"+base_url+"assets/img-profil/siswa/"+result.foto_siswa+"'></td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Induk Siswa </td>";
                html += "<td>"+result.induk_siswa+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Nama Siswa </td>";
                html += "<td>"+result.nama_siswa+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Jenis Kelamin</td>";
                html += "<td>"+result.jk_siswa+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Alamat  </td>";
                html += "<td>"+result.alamat_siswa+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Kelas </td>";
                html += "<td>"+result.nama_kelas+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Poin Siswa </td>";
                html += "<td>"+result.poin_siswa+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Status Sanksi </td>";
                html += "<td>"+result.nama_sanksi+"</td>";
                html += "</tr>";
                html += "</table>";

                $('#table-detail-siswa').html(html);
            }
        })
    })

    $('#btn-reset-all-poin').click(function(){
        swal({
            title: 'Yakin Reset Semua Poin Siswa ?',
            text: "Poin akan kembali menjadi 0 pada semua siswa!",
            icon: 'warning',
            buttons:{
                cancel: {
                    visible: true,
                    text : 'Tidak, cancel!',
                    className: 'btn btn-danger'
                },        			
                confirm: {
                    text : 'Ya, Reset!',
                    className : 'btn btn-success'
                }
            }
        }).then((willreset) => {
            if (willreset) {
                var ops = "reset";
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{ops:ops},
                    url:'resetAllPoin',
                    success:function(result){
                        swal('Berhasil !',{
                            icon:'success',
                            button:false,
                            timer:2000
                        }).then((result)=>{
                            $('#table-siswa').DataTable().ajax.reload();
                        })
                    }
                })
            }
        })
    })

})
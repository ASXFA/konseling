$(function(){
    $('#li-kelola').addClass('active submenu');
    $('#base').addClass('show');
    $('#sub-pelanggaran').addClass('active');

    $('#table-pelanggaran').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"pelanggaranLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#kode_jenis_pel_pelanggaran').on('change',function(){
        var kode_pel = $('#kode_jenis_pel_pelanggaran').val();
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{kode_pel:kode_pel},
            url:'jenispelanggaranByKode',
            success:function(results){
                $('#poin_pelanggaran').val(results.poin_jenis_pelanggaran);
            }
        })
    })

    $('#btn-tambah-pelanggaran').click(function(){
        $.ajax({
            method:'POST',
            dataType:'JSON',
            url:'getKodePelanggaran',
            success:function(result){
                $('#kode_pelanggaran').val(result.kode);
            }
        })
        $('#induk_siswa_pelanggaran').prop('selectedIndex',0);
        $('#kode_jenis_pel_pelanggaran').prop('selectedIndex',0);
        $('#tanggal_pelanggaran').val('');
        $('#keterangan_pelanggaran').val('');
        $('#id_pelanggaran').val('');
        $('#operation').val('');
        $('#modal-pelanggaran').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Tambah Pelanggaran');
        $('#operation').val('tambah');
        // $('#warning').html('<i> Akun akan otomatis terbuat dengan username dan password sesuai dengan nomor induk !');
    })

    $(document).on('click','.editPelanggaran',function(){
        var id = $(this).attr('id');
        $('#modal-pelanggaran').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Edit Pelanggaran');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id:id},
            url:'pelanggaranById',
            success:function(result){
                $('#kode_pelanggaran').val(result.kode_pelanggaran);
                $('#induk_siswa_pelanggaran').val(result.induk_siswa_pelanggaran);
                $('#kode_jenis_pel_pelanggaran').val(result.kode_jenis_pel_pelanggaran);
                $('#tanggal_pelanggaran').val(result.tanggal_pelanggaran);
                $('#keterangan_pelanggaran').val(result.keterangan_pelanggaran);
                $('#poin_pelanggaran').val(result.poin_jenis_pelanggaran);
                $('#old_jenis_pelanggaran').val(result.kode_jenis_pel_pelanggaran);
                $('#id_pelanggaran').val(result.id_pelanggaran);
                $('#operation').val('edit');
                // $('#warning').html('<i> Mengganti Nomor Induk akan mengganti nomor induk di akun juga ! </i>');
            }
        })
    })

    $('#form-pelanggaran').submit(function(e){
        e.preventDefault();   
        $.ajax({
            url: 'doPelanggaran',
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
                    $('#modal-pelanggaran').modal('hide');
                    $('#table-pelanggaran').DataTable().ajax.reload();
                    $('#induk_siswa_pelanggaran').prop('selectedIndex',0);
                    $('#kode_jenis_pel_pelanggaran').prop('selectedIndex',0);
                    $('#tanggal_pelanggaran').val('');
                    $('#keterangan_pelanggaran').val('');
                    $('#old_jenis_pelanggaran').val('');
                    $('#id_pelanggaran').val('');
                    $('#operation').val('');
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

    $(document).on('click','.deletePelanggaran',function(){
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
                var id = $(this).attr('id');
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{id:id},
                    url:'deletePelanggaran',
                    success:function(result){
                        swal('Berhasil !',{
                            icon:'success',
                            button:false,
                            timer:2000
                        }).then((result)=>{
                            $('#table-pelanggaran').DataTable().ajax.reload();
                        })
                    }
                })
            }
        })
    })

    $(document).on('click','.detailPelanggaran',function(){
        var id = $(this).attr('id');
        $('#modal-detail-pelanggaran').modal({backdrop:'static',show:true});
        $('#exampleModalLabelDetail').html('Detail Pelanggaran');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id:id},
            url:'detailPelanggaran',
            success:function(result){
                var html = "";
                html += "<tr>";
                html += "<td>Kode Pelanggaran </td>";
                html += "<td>"+result.kode_pelanggaran+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Tanggal </td>";
                html += "<td>"+result.tanggal_pelanggaran+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Induk Siswa </td>";
                html += "<td>"+result.induk_siswa_pelanggaran+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Nama Siswa </td>";
                html += "<td>"+result.nama_siswa+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Jenis Pelanggaran</td>";
                html += "<td>"+result.nama_jenis_pelanggaran+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Poin Pelanggaran </td>";
                html += "<td>"+result.poin_jenis_pelanggaran+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Keterangan </td>";
                html += "<td>"+result.keterangan_pelanggaran+"</td>";
                html += "</tr>";

                $('#table-detail-pelanggaran').html(html);
            }
        })
    })

})
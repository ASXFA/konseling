$(function(){
    // $('#li-user').attr('class','nav-item submenu');
    // $('#base').attr('class','collapse show');
    // $('#sub-guru').attr('class','active');
    $('#text-info-catatan').hide();
    $('#table-catatan').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"catatanLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    // $('#id_siswa_print').select2();
    $.fn.select2.defaults.set( "theme", "bootstrap" );
    $( ".select2-single" ).select2();
    $( ".select2-single, .select2-multiple, .select2-allow-clear, .js-data-example-ajax" ).on( "select2:open", function() {
        if ( $( this ).parents( "[class*='has-']" ).length ) {
            var classNames = $( this ).parents( "[class*='has-']" )[ 0 ].className.split( /\s+/ );

            for ( var i = 0; i < classNames.length; ++i ) {
                if ( classNames[ i ].match( "has-" ) ) {
                    $( "body > .select2-container" ).addClass( classNames[ i ] );
                }
            }
        }
    });

    $('#id_pelanggaran_catatan_kasus').change(function(){
        var id = $('#id_pelanggaran_catatan_kasus').val();
        $.ajax({
            method:'POST',
            data:{id:id},
            dataType:'JSON',
            url:'detailPelanggaran',
            success:function(result){
                $('#induk_siswa').val(result.induk_siswa_pelanggaran);
                $('#nama_siswa').val(result.nama_siswa);
                $('#jenis_pelanggaran').val(result.nama_jenis_pelanggaran);
                $('#poin_pelanggaran').val(result.poin_jenis_pelanggaran);
                $('#tanggal_pelanggaran').val(result.tanggal_pelanggaran);
                $('#keterangan_pelanggaran').val(result.keterangan_pelanggaran);
            }
        })
    })

    $('#btn-tambah-catatan').click(function(){
        $('#text-info-catatan').hide();
        $('#id_pelanggaran_catatan_kasus').removeAttr('readonly');
        $('#id_pelanggaran_catatan_kasus').prop('selectedIndex',0);
        $('#induk_siswa').val('');
        $('#jenis_pelanggaran').val('');
        $('#tanggal_pelanggaran').val('');
        $('#nama_siswa').val('');
        $('#poin_pelanggaran').val('');
        $('#keterangan_pelanggaran').val('');
        $('#penyelesaian_catatan_kasus').val('');
        $('#evaluasi_catatan_kasus').val('');
        $('#tanggal_catatan_kasus').val('');
        $('#pihak_catatan_kasus').val('');
        $('#modal-catatan').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Tambah Catatan Kasus');
        $('#operation').val('tambah');
        // $('#warning').html('<i> Akun akan otomatis terbuat dengan username dan password sesuai dengan nomor induk !');
    })

    $(document).on('click','.editCatatan',function(){
        var id = $(this).attr('id');
        $('#modal-catatan').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Edit Catatan Kasus');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id:id},
            url:'catatanById',
            success:function(result){
                $('#id_pelanggaran_catatan_kasus').val(result.id_pelanggaran_catatan_kasus).trigger('change');
                $('#id_pelanggaran_catatan_kasus').attr('readonly','readonly');
                $('#penyelesaian_catatan_kasus').val(result.penyelesaian_catatan_kasus);
                $('#evaluasi_catatan_kasus').val(result.evaluasi_catatan_kasus);
                $('#tanggal_catatan_kasus').val(result.tanggal_catatan_kasus);
                $('#pihak_catatan_kasus').val(result.pihak_catatan_kasus);
                $('#id_catatan_kasus').val(result.id_catatan_kasus);
                $('#operation').val('edit');
                $('#text-info-catatan').show();
            }
        })
    })

    $('#form-catatan').submit(function(e){
        e.preventDefault();   
        $.ajax({
            url: 'doCatatan',
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
                    $('#modal-catatan').modal('hide');
                    $('#table-catatan').DataTable().ajax.reload();
                    $('#id_pelanggaran_catatan_kasus').prop('selectedIndex',0);
                    $('#induk_siswa').val('');
                    $('#jenis_pelanggaran').val('');
                    $('#tanggal_pelanggaran').val('');
                    $('#nama_siswa').val('');
                    $('#poin_pelanggaran').val('');
                    $('#keterangan_pelanggaran').val('');
                    $('#penyelesaian_catatan_kasus').val('');
                    $('#evaluasi_catatan_kasus').val('');
                    $('#tanggal_catatan_kasus').val('');
                    $('#pihak_catatan_kasus').val('');
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

    $(document).on('click','.deleteCatatan',function(){
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
                var id = $(this).attr('id');
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{id:id},
                    url:'deleteCatatan',
                    success:function(result){
                        swal('Berhasil !',{
                            icon:'success',
                            button:false,
                            timer:2000
                        }).then((result)=>{
                            $('#table-catatan').DataTable().ajax.reload();
                        })
                    }
                })
            }
        })
    })

    $(document).on('click','.detailCatatan',function(){
        var id = $(this).attr('id');
        $('#modal-detail-catatan').modal({backdrop:'static',show:true});
        $('#exampleModalLabelDetail').html('Detail CAtatan Kasus');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id:id},
            url:'detailCatatan',
            success:function(result){
                var html = "";
                html +="<table class='table table-borderd'>";
                html += "<tr>";
                html += "<td>Kode Pelanggaran </td>";
                html += "<td>"+result.pelanggaran.kode_pelanggaran+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Tanggal Pelanggaran </td>";
                html += "<td>"+result.pelanggaran.tanggal_pelanggaran+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Induk Siswa</td>";
                html += "<td>"+result.siswa.induk_siswa+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Nama Siswa</td>";
                html += "<td>"+result.siswa.nama_siswa+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Jenis Pelanggaran</td>";
                html += "<td>"+result.jp.nama_jenis_pelanggaran+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Poin Pelanggaran</td>";
                html += "<td>"+result.jp.poin_jenis_pelanggaran+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Keterangan Pelanggaran</td>";
                html += "<td>"+result.pelanggaran.keterangan_pelanggaran+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Penyelesaian / Tindakan</td>";
                html += "<td>"+result.catatan.penyelesaian_catatan_kasus+"</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Evaluasi Catatan Kasus</td>";
                html += "<td>"+result.catatan.evaluasi_catatan_kasus+"<td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Tanggal Catatan Kasus</td>";
                html += "<td>"+result.catatan.tanggal_catatan_kasus+"<td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Pihak yang terlibat</td>";
                html += "<td>"+result.catatan.pihak_catatan_kasus+"<td>";
                html += "</tr>";
                html += "</table>";

                $('#table-detail-catatan').html(html);
            }
        })
    })

})
$(function(){
    $('#li-kelola').addClass('active submenu');
    $('#base').addClass('show');
    $('#sub-absensi').addClass('active');

    $('#table-absensi').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"absensiLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $(document).on('click','.beriNilai',function(){
        var id_absensi = $(this).attr('id');
        $('#modal-absensi').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Beri Nilai');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id_absensi:id_absensi},
            url:'absensiById',
            success:function(result){
                $('#nilai_absensi').val(result.nilai_absensi);
                $('#id_absensi').val(result.id_absensi);
                $('#operation').val('edit');
                // $('#warning').html('<i> Mengganti Nomor Induk akan mengganti nomor induk di akun juga ! </i>');
            }
        })
    })

    $('#form-absensi').submit(function(e){
        e.preventDefault();   
        $.ajax({
            url: 'doAbsensi',
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
                    $('#modal-absensi').modal('hide');
                    $('#table-absensi').DataTable().ajax.reload();
                    $('#nilai_absensi').val();
                    $('#id_absensi').val();
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
})
$(function(){
    $('#kmeans-page').addClass('active');

    $('.centroid').DataTable();
    $('#table-hasil').DataTable({});
    swal('Menghitung Kmeans .....',{
        icon:'info',
        button:false,
        timer:8000,
        closeOnClickOutside: false,
    }).then((result)=>{
        swal('Berhasil !',{
            icon:'success',
            button:false,
            timer:2000,
            closeOnClickOutside: false,
        })
    })

    $('#table-data-real').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"dataRealLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });
    $('#table-data-konversi').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"dataKonversiLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $.ajax({
        mehtod:'POST',
        dataType:'JSON',
        url:'cekNilaiKehadiran',
        success:function(result){
            for(var i = 0; i<result.length; i++){
                if (result[i].nilai_absensi == null) {
                    swal('Ada nilai kehadiran yang belum diinput !',{
                        icon:'error',
                        button:false,
                        timer:4000
                    }).then((results)=>{
                        window.location.href='listAbsensi';
                    })
                }
            }
        }
    })
})
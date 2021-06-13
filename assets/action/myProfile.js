$(function(){
    $('#small-text').hide();
    $('#btn-old-pass').click(function(){
        var attr = $('#old_pass').attr('type');
        if (attr == "password") {
            $('#btn-old-pass').html('<i class="fa fa-eye"></i>');
            $('#old_pass').attr('type','text');
        }else{
            $('#btn-old-pass').html('<i class="fa fa-eye-slash"></i>');
            $('#old_pass').attr('type','password');
        }
    })
    $('#btn-new-pass').click(function(){
        var attr = $('#new_pass').attr('type');
        if (attr == "password") {
            $('#btn-new-pass').html('<i class="fa fa-eye"></i>');
            $('#new_pass').attr('type','text');
        }else{
            $('#btn-new-pass').html('<i class="fa fa-eye-slash"></i>');
            $('#new_pass').attr('type','password');
        }
    })
    $('#btn-conf-pass').click(function(){
        var attr = $('#conf_pass').attr('type');
        if (attr == "password") {
            $('#btn-conf-pass').html('<i class="fa fa-eye"></i>');
            $('#conf_pass').attr('type','text');
        }else{
            $('#btn-conf-pass').html('<i class="fa fa-eye-slash"></i>');
            $('#conf_pass').attr('type','password');
        }
    })

    $('#conf_pass').keyup(function(){
        var newpass = $("#new_pass").val();
        var conf_pass = $('#conf_pass').val();
        console.log(newpass);
        console.log(conf_pass);
        if (conf_pass === newpass) {
            $('#small-text').hide();
            $('#conf_pass').css('border-color','green');
        }else{
            $('#small-text').show();
            $('#small-text').html('Password Tidak Sama !');
            $('#small-text').addClass('text-danger');
            $('#conf_pass').css('border-color', 'red');
        }
    });

    $('#gantiPass').submit(function(e){
        e.preventDefault();
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:new FormData(this),
            processData: false,
            contentType: false,
            url:'gantiPass',
            success:function(result){
                if (result.cond == 1) {
                    swal('Berhasil Ganti Password !, Silahkan login kembali',{
                        icon:'success',
                        button:false,
                        timer:2000
                    }).then((results)=>{
                        window.location.href='logout';
                    })
                }else{
                    swal('Gagal '+result.pesan,{
                        icon:'error',
                        button:false,
                        timer:2000
                    })
                }
            }
        })
    })
    $('#editProfil').click(function(){
        $('#modal-edit-profil').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Edit Profil');
        $('#operation').val('edit');
        var induk = $('#induk_user_edit').val();
        var role = $('#role_user').val();
        if (role != 3) {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:{induk:induk},
                url:'guruByInduk',
                success:function(result){
                    $('#nama_user_edit').val(result.nama_guru);
                    $('#telp_user_edit').val(result.telp_guru);
                }
            })
        }else{
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:{induk:induk},
                url:'siswaByInduk',
                success:function(result){
                    $('#nama_user_edit').val(result.nama_siswa);
                    $('#jk_user_edit').val(result.jk_siswa);
                    $('#alamat_user_edit').val(result.alamat_siswa);
                }
            })
        }
    })

    $('#form-edit-profil').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: 'editProfil',
            data: new FormData(this),
            processData: false,
            contentType: false,
            method: 'POST',
            dataType:'JSON',
            success:function(result){
                if (result.cond == '1') {
                    swal('Berhasil !',{
                        icon:'success',
                        button:false,
                        timer:2000
                    }).then((results)=>{
                        location.reload();
                    })
                }else{
                    swal('Gagal !',{
                        icon:'error',
                        button:false,
                        timer:2000
                    })
                }
            }
        })
    })

    // preview img
    $("#inputFile").change(function(event) {  
        fadeInAdd();
        getURL(this);    
    });
    
    $("#inputFile").on('click',function(event){
        fadeInAdd();
    });
    
    function getURL(input) {    
        if (input.files && input.files[0]) {   
            var reader = new FileReader();
            var filename = $("#inputFile").val();
            filename = filename.substring(filename.lastIndexOf('\\')+1);
            reader.onload = function(e) {
                debugger;      
                $('#imgView').attr('src', e.target.result);
                $('#imgView').hide();
                $('#imgView').fadeIn(500);      
                $('.custom-file-label').text(filename);             
            }
            reader.readAsDataURL(input.files[0]);    
        }
        $(".alert").removeClass("loadAnimate").hide();
    }
    
    function fadeInAdd(){
        fadeInAlert();  
    }
    function fadeInAlert(text){
        $(".alert").text(text).addClass("loadAnimate");  
    }
    // end preview img

    $('#editFoto').submit(function(e){
        e.preventDefault();
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:new FormData(this),
            processData: false,
            contentType: false,
            url:'editFoto',
            success:function(result){
                if (result.cond == '1') {
                    swal('Berhasil !',{
                        icon:'success',
                        button:false,
                        timer:2000
                    }).then((results)=>{
                        location.reload();
                    })
                }else{
                    swal('Gagal !',{
                        icon:'error',
                        button:false,
                        timer:2000
                    })
                }
            }
        })
    })
})
$(function(){
    $('#profilUser-page').addClass('active bg-success');
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    
    $('#email_akun').keyup(function(){
        var cek = $('#email_akun').val();
        if (cek.indexOf('@') > -1) {
            $('#email_akun').removeClass('is-invalid');
        }else{
            $('#email_akun').addClass('is-invalid');
        }
    })

    console.log(email_sekarang);

    $('#email_akun').keyup(function(){
        var checking = $('#email_akun').val();
        if (checking.indexOf('.com') > -1) {
            $.ajax({
                method: 'POST',
                data: {email:checking},
                dataType: 'JSON',
                url: 'getByEmail',
                success:function(result){
                    if (result.cond == '1') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Email bisa digunakan !'
                        })
                        $('#email_akun').addClass('is-valid');
                        $('#form-btn').removeAttr('disabled','disabled');
                    }else{
                        if (checking == email_sekarang) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Email bisa digunakan !'
                            })
                            $('#email_akun').addClass('is-valid');
                            $('#form-btn').removeAttr('disabled','disabled');  
                        }else{
                            Toast.fire({
                                icon: 'error',
                                title: 'Email sudah digunakan !'
                            })
                            $('#email_akun').removeClass('is-valid');
                            $('#form-btn').attr('disabled','disabled');
                        }
                    }
                }
            })
        }else{
            $('#email_akun').removeClass('is-valid');
            $('#form-btn').attr('disabled','disabled');       
        }
    })


    $('#btn-edit').click(function(){
        var id = $('#id_user').val();
        $('#modals').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Edit User')
        $('#id_user').val(id);
        $('#operation').val('Edit');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data: {id:id},
            url:'userById',
            success:function(result){
                $('#nama_user').val(result.nama_user);
                $('#jabatan_user').val(result.jabatan_user);
                $('#nip_user').val(result.nip_user);
                $('#nip_user').attr('readonly','readonly');
                $('#no_hp_user').val(result.no_hp_user);
                $('#email_akun').val(result.email_akun);
                $('#level_akun').val(result.level_akun);
                $('#id_user').val(result.id_user);
                email_sekarang = result.email_akun;
            }
        })
    })

    $('#form-modal').submit(function(e){
        e.preventDefault();
        var nama_user = $('#nama_user').val();
        var jabatan_user = $('#jabatan_user').val();
        var nip_user = $('#nip_user').val();
        var no_hp_user = $('#no_hp_user').val();
        var email_akun = $('#email_akun').val();
        var level_akun = $('#level_akun').val();
        var operation = $('#operation').val();
        if (nama_user != '' && jabatan_user != '' && nip_user != '' && no_hp_user != '' && email_akun != '' && level_akun !='') {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                processData: false,
                contentType: false,
                data:new FormData(this),
                url:'doUser',
                success:function(result){
                    Swal.fire({
                        icon: 'success',
                        title: result.msg,
                        timer:3000,
                    }).then((results) => {
                        /* Read more about handling dismissals below */
                        if (results.dismiss === Swal.DismissReason.timer) {
                            location.reload();
                        }else if(results.isConfirmed){
                            location.reload();
                        }
                    })
                }
            })
        }else{
            Swal.fire({
                icon: 'Warning',
                title: 'Peringatan !',
                text: 'Field Tidak boleh kosong !'
            })
        }
    })

    $('#pass_baru').click(function(){
        $('#pass_baru').addClass('is-invalid');
    })

    // Validating Input Password
    $('#pass_baru').keyup(function(){
        var cek_pass = $('#pass_baru').val();
        console.log(cek_pass.length);
        if (cek_pass.length >= 8) {
            $('#pass_baru').removeClass('is-invalid');
            $('#pass_baru').addClass('is-valid');
        }else{
            $('#pass_baru').removeClass('is-valid');
            $('#pass_baru').addClass('is-invalid');
        }
    })

    $('#savePassword').attr('disabled','disabled');
    $('#conf_pass').keyup(function(){
        var pass_baru = $('#pass_baru').val();
        var conf_pass = $('#conf_pass').val();
        if (pass_baru != conf_pass) {
            $('#conf_pass').removeClass('is-valid');
            $('#conf_pass').addClass('is-invalid');
            $('#savePassword').attr('disabled','disabled');
        }else{
            $('#conf_pass').removeClass('is-invalid');
            $('#conf_pass').addClass('is-valid');
            $('#savePassword').removeAttr('disabled','disabled');
        }
    })
    

    $('#formEditPassword').submit(function(e){
        e.preventDefault();
        var pass_lama = $('#pass_lama').val();
        var pass_baru = $('#pass_baru').val();
        var conf_pass = $('#conf_pass').val();

        if (pass_lama != "" && pass_baru != "" && conf_pass !="") {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:{pass_lama:pass_lama,pass_baru:pass_baru},
                url:'editPassword',
                success:function(result){
                    if (result.cond == 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Peringatan !',
                            text: 'Password Lama Anda Salah !'
                        })
                    }else if(result.cond == 1){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil diganti !',
                            timer:3000,
                        }).then((results) => {
                            if (results.dismiss === Swal.DismissReason.timer) {
                                window.location.href='logout';
                            }else if(results.isConfirmed){
                                window.location.href='logout';
                            }
                        });
                    }
                }
            })
        }else{
            Swal.fire({
                icon: 'Warning',
                title: 'Peringatan !',
                text: 'Field Tidak boleh kosong !'
            })
        }
    })
})
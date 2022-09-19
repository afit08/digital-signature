$(function(){

    // Validation Input Email
    $('#email-login').click(function(){
        $('#email-login').addClass('is-invalid');
    })

    $('#email-login').keyup(function(){
        var cek = $('#email-login').val();
        if (cek.indexOf('@') > -1) {
            $('#email-login').removeClass('is-invalid');
        }else{
            $('#email-login').addClass('is-invalid');
        }
    })

    $('#password-login').click(function(){
        $('#password-login').addClass('is-invalid');
    })

    // Validating Input Password
    $('#password-login').keyup(function(){
        var cek_pass = $('#password-login').val();
        if (cek_pass.length >= 8) {
            $('#btn-login').removeAttr('disabled');
            $('#password-login').removeClass('is-invalid');
        }else{
            $('#btn-login').attr('disabled','disabled');
            $('#password-login').addClass('is-invalid');
        }
    })

    // $('#btn-show-pass').click(function(){
    //     var attr = $('#pass').attr('type');
    //     if (attr == "password") {
    //         $('#btn-show-pass').html('<i class="zmdi zmdi-eye-off"></i>');
    //         $('#pass').attr('type','text');
    //     }else if(attr == "text"){
    //         $('#btn-show-pass').html('<i class="zmdi zmdi-eye"></i>');
    //         $('#pass').attr('type','password');
    //     }
    // })

    $('#form-login').submit(function(e){
        e.preventDefault();
        var email = $('#email-login').val();
        var pass = $('#password-login').val();
        if (email != "" && pass != "") {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                processData: false,
                contentType: false,
                data:new FormData(this),
                url:'action_login',
                success:function(results){
                    if (results.condition == 2) {
                        let timerInterval
                        Swal.fire({
                            icon:'success',
                            title:'Sukses Login !',
                            text:results.pesan,
                            html: 'Mohon Tunggu, Sedang mengalihkan ',
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location.href=results.url;
                            }
                        })
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed !',
                            text: results.pesan
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
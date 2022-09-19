$(function(){
    // $("#example1").DataTable({
    //     "responsive": true, "lengthChange": false, "autoWidth": true,
    //     // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#user-page').attr('class','nav-link active bg-success');
    $('#form-btn').attr('disabled','disabled');
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

    $('#email_akun').click(function(){
        $('#email_akun').addClass('is-invalid');
        var cek = $('#email_akun').val();
        if (cek.indexOf('@') > -1) {
            $('#email_akun').removeClass('is-invalid');
        }else{
            $('#email_akun').addClass('is-invalid');
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
                        Toast.fire({
                            icon: 'error',
                            title: 'Email sudah digunakan !'
                        })
                        $('#email_akun').removeClass('is-valid');
                        $('#form-btn').attr('disabled','disabled');  
                    }
                }
            })
        }else{
            $('#email_akun').removeClass('is-valid');
            $('#form-btn').attr('disabled','disabled');       
        }
    })


    $('#table-user').DataTable({
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": true,
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"userLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-tambah').click(function(){
        $('#modals').modal({backdrop: 'static',show:true});
        $('#exampleModalLabel').html('Tambah User');
        $('#email_akun').removeClass('is-valid');
        $('#email_akun').removeClass('is-invalid');
        $('#form-btn').val('Tambah');
        // kosongkan Field dan disable
        $('#nama_user').val('');
        $('#nama_user').attr('disabled','disabled');
        $('#nip_user').val('');
        $('#nip_user').attr('disabled','disabled');
        $('#jabatan_user').val('');
        $('#jabatan_user').attr('disabled','disabled');
        $('#no_hp_user').val(''); 
        $('#no_hp_user').attr('disabled','disabled'); 
        $('#email_akun').val(''); 
        $('#email_akun').attr('disabled','disabled'); 
        $('#prodi_user').val(''); 
        $('#prodi_user').attr('disabled','disabled'); 
        var selectLevel = $('#level_akun').val();
        if (selectLevel == 1 && selectLevel == 2) {
            $('#nama_user').removeAttr('disabled','disabled');
            $('#nip_user').val('');
            $('#nip_user').removeAttr('readonly','readonly');
            $('#nip_user').removeAttr('disabled','disabled');
            $('#jabatan_user').removeAttr('disabled','disabled');
            $('#no_hp_user').removeAttr('disabled','disabled');
            $('#email_akun').removeAttr('disabled','disabled');
            $('#prodi_user').val('-');
            $('#prodi_user').removeAttr('disabled','disabled');
            $('#prodi_user').attr('readonly','readonly');
        }else if(selectLevel == 3){
            $('#nama_user').removeAttr('disabled','disabled');
            $('#nip_user').removeAttr('disabled','disabled');
            $('#nip_user').val('-');
            $('#nip_user').attr('readonly','readonly');
            $('#jabatan_user').removeAttr('disabled','disabled');
            $('#no_hp_user').removeAttr('disabled','disabled');
            $('#email_akun').removeAttr('disabled','disabled');
            $('#prodi_user').removeAttr('disabled','disabled');
        }
        
        $('#operation').val('Tambah');
    })
    
    $('#level_akun').change(function(){
        var selectLevel = $('#level_akun').val();
        if (selectLevel != 3) {
            $('#nama_user').removeAttr('disabled','disabled');
            $('#nip_user').val('');
            $('#nip_user').removeAttr('readonly','readonly');
            $('#nip_user').removeAttr('disabled','disabled');
            $('#jabatan_user').removeAttr('disabled','disabled');
            $('#no_hp_user').removeAttr('disabled','disabled');
            $('#email_akun').removeAttr('disabled','disabled');
            $('#prodi_user').val('-');
            $('#prodi_user').removeAttr('disabled','disabled');
            $('#prodi_user').attr('readonly','readonly');
        }else if(selectLevel == 3){
            $('#nama_user').removeAttr('disabled','disabled');
            $('#nip_user').removeAttr('disabled','disabled');
            $('#nip_user').val('-');
            $('#nip_user').attr('readonly','readonly');
            $('#jabatan_user').removeAttr('disabled','disabled');
            $('#no_hp_user').removeAttr('disabled','disabled');
            $('#email_akun').removeAttr('disabled','disabled');
            $('#prodi_user').val('');
            $('#prodi_user').removeAttr('disabled','disabled');
            $('#prodi_user').removeAttr('readonly','readonly');
        }
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
                            $('#table-user').DataTable().ajax.reload();
                            $('#modals').modal('hide');
                        }else if(results.isConfirmed){
                            $('#table-user').DataTable().ajax.reload();
                            $('#modals').modal('hide');
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

    $(document).on('click','.detail',function(){
        var id = $(this).attr('id');
        $('#modals-detail').modal({backdrop:'static',show:true});
        $('#exampleModalLabelDetail').html('Detail User');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data: {id:id},
            url:'userById',
            success:function(result){
                var html = '';
                html += '<table class="table table-borderless">';
                html += '<tr>';
                html += '<td> <h5> Data User </h5> </td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Nama User </td>';
                html += '<td> : </td>';
                html += '<td>'+result.nama_user+'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> NIDN </td>';
                html += '<td> : </td>';
                html += '<td>'+result.nip_user+'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Jabatan </td>';
                html += '<td> : </td>';
                html += '<td>'+result.jabatan_user+'</td>';
                html += '</tr>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Prodi </td>';
                html += '<td> : </td>';
                html += '<td>'+result.prodi_user+'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> No Hp </td>';
                html += '<td> : </td>';
                html += '<td>'+result.no_hp_user+'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Created At </td>';
                html += '<td> : </td>';
                html += '<td>'+result.created_at+'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Created By </td>';
                html += '<td> : </td>';
                html += '<td>'+result.created_by+'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Updated At </td>';
                html += '<td> : </td>';
                html += '<td>'+result.updated_at+'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Updated By </td>';
                html += '<td> : </td>';
                html += '<td>'+result.updated_by+'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> <h5> User Akun </h5> </td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Email  </td>';
                html += '<td> : </td>';
                html += '<td>'+result.email_akun+'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Level  </td>';
                html += '<td> : </td>';
                html += '<td>'+result.level_akun+'</td>';
                html += '</tr>';
                html += '</table>';

                $('#table-detail').html(html);
            }
        })
    })

    $(document).on('click','.edit',function(){
        var id = $(this).attr('id');
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
                $('#no_hp_user').val(result.no_hp_user);
                $('#email_akun').val(result.email_akun);
                $('#level_akun').val(result.level_akun);
                $('#id_user').val(result.id_user);
            }
        })
    })

    $(document).on('click','.hapus',function(){
        Swal.fire({
            title: 'Yakin Di Hapus ?',
            text: 'Data user & akun akan terhapus sementara !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus !'
        }).then((result) => {
            if (result.isConfirmed) {
                var id = $(this).attr('id');
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{id:id},
                    url:'deleteUser',
                    success:function(result){
                        Swal.fire({
                            icon: 'success',
                            title: result.msg,
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-user').DataTable().ajax.reload();
                            }else if(results.isConfirmed){
                                $('#table-user').DataTable().ajax.reload();
                            }
                        })
                    }
                })
            }
        })
    })

})
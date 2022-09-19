$(function(){
    // $("#example1").DataTable({
    //     "responsive": true, "lengthChange": false, "autoWidth": true,
    //     // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#mhs-page').attr('class','nav-link active bg-success');

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


    $('#table-mhs').DataTable({
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": true,
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"mhsLists",
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
        $('#form-btn').val('Tambah');
        $('#email_akun').removeClass('is-valid');
        $('#email_akun').removeClass('is-invalid');
        // kosongkan Field
        $('#nama_mhs').val('');
        $('#npm_mhs').val('');
        $('#prodi_mhs').val('');
        $('#no_hp_mhs').val(''); 
        $('#email_mhs').val(''); 

        $('#operation').val('Tambah'); 
    })

    $('#form-modal').submit(function(e){
        e.preventDefault();
        var nama_mhs = $('#nama_mhs').val();
        var npm_mhs = $('#npm_mhs').val();
        var prodi_mhs = $('#prodi_mhs').val();
        var no_hp_mhs = $('#no_hp_mhs').val();
        var email_akun = $('#email_akun').val();
        var level_akun = $('#level_akun').val();
        var operation = $('#operation').val();
        if (nama_mhs != '' && prodi_mhs != '' && npm_mhs != '' && no_hp_mhs != '' && email_akun != '' && level_akun !='') {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                processData: false,
                contentType: false,
                data:new FormData(this),
                url:'doMhs',
                success:function(result){
                    Swal.fire({
                        icon: 'success',
                        title: result.msg,
                        timer:3000,
                    }).then((results) => {
                        /* Read more about handling dismissals below */
                        if (results.dismiss === Swal.DismissReason.timer) {
                            $('#table-mhs').DataTable().ajax.reload();
                            $('#modals').modal('hide');
                        }else if(results.isConfirmed){
                            $('#table-mhs').DataTable().ajax.reload();
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
            url:'mhsById',
            success:function(result){
                var html = '';
                html += '<table class="table table-borderless">';
                html += '<tr>';
                html += '<td> <h5> Data Mahasiswa </h5> </td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Nama</td>';
                html += '<td> : </td>';
                html += '<td>'+result.nama_mhs+'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> NPM </td>';
                html += '<td> : </td>';
                html += '<td>'+result.npm_mhs+'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Prodi </td>';
                html += '<td> : </td>';
                html += '<td>'+result.prodi_mhs+'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> No Hp </td>';
                html += '<td> : </td>';
                html += '<td>'+result.no_hp_mhs+'</td>';
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
                html += '<td> <h5> Mahasiswa Akun </h5> </td>';
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
        $('#id_mhs').val(id);
        $('#operation').val('Edit');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data: {id:id},
            url:'mhsById',
            success:function(result){
                $('#nama_mhs').val(result.nama_mhs);
                $('#prodi_mhs').val(result.prodi_mhs);
                $('#nip_mhs').val(result.nip_mhs);
                $('#no_hp_mhs').val(result.no_hp_mhs);
                $('#email_akun').val(result.email_akun);
                $('#level_akun').val(result.level_akun);
                $('#id_mhs').val(result.id_mhs);
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
                    url:'deleteMhs',
                    success:function(result){
                        Swal.fire({
                            icon: 'success',
                            title: result.msg,
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-mhs').DataTable().ajax.reload();
                            }else if(results.isConfirmed){
                                $('#table-mhs').DataTable().ajax.reload();
                            }
                        })
                    }
                })
            }
        })
    })

})
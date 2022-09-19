$(function(){
    // $("#example1").DataTable({
    //     "responsive": true, "lengthChange": false, "autoWidth": true,
    //     // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#mhs-page').attr('class','nav-link active bg-success');

    // const Toast = Swal.mixin({
    //     toast: true,
    //     position: 'top-end',
    //     showConfirmButton: false,
    //     timer: 3000,
    //     timerProgressBar: true,
    //     didOpen: (toast) => {
    //     toast.addEventListener('mouseenter', Swal.stopTimer)
    //     toast.addEventListener('mouseleave', Swal.resumeTimer)
    //     }
    // })


    $('#table-list-hapus').DataTable({
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": true,
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"listHapusMhs",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    // $('#btn-tambah').click(function(){
    //     $('#modals').modal({backdrop: 'static',show:true});
    //     $('#exampleModalLabel').html('Tambah User');
    //     $('#form-btn').val('Tambah');
    //     // kosongkan Field
    //     $('#nama_user').val('');
    //     $('#nip_user').val('');
    //     $('#jabatan_user').val('');
    //     $('#no_hp_user').val(''); 
    //     $('#email_akun').val(''); 
    //     $('#level_akun')[0].selectedIndex; 

    //     $('#operation').val('Tambah');
    //     $('#id_jenis').val(0);
    // })

    // $('#form-modal').submit(function(e){
    //     e.preventDefault();
    //     var nama_user = $('#nama_user').val();
    //     var jabatan_user = $('#jabatan_user').val();
    //     var nip_user = $('#nip_user').val();
    //     var no_hp_user = $('#no_hp_user').val();
    //     var email_akun = $('#email_akun').val();
    //     var level_akun = $('#level_akun').val();
    //     var operation = $('#operation').val();
    //     if (nama_user != '' && jabatan_user != '' && nip_user != '' && no_hp_user != '' && email_akun != '' && level_akun !='') {
    //         $.ajax({
    //             method:'POST',
    //             dataType:'JSON',
    //             processData: false,
    //             contentType: false,
    //             data:new FormData(this),
    //             url:'doUser',
    //             success:function(result){
    //                 Swal.fire({
    //                     icon: 'success',
    //                     title: result.msg,
    //                     timer:3000,
    //                 }).then((results) => {
    //                     /* Read more about handling dismissals below */
    //                     if (results.dismiss === Swal.DismissReason.timer) {
    //                         $('#table-user').DataTable().ajax.reload();
    //                         $('#modals').modal('hide');
    //                     }else if(results.isConfirmed){
    //                         $('#table-user').DataTable().ajax.reload();
    //                         $('#modals').modal('hide');
    //                     }
    //                 })
    //             }
    //         })
    //     }else{
    //         Swal.fire({
    //             icon: 'Warning',
    //             title: 'Peringatan !',
    //             text: 'Field Tidak boleh kosong !'
    //         })
    //     }
    // })

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
                html += '<td> Nama </td>';
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
                html += '<td> Deleted At </td>';
                html += '<td> : </td>';
                html += '<td>'+result.deleted_at+'</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Deleted By </td>';
                html += '<td> : </td>';
                html += '<td>'+result.deleted_by+'</td>';
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

    $(document).on('click','.restore',function(){
        Swal.fire({
            title: 'Yakin Di Restore data mahasiswa dan akun ini ?',
            text: 'Data user & akun akan kembali aktif !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Restore !'
        }).then((result) => {
            if (result.isConfirmed) {
                var id = $(this).attr('id');
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{id:id},
                    url:'restoreMhs',
                    success:function(result){
                        Swal.fire({
                            icon: 'success',
                            title: result.msg,
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-list-hapus').DataTable().ajax.reload();
                            }else if(results.isConfirmed){
                                $('#table-list-hapus').DataTable().ajax.reload();
                            }
                        })
                    }
                })
            }
        })
    })

})
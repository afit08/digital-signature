$(function(){
    // $("#example1").DataTable({
    //     "responsive": true, "lengthChange": false, "autoWidth": true,
    //     // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#akun-page').attr('class','nav-link active bg-success');

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


    $('#table-akun').DataTable({
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": true,
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"akunLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $(document).on('click','.detail',function(){
        var id = $(this).attr('id');
        $('#modals-detail').modal({backdrop:'static',show:true});
        $('#exampleModalLabelDetail').html('Detail User');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data: {id:id},
            url:'akunById',
            success:function(result){
                var html = '';
                html += '<table class="table table-borderless">';
                // html += '<tr>';
                // html += '<td> <h5> Data User </h5> </td>';
                // html += '</tr>';
                // html += '<tr>';
                // html += '<td> Nama User </td>';
                // html += '<td> : </td>';
                // html += '<td>'+result.nama_user+'</td>';
                // html += '</tr>';
                // html += '<tr>';
                // html += '<td> NIDN </td>';
                // html += '<td> : </td>';
                // html += '<td>'+result.nip_user+'</td>';
                // html += '</tr>';
                // html += '<tr>';
                // html += '<td> Jabatan </td>';
                // html += '<td> : </td>';
                // html += '<td>'+result.jabatan_user+'</td>';
                // html += '</tr>';
                // html += '<tr>';
                // html += '<td> No Hp </td>';
                // html += '<td> : </td>';
                // html += '<td>'+result.no_hp_user+'</td>';
                // html += '</tr>';
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
                html += '</table>';

                $('#table-detail').html(html);
            }
        })
    })

    // $('#btnTambahKriteria').click(function(){
    //     $('#modalKriteria').modal({backdrop: 'static',show:true});
    //     $('#btnKriteria').val('Tambah');
    //     $('#operation').val('Tambah');
    //     $('#nama_kriteria').val('');
    //     $('#id_jenis').val(0);
    // })

    // $('#formKriteria').submit(function(e){
    //     e.preventDefault();
    //     var id_kriteria = $('#id_kriteria').val();
    //     var nama_kriteria = $('#nama_kriteria').val();
    //     var id_jenis = $('#id_jenis').val();
    //     var operation = $('#operation').val();
    //     if (nama_kriteria != '' && id_jenis != '') {
    //         $.ajax({
    //             method:'POST',
    //             dataType:'JSON',
    //             data:{id_kriteria:id_kriteria,nama_kriteria:nama_kriteria,id_jenis:id_jenis,operation:operation},
    //             url:'doKriteria',
    //             success:function(result){
    //                 Swal.fire({
    //                     icon: 'success',
    //                     title: 'Berhasil '+operation+' !',
    //                     timer:3000,
    //                 }).then((results) => {
    //                     /* Read more about handling dismissals below */
    //                     if (results.dismiss === Swal.DismissReason.timer) {
    //                         $('#table-kriteria').DataTable().ajax.reload();
    //                         $('#btnKriteria').val('Tambah');
    //                         $('#operation').val('Tambah');
    //                         $('#nama_kriteria').val('');
    //                         $('#id_jenis').val('');
    //                         $('#id_kriteria').val('');
    //                         $('#modalKriteria').modal('hide');
    //                     }else if(results.isConfirmed){
    //                         $('#table-kriteria').DataTable().ajax.reload();
    //                         $('#btnKriteria').val('Tambah');
    //                         $('#operation').val('Tambah');
    //                         $('#nama_kriteria').val('');
    //                         $('#id_jenis').val('');
    //                         $('#id_kriteria').val('');
    //                         $('#modalKriteria').modal('hide');
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

    // $(document).on('click','.editKriteria',function(){
    //     var id = $(this).attr('id');
    //     $('#modalKriteria').modal({backdrop:'static',show:true});
    //     $('#id_kriteria').val(id);
    //     $('#btnKriteria').val('Edit');
    //     $('#operation').val('Edit');
    //     $.ajax({
    //         method:'POST',
    //         dataType:'JSON',
    //         data:{id_kriteria:id},
    //         url:'kriteriaById',
    //         success:function(result){
    //             $('#nama_kriteria').val(result.nama_kriteria);
    //             $('#id_jenis').val(result.id_jenis);
    //         }
    //     })
    // })

    // $(document).on('click','.deleteKriteria',function(){
    //     Swal.fire({
    //         title: 'Yakin Di Hapus ?',
    //         text: 'Data akan terhapus permanen !',
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Ya, Hapus !'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             var id = $(this).attr('id');
    //             $.ajax({
    //                 method:'POST',
    //                 dataType:'JSON',
    //                 data:{id_kriteria:id},
    //                 url:'deleteKriteria',
    //                 success:function(result){
    //                     Swal.fire({
    //                         icon: 'success',
    //                         title: 'Berhasil dihapus !',
    //                         timer:3000,
    //                     }).then((results) => {
    //                         /* Read more about handling dismissals below */
    //                         if (results.dismiss === Swal.DismissReason.timer) {
    //                             $('#table-kriteria').DataTable().ajax.reload();
    //                         }else if(results.isConfirmed){
    //                             $('#table-kriteria').DataTable().ajax.reload();
    //                         }
    //                     })
    //                 }
    //             })
    //         }
    //     })
    // })

})
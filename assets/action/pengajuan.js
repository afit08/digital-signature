$(function () {
    // $("#example1").DataTable({
    //     "responsive": true, "lengthChange": false, "autoWidth": true,
    //     // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    // }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $('#pengajuan-page').attr('class', 'nav-link active bg-success');

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

    // $('#email_akun').click(function(){
    //     $('#email_akun').addClass('is-invalid');
    //     var cek = $('#email_akun').val();
    //     if (cek.indexOf('@') > -1) {
    //         $('#email_akun').removeClass('is-invalid');
    //     }else{
    //         $('#email_akun').addClass('is-invalid');
    //     }
    // })

    // $('#email_akun').keyup(function(){
    //     var cek = $('#email_akun').val();
    //     if (cek.indexOf('@') > -1) {
    //         $('#email_akun').removeClass('is-invalid');
    //     }else{
    //         $('#email_akun').addClass('is-invalid');
    //     }
    // })

    // $('#email_akun').keyup(function(){
    //     var checking = $('#email_akun').val();
    //     if (checking.indexOf('.com') > -1) {
    //         $.ajax({
    //             method: 'POST',
    //             data: {email:checking},
    //             dataType: 'JSON',
    //             url: 'getByEmail',
    //             success:function(result){
    //                 if (result.cond == '1') {
    //                     Toast.fire({
    //                         icon: 'success',
    //                         title: 'Email bisa digunakan !'
    //                     })
    //                     $('#email_akun').addClass('is-valid');
    //                     $('#form-btn').removeAttr('disabled','disabled');
    //                 }else{
    //                     Toast.fire({
    //                         icon: 'error',
    //                         title: 'Email sudah digunakan !'
    //                     })
    //                     $('#email_akun').removeClass('is-valid');
    //                     $('#form-btn').attr('disabled','disabled');  
    //                 }
    //             }
    //         })
    //     }else{
    //         $('#email_akun').removeClass('is-valid');
    //         $('#form-btn').attr('disabled','disabled');       
    //     }
    // })


    $('#table-pengajuan').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": true,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: "pengajuanLists",
            type: "post",
        },
        "columnDefs": [
            {
                "targets": [-1],
                "orderable": false,
            },
        ],
    });

    $('#btn-tambah').click(function () {
        $('#modals').modal({ backdrop: 'static', show: true });
        $('#exampleModalLabel').html('Tambah Pengajuan');
        $('#form-btn').val('Tambah');
        // kosongkan Field
        $('#perihal_pengajuan').val('');
        $('#deskripsi_pengajuan').val('');
        $('#file_pengajuan').val('');
        $('#old_nama_file').val('');
        $('#id_pengajuan').val('');
        $('input[name="pengesah[]"]').prop('checked', false);

        $('#operation').val('Tambah');
    })

    $('#table-pengesah').DataTable({
        "lengthMenu": [5, 10, 15]
    });

    $('#form-modal').submit(function (e) {
        e.preventDefault();
        var perihal_pengajuan = $('#perihal_pengajuan').val();
        var file_pengajuan = $('#file_pengajuan').val();
        var deskripsi_pengajuan = $('#deskripsi_pengajuan').val();
        if (perihal_pengajuan != '' && deskripsi_pengajuan != '' && $('input[name="pengesah[]"]:checked').length > 0) {
            $.ajax({
                method: 'POST',
                dataType: 'JSON',
                processData: false,
                contentType: false,
                data: new FormData(this),
                url: 'doPengajuan',
                success: function (result) {
                    Swal.fire({
                        icon: 'success',
                        title: result.msg,
                        timer: 3000,
                    }).then((results) => {
                        /* Read more about handling dismissals below */
                        if (results.dismiss === Swal.DismissReason.timer) {
                            $('#table-pengajuan').DataTable().ajax.reload();
                            $('#modals').modal('hide');
                        } else if (results.isConfirmed) {
                            $('#table-pengajuan').DataTable().ajax.reload();
                            $('#modals').modal('hide');
                        }
                    })
                }
            })
        } else {
            Swal.fire({
                icon: 'Warning',
                title: 'Peringatan !',
                text: 'Field Tidak boleh kosong !'
            })
        }
    })

    $(document).on('click', '.detail', function () {
        var id = $(this).attr('id');
        $('#modals-detail').modal({ backdrop: 'static', show: true });
        $('#exampleModalLabelDetail').html('Detail Pengajuan');
        $.ajax({
            method: 'POST',
            dataType: 'JSON',
            data: { id: id },
            url: 'pengajuanById',
            success: function (result) {
                var html = '';
                html += '<table class="table table-borderless">';
                html += '<tr>';
                html += '<td colspan="4"> <h5> Data Mahasiswa </h5> </td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td width="15%"> Nama</td>';
                html += '<td> : </td>';
                html += '<td>' + result.nama_mhs + '</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> NPM </td>';
                html += '<td> : </td>';
                html += '<td>' + result.npm_mhs + '</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Prodi </td>';
                html += '<td> : </td>';
                html += '<td>' + result.prodi_mhs + '</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td colspan="4"> <h5> Data Pengajuan </h5> </td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Perihal </td>';
                html += '<td> : </td>';
                html += '<td>' + result.perihal_pengajuan + '</td>';
                html += '<td> Nama File </td>';
                html += '<td> : </td>';
                html += '<td>' + result.nama_file_pengajuan + '</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Deksripsi </td>';
                html += '<td> : </td>';
                html += '<td colspan="4">' + result.deskripsi_pengajuan + '</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Created At </td>';
                html += '<td> : </td>';
                html += '<td>' + result.created_at + '</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Created By </td>';
                html += '<td> : </td>';
                html += '<td>' + result.created_by + '</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Updated At </td>';
                html += '<td> : </td>';
                html += '<td>' + result.updated_at + '</td>';
                html += '</tr>';
                html += '<tr>';
                html += '<td> Updated By </td>';
                html += '<td> : </td>';
                html += '<td>' + result.updated_by + '</td>';
                html += '</tr>';
                html += '</table>';
                html += '<table class="table table-bordered">';
                html += '<thead>';
                html += '<th> Nama Pengesah </th>';
                html += '<th> NIDN </th>';
                html += '<th> Jabatan </th>';
                html += '<th class="text-center"> Status </th>';
                html += '</thead>';
                html += '<tbody>';
                for (var i = 0; i < result.pengesah.length; i++) {
                    html += '<tr>';
                    html += '<td>' + result.pengesah[i].nama_user + '</td>';
                    html += '<td>' + result.pengesah[i].nip_user + '</td>';
                    html += '<td>' + result.pengesah[i].jabatan_user + '</td>';
                    if (result.pengesah[i].status == 0) {
                        var ht = '<i class="fa fa-times text-danger" title="Belum Ditandatangani"></i>';
                    } else {
                        var ht = '<i class="fa fa-check text-success" title="Sudah Ditandatangani"></i>';
                    }
                    html += '<td class="text-center">' + ht + '</td>';
                    html += '</tr>';
                }
                html += '</tbody>';
                html += '</table>';

                $('#table-detail').html(html);
            }
        })
    })

    $(document).on('click', '.edit', function () {
        var id = $(this).attr('id');
        $('#modals').modal({ backdrop: 'static', show: true });
        $('#exampleModalLabel').html('Edit Pengajuan');
        $('#form-btn').html('Edit');

        $('#id_pengajuan').val(id);
        $('#operation').val('Edit');
        $.ajax({
            method: 'POST',
            dataType: 'JSON',
            data: { id: id },
            url: 'pengajuanById',
            success: function (result) {
                $('#perihal_pengajuan').val(result.perihal_pengajuan);
                $('#old_nama_file').val(result.nama_file_pengajuan);
                $('#deskripsi_pengajuan').val(result.deskripsi_pengajuan);
                for (var i = 0; i < result.pengesah.length; i++) {
                    $('#defaultCheck' + result.pengesah[i].id_pengesah).prop('checked', true);
                }
            }
        })
    })

    $(document).on('click', '.hapus', function () {
        Swal.fire({
            title: 'Yakin Di Hapus ?',
            text: 'Data akan terhapus sementara !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus !'
        }).then((result) => {
            if (result.isConfirmed) {
                var id = $(this).attr('id');
                $.ajax({
                    method: 'POST',
                    dataType: 'JSON',
                    data: { id: id },
                    url: 'deletePengajuan',
                    success: function (result) {
                        Swal.fire({
                            icon: 'success',
                            title: result.msg,
                            timer: 3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-pengajuan').DataTable().ajax.reload();
                            } else if (results.isConfirmed) {
                                $('#table-pengajuan').DataTable().ajax.reload();
                            }
                        })
                    }
                })
            }
        })
    })

    $(document).on('click', '.sign', function () {
        Swal.fire({
            title: 'Yakin melakukan tanda tangan digital ?',
            text: 'anda akan menandatangani pengajuan ini !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Tanda Tangan !'
        }).then((result) => {
            if (result.isConfirmed) {
                var id = $(this).attr('id');
                $.ajax({
                    method: 'POST',
                    dataType: 'JSON',
                    data: { id: id },
                    url: base_url + 'sign',
                    success: function (results) {
                        if (results.cond == '1') {
                            Swal.fire({
                                icon: 'success',
                                title: results.msg,
                                timer: 3000,
                            }).then((results) => {
                                /* Read more about handling dismissals below */
                                if (results.dismiss === Swal.DismissReason.timer) {
                                    location.reload();
                                } else if (results.isConfirmed) {
                                    location.reload();
                                }
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: results.msg,
                                timer: 3000,
                            }).then((results) => {
                                /* Read more about handling dismissals below */
                                if (results.dismiss === Swal.DismissReason.timer) {
                                    location.reload();
                                } else if (results.isConfirmed) {
                                    location.reload();
                                }
                            })
                        }
                    }
                })
            }
        })
    })

    $(document).on('click', '.btn-qr', function () {
        var id = $(this).attr('id');
        $('#modal-qr').modal({ backdrop: 'static', show: true });
        $('#exampleModalLabel').html('Detail QRCODE');
        $.ajax({
            method: 'POST',
            dataType: 'JSON',
            data: { id: id },
            url: base_url + 'pengajuanDetailId',
            success: function (result) {
                var html = '';
                html += '<img class="img-thumbnail d-block mx-auto" src="' + base_url + 'assets/file/images/' + result.qr_code + '">';
                $('#body-modal').html(html);
            }
        })
    })
    $('#pesan_pengajuan').summernote({
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol', 'paragraph']]
        ]
    })

    $('#btn-tolak').click(function () {
        $('#modal-tolak').modal({ backdrop: 'static', show: true });
        $('#exampleModalLabelTolak').html('Form Penolakan Pengajuan');
    })

    $('#form-tolak').submit(function (e) {
        e.preventDefault();
        var pesan_pengajuan = $('#pesan_pengajuan').val();
        if (pesan_pengajuan) {
            $.ajax({
                method: 'POST',
                dataType: 'JSON',
                processData: false,
                contentType: false,
                data: new FormData(this),
                url: base_url + 'gantiStatusPengajuan',
                success: function (result) {
                    Swal.fire({
                        icon: 'success',
                        title: result.msg,
                        timer: 3000,
                    }).then((results) => {
                        /* Read more about handling dismissals below */
                        if (results.dismiss === Swal.DismissReason.timer) {
                            location.reload();
                        } else if (results.isConfirmed) {
                            location.reload();
                        }
                    })
                }
            })
        } else {
            Swal.fire({
                icon: 'Warning',
                title: 'Peringatan !',
                text: 'Field Tidak boleh kosong !'
            })
        }
    })

    $(document).on('click', '.proses', function () {
        Swal.fire({
            title: 'Pengajuan tersebut akan diproses ?',
            text: 'pengajuan ini akan dilanjutkan ke proses tanda tangan !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya'
        }).then((result) => {
            if (result.isConfirmed) {
                var id = $(this).attr('id');
                var status = 1;
                var pesan_pengajuan = '-';
                $.ajax({
                    method: 'POST',
                    dataType: 'JSON',
                    data: { id: id, status: status, pesan_pengajuan: pesan_pengajuan },
                    url: base_url + 'gantiStatusPengajuan',
                    success: function (results) {
                        if (results.cond == '1') {
                            Swal.fire({
                                icon: 'success',
                                title: results.msg,
                                timer: 3000,
                            }).then((results) => {
                                /* Read more about handling dismissals below */
                                if (results.dismiss === Swal.DismissReason.timer) {
                                    location.reload();
                                } else if (results.isConfirmed) {
                                    location.reload();
                                }
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: results.msg,
                                timer: 3000,
                            }).then((results) => {
                                /* Read more about handling dismissals below */
                                if (results.dismiss === Swal.DismissReason.timer) {
                                    location.reload();
                                } else if (results.isConfirmed) {
                                    location.reload();
                                }
                            })
                        }
                    }
                })
            }
        })
    })

})
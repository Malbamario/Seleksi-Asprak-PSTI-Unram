$(document).ready(function () {
    $(document).on('click','#hapus',function (e) {
        e.preventDefault();
        var data=$(this).data('a');
        var url=$(this).attr('href');
    });

    $(document).on('click','#hapus',function (e) {
        e.preventDefault();
        var data=$(this).data('a');
        var url=$(this).attr('href');
        var confirm=window.confirm("Apakah anda ingin mengahapus data "+data+" ?");
        if (confirm==true){
            $.ajax({
                type:'GET',
                url:url,
                dataType:'JSON',
                cache:false,
                success:function (e) {
                    if (e=='success'){
                        alert('berhasil hapus data!');
                        setTimeout(function () {
                            location.reload();
                        },100);
                    }else{
                        alert('Gagal Hapus data'+e);
                    }
                }
            });
        }else{
            return false;
        }
    });
    $('a#out').click(function () {
        var confirm=window.confirm("Apakah anda ingin keluar ?");
        if (confirm == true){
            return true;
        }else{
            return false;
        }
    });
    $('form#form').on('submit',function (e) {
        e.preventDefault();
        var url=$(this).attr('action');
        var data=$(this).serialize();
        $.ajax({
            url:url,
            data:data,
            dataType:'JSON',
            type:'POST',
            beforeSend:function(){
                $("#buttonsimpan").html("process..");
                $("input,#buttonsimpan,#buttonreset").attr('disabled',true);
                console.log(data);
            },
            success:function (e) {
                if (e=='success'){
                    alert('Berhasil Memasukan Data!');
                    setTimeout(function () {
                        location.reload();
                    },100);
                }else if (e=='ada data'){
                    alert('Data tidak Boleh sama!');
                    setTimeout(function () {
                        location.reload();
                    },100);
                }else if (e=='failed'){
                    alert('Gagal Memasukan data!');
                    setTimeout(function () {
                        location.reload();
                    },100);
                }else{
                    alert('Berhasil Update Data!');
                    window.location.href=e;
                }
            }
        })
    })
    $('form#formlogin').on('submit', function(event) {
        event.preventDefault();
        var url=$(this).attr('action');
        var data=$(this).serialize();
        $.ajax({
            url:url,
            data:data,
            dataType:'JSON',
            type:'POST',
            beforeSend:function(){
                $("#buttonsimpan").html("process..");
                $("input,#buttonsimpan,#buttonreset").attr('disabled',true);
            },
            success:function(e){
                if (e=='success') {
                    location.reload();
                }else{
                    $('#value').html(e);
                        $('#alert').slideDown('slow', function() {
                        setTimeout(function () {
                            location.reload();
                        },1500);
                    });
                }
            }
        });
    });
    $('button#hidden').on('click',function () {
        $('ul.nav').slideToggle();
    })
    $('button#btn-dropdown').on('click',function () {
        $(this).next('#panel-dropdown').toggleClass('show');
    })

    $('#isiNilai').load("./proses/proseslihat.php/?op=nilai");

    $('#pilihNilai').change(function(){
        pilihNilai(this);
    });

    $('#pilihMatkul').change(function() {
        var value =$(this).val();
        pilihMahasiswa = $('#pilihMahasiswa');
        isiMahasiswa(pilihMahasiswa, this, value);
    });
    $("#pilihHasil").change(function() {
        var value=$(this).val();
        $("#valueHasil").hide("slow");
        document.cookie="pilih="+value+";expires=3600;path=/";
        if (getCookieData) {
            $("#valueHasil").load("./hasil.php").slideToggle("slow");
            $('button#btn-dropdown').attr('disabled', false);
        }
    });

    function pilihNilai(el) {
        var value = $(el).val();
        var pilihMatkul = $('#pilihMatkul');
        pilihMatkul.val(value);
        if(value>0) pilihMatkul.attr("disabled", "disabled");
        else pilihMatkul.removeAttr("disabled");
        isiMahasiswa($('#pilihMahasiswa'), pilihMatkul, value);
        $('#isiNilai').hide().load("./proses/proseslihat.php/?op=nilai&id="+value).fadeIn('400');
    }

    function isiMahasiswa(el, pilihMatkul, value){
        if($(pilihMatkul).val()==null) el.attr("disabled", "disabled");
        else el.removeAttr("disabled");
        el.hide().load("./proses/proseslihat.php/?op=mahasiswa&id="+value).fadeIn();
    }

    function getCookieData(){
        var data=getCookie("pilih");
        if (data==null && data=="") {
            return false;
        }else{
            return true;
        }
    }
    $('button#btn-dropdown').attr('disabled', true);

    pilihNilai($('#pilihNilai'));
})
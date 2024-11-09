<button id="" name="" class="btn btn-danger btn-mini" onclick="bt_del(this.id)"><i class="ti ti-trash"></i> ลบ</button>

<script>
    function bt_del(id) {

        var del_id = id;
        $.ajax({
            type: "post",
            url: "../form/w2_del_wfr.php",
            data: {
                id: del_id
            },
            success: function (response) {
                console.log('----------------------')
                console.log(response);
                console.log('----------------------')
                location.reload();

            }
        });
    }

    $(document).ready(function () {

        $('tr').each(function (k, v) {
            if ($(this).attr('id') !== undefined) {

                /******* ทำปุ่มลบ ********/
                let infoBtn = $(this).find("td:eq(6)").find(".btn.btn-success.btn-mini");
                let infoBtn2 = $(this).find("td:eq(6)").find(".btn.btn-info.btn-mini");
                let del = $(this).find("td:eq(6)").find(".btn.btn-danger.btn-mini");
                infoBtn2.after(del);
                infoBtn2.after('&nbsp;&nbsp;')
                /******* จบทำปุ่มลบ ********/

                let id = $(this).attr('id').replace('tr_wf_', '');
                $(del).attr('id', id);

                let date = new Date($(this).find("td:eq(1)").text());
                let day = String(date.getDate()).padStart(2, '0');
                let month = String(date.getMonth() + 1).padStart(2, '0');
                let year = date.getFullYear();

                let formattedDate = day + '/' + month + '/' + year;
                $(this).find("td:eq(1)").text(formattedDate);
                console.log('------------------------------------');
                console.log(formattedDate);
                console.log('------------------------------------');
            }
        });


    });
</script>
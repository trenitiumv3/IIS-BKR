<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD</h2>
    </div>

    <!-- Widgets -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">            
            <label for="email_address">Barcode <span class="col-pink">*</span></label>
            <span class="cd-error-message font-bold col-pink" id="err-barcode"></span>
            <div class="form-group">
                <div class="form-line">
                    <input type="text" maxlength="13" id="barcode-input" data-label="#err-barcode" class="form-control" placeholder="Masukan data barcode" autofocus>
                </div>
            </div>

            <img id="barcode"/>            
            <br/>
            <button type="button" class="btn btn-primary m-t-15 waves-effect btn-generate">Generate</button>
            <a href="" id="btn-download" download>
                <button type="button" class="btn btn-primary m-t-15 waves-effect btn-download">Download</button>
            </a>
        </div>
    </div>    
</div>
<script>
    $(".btn-generate").click(function(){
        var value = $("#barcode-input").val();
        if(value.length<13){
            alertify.set('notifier', 'position', 'bottom-right');
            alertify.error('Barcode harus 13 digit');
        }else{
            checkBarcode(value);      
        }
    });

    function checkBarcode(value){
        $.ajax({
            url: "<?php echo site_url('item/checkBarcode')?>"+"/"+value,            
            type: "GET",
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $("#load_screen").show();
            },
            success: function(data) {
                if (data.status != 'error') {
                    $("#load_screen").hide();
                    $(".modal").hide();
                    alertify.set('notifier', 'position', 'bottom-right');
                    alertify.success(data.msg);
                    $("#barcode").JsBarcode(value);
                    JsBarcode("#barcode", value, {                        
                        width:1,
                        height:60
                    });
                    $("#btn-download").attr("href",$("#barcode").attr("src"));                                  
                } else {
                    $("#load_screen").hide();
                    alertify.set('notifier', 'position', 'bottom-right');
                    alertify.error(data.msg);
                }
            },
            error: function(xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                //alertify.error(xhr.responseText);
                $("#load_screen").hide();
                alertify.set('notifier', 'position', 'bottom-right');
                alertify.error('Cannot response server !');
            }
        });    
    }
    
</script>
<style>
    #report-table{
        width:100%!important;
    }
    .highlight{
        font-size:20px;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2>
            Report Penjualan Tanggal <?php echo date("d F Y");?>        
        </h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">                    
                  
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="datepicker form-control" placeholder="Please choose a date...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="report-table" class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>             
                                    <th>ID Transaksi</th>                               
                                    <th>Jenis Pembayaran</th>
                                    <th>Total</th>
                                    <th>Discount</th>
                                    <th>Kasir</th> 
                                    <th>Action</th>                                            
                                </tr>
                            </thead>                                    
                            <tbody>
                                <?php 
                                    $totalPurchase=0;
                                    foreach($data_purchase as $row){ 
                                        $totalPurchase += $row['total_price'];
                                        //$discount = $row['extra_discount']? 
                                ?>
                                <tr>
                                    <td><?php echo $row['id'];?></td>
                                    <td><?php echo $row['type_purchase'];?></td>
                                    <td><?php echo $row['total_price'];?></td>
                                    <td><?php echo $row['extra_discount'];?></td>
                                    <td><?php echo $row['name'];?></td>                                    
                                    <td class="dt-center">
                                        <a href="<?php echo site_url()."/Report/reportClinicPoliVisitDetail/";?>">
                                            <button type="button" class="btn btn-primary btn-xs">
                                                <span class="glyphicon glyphicon-plus"></span>&nbsp Detail
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <table class="table">
                            <tr>
                                <td><b>Total Penjualan</b></td>
                                <td class="highlight"><?php echo $totalPurchase;?></td>
                            </tr>
                            <tr>
                                <td><b>Total Transaksi</b></td>
                                <td class="highlight"><?php echo count($data_purchase);?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->           
</div>

<script>
    $(function() {
        $('input').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
        var table = $('#report-table').DataTable({
            "lengthChange": false,
            columns: [
                { data: 0,"width": "10%" },
                { data: 1, "width": "10%"},
                { data: 2, "width": "20%"},
                { data: 3, "width": "10%"},
                { data: 4, "width": "20%"},
                { data: 5, "width": "30%"}
            ]
        });
    });
</script>
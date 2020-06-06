<?php
// Techker made 2020
include "session.php"; include "functions.php";
if ((!$rPermissions["is_admin"]) OR (!hasPermissions("adv", "connection_logs"))) { exit; }

$rows =getStreamProviders();

if(isset($_GET['delete'])){
deleteProviderDNS($_GET['delete']);
header("Location: line_connections.php");
}

if(isset($_POST['add'])){
  $name	= strip_tags($_REQUEST['name']);
  $pass	= strip_tags($_REQUEST['pass']);
  $user	= strip_tags($_REQUEST['user']);
	$url	= strip_tags($_REQUEST['url']);


insertProviderDNS($name,$url,$user,$pass);
header("Location: line_connections.php");
}

if ($rSettings["sidebar"]) {
    include "header_sidebar.php";
} else {
    include "header.php";
}


        if ($rSettings["sidebar"]) { ?>
        <div class="content-page"><div class="content boxed-layout-ext"><div class="container-fluid">
        <?php } else { ?>
        <div class="wrapper boxed-layout-ext"><div class="container-fluid">
        <?php } ?>
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Provider DNS</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body" style="overflow-x:auto;">


                                <div class="form-group row mb-4">

                                  <form method="post" >

                                        <div class="form-group">
                                          <label class="bmd-label-floating">URL (http://serverdns:PORT no /)</label>
                                          <input type="text" class="form-control" name="url" placeholder="http://serverdns:PORT">
                                          <label class="bmd-label-floating">Provider Name</label>
                                          <input type="text" class="form-control" name="name">
                                          <label class="bmd-label-floating">Provider user</label>
                                        <input type="text" class="form-control" name="user">
                                        <label class="bmd-label-floating">Provider pass</label>
                                        <input type="text" class="form-control" name="pass">
                                        <br></br>
                                        <button type="submit" name="add" class="btn btn-warning">ADD</button>
                                        </div>
                                </form>
                                </div>
                                <table id="datatable-activity" class="table dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>Provider ID</th>
                                            <th>Provider Name</th>
                                            <th>Provider DNS</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <?php   while($row = mysqli_fetch_assoc($rows)) { ?>
                                            <td><?php echo $row['provider_id'];?></td>
                                            <td><?php echo $row['provider_name'];?></td>
                                            <td><?php echo $row['provider_dns'];?></td>
                                            <td class="text-center">
                                        <?php if (hasPermissions("adv", "edit_bouquet")) { ?>
                                                <div class="btn-group">
                                                <a class="btn btn-light waves-effect waves-light btn-xs" href="./line_connections.php?delete=<?=$row['provider_id']; ?>"><i class="mdi mdi-close"></i></a>
                                                <button type="button" data-toggle="modal" data-target="#exampleModal" data-placement="top" title="Check" data-original-title="Check" class="btn btn-light waves-effect waves-light btn-xs btn-reboot-server" data-id="<?=$row['provider_id'];?>"><i class="mdi mdi-restart"></i></button>                                                </div>
                                        <?php } else { echo "--"; } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
                <!-- end row-->
            </div> <!-- end container -->
        </div>
        <div class="modal fade downloadModal" id="exampleModal" role="dialog" aria-labelledby="downloadLabel" aria-hidden="true" style="display: none;" >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="downloadModal">Check Connections</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                          <div class="fetched-data"></div>
                        </div>

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- end wrapper -->
        <?php if ($rSettings["sidebar"]) { echo "</div>"; } ?>
        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 copyright text-center"><?=getFooter()?></div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->

        <script src="assets/js/vendor.min.js"></script>
        <script src="assets/libs/jquery-toast/jquery.toast.min.js"></script>
        <script src="assets/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables/dataTables.bootstrap4.js"></script>
        <script src="assets/libs/select2/select2.min.js"></script>
        <script src="assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables/responsive.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables/buttons.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables/buttons.flash.min.js"></script>
        <script src="assets/libs/datatables/buttons.print.min.js"></script>
        <script src="assets/libs/datatables/dataTables.keyTable.min.js"></script>
        <script src="assets/libs/datatables/dataTables.select.min.js"></script>
        <script src="assets/js/pages/form-remember.js"></script>
        <script src="assets/js/app.min.js"></script>



  <script>


  $(document).ready(function(){
    $('#exampleModal').on('show.bs.modal', function (e) {
        var rowid = $(e.relatedTarget).data('id');
        $.ajax({
            type : 'post',
            url : 'fetch_record.php', //Here you will fetch records
            data :  'rowid='+ rowid, //Pass $id
            success : function(data){
            $('.fetched-data').html(data);//Show fetched data from database
            }
        });
     });
});

  function doDownload() {
      if ($("#download_url").val().length > 0) {
          window.open($("#download_url").val());
      }
  }

  function download() {
      $("#download_type").val("");
      $("#download_button").attr("disabled", true);
      $('.downloadModal').modal('show');
  }
          </script>
        <!-- App js-->
        <script src="assets/js/app.min.js"></script>
    </body>
</html>

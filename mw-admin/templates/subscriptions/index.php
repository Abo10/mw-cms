<?php
$subscriptions = Cmwdb::$db->get('usr_suscriptions');
?>
<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Subscriptions
                </h1>

            </div>
        </div>
        <?php if ($subscriptions) { ?>

            <table class="table table-bordered table-striped table-responsive" id="all_posts">

                <tr>
                    <th width="2%">ID</th>
                    <th width="2%">Email</th>
                    <th>Date-time</th>
                </tr>
                <tbody>
                <?php foreach ($subscriptions as $key => $item) { ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= $item['email'] ?></td>
                        <td><?= $item['date'] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php }else{ ?>
            <h2>There is no subscriptions yet</h2>
        <?php } ?>

    </div>
</div>
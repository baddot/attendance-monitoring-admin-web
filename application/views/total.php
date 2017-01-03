<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php echo '<h3>Teacher: ' . $fullname . '</h3>'; ?>
                <?php echo '<h4>Total Present: ' . $total . '</h4>' ?>
                <?php echo '<h4>Total Absent: ' . $totalabsent . '</h4>' ?>
                <?php echo '<h4>Total Duty Hours: ' . $totalduty . '</h4>' ?>
            </div>
        </div>
    </div>
</div><!--/.row-->	
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">exprt</div>
            <div class="panel-body">
                <?php echo validation_errors(); ?>
                <?php echo form_open(base_url('teachers/export/' . $t_id), array('role'=>"form",'method'=>'get')); ?>



                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                        echo form_dropdown('month', my_month_array(), set_value('month'), array(
                            'class' => 'form-control'
                        ));
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                        echo form_dropdown('year', year_combo(2015, 2020), set_value('year'), array(
                            'class' => 'form-control'
                        ));
                        ?>
                    </div>
                    <?php echo form_hidden('t_id',$t_id) ?>
                    <div class="form-group"><?php
                        echo form_submit('btn2', 'Export Excel Report', array(
                            'class' => 'btn btn-primary'
                        ));
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                </div>

                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>
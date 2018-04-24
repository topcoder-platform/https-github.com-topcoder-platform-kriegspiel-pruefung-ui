<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-dashboard"></i> Live Tournaments
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                     <th>Tournament Name</th>
                      <th>Start</th>
                      <th>End</th>
                      <th>Attempts per player</th>
                      <th class="text-center">Actions</th>
                    </tr>
                    <?php
                    if(!empty($tournaments))
                    {
                        foreach($tournaments as $record)
                        {
                    ?>
                    <tr>
                      <td><?php echo $record->title ?></td>
                      <td><?php echo gmdate('r', ($record->dateStart / 1000)); ?></td>
                      <td><?php echo gmdate('r', ($record->dateEnd / 1000)); ?></td>
                      <td><?php echo $record->playerAttemptsPerMatch ?></td>
                      <td class="text-center">
                          <a class="btn btn-sm btn-primary" href="<?php echo base_url().'participate' ?>" title="Enter Tournament"><i class="fa fa-bolt"></i></a>
                          <?php if ($this->session->userdata('MATCH_ID') !== null) { ?>
                          <a class="btn btn-sm btn-info" href="<?php echo base_url().'leaderboard' ?>" title="View Leaderboard"><i class="fa fa-graduation-cap"></i></a>
                          <?php } ?>
                      </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                  </table>
                  
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>

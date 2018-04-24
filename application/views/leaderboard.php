<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-graduation-cap"></i> Leaderboard
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                     <th>Player Name</th>
                      <th>Score</th>
                      <th>Rank</th>
                    </tr>
                    <?php
                    if(!empty($leaderboard))
                    {
                        foreach($leaderboard as $record)
                        {
                    ?>
                    <tr class = "<?php echo $this->session->userdata('name') === $record->playerName ? 'bg-primary' : '' ?>" >
                      <td><?php echo $record->playerName ?></td>
                      <td><?php echo $record->score ?></td>
                      <td><?php echo $record->rank ?></td>
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

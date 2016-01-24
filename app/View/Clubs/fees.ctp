<div id="PageContent" class="wrapper wrapper-content animated fadeInRight">
    <div class="row">

        <div class="col-lg-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?=__('Fees');?></h5>
                    <div class="ibox-tools">

                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-cog" style="font-size: 16px"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#"><?=__('Izvještaj');?></a>
                            </li>
                            <li><a href="#">Config option 2</a>
                            </li>
                        </ul>

                    </div>
                </div>
                <div class="ibox-content">
                    <div class="form-group" id="data_4">
                        <label class="font-normal">Mjesec</label>
                        <div class="input-group date col-md-4">
                            <span class="input-group-addon" style="background-color:#1ab394; color:white;"><i class="fa fa-calendar"></i></span>
                            <input type="text" class="form-control" value="<?=$month;?>">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table #table-bordered #table-striped" id="FeesTable">
                            <thead>
                                <tr>
                                    <th><?=__('Name');?></th><th><?=__('Paid');?></th><th><?=__('Note');?></th>
                                </tr>
                            </thead>
                                <tbody>
                                <? foreach($fees as $fee):?>
                                <tr>
                                    <td><?=$fee['User']['name'].' '.$fee['User']['surname'];?></td>
                                    <td>
                                        <div class="checkbox checkbox-success">
                                            <input id="checkbox#<?
                                                echo $fee['User']['id'].'*';
                                                if (!empty($fee['MembershipFee'][0]['id'])){echo $fee['MembershipFee'][0]['id'];}
                                                ?>"
                                                   class="feeCheckbox"
                                                   type="checkbox" <?if(!empty($fee['MembershipFee'][0]['paid']) && $fee['MembershipFee'][0]['paid']){echo "checked";}?>>
                                            <label for="checkbox#<?
                                            echo $fee['User']['id'].'*';
                                            if (!empty($fee['MembershipFee'][0]['id'])){echo $fee['MembershipFee'][0]['id'];}
                                            ?>">
                                                <?$fee['User']['fullname'];?>
                                            </label>
                                        </div>
                                    </td>
                                    <?if (!empty($fee['MembershipFee'][0]['note'])){$note = $fee['MembershipFee'][0]['note'];}
                                    else {$note = '';} ?>
                                    <?if (!empty($fee['MembershipFee'][0]['id'])){$slug = $fee['MembershipFee'][0]['id'];}
                                    else {$slug = '';}
                                    ?>
                                    <td><?=$this->Form->input('note', array(
                                            'label' => false,
                                            'value'=>$note,
                                            'class'=>'FeeNote',
                                            'id'=>'FeeNote#'.$fee['User']['id'].'*'.$slug
                                        ));?></td>
                                </tr>

                            <?endforeach;?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?=$this->element('Scripts');?>

<script>
    $(document).ready(function(){

        $.event.special.inputchange = {
            setup: function() {
                var self = this, val;
                $.data(this, 'timer', window.setInterval(function() {
                    val = self.value;
                    if ( $.data( self, 'cache') != val ) {
                        $.data( self, 'cache', val );
                        $( self ).trigger( 'inputchange' );
                    }
                }, 2000));
            },
            teardown: function() {
                window.clearInterval( $.data(this, 'timer') );
            },
            add: function() {
                $.data(this, 'cache', this.value);
            }
        };

        $('.FeeNote').on('inputchange', function(){
            var id = $(this).attr('id');

            var start_pos = id.indexOf('#') + 1;
            var end_pos = id.indexOf('*',start_pos);

            var user_id = id.substring(start_pos,end_pos);
            var fee_id = id.substring(id.indexOf('*') + 1);

            var note = $(this).val();
            console.log(note);

            $.ajax({
                url:'/clubs/updateFeeNote',
                data:{
                    user_id:user_id,
                    fee_id: fee_id,
                    note: note
                },
                type:'POST',
                dataType:'JSON',
                success:function(response){
                    if(response.success){
                        toastr.success(response.message);
                    } else{
                        toastr.error(response.message);
                    }
                },
                error:function(){

                }
            });
        })

        $('#data_4 .input-group.date').datepicker({

            format: 'mm.yyyy',
            minViewMode: 1,
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            todayHighlight: true
        });

        $('#data_4 input').on('change',function(){
            var date = $(this).val();
            var date_array = date.split('.');

            window.location = '/clubs/fees/'+date_array[0]+'/'+date_array[1];
        });

        $('.feeCheckbox').on('change',function(){
            var id = $(this).attr('id');

            var start_pos = id.indexOf('#') + 1;
            var end_pos = id.indexOf('*',start_pos);

            var user_id = id.substring(start_pos,end_pos);
            var fee_id = id.substring(id.indexOf('*') + 1);
            var checked = $(this).is(":checked");

            console.log(user_id, checked, fee_id);
            $.ajax({
                url:'/clubs/updateFee',
                data:{
                    user_id:user_id,
                    fee_id: fee_id,
                    paid:checked
                },
                type:'POST',
                dataType:'JSON',
                success:function(response){
                    if(response.success){
                        toastr.success(response.message);
                    } else{
                        toastr.error(response.message);
                    }
                },
                error:function(){

                }
            });
        });
    });
</script>

<div id="PageContent" class="wrapper wrapper-content animated fadeInRight">
    <div class="row">

        <div class="col-lg-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?=__('Fees');?></h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a>
                            </li>
                            <li><a href="#">Config option 2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="form-group" id="data_4">
                        <label class="font-noraml">Mjesec</label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" class="form-control" value="<?=$month;?>">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>id</th><th>name</th><th>paid</th><th>note</th>
                                </tr>
                            </thead>
                                <tbody>
                                <? foreach($fees as $fee):?>
                                <tr>
                                    <td><?=$fee['User']['id'];?></td><td><?=$fee['User']['name'].' '.$fee['User']['surname'];?></td>
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
                                    <td><?=$this->Form->input('note', array('label' => false,'value'=>$note));?></td>
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

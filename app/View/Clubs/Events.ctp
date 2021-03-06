<div id="PageContent" class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox">
            <div class="ibox-title">
                <h5><?=__('Create event');?>
                    <small>(<?=__("training");?>, <?=__("competition");?>...)</small>
                </h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <?=$this->Form->create('ClubEvent', ['url' => array('controller' => 'clubs', 'action' => 'createClubEvent')]);?>
                <div class="row">
                    <?= $this->Form->input('name',array(
                        'placeholder'=>__("Name"),
                        'label'=>false,
                        'class'=>'form-control cleanInput',
                        'div'=>'form-group col-md-4'));
                    ?>
                </div>
                <div class="row">
                    <div class="form-group" id="data_1">

                        <div class="col-md-2">
                            <div class="input-group date ">
                                <span class="input-group-addon" style="background-color: #1ab394;
        color: white;"><i class="fa fa-calendar"></i></span>
                                <input name="data[ClubEvent][date]" type="text" class="form-control cleanInput" placeholder="<?=__("Date");?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group clockpicker" data-autoclose="true">
                                <span class="input-group-addon" style="background-color: #1ab394;color: white;"><i class="fa fa-clock-o"></i></span>
                                <input name="data[ClubEvent][time]" type="text" class="form-control cleanInput" placeholder="<?=__('Time');?>">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                        <?=$this->Form->select('ClubGroup.id',$clubGroups,
                            ['multiple'=>true,
                                'style'=>'width:155px;margin: 0 20px 0 0;',
                                'class'=>"chosen-select form-control cleanInput",
                                'tabindex'=>'3',
                                'data-placeholder'=>__('Add groups')


                            ]);?>
                        </div>
                        <div class="col-md-2">
                             <?=$this->Form->select('User.id',$clubMembers,
                                ['multiple'=>true,
                                    'style'=>'width:155px;',
                                    'class'=>"chosen-select form-control",
                                    'tabindex'=>'4',
                                    'data-placeholder'=>__('Add members')

                                ]);?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                    <button class="btn btn-primary"  type="submit"><?=__("Create");?></button>
                </form>
            </div>

        </div>
    </div>



    <div class="row">
        <?if($events):?>
        <?foreach($events as $event):?>
            <div id="ClubEvent-<?=$event['ClubEvent']['id'];?>" class="ibox <?if($event['ClubEvent']['date'] < date('Y-m-d',time())){echo 'collapsed';};?>">
                <div class="ibox-title">
                    <h5><?=$event['ClubEvent']['name'].' - '.date('d.m.Y',strtotime($event['ClubEvent']['date'])) ;?></h5>
                    <div class="ibox-tools">

                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-cog"></i>
                        </a>
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Edit</a>
                            </li>
                            <li><a onclick="deleteClubEvent(<?=$event['ClubEvent']['id'];?>);">Delete</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="ibox-content">
                    <fieldset>
                    <?foreach($event['User'] as $user):?>
                        <div class="checkbox checkbox-success">
                            <input id="checkbox#<?=$user['id'].'&'.$event['ClubEvent']['id'];?>*"
                                   class="attendanceCheckbox"
                                   type="checkbox" <?if($user['UsersClubEvent']['attended']){echo "checked";}?>>
                            <label for="checkbox#<?=$user['id'].'&'.$event['ClubEvent']['id'];?>*">
                                <?=$user['fullname'];?>
                            </label>
                        </div>

                    <? endforeach;?>

                    <?foreach($event['ClubGroup'] as $clubGroup):?>

                        <?foreach($clubGroup['User'] as $user):?>
                        <!--
                            <div class="checkbox checkbox-success">
                                <input id="checkbox#<?=$user['id'].'&'.$event['ClubEvent']['id'];?>*" type="checkbox" class="attendanceCheckbox">
                                <label for="checkbox#<?=$user['id'].'&'.$event['ClubEvent']['id'];?>*">
                                    <?=$user['fullname'];?>
                                </label>
                            </div>
                        -->


                        <? endforeach;?>
                    <? endforeach;?>
                        </fieldset>
                </div>
            </div>
        <? endforeach;?>
        <? endif;?>
    </div>
</div>


<?=$this->element('Scripts');?>
<script src="/js/plugins/validate/jquery.validate.min.js"></script>

<script>
    $(document).ready(function(){

        $('.attendanceCheckbox').on('change',function(){
            var id = $(this).attr('id');
            var checked = $(this).is(":checked");


            var test_str = id;

            var start_pos = test_str.indexOf('#') + 1;
            var end_pos = test_str.indexOf('&',start_pos);
            var text_to_get = test_str.substring(start_pos,end_pos);
            var user_id = text_to_get;
            //console.log(user_id);

            var start_pos = test_str.indexOf('&') + 1;
            var end_pos = test_str.indexOf('*',start_pos);
            var text_to_get = test_str.substring(start_pos,end_pos);
            var event_id = text_to_get;
            //console.log(event_id);

            $.ajax({
                url:'/clubs/updateAttendance',
                data:{
                    user_id:user_id,
                    event_id:event_id,
                    attended:checked
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

        $(".input-group.date").datepicker({
            todayBtn: "linked",
            format: 'dd.mm.yyyy',
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true
        });

        $(".input-group.clockpicker").clockpicker();
        $(".chosen-select").chosen();

        $("#ClubEventEventsForm").validate({
            rules: {
                'data[ClubEvent][name]': { required : true },
                //'data[ClubEvent][password]': { required : true}
            },
            messages: {
                'data[ClubEvent][name]': { required : '<?=__("Required field");?>'},
                //'data[User][password]': { required : '<?=__("Required field");?>'}
            },
            submitHandler: function(){
                $.ajax({
                    url: "/clubs/createClubEvent",
                    data: $('#ClubEventEventsForm').serialize(),
                    type: "POST",
                    dataType: 'json',
                    success:function(data){
                        if(data.success){
                            toastr.success(data.message, function(){
                               location.reload();
                            });

                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error:function(){
                        toastr.error("<?=__('We\'re sorry!').' '.__('Something went wrong');?>");
                    }
                });

                return false;
            }
        });
    });

    function deleteClubEvent(club_event_id){
        $.ajax({
            url:'/clubs/deleteClubEvent',
            data:{
                event_id:club_event_id
            },
            type:'POST',
            dataType:'JSON',
            success:function(response){
                if(response.success){
                    toastr.success(response.message);
                    $('#ClubEvent-'+club_event_id).remove();
                } else{
                    toastr.error(response.message);
                }
            },
            error:function(){

            }
        });
    }
</script>

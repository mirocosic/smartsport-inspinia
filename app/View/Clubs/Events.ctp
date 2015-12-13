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
                <?= $this->Form->input('name',array(
                    'placeholder'=>__("Name"),
                    'label'=>false,
                    'class'=>'form-control',
                    'div'=>'form-group'));
                ?>
                <div class="form-group" id="data_1">

                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input name="data[ClubEvent][date]" type="text" class="form-control" placeholder="datum">
                    </div>
                </div>

                <div class="form-group">

                    <div class="input-group clockpicker" data-autoclose="true">
                        <span class="input-group-addon">
                            <span class="fa fa-clock-o"></span>
                        </span>
                        <input name="data[ClubEvent][time]" type="text" class="form-control">

                    </div>
                </div>


                <div class="form-group">
                    <?$options = array('Amerika'=>'Amerika', 'Croatia'=>'Croatia');?>
                    <?=$this->Form->select('ClubGroup.id',$clubGroups,
                        ['multiple'=>true,
                            'style'=>'width:350px;',
                            'class'=>"chosen-select form-control",
                            'tabindex'=>'4',
                            'data-placeholder'=>__('Add groups')

                        ]);?>
                </div>

                <div class="form-group">
                    <?$options = array('Amerika'=>'Amerika', 'Croatia'=>'Croatia');?>
                    <?=$this->Form->select('User.id',$clubMembers,
                        ['multiple'=>true,
                            'style'=>'width:350px;',
                            'class'=>"chosen-select form-control",
                            'tabindex'=>'4',
                            'data-placeholder'=>__('Add users')

                        ]);?>
                </div>

                    <button class="btn btn-sm btn-primary"  type="submit"><?=__("Create");?></button>
                </form>
            </div>

        </div>
    </div>

    <?//debug($events);?>

    <div class="row">
        <?if($events):?>
        <?foreach($events as $event):?>
            <div class="ibox">
                <div class="ibox-title">
                    <h5><?=$event['ClubEvent']['name'].' - '.$event['ClubEvent']['date'] ;?></h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>

                    </div>
                </div>
                <div class="ibox-content">
                    <fieldset>
                    <?foreach($event['User'] as $user):?>
                        <div class="checkbox checkbox-success">
                            <input id="checkbox<?=$user['id'];?>" type="checkbox">
                            <label for="checkbox<?=$user['id'];?>">
                                <?=$user['fullname'];?>
                            </label>
                        </div>

                    <?endforeach;?>

                    <?foreach($event['ClubGroup'] as $clubGroup):?>

                        <?foreach($clubGroup['User'] as $user):?>

                            <div class="checkbox checkbox-success">
                                <input id="checkbox<?=$user['id'];?>" type="checkbox">
                                <label for="checkbox<?=$user['id'];?>">
                                    <?=$user['fullname'];?>
                                </label>
                            </div>



                        <? endforeach;?>
                    <?endforeach;?>
                        </fieldset>
                </div>
            </div>
        <?endforeach;?>
        <?endif;?>
    </div>
</div>


<?=$this->element('Scripts');?>
<script src="/js/plugins/validate/jquery.validate.min.js"></script>

<script>
    $(document).ready(function(){
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

</script>

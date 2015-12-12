<div id="PageContent" class="wrapper wrapper-content animated fadeInRight">

    <?=debug($clubGroups);?>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox-content" style="margin-bottom: 20px;">
                    <form role="form" class="form-inline" action="#">
                        <div class="form-group">
                            <label for="exampleInputEmail2" class="sr-only"><?=__("Group name");?></label>
                            <input placeholder="<?=__("Group name");?>" id="clubGroupName" class="form-control">
                        </div>
                        <button class="btn btn-sm btn-primary" onclick="addClubGroup()" type="submit"><?=__("Create");?></button>
                    </form>
            </div>
        </div>
    </div>
   
    <div class="grid">
        <div id="ClubGroup-All" class="grid-item">
            <div id="AllMembersPanel" class="ibox">
                <div class="ibox-title">
                    <h5><?=__("All members");?></h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>

                    </div>
                </div>
                <div class="ibox-content">
                    <ul id="sortable1" class="connectedSortable" style="min-height: 10px;padding:0;">
                      <li id="club-group-user-id-0" class="dd-item">
                          <div class="dd-handle">  Item 1</div>
                      </li>
                      <li id="club-group-user-id-0"class="dd-item"><div class="dd-handle">  Item 1</div></li>
                      <li id="club-group-user-id-0"class="ui-state- dd-item"><div class="dd-handle">  Item 1</div></li>
                      <li id="club-group-user-id-0"class="ui-state- dd-item"><div class="dd-handle">  Item 1</div></li>
                      <li id="club-group-user-id-0"class="ui-state- dd-item"><div class="dd-handle">  Item 1</div></li>
                    </ul>
                </div>
            </div>
        </div>

        <? if($clubGroups):?>
            <? foreach($clubGroups as $clubGroup):?>
                <div id="ClubGroup-<?=$clubGroup['ClubGroup']['id'];?>" class="grid-item">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5><?=$clubGroup['ClubGroup']['name'];?></h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a data-toggle="modal" data-target="#deleteClubGroupModal" data-club-group-id="<?=$clubGroup['ClubGroup']['id'];?>">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">

                            <ul id="sortable3" class="connectedSortable" style="padding:0;min-height:20px;">
                                <?foreach($clubGroup['User'] as $user):?>
                                    <li id="club-group-user-id-<?=$user['id'];?>" class=" dd-item">
                                        <div id="0" class="dd-handle"><?=$user['name'].' '.$user['surname'];?></div>
                                    </li>
                                <?endforeach;?>

                            </ul>
                        </div>
                    </div>
                </div>
            <?endforeach;?>
        <?endif;?>
    </div>
   
</div>
 <style>

        .grid .ibox {
            margin-bottom: 0;
        }

        .grid-item {
            margin-bottom: 25px;
            width: 300px;
        }
    </style>


<!-- Modal -->
<div class="modal fade" id="deleteClubGroupModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center"><?=__("Delete group?");?></h4>
            </div>

            <div class="modal-footer center-block ">
                <div class="text-center">
                    <button type="button" class="btn btn-sm btn-default text-center" data-dismiss="modal"><?=__("Cancel");?></button>
                    <button type="button" id="deleteClubGroupButton" class="btn btn-sm btn-primary text-center" data-dismiss="modal"><?=__('Delete');?></button>
                </div>

            </div>
        </div>
    </div>
</div>

<?=$this->element('Scripts');?>

<script>
    $(document).ready(function(){

        toastr.options = {
            "closeButton": false,
            "debug": false,
            "progressBar": true,
            "preventDuplicates": true,
            "positionClass": "toast-top-center",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "400",
            "timeOut": "1000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        $('.grid').masonry({
            // options
            itemSelector: '.grid-item',
            columnWidth: 300,
            gutter: 25
        });

    });
</script>


<script>
    $(document).ready(function(){

        $("#sortable1, #sortable2, #sortable3").sortable({
            connectWith: ".connectedSortable"
        }).disableSelection();

        $("#sortable1, #sortable2, #sortable3").sortable({
            start:function(event, ui){
                $(ui.item).addClass('dd-item-dragged');
                $('.grid').masonry("layout");
            },
            stop: function( event, ui ) {
                $(ui.item).removeClass('dd-item-dragged');
                var array = $( "#sortable3" ).sortable( "toArray" );
                console.log(array);
                $('.grid').masonry("layout");
            }
        });
        $('.ibox-content').on('hidden.bs.collapse', function(e){
            alert('Fired!');
        });

        $('.ibox-content').on('shown.bs.collapse', function(e){
            alert('Fired!');
        });

        $('#deleteClubGroupModal').on('show.bs.modal', function(e) {

            //get data-id attribute of the clicked element
            var clubGroupId = $(e.relatedTarget).data('club-group-id');

            $('#deleteClubGroupButton').attr('onclick','deleteClubGroup('+clubGroupId+')');
        });


    });

    function addClubGroup(){
        $.ajax({
            url:'/clubs/addClubGroup',
            data:{
                name:$('#clubGroupName').val(),
                club_id:1 // testing, enter from session data!
            },
            type:'POST',
            dataType:'JSON',
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
    }
    function deleteClubGroup(id){

        $.ajax({
            url:'/clubs/deleteClubGroup',
            data:{
                id:id
            },
            type:'POST',
            dataType:'JSON',
            success:function(data){
                if(data.success){
                    toastr.success(data.message);
                    $('#ClubGroup-'+id).remove();
                    $('.grid').masonry("layout");
                } else {
                    toastr.error(data.message);
                }
            },
            error:function(){
                toastr.error("<?=__('We\'re sorry!').' '.__('Something went wrong');?>");
            }
        });
    }

</script>
<div id="PageContent" class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox col-md-4">
        <div class="ibox-title">
            <h5><?=__("Enter current weight");?></h5>
        </div>
        <div class="ibox-content">
            <div class="form-group">
                <div class="input-group m-b" >
                <span class="input-group-btn">
                <button type="button" onclick="addWeight();" class="btn btn-primary"><?=__("Save");?></button> </span>
                <input id="weightInput" type="text" class="form-control text-right"> <span class="input-group-addon">kg</span></div>
               
            </div>
        </div>
    </div>
</div>
<?=$this->element('Scripts');?>
<script>
    function addWeight(){
        $.ajax({
            url:"/users/addWeight",
            data:{
                weight:$('#weightInput').val()
            },
            type:"POST",
            dataType:'JSON',
            success:function(data){
                if(data.success){
                    $('#weightInput').val("");
                    toastr.success(data.message);

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

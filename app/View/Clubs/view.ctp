<div class="row  border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?=$club['Club']['name'];?></h2>

    </div>

</div>
<div class="row">
    <div class="ibox col-md-6">
        <div class="ibox-title">
            <h5><?=__("Club info");?></h5>
            <div class="ibox-tools">
                <a data-toggle="modal" data-target="#editClubInfo"><i class="fa fa-pencil"></i></a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="col-md-4">
                <img alt="image" class="img-responsive" src="/img/profilna.jpg">
            </div>
            <div class="col-md-8">
                <dl class="dl-horizontal">

                    <dt><?=__("Address");?></dt> <dd><?=$club['Club']['address'].', '.$club['Club']['zip_code'].' '.$club['Club']['city'];?></dd>
                    <dt><?=__("E-mail");?></dt> <dd> <a href="mailto:<?=$club['Club']['mail'];?>"><?=$club['Club']['mail'];?></a></dd>
                    <dt><?=__("Phone");?></dt> <dd><a href="tel:<?=$club['Club']['phone'];?>" class="text-navy"><?=$club['Club']['phone'];?></a> </dd>
                    <dt><?=__("OIB");?></dt> <dd><?=$club['Club']['oib'];?></dd>
                </dl>

            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="editClubInfo" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center"><?=__("Edit info");?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="editClubInfoForm" action="/clubs/editInfo">
                    <div class="form-group">
                        <input type="text"
                               name="name"

                               placeholder="<?=__("Name");?>"
                               class="form-control cleanInput"
                               value="<?=$club['Club']['name'];?>"
                        >
                    </div>
                    <div class="form-group">
                        <input type="text"
                               name="oib"
                               placeholder="<?=__("OIB");?>"
                               class="form-control cleanInput"
                               value="<?=$club['Club']['oib'];?>"
                        >
                    </div>
                    <div class="form-group">
                        <input type="text"
                               name="address"
                               placeholder="<?=__("Address");?>"
                               class="form-control cleanInput"
                               value="<?=$club['Club']['address'];?>"
                        >
                    </div>
                    <div class="form-group">
                        <input type="text"
                               name="city"
                               placeholder="<?=__("City");?>"
                               class="form-control cleanInput"
                               value="<?=$club['Club']['city'];?>"
                        >
                    </div>
                    <div class="form-group">
                        <input type="text"
                               name="zip_code"
                               placeholder="<?=__("Zip code");?>"
                               class="form-control cleanInput"
                               value="<?=$club['Club']['zip_code'];?>"
                        >
                    </div>
                    <div class="form-group">
                        <input type="text"
                               name="mail"
                               placeholder="<?=__("Mail");?>"
                               class="form-control cleanInput"
                               value="<?=$club['Club']['mail'];?>"
                        >
                    </div>
                    <div class="form-group">
                        <input type="text"
                               name="phone"
                               placeholder="<?=__("Phone");?>"
                               class="form-control cleanInput"
                               value="<?=$club['Club']['phone'];?>"
                        >
                    </div>


            </div>

            <div class="modal-footer center-block ">
                <div class="text-center">
                    <button type="button" class="btn btn-sm btn-default text-center" data-dismiss="modal"><?=__("Cancel");?></button>
                    <button type="submit" class="btn btn-sm btn-primary text-center" ><?=__('Save');?></button>
                </div>

            </div>
            </form>
        </div>
    </div>
</div>

<?=$this->element('Scripts');?>

<script>

    $(document).ready(function() {

        $.validator.addMethod('check_oib', function(oib, element) {
            oib = oib.toString();
            if (oib.length != 11) return false;

            var b = parseInt(oib, 10);
            if (isNaN(b)) return false;

            var a = 10;
            for (var i = 0; i < 10; i++) {
                a = a + parseInt(oib.substr(i, 1), 10);
                a = a % 10;
                if (a == 0) a = 10;
                a *= 2;
                a = a % 11;
            }
            var kontrolni = 11 - a;
            if (kontrolni == 10) kontrolni = 0;

            return kontrolni == parseInt(oib.substr(10, 1));

        }, "Nepostojeći poštanski broj");

        $('#editClubInfoForm').validate({
            rules: {
                'name': {required: true},
                'address': {required: true},
                'city': {required: true},
                'zip_code': { required : true, minlength: 5,maxlength:5, digits:true },
                'mail': {required: true, email:true },
                'phone': {required: true},
                'oib': {required: true, digits:true, minlength:11,maxlength:11,check_oib:true},

            },
            messages: {
                'name': {required: "<?=__('Please enter field');?>"},
                'address': {required: "<?=__('Please enter field');?>"},
                'city': {required: "<?=__('Please enter field');?>"},
                'zip_code': {required: "<?=__('Please enter field');?>",digits:"<?=__("Please enter valid ZIP code");?>"},
                'mail': {required: "<?=__('Please enter field');?>", email:"<?=__('Please enter valid mail');?>"},
                'phone': {required: "<?=__('Please enter field');?>"},
                'oib': {
                    required: "<?=__('Please enter field');?>",
                    digits:"<?=__('Please enter valid OIB');?>",
                    minlength:"<?=__('Please enter valid OIB');?>",
                    maxlength:"<?=__('Please enter valid OIB');?>",
                    check_oib:"<?=__('Please enter valid OIB');?>",
                }
            },
            submitHandler:function(){
                $.ajax({
                    url:'/clubs/editInfo',
                    data: $("#editClubInfoForm").serialize(),
                    type:'POST',
                    dataType:'JSON',
                    success: function(response){
                        //toastr.success(response.message);
                        $('#editClubInfo').modal('hide');
                        location.reload();
                    },
                    error: function(response){
                        if(response.status = '403'){
                            toastr.error(<?=__("Ooops");?>);
                        } else {toastr.error(<?=__("Ooops");?>);}
                    }
                });
                return false;
            }

        })
    });


</script>


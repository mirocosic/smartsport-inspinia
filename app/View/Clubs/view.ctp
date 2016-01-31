<div class="row  border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>NK Dinamo</h2>

    </div>

</div>
<div class="row">
    <div class="ibox col-md-3">
        <img alt="image" class="img-responsive" src="/img/profilna.jpg">
    </div>

    <div class="ibox col-md-4">
        <div class="ibox-title">
            <h5><?=__("Club info");?></h5>
            <div class="ibox-tools">
                <a data-toggle="modal" data-target="#editClubInfo"><i class="fa fa-pencil"></i></a>
            </div>
        </div>
        <div class="ibox-content">

                <dl class="dl-horizontal">

                    <dt><?=__("Address");?></dt> <dd><?=$club['Club']['address'].', '.$club['Club']['zip_code'].' '.$club['Club']['city'];?></dd>
                    <dt><?=__("E-mail");?></dt> <dd> <a href="mailto:<?=$club['Club']['mail'];?>"><?=$club['Club']['mail'];?></a></dd>
                    <dt><?=__("Phone");?></dt> <dd><a href="tel:<?=$club['Club']['phone'];?>" class="text-navy"><?=$club['Club']['phone'];?></a> </dd>
                    <dt><?=__("OIB");?></dt> <dd><?=$club['Club']['oib'];?></dd>
                </dl>

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
        $('#editClubInfoForm').validate({
            rules: {
                'name': {required: true},
                'address': {required: true},
                'name': {required: true},
                'name': {required: true},
                'name': {required: true},
                'name': {required: true},

            },
            messages: {
                'name': {required: 'Obavezno polje'},
            },
            submitHandler:function(){

                return false;
            }

        })
    });


</script>


<div id="PageContent" class="wrapper wrapper-content animated fadeInRight">

    <div class="ibox">
        <div class="ibox-title">
            <h5><?=__("Settings");?></h5>
        </div>
        <div class="ibox-content">
            <div class="col-md-1"><?=__('Language');?></div>

            <div class="col-md-1">
                <a class="langUrl <?=($this->Session->read('Config.language') == 'hrv')?'selected':''?>"

                   href="<?=$this->Html->url(array('language'=>'hrv'));?>">
                    HR
                </a>
                <a class="langUrl <?=($this->Session->read('Config.language') == 'eng')?'selected':''?>"
                   href="<?=$this->Html->url(array('language'=>'eng'));?>">
                    EN
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<?=$this->element('Scripts');?>
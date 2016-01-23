<div id="PageContent" class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <? debug($fees);?>
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                          <!--  <thead>
                                <tr>
                                    <th>id</th><th>name</th><th>paid</th><th>note</th>
                                </tr>
                            </thead>-->
                           <!-- <tbody>
                                <?/* foreach($fees as $fee):*/?>
                                <tr>
                                   <!-- <td><?=$fee['MembershipFee']['id'];?></td><td><?/*/*=$fee['User']['name'].' '.$fee['User']['surname'];?></td>-->
                                    <td>
                                        <div class="checkbox checkbox-success">
                                            <input id="checkbox#<?/*=$fee['MembershipFee']['id'];*/?>"
                                                   class="feeCheckbox"
                                                   type="checkbox" <?/*if($fee['MembershipFee']['paid']){echo "checked";}*/?>>
                                            <label for="checkbox#<?/*=$fee['MembershipFee']['id'];*/?>">
                                                <?/*=$fee['User']['name'].' '.$fee['User']['surname'];*/?>
                                            </label>
                                        </div>
                                    </td>
                                    <td><?/*=$this->Form->input('note', array('label' => false));*/?></td>
                                </tr>

                            <?/*endforeach;*/?>
                            </tbody>-->
                        </table>
                    </div>



                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th></th>
                                <th>
                                    Extra small devices
                                    <small>Phones (&lt;768px)</small>
                                </th>
                                <th>
                                    Small devices
                                    <small>Tablets (≥768px)</small>
                                </th>
                                <th>
                                    Medium devices
                                    <small>Desktops (≥992px)</small>
                                </th>
                                <th>
                                    Large devices
                                    <small>Desktops (≥1200px)</small>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th>Grid behavior</th>
                                <td>Horizontal at all times</td>
                                <td colspan="3">Collapsed to start, horizontal above breakpoints</td>
                            </tr>
                            <tr>
                                <th>Max container width</th>
                                <td>None (auto)</td>
                                <td>750px</td>
                                <td>970px</td>
                                <td>1170px</td>
                            </tr>
                            <tr>
                                <th>Class prefix</th>
                                <td>
                                    <code>.col-xs-</code>
                                </td>
                                <td>
                                    <code>.col-sm-</code>
                                </td>
                                <td>
                                    <code>.col-md-</code>
                                </td>
                                <td>
                                    <code>.col-lg-</code>
                                </td>
                            </tr>
                            <tr>
                                <th># of columns</th>
                                <td colspan="4">12</td>
                            </tr>
                            <tr>
                                <th>Max column width</th>
                                <td class="text-muted">Auto</td>
                                <td>60px</td>
                                <td>78px</td>
                                <td>95px</td>
                            </tr>
                            <tr>
                                <th>Gutter width</th>
                                <td colspan="4">30px (15px on each side of a column)</td>
                            </tr>
                            <tr>
                                <th>Nestable</th>
                                <td colspan="4">Yes</td>
                            </tr>
                            <tr>
                                <th>Offsets</th>
                                <td colspan="4">Yes</td>
                            </tr>
                            <tr>
                                <th>Column ordering</th>
                                <td colspan="4">Yes</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?=$this->element('Scripts');?>
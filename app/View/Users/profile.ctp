<div id="PageContent" class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">

                    <div class="col-md-3">
                        <img alt="image" class="img-responsive" src="/img/<?=$_SESSION['Auth']['ProfileImg'];?>">
                    </div>

                    <div class="col-md-9">
                        <h4><strong><?=AuthComponent::user('fullname');?></strong></h4>
                        <p><i class="fa fa-map-marker"></i> Riviera State 32/106</p>
                        <h5>
                            About me
                        </h5>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.
                        </p>
                        <div class="row m-t-lg">
                            <div class="col-md-4">
                                <span class="bar" style="display: none;">5,3,9,6,5,9,7,3,5,2</span><svg class="peity" height="16" width="32"><rect fill="#1ab394" x="0" y="7.111111111111111" width="2.3" height="8.88888888888889"></rect><rect fill="#d7d7d7" x="3.3" y="10.666666666666668" width="2.3" height="5.333333333333333"></rect><rect fill="#1ab394" x="6.6" y="0" width="2.3" height="16"></rect><rect fill="#d7d7d7" x="9.899999999999999" y="5.333333333333334" width="2.3" height="10.666666666666666"></rect><rect fill="#1ab394" x="13.2" y="7.111111111111111" width="2.3" height="8.88888888888889"></rect><rect fill="#d7d7d7" x="16.5" y="0" width="2.3" height="16"></rect><rect fill="#1ab394" x="19.799999999999997" y="3.555555555555557" width="2.3" height="12.444444444444443"></rect><rect fill="#d7d7d7" x="23.099999999999998" y="10.666666666666668" width="2.3" height="5.333333333333333"></rect><rect fill="#1ab394" x="26.4" y="7.111111111111111" width="2.3" height="8.88888888888889"></rect><rect fill="#d7d7d7" x="29.7" y="12.444444444444445" width="2.3" height="3.5555555555555554"></rect></svg>
                                <h5><strong>169</strong> Posts</h5>
                            </div>
                            <div class="col-md-4">
                                <span class="line" style="display: none;">5,3,9,6,5,9,7,3,5,2</span><svg class="peity" height="16" width="32"><polygon fill="#1ab394" points="0 15 0 7.166666666666666 3.5555555555555554 10.5 7.111111111111111 0.5 10.666666666666666 5.5 14.222222222222221 7.166666666666666 17.77777777777778 0.5 21.333333333333332 3.833333333333332 24.888888888888886 10.5 28.444444444444443 7.166666666666666 32 12.166666666666666 32 15"></polygon><polyline fill="transparent" points="0 7.166666666666666 3.5555555555555554 10.5 7.111111111111111 0.5 10.666666666666666 5.5 14.222222222222221 7.166666666666666 17.77777777777778 0.5 21.333333333333332 3.833333333333332 24.888888888888886 10.5 28.444444444444443 7.166666666666666 32 12.166666666666666" stroke="#169c81" stroke-width="1" stroke-linecap="square"></polyline></svg>
                                <h5><strong>28</strong> Following</h5>
                            </div>
                            <div class="col-md-4">
                                <span class="bar" style="display: none;">5,3,2,-1,-3,-2,2,3,5,2</span><svg class="peity" height="16" width="32"><rect fill="#1ab394" x="0" y="0" width="2.3" height="10"></rect><rect fill="#d7d7d7" x="3.3" y="4" width="2.3" height="6"></rect><rect fill="#1ab394" x="6.6" y="6" width="2.3" height="4"></rect><rect fill="#d7d7d7" x="9.899999999999999" y="10" width="2.3" height="2"></rect><rect fill="#1ab394" x="13.2" y="10" width="2.3" height="6"></rect><rect fill="#d7d7d7" x="16.5" y="10" width="2.3" height="4"></rect><rect fill="#1ab394" x="19.799999999999997" y="6" width="2.3" height="4"></rect><rect fill="#d7d7d7" x="23.099999999999998" y="4" width="2.3" height="6"></rect><rect fill="#1ab394" x="26.4" y="0" width="2.3" height="10"></rect><rect fill="#d7d7d7" x="29.7" y="6" width="2.3" height="4"></rect></svg>
                                <h5><strong>240</strong> Followers</h5>
                            </div>
                        </div>
                        <div class="user-button">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary btn-sm btn-block"><i class="fa fa-envelope"></i> Send Message</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default btn-sm btn-block"><i class="fa fa-coffee"></i> Buy a coffee</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
            </div>

        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox">
            <div class="ibox-title">
                <h5><?=__("History");?></h5>
            </div>
            <div class="ibox-content">
                <div id="vertical-timeline" class="vertical-container center-orientation dark-timeline">
                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon navy-bg">
                            <i class="fa fa-briefcase"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <h2>Meeting</h2>
                            <p>Conference on the sales results for the previous year. Monica please examine sales trends in marketing and products. Below please find the current status of the sale.
                            </p>
                            <a href="#" class="btn btn-sm btn-primary"> More info</a>
                                    <span class="vertical-date">
                                        Today <br>
                                        <small>Dec 24</small>
                                    </span>
                        </div>
                    </div>

                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon blue-bg">
                            <i class="fa fa-file-text"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <h2>Send documents to Mike</h2>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since.</p>
                            <a href="#" class="btn btn-sm btn-success"> Download document </a>
                                    <span class="vertical-date">
                                        Today <br>
                                        <small>Dec 24</small>
                                    </span>
                        </div>
                    </div>

                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon lazur-bg">
                            <i class="fa fa-coffee"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <h2>Coffee Break</h2>
                            <p>Go to shop and find some products. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's. </p>
                            <a href="#" class="btn btn-sm btn-info">Read more</a>
                            <span class="vertical-date"> Yesterday <br><small>Dec 23</small></span>
                        </div>
                    </div>

                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon yellow-bg">
                            <i class="fa fa-phone"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <h2>Phone with Jeronimo</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut.</p>
                            <span class="vertical-date">Yesterday <br><small>Dec 23</small></span>
                        </div>
                    </div>

                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon lazur-bg">
                            <i class="fa fa-user-md"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <h2>Go to the doctor dr Smith</h2>
                            <p>Find some issue and go to doctor. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. </p>
                            <span class="vertical-date">Yesterday <br><small>Dec 23</small></span>
                        </div>
                    </div>

                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon navy-bg">
                            <i class="fa fa-comments"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <h2>Chat with Monica and Sandra</h2>
                            <p>Web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like). </p>
                            <span class="vertical-date">Yesterday <br><small>Dec 23</small></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-6">
        <div class="ibox float-e-margins ">
            <div class="ibox-title">
                <h5><?=__("Weight chart");?></h5>
                <!--<button class="btn btn-sm btn-primary" onclick="loadData()")>Load</button> -->
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="" onclick="loadData()">
                        <i class="fa fa-refresh"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#">Refresh</a>
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
                <div id="UserWeightChart"></div>

            </div>
        </div>
    </div>
    </div>
</div>

<?=$this->element('Scripts');?>
<script src="/js/plugins/morris/morris.js"></script>
<script src="/js/plugins/morris/raphael-2.1.0.min.js"></script>

<script>

    function loadData(){
        $.ajax({
            url:'/users/getWeightData',
            data:{
                user_id:8

            },
            type:'POST',
            dataType:'JSON',
            success: function(data){

                userWeightChart.setData(data);
            },
            failure: function(){}
        });
    }

    var userWeightChart = new Morris.Line({
        // ID of the element in which to draw the chart.
        element: 'UserWeightChart',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: [
            { date: '2008', weight: 27.5 },
            { date: '2009', weight: 10 },
            { date: '2010', weight: 5 },
            { date: '2011', weight: 5 },
            { date: '2012', weight: 20 }
        ],
        // The name of the data record attribute that contains x-values.
        xkey: 'date',
        // A list of names of data record attributes that contain y-values.
        ykeys: ['weight'],
        // Labels for the ykeys -- will be displayed when you hover over the
        // chart.
        labels: ['weight'],
        lineColors:['#1ab394'],
        ymax:'auto',
        ymin:'auto',
        yLabelFormat: function(y){

            return Math.round(parseFloat(y) * 10) / 10;
        }
    });
</script>

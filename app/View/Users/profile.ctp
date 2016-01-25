<div id="PageContent" class="wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>User weight chart </h5>
                <button class="btn btn-sm btn-primary" onclick="loadData()")>Load</button>
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
                <div id="UserWeightChart"></div>

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

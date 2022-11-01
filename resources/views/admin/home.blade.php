@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ __('Dashboard') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{__('Home')}}</a></li>
              <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
     <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
          <div class="row">
              @if(Auth::user()->systemAdmin())
              <div class="col-md-4 col-sm-6 col-12">
                  <div class="info-box">
                      <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                      <div class="info-box-content">
                          <span class="info-box-text" style="font-size: 18px" style="font-size: 18px">{{__('Customers')}}</span>
                          <span class="info-box-text" style="font-size: 15px">{{$customers}}</span>
                      </div>

                  </div>
              </div>
              <div class="col-md-4 col-sm-6 col-12">
                  <div class="info-box">
                      <span class="info-box-icon bg-info"><i class="fas fa-user-tie"></i></span>
                      <div class="info-box-content">
                          <span class="info-box-text" style="font-size: 18px">{{__('Providers')}}</span>
                          <span class="info-box-text" style="font-size: 15px">{{$admins}}</span>
                      </div>

                  </div>
              </div>
              <div class="col-md-4 col-sm-6 col-12">
                  <div class="info-box">
                      <span class="info-box-icon bg-info"><i class="fas fa-city"></i></span>
                      <div class="info-box-content">
                          <span class="info-box-text" style="font-size: 18px">{{__('Cities')}}</span>
                          <span class="info-box-text" style="font-size: 15px">{{$cities}}</span>
                      </div>
                  </div>
              </div>
              <div class="col-md-4 col-sm-6 col-12">
                  <div class="info-box">
                      <span class="info-box-icon bg-info"><i class="fas fa-list"></i></span>
                      <div class="info-box-content">
                          <span class="info-box-text" style="font-size: 18px">{{__('Categories')}}</span>
                          <span class="info-box-text" style="font-size: 15px" >{{$categories}}</span>
                      </div>

                  </div>
              </div>
              <div class="col-md-4 col-sm-6 col-12">
                  <div class="info-box">
                      <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                      <div class="info-box-content">
                          <span class="info-box-text" style="font-size: 18px">{{__('Items')}}</span>
                          <span class="info-box-text" style="font-size: 15px" >{{$items}}</span>
                      </div>

                  </div>
              </div>
              <div class="col-md-4 col-sm-6 col-12">
                      <div class="info-box">
                          <span class="info-box-icon bg-info"><i class="fab fa-adversal"></i></span>
                          <div class="info-box-content">
                              <span class="info-box-text" style="font-size: 18px">{{__('Ads')}}</span>
                              <span class="info-box-text" style="font-size: 15px" >{{$ads}}</span>
                          </div>

                      </div>
                  </div>

              @else
                  <div class="col-md-4 col-sm-6 col-12">
                      <div class="info-box">
                          <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                          <div class="info-box-content">
                              <span class="info-box-text" style="font-size: 18px">{{__('Items')}}</span>
                              <span class="info-box-text" style="font-size: 15px" >{{$items}}</span>
                          </div>

                      </div>
                  </div>

                  <div class="col-md-4 col-sm-6 col-12">
                      <div class="info-box">
                          <span class="info-box-icon bg-info"><i class="fab fa-adversal"></i></span>
                          <div class="info-box-content">
                              <span class="info-box-text" style="font-size: 18px">{{__('Ads')}}</span>
                              <span class="info-box-text" style="font-size: 15px" >{{$ads}}</span>
                          </div>

                      </div>
                  </div>

                  <div class="col-md-4 col-sm-6 col-12">
                  <div class="info-box">
                      <span class="info-box-icon bg-info"><i class="fas fa-reply"></i></span>
                      <div class="info-box-content">
                          <span class="info-box-text" style="font-size: 18px">{{__('Reviews')}}</span>
                          <span class="info-box-text" style="font-size: 15px" >{{$reviews}}</span>
                      </div>

                  </div>
              </div>
              @endif
          </div>
          <div class="row">
              <div class="col-md-12">
              <div class="card card-info">
              <div class="card-header">
                  <h3 class="card-title" style="float: right;">{{__('Items By City')}}</h3>
{{--                  <div class="card-tools">--}}
{{--                      <button type="button" class="btn btn-tool" data-card-widget="collapse">--}}
{{--                          <i class="fas fa-minus"></i>--}}
{{--                      </button>--}}
{{--                      <button type="button" class="btn btn-tool" data-card-widget="remove">--}}
{{--                          <i class="fas fa-times"></i>--}}
{{--                      </button>--}}
{{--                  </div>--}}
              </div>
              <div class="card-body">
                  <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                      <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 487px;" width="487" height="250" class="chartjs-render-monitor"></canvas>
                  </div>
              </div>
              </div>
              </div>
          </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
@endsection

@section('js-body')
<script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset('plugins/chart.js/Chart.min.js')}}"></script>
<script src="{{ asset('dist/js/adminlte.min.js')}}"></script>
<script src="{{ asset('dist/js/demo.js')}}"></script>

<script>
counterAnimation();
function counterAnimation() {
  $('.count').each(function () {
      $(this).prop('Counter',0).animate({
          Counter: $(this).text()
      }, {
          duration: 400,
          easing: 'swing',
          step: function (now) {
              $(this).text(Math.ceil(now));
          }
      });
  });
}
</script>
<script>
    $(function () {
        /* ChartJS
         * -------
         * Here we will create a few charts using ChartJS
         */
        var cities = new Array();
        <?php foreach($cities_names as $key => $val){ ?>
        cities.push('<?php echo $val; ?>');
        <?php } ?>

        var cities_items_cnt = new Array();
        <?php foreach($items_count_by_city as $key => $val){ ?>
        cities_items_cnt.push('<?php echo $val; ?>');
        <?php } ?>
        var areaChartData = {
            labels  : cities,
            datasets: [
                {
                    label               : '{{__('Items')}}',
                    backgroundColor     : 'rgba(60,141,188,0.9)',
                    borderColor         : 'rgba(60,141,188,0.8)',
                    pointRadius          : false,
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(60,141,188,1)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data                : cities_items_cnt
                },
            ]
        }

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
       // var temp1 = areaChartData.datasets[1]
       // barChartData.datasets[0] = temp1
        barChartData.datasets[0] = temp0

        var barChartOptions = {
            responsive              : true,
            maintainAspectRatio     : false,
            datasetFill             : false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
                }
        }

        new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        })

    })
</script>

@endsection

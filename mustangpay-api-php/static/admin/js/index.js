(function($){
    //
    var option = {
        tooltip : {
            trigger: 'item',
            formatter : function (params) {
                var date = new Date(params.value[0]);
                data = date.getFullYear() + '-'
                    + (date.getMonth() + 1) + '-'
                    + date.getDate() + ' '
                    + date.getHours() + ':'
                    + date.getMinutes();
                return data + '<br/>'
                    + params.value[1] + ', '
                    + params.value[2];
            }
        },
        dataZoom: {
            show: true,
            start : 50
        },
        grid: {
            top : '20px',
            y2: 80
        },
        xAxis : [
            {
                type : 'time',
                splitNumber:10
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name: 'series1',
                type: 'line',
                showAllSymbol: true,
                symbolSize: function (value){
                    return Math.round(value[2]/10) + 2;
                },
                itemStyle : {
                  normal : {
                      color : '#4488BB',
                      lineStyle : {
                          width : 1,
                          type : 'solid'
                      }
                  }
                },
                data: (function () {
                    var d = [];
                    var len = 0;
                    var now = new Date();
                    var value;
                    while (len++ < 100) {
                        d.push([
                            new Date(2015, 9, 1, 0, len * 10000),
                            (Math.random()*30).toFixed(2) - 0,
                            (Math.random()*100).toFixed(2) - 0
                        ]);
                    }
                    return d;
                })()
            }
        ]
    };

    $(function(){
        var _eChart = echarts.init(document.getElementById('eChart'));
        _eChart.setOption(option);
    });
})(jQuery);
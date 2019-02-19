var chart = {
	column: function(title, y_title, container, id){
		var data = eval('('+$('#'+id).val()+')');
		//console.info(data.series);
		$('#'+container).highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: title
	        },
	        subtitle: {
		        text: data.sub_title
		    },
	        xAxis: {
	            categories: data.categories
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: y_title
	            }
	        },
	        tooltip: {
	            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	            '<td style="padding:0"><b>{point.y}</b></td></tr>',
	            footerFormat: '</table>',
	            shared: true,
	            useHTML: true
	        },
	        plotOptions: {
	            column: {
	                pointPadding: 0.2,
	                borderWidth: 0
	            }
	        },
	        series: data.series
	    });
	},
	singleColumn: function(title, y_title, container, id){
		var data = eval('('+$('#'+id).val()+')');
		//console.info(data.series);
		$('#'+container).highcharts({
	        chart: {
	            type: 'column'
	        },
	        title: {
	            text: title
	        },
	        subtitle: {
		        text: data.sub_title
		    },
	        xAxis: {
	            categories: data.categories
	        },
	        yAxis: {
	            min: 0,
	            title: {
	                text: y_title
	            }
	        },
	        tooltip: {
	            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
	            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
	            '<td style="padding:0"><b>{point.y}</b></td></tr>',
	            footerFormat: '</table>',
	            shared: true,
	            useHTML: true
	        },
	        plotOptions: {
	            column: {
	                pointPadding: 0.2,
	                borderWidth: 0,
	                dataLabels:{
	                	enabled: true
	                }
	            }
	        },
	        series: data.series
	    });
	},
	mixline: function(title, y_title, container, id){
		var data = eval('('+$('#'+id).val()+')');
		$('#'+container).highcharts({
			chart: {
				zoomType: 'x'
			},
	        title: {
		        text: title,
		        x: -20
		    },
		    subtitle: {
		        text: data.sub_title,
		        x: -20
		    },
		    xAxis: {
		        categories: data.categories
		    },
		    yAxis: {
		        title: {
		            text: y_title
		        }
		    },
	        tooltip: {
	            shared: true
	        },
		    legend: {
		        layout: 'vertical',
		        align: 'right',
		        verticalAlign: 'middle',
		        borderWidth: 0
		    },
		    series: data.series
	    });
	},
	pie: function(title, container, id){
		var data = eval('('+$('#'+id).val()+')');
		$('#'+container).highcharts({
			chart: {
		        plotBackgroundColor: null,
		        plotBorderWidth: null,
		        plotShadow: false,
		        type: 'pie'
		    },
		    title: {
		        text: title
		    },
		    tooltip: {
		        pointFormat: '{series.name} {point.y}: <b>占{point.percentage:.1f}%</b>'
		    },
		    plotOptions: {
		        pie: {
		            allowPointSelect: true,
		            cursor: 'pointer',
		            dataLabels: {
		            	enabled: false
		                /*enabled: true,
		                format: '<b>{point.name}</b><br>{point.percentage:.1f} %',
		                distance: -50,
		                filter: {
		                    property: 'percentage',
		                    operator: '>',
		                    value: 4
		                }*/
		            },
		            showInLegend: true
		        }
		    },
		    series: [{
		        name: '数量',
		        colorByPoint: true,
		        data: data
		    }]
	    });
	}
} 
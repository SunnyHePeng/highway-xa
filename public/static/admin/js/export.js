$('.export-file').on('click', function(){
	var type = $(this).attr('data-type');
	var obj = $(this).attr('data-obj');

	var filename = $(this).attr('data-name') +'-'+ getFilename();
	if(type == 'dy'){
		$('#'+obj).jqprint({importCSS: true, printContainer: true});
	}else if(type == "excel"){
		$('#'+obj).tableExport({fileName: filename, 
								type: type, 
								worksheetName: ['table 1','table 2', 'table 3']
							});
	}else if(type == "pdf"){
		$('#'+obj).tableExport({fileName: filename, 
								type: type, 
								jspdf: {format: 'bestfit',
                               			autotable: {tableWidth: 'wrap'}
                               		   },
								pdfmake: {enabled: true}
							});
	}else{
		$('#'+obj).tableExport({fileName: filename, 
								type: type, 
								escape: 'false', 
								htmlContent: 'true'
							});
	}
});

function getFilename(){
	var d = new Date(); 
	var curYear = d.getFullYear(); 
	var curMonth = "" + (d.getMonth() + 1);
    var curDate = "" + d.getDate();
    if (curMonth.length == 1) {
        curMonth = "0" + curMonth;
    }
    if (curDate.length == 1) {
        curDate = "0" + curDate;
    }
    var fileName = curYear + curMonth + curDate;
    return fileName;
}
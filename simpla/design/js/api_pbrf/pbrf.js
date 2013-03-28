$(document).ready(function() {
	$("#print_blank").click(function(){
		$("#modal_window").removeClass("hidden");
		$(".opaco").removeClass("hidden");
	})

	$(".opaco").click(function(){
		$("#modal_window").addClass("hidden");
		$(".opaco").addClass("hidden");
	})
	$(".close").click(function(){
		$("#modal_window").addClass("hidden");
		$(".opaco").addClass("hidden");
	})

	var domen = "http://pbrf.ru/";

	var pdf_f7 = $("#pdf_f7").get();
	var pdf_f112 = $("#pdf_f112").get();
	$(pdf_f7).click(function(){
		//если ссылка еще не была получена
		if($(pdf_f7).attr("rel") == "false"){
			var data = {};
			data = {
				surname : $("#surname").val(),
				name : $("#name_client").val(),
				patronomic : $("#patronomic").val(),
				country : $("#country").val(),
				region : $("#region").val(),
				city : $("#city").val(),
				street : $("#street").val(),
				build : $("#build").val(),
				appartment : $("#appartment").val(),
				zip : $("#zip").val(),
				declared_value : $("#declared_value").val(),
				cod_amount : $("#cod_amount").val()
			};

			pdf_click(pdf_f7, "F7", data);
		}else{

		}
	})
	$(pdf_f112).click(function(){
		//если ссылка еще не была получена
		if($(pdf_f112).attr("rel") == "false"){
			var data = {};
			data = {
				surname : $("#surname").val(),
				name : $("#name_client").val(),
				patronomic : $("#patronomic").val(),
				country : $("#country").val(),
				region : $("#region").val(),
				city : $("#city").val(),
				street : $("#street").val(),
				build : $("#build").val(),
				appartment : $("#appartment").val(),
				zip : $("#zip").val(),
				sum_num : $("#sum_num").val()
			};

			pdf_click(pdf_f112, "F112", data);
		}else{

		}
	})

	function pdf_click(obj, blank, arr){
		var type = "pdf";
		var id_order = $("#print_blank").attr("rel");
		$(obj).find("img").attr("src", "/design/work/images/loader.gif");
		$.post("/simpla/index.php?module=PBRFAdmin", {arr: arr, type: type, blank: blank, id_order: id_order}, function(data){
			
			if(data.error != 0){
				$(obj).find("img").attr("src", "design/images/pdf.png");
				alert("Error: " + data.message);
			}else{
				$(obj).find("img").attr("src", "design/images/put.png");
				$(obj).find("span").html("Ссылка для скачивания готова");
				$(obj).attr("rel", "true");
				var parent = $(obj).closest("a");
				var html = "<a href='" + data.url + "' target='_blank' style='padding:0'>" + $(parent).html() + "</a>";
				$(parent).attr("href", data.url);
				$(parent).attr("target", "_blank");
			}
		}, "json");
	}
})
<script type='text/javascript'>
 $(function(){

	 /* 商品リストダイアログ */
 	 $("#goods_list_dialog").dialog({
 	             buttons: [{
 	                 text: "OK",
 	                 id:"ConfirmGoodsListBotton",
 	                 click: function () {
 	                     var current_line_no = <?php echo $current_line_no ?>;
 	                     //var id = $('#goods_list').jqGrid('getGridParam','selrow');

                         var selrows = jQuery('#goods_list').getGridParam('selarrrow');

	                     if (selrows.length > 0){
		                    for (var i = 0; i < selrows.length; i++){
		                       if(i > 0){
		                          copyRow();
		                          current_line_no++;
		                       }
		                       updateGoodsDetail(selrows[i],current_line_no);
		                    }
	                     }
  		                 $("#goods_list_dialog").dialog('close');
 	                 }
 	             },
 	             {
 	                 text: "CANCEL",
 	                 click: function () {
 	                     $("#goods_list_dialog").dialog('close');
 	                 }
 	             }],
 	             beforeClose:function(){
 	                $("#goods_list_dialog").remove();
 	             },
 	             draggable: false,
 	             autoOpen: true,
 	             resizable: false,
 	             zIndex: 2000,
 	             width:740,
 	             height:500,
 	             position:[($(window).width() / 2) -  (740 / 2) ,($(window).height() / 2) -  (500 / 2)],
 	             modal: true,
 	             title: "商品リスト"
 	 });

	 /*  商品選択用テーブル
      --------------------------------------------------------*/
      jQuery("#goods_list").jqGrid({
          url: <?php echo "'".$html->url('feed').'/goods_list/'.$goods_kbn_id."'" ?>,
          datatype: 'json',
          mtype: 'POST',
          colNames: ['ダミー','商品コード', '商品名','Rev','価格','原価','ベンダー名'],
          colModel: [
                     { name: 'Dummy'          , index: 'Dummy'     , width: 80},
                     { name: 'GoodsCode'      , index: 'GoodsCode' , width: 80 },
                     { name: 'GoodsName'      , index: 'GoodsName' , width: 350},
                     { name: 'Revision'       , index: 'Revision'  , width: 50 , align:'center'},
                     { name: 'Price'          , index: 'Price'     , width: 80 , align:'right'},
                     { name: 'Cost'           , index: 'Cost'      , width: 80 , align:'right'},
                     { name: 'Vendor'         , index: 'Vendor'    , width: 180               }
                    ],
          onSelectRow: function (id) {
              if (id !== null) {  $("#ConfirmGoodsListBotton").attr('disabled',false);  }
          },
          loadComplete: function (data) {
          },
          loadError: function(xhr, status, error) {
              alert("データの読み込みに失敗しました。 "+error);
          },
          pager: $('#goods_list_pager'),
          viewrecords: true,
          rowNum: 20,
          rowList: [10, 20, 30, 40, 50],
          loadonce: true,
          multiselect: true,
          /* 履歴表示をやめて、最新改定のみ表示
          grouping:true,
   	      groupingView : {
   		      groupField : ['Dummy'],
   		      groupColumnShow : [false],
   		      groupCollapse : true
   	      },*/
          emptyrecords: "NO RECORDS HERE",
          viewrecords: true,
          imgpath: 'themes/basic/images',
          width:'755',
          height: '325',
          //rownumbers: true
      });
      //最新改定のみ表示するため、Dummy列を非表示にする
      $('#goods_list').jqGrid('hideCol', 'Dummy');

      $("#goods_list")
      .navGrid('#goods_list_pager',{refresh:false,edit:false,add:false,del:false,search:false})
      .jqGrid(
    		    'navButtonAdd',
    		    '#goods_list_pager',
    		    {
    		        caption: "商品追加",
    		        buttonicon: "ui-icon-plus",
    		        onClickButton: function () {

    		    	 $(this).simpleLoading('show');
    		    	 $.get(<?php echo "'".$html->url('GoodsAdditionForm')."/".$goods_ctg_id."/".$goods_kbn_id."/".$current_line_no."'" ?>,function(html){
    		    		 $(this).simpleLoading('hide');
         		        $("#goods_addition_dialog_wrapper").html(html);
           		    });
    		        }
    		    }
       );

      //商品選択ボタン
      $("#ConfirmGoodsListBotton").attr('disabled',true);
 });
</script>

<div id="goods_list_dialog">
 <table id="goods_list"        ></table>
 <div   id="goods_list_pager"  style="text-align:center;"></div>
 <div id="goods_addition_dialog_wrapper"></div>
</div>




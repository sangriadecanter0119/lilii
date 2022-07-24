<?php
set_time_limit(600);

//====================================================+
// File name   :
// Begin       :
// Last Update :
// Description :
// Author      :
//
// 参考URL      : http://www.monzen.org/Refdoc/tcpdf/
//====================================================+

/**
 *  @since
 *  @author
 *
 */

App::import( 'Vendor', 'TCPDF', array('file'=>'phpexcel' . DS . 'PHPExcel' . DS . 'Shared'. DS . 'PDF'. DS . 'tcpdf.php') );

/**
 * TCPDF インスタンス化
 * 引数１：用紙方向(L=横, P=縦)
 * 引数２：単位(mm, cm, pt=ポイント, in=インチ)
 * 引数３：用紙の大きさ(An, Bn...)
 */
$obj = new TCPDF('P', 'mm', 'A4');
//デフォルトだと空のページヘッダーが線となって表示されるので消す
$obj->setPrintHeader( false );
$obj->setPrintFooter( false );
//set auto page breaks
//$obj->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);

/**
 * 日本語フォントを指定
 * 引数１：フォント名
 * 引数２：スタイル(空文字=標準, B=太字, I=斜体, U=下線付, D=取消線付)
 * 引数３：サイズ
 */
$obj->SetFont('arialunicid0-japanese', '', 8);

//書き出し対象のページを準備
$obj->AddPage();

$groom_nm="";
$bride_nm="";
if($estimate_header[0]['EstimateTrnView']['grmls_kj'].$estimate_header[0]['EstimateTrnView']['grmfs_kj'] != null){
	$groom_nm = $estimate_header[0]['EstimateTrnView']['grmls_kj'].$estimate_header[0]['EstimateTrnView']['grmfs_kj'].'様  ';
}
if($estimate_header[0]['EstimateTrnView']['brdls_kj'].$estimate_header[0]['EstimateTrnView']['brdfs_kj'] != null){
	$bride_nm = $estimate_header[0]['EstimateTrnView']['brdls_kj'].$estimate_header[0]['EstimateTrnView']['brdfs_kj'].'様  ';
}

$tax_rate = $estimate_header[0]['EstimateTrnView']['hawaii_tax_rate'] * 100;
$tax      = round($estimate_header[0]['EstimateTrnView']['yen_tax']);
$service  = round($estimate_header[0]['EstimateTrnView']['service_yen_fee']);
$service_rate  = $estimate_header[0]['EstimateTrnView']['service_rate'] * 100;
$subtotal = $estimate_header[0]['EstimateTrnView']['yen'];
$discountA = round($estimate_header[0]['EstimateTrnView']['discount_yen']);
$discount_rate = $estimate_header[0]['EstimateTrnView']['discount_rate'] * 100;
$discountB = $estimate_header[0]['EstimateTrnView']['discount'];
$total    = $estimate_header[0]['EstimateTrnView']['total_yen'];
$is_discounted = ($discountA <= 0 && $discountB <= 0) ? false : true;

$opt1    = $estimate_header[0]['EstimateTrnView']['additional_goods_nm1'];
$opt_p1  = $estimate_header[0]['EstimateTrnView']['additional_goods_price1'];
$opt2    = $estimate_header[0]['EstimateTrnView']['additional_goods_nm2'];
$opt_p2  = $estimate_header[0]['EstimateTrnView']['additional_goods_price2'];
$opt3    = $estimate_header[0]['EstimateTrnView']['additional_goods_nm3'];
$opt_p3  = $estimate_header[0]['EstimateTrnView']['additional_goods_price3'];


if(empty($estimate_header[0]['EstimateTrnView']['upd_dt'])){
$d = date('Y年m月d日', strtotime($estimate_header[0]['EstimateTrnView']['reg_dt']));
}else{
$d = date('Y年m月d日', strtotime($estimate_header[0]['EstimateTrnView']['upd_dt']));
}

//ステータスが仮約定以前の場合は挙式予定日を、それ以外は挙式日を表示する
if($customer['CustomerMstView']['status_id'] < CS_CONTRACTING){
  $wedding_dt = $common->evalSpaceForShortDateKanji($customer['CustomerMstView']['wedding_planned_dt']);
}else{
  $wedding_dt= $common->evalSpaceForShortDateKanji($customer['CustomerMstView']['wedding_dt']);
}

if($customer['CustomerMstView']['status_id'] < CS_CONTRACTING){
  $wedding_place= $customer['CustomerMstView']['wedding_planned_place'];
}else{
  $wedding_place= $customer['CustomerMstView']['wedding_place'];
}

if($customer['CustomerMstView']['status_id'] < CS_CONTRACTING){
  $wedding_time= $common->evalSpaceForTimeKanji($customer['CustomerMstView']['wedding_planned_time']);
}else{
  $wedding_time= $common->evalSpaceForTimeKanji($customer['CustomerMstView']['wedding_time']);
}

$html = '<style>.border{ border:1px solid black;}</style>';

$html .= '<div><table border="0">
                 <tr>
                     <td align="left" style="border-bottom:3px solid black"><img src="./images/title_wd.png" border="0" width="80px" height="34px" /></td>
                 </tr>  
                 <tr>
                     <td style="border-bottom:1px solid black;height:1px">&nbsp;</td>
                 </tr>               
              </table>
              <br />
              <table border="0">
                  <tr>
                     <td>&nbsp;</td>
                     <td align="right">' .$d.'</td>
                  </tr>
                  <tr>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                  </tr>
                   <tr>
                     <td>&nbsp;</td>
                     <td align="right">株式会社ミケランジェロ</td>
                  </tr>
                  <tr>
                     <td align="left">
                       <span style="text-decoration:underline">
                          <font size="12">'.$groom_nm.'</font>&nbsp;&nbsp;
                          <font size="12">'.$bride_nm.'</font>
                       </span>
                     </td>
                     <td align="right">ホワイトドア</td>
                  </tr>
                  <tr>
                     <td align="left">&nbsp;</td>
                     <td align="right">TEL：03-5363-2442</td>
                  </tr>
                  <tr>
                     <td align="left">&nbsp;</td>
                     <td align="right">FAX：03-5363-1416</td>
                  </tr>
                  <tr>
                      <td colspan="2" align="center"><font size="14"><strong>お見積書</strong></font></td>
                  </tr>
                  <tr>
                      <td colspan="2" align="center">&nbsp;</td>
                  </tr>
                  <tr>
                     <td colspan="2" align="left">このたびはホワイトドアをご利用いただき誠にありがとうございます。</td>
                  </tr>
                   <tr>
                     <td colspan="2" align="left">ご依頼のございましたお見積もりにつきまして、下記のとおりご案内申し上げます。</td>
                  </tr>
                  <tr>
                      <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>';
                  
                       if($is_discounted){
                    	 $html .='<td colspan="2" align="left"><font size="14" style="text-decoration:underline">お見積金額：￥'.number_format((($subtotal + $tax + $service + $opt_p1 + $opt_p2 + $opt_p3)-$discountA-$discountB)- $credit_amount).'  (税込)</font></td>';
                       }else{
	                     $html .='<td colspan="2" align="left"><font size="14" style="text-decoration:underline">お見積金額：￥'.number_format(($subtotal + $tax + $service + $opt_p1 + $opt_p2 + $opt_p3) - $credit_amount).'  (税込)</font></td>';
                       }
                 $html .='</tr>';
      
                 $html.='<tr><td colspan="2">&nbsp;</td></tr>
                         <tr><td colspan="2" align="left">挙式日時：'.$wedding_dt.' '.$wedding_time.'</td></tr>
                         <tr><td colspan="2" align="left">挙式場名：'.$wedding_place.'</td></tr>';

$html.= '</table></div>';

$html.= '<div><table border="0" cellspacing="0" cellpadding="2">
	       <tr align="center">
	        <th width="100" class="border">項目</th>
		    <th width="310" class="border">内容</th>
		    <th width="50"  class="border">単価</th>
		    <th width="25"  class="border">数量</th>
		    <th width="55"  class="border">金額</th>
	      </tr>';

    /* 同じ商品コードは１つの注文に束ねる  */
    for($main=0;$main < count($estimate_dtl);$main++)
    {
    	for($sub=$main+1;$sub < count($estimate_dtl);$sub++)
        {
        	//自分自身以外の見積
        	if($estimate_dtl[$main]['EstimateDtlTrnView']['id'] != $estimate_dtl[$sub]['EstimateDtlTrnView']['id'] ){
        	   //同じ商品コード
        	   if($estimate_dtl[$main]['EstimateDtlTrnView']['goods_cd'] == $estimate_dtl[$sub]['EstimateDtlTrnView']['goods_cd'] ){

        	     //商品カテゴリがTransportation(Guest)又はReceptionTransportationの場合のみ集約
        	     if($estimate_dtl[$main]['EstimateDtlTrnView']['goods_ctg_id'] == GC_TRANS_GST ||
        	   	    $estimate_dtl[$main]['EstimateDtlTrnView']['goods_ctg_id'] == GC_RECEPTION_TRANS ){
        	   	    //個数を追加
        	   	    $estimate_dtl[$main]['EstimateDtlTrnView']['num'] += $estimate_dtl[$sub]['EstimateDtlTrnView']['num'];
        	   	    //一致した商品の片方は見積対象外とする
        	   	    $estimate_dtl[$sub]['EstimateDtlTrnView']['del_kbn'] = true;
        	     }
        	   }
        	}
        }
    }

    // データ
    for($i=0;$i < count($estimate_dtl);$i++)
    {
       if($estimate_dtl[$i]['EstimateDtlTrnView']['del_kbn']==false){
         $html .= '<tr><td class="border">'                 . $estimate_dtl[$i]['EstimateDtlTrnView']['goods_ctg_nm']            . '</td>'.
                      '<td class="border">'                 . '《'. $estimate_dtl[$i]['EstimateDtlTrnView']['goods_kbn_nm'].'》'.'<br />'.str_replace ("\n", "<br />", $estimate_dtl[$i]['EstimateDtlTrnView']['sales_goods_nm']).'</td>'.
                      '<td align="right" class="border">￥' . number_format($estimate_dtl[$i]['EstimateDtlTrnView']['yen_price'])        . '</td>'.
                      '<td align="right" class="border">'   . (int)$estimate_dtl[$i]['EstimateDtlTrnView']['num']                       . '</td>'.
                      '<td align="right" class="border">￥' . number_format($estimate_dtl[$i]['EstimateDtlTrnView']['yen_price'] * (int)$estimate_dtl[$i]['EstimateDtlTrnView']['num']) . '</td></tr>';
       }
    }

         //小計は表示しない
         //'<tr><td colspan="4" align="right" class="border">小計</td><td align="right" class="border">￥' . number_format($subtotal) . '</td></tr>' .
$html .= '<tr><td colspan="2"></td><td colspan="2" align="right" class="border">ハワイ州税</td><td align="right" class="border">￥'. number_format($tax) .'</td></tr>' .
         '<tr><td colspan="2"></td><td colspan="2" align="right" class="border">'.$estimate_dtl[0]['EstimateDtlTrnView']['service_rate_nm'].'</td><td align="right" class="border">￥'. number_format($service) .'</td></tr>';
  
         //割引率又は割引額、追加商品が適用されている場合は小計を表示する
         if($is_discounted || !empty($opt1) || !empty($opt2) || !empty($opt3)){
         	$html .= '<tr><td colspan="2"></td><td colspan="2" align="right" class="border">小計</td><td align="right" class="border">￥' . number_format($subtotal + $tax +$service) . '</td></tr>';
         	//割引率が適用されている
         	if($discountA > 0){
         		$html .= '<tr><td colspan="2"></td><td colspan="2" align="right" class="border">'.$estimate_dtl[0]['EstimateDtlTrnView']['discount_rate_nm'] .'['. $discount_rate .'%]</td><td align="right" class="border">(￥' .number_format($discountA) . ')</td></tr>';
         	}
         	//割引額が適用されている
         	if($discountB > 0){
         		$html .='<tr><td colspan="2"></td><td colspan="2" align="right" class="border">'.$estimate_dtl[0]['EstimateDtlTrnView']['discount_nm'].'</td><td align="right" class="border">(￥' .number_format($discountB) . ')</td></tr>' ;
         	}
         	//追加商品が適用されている
         	if(!empty($opt1)){ $html .= '<tr><td colspan="2"></td><td colspan="2" align="right" class="border">'.$opt1.'</td><td align="right" class="border">￥' . number_format($opt_p1) . '</td></tr>';}
            if(!empty($opt2)){ $html .= '<tr><td colspan="2"></td><td colspan="2" align="right" class="border">'.$opt2.'</td><td align="right" class="border">￥' . number_format($opt_p2) . '</td></tr>';}
            if(!empty($opt3)){ $html .= '<tr><td colspan="2"></td><td colspan="2" align="right" class="border">'.$opt3.'</td><td align="right" class="border">￥' . number_format($opt_p3) . '</td></tr>';}
            
         	$html .= '<tr><td colspan="2"></td><td colspan="2" align="right" class="border">合計</td><td align="right" class="border">￥' . number_format(($subtotal + $tax +$service + $opt_p1 + $opt_p2 + $opt_p3)-$discountA-$discountB) . '</td></tr>';
            if($credit_amount > 0){
          		$html .= '<tr><td colspan="2"></td><td colspan="2" align="right" class="border">ご入金金額</td><td align="right" class="border">(￥' . number_format($credit_amount) . ')</td></tr>'.
          		         '<tr><td colspan="2"></td><td colspan="2" align="right" class="border">合計</td><td align="right" class="border">￥' . number_format((($subtotal + $tax + $service + $opt_p1 + $opt_p2 + $opt_p3)-$discountA-$discountB)- $credit_amount) . '</td></tr>' ;
          	}

         }else{
         	$html .= '<tr><td colspan="2"></td><td colspan="2" align="right" class="border">合計</td><td align="right" class="border">￥' . number_format($subtotal + $tax +$service) . '</td></tr>';
         	if($credit_amount > 0){
          		$html .= '<tr><td colspan="2"></td><td colspan="2" align="right" class="border">ご入金金額</td><td align="right" class="border">(￥' . number_format($credit_amount) . ')</td></tr>'.
            	         '<tr><td colspan="2"></td><td colspan="2" align="right" class="border">お見積金額合計</td><td align="right" class="border">￥' . number_format(($subtotal + $tax + $service) - $credit_amount) . '</td></tr>';
          	}
         }     
         

$html .=  '</table></div>';

/* 備考:見積画面の文言 */
if(!empty($estimate_dtl[0]['EstimateDtlTrnView']['pdf_note'])){
$html .=  '<div><table border="0" cellspacing="0" cellpadding="2"><tr><td>【備考】</td></tr><tr><td>'.nl2br($estimate_dtl[0]['EstimateDtlTrnView']['pdf_note']).'</td></tr></table></div>';
}

/* 注意事項:帳票管理画面の文言*/
$html .= '<div><table border="0" cellspacing="0" cellpadding="2"><tr><td>【注意事項】</td></tr>';
$note = explode("\n", $report[0]['ReportMst']['note']);
for($i=0; $i < count($note);$i++){
	$html .= '<tr><td>'.$note[$i].'</td></tr>';
}
$html .= '</table></div>';

/* フッター:帳票管理画面の文言 */
/*
$html .= '<div>
               <table border="0" bgcolor="pink">
                 <tr align="center"><td><font size="10">www.realweddings.jp</font></td></tr>
               </table>
          </div>';
*/
$obj->writeHTML($html, true, 0, true, 0);

//改行
//$obj->Ln();

/**
 * 引数１：ファイル名
 * 引数２：出力先(I=ブラウザ, D=ダウンロード, F=ファイル保存, S=文字列として出力)
 */
$out = $obj->Output($filename, "D");
?>
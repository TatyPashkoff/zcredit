<?php

use common\models\CreditHistory;
use common\models\Credits;
use yii\helpers\Html;

$this->title = 'Списание средств';
$this->params['breadcrumbs'][] = $this->title;

?>
    <div class="table-responsive">
        <table class="class=" table
        "" border="1">
        <thead>
        <tr class="offer__table__tr">
            <th scope="col">№</th>
            <th scope="col">Наименование товара</th>
            <th scope="col">Единица</th>
            <th scope="col">Количество</th>
            <th scope="col">Цена</th>
            <th scope="col">Стоимость поставки</th>
            <th scope="col">Ставка НДС, %</th>
            <th scope="col">Сумма НДС</th>
            <th scope="col">Стоимость с НДС</th>

        </tr>
        </thead>
        <tbody>

        <?php

        if (isset($credit->creditItems)) {
            $npp = 0;
            $cnt = 0;
            $sum = 0;
            $sum_price = 0;
            $s = 0;
            $nds_sum = 0;
            $clear_price = 0;


            $nds = $credit->credit_limit == 3 ? 1.25 : 1.35;
            if (!$credit->nds) {
                $nds += 1.15;
            }
            $nds_title = '15';
            foreach ($credit->creditItems as $credit_item) {
                $npp++;
                $cnt += $credit_item->quantity;

                //$sum_price += $credit_item->amount* $credit_item->quantity;

                //  $s = $credit_item->amount * $credit_item->quantity;
//
//                        $price_nds = $nds * $s;
//                        $sum += $price_nds;


                $nds_percent = 1;
                $reverse_nds_percent = 1.15;
                //$discount = Yii::$app->user->identity->discount ? Yii::$app->user->identity->discount : 0;
                // $discount_s = $credit_item->amount * $discount / 100;
                // $discount_sum = $credit_item->amount - $discount_s; // стоимость товара со скидкой

                if (!$credit->nds) {
                    $nds_percent = 1.15;
                    $reverse_nds_percent = 1;
                    $nds_title = "(без ндс)";
                }
                // вычисление процента кредита
                $percent = ($credit_item->price) / ($credit_item->discount_sum * $credit_item->quantity * $nds_percent);

                // Сумма товара с процентами но без НДС если таковой имеется
                $item_price = $credit_item->discount_sum / $reverse_nds_percent /* * $percent*/
                ;
                $sum_price += $credit_item->discount_sum * $credit_item->quantity;
                $clear_price += $credit_item->discount_sum * $credit_item->quantity;
                // сумма ндс
                $nds_sum = $credit_item->discount_sum - $item_price;

                ?>

                <tr class="offer__table__tr">
                    <th scope="col"><?= $npp ?></th>
                    <td><?= $credit_item->title ?></td>
                    <td>шт</td>
                    <td><?= $credit_item->quantity ?></td>
                    <td><?= number_format($item_price, 2, '.', ' ') ?></td>
                    <td><?= number_format($item_price * $credit_item->quantity, 2, '.', ' ') ?></td>
                    <td><?= $nds_title ?> </td>
                    <td><?= number_format($nds_sum * $credit_item->quantity, 2, '.', ' ') ?> </td>
                    <td><?= number_format(($item_price + $nds_sum) * $credit_item->quantity, 2, '.', ' ') ?></td>
                </tr>

            <?php }

        }
        $user_id = $credit->client->id;
        $summ = $credit->client->summ;
        $credit_id = $credit->id;
        $credit_history_price = $credit->history->price;

        if( $credit_history = CreditHistory::find()->with(['credit','client','contract'])->where(['credit_id' => $credit_id])->andWhere(['payment_status'=>'0'])->one() ) {

                //return json_encode(['credit_history' => $credit_history->price]);

                if($summ > 0){
                    // списать с лицевого счета
                    print_r($credit_history->client->summ . '-' . $credit_history->price);
                }


            }

        ?>

        </tbody>
        </table>
    </div>

    <div class="offer__table__summ">

        <div class="offer__table-item">
            <span class="offer__table-res">
                Итого на общую сумму
            </span>
            <span class="offer__table-count">
                <?= number_format($sum_price, 2, '.', ' ') ?>
            </span>
        </div>

    </div>

    <div class="pay">

        <input type="number" data-name="balance" id="balance" readonly>
        <div class="btn btn-default cards_get">
            <?= Yii::t('app', 'посмотреть баланс') ?>
        </div>
    </div>

    <div class="row">
        <div id="info-block"></div>
    </div>
    <div class="row">
        <div id="summ-block"></div>
    </div>
    <div>

        <input type="number" style="display: none;" data-name="discard_sum" id="discard_sum">
        <div class="btn btn-default discard " style="display: none;"><?= Yii::t('app', 'Списать') ?></div>
    </div>

<?php
$script = "
    $('document').ready(function(){
    var user_id = '{$user_id}';
    var summ = '{$summ}';
    var credit_id ='{$credit_id}';
    var credit_history_price = {$credit_history_price};
    
     console.log(credit_history_price * 100);
    
    $('#discard_sum').val(credit_history_price); 
    if(summ > 0) 
      $('#summ-block').html('сумма на лицевом счете: '+summ+' сум'); 
      
    if(summ > 0 && summ < credit_history_price){
                $('.discard').fadeIn();
                $('#discard_sum').fadeIn();
                credit_history_price -= summ ;
                summ = 0                
      }
    if(summ > credit_history_price){
                $('.discard').fadeIn();
                $('#discard_sum').fadeIn();
                summ -= credit_history_price;
                credit_history_price = 0;                
        }
        
         console.log(summ);
         console.log(credit_history_price);
         

     $('.cards_get').click(function(){	  
	   $.ajax({
            type: 'post',
            url: '/billing-credits/get-balance',          
            data: 'user_id='+user_id+'&_csrf=' + yii.getCsrfToken(),
            dataType: 'json',
            success: function(data){
                if(data){ 
                  console.log(data.balance);   	               
	              $('#balance').val(data.balance);            
                }else{
                    alert('no data');
                }
            },
            error: function(data){
               alert('error');
            }
          }); 	     
	 
	 })
	 
	 $('.discard').click(function(){
         	if (confirm('Продолжить')) {  
                sum = document.getElementById('discard_sum').value;
                console.log(sum);
                   $.ajax({
                        type: 'post',
                        url: '/billing-credits/pay',           
                        data: 'credit_id='+credit_id+'user_id='+user_id+'&credit_history_price='+credit_history_price+'&summ='+summ+'&_csrf=' + yii.getCsrfToken(),
                        dataType: 'json',
                        success: function(data){
                            if(data){                              
                              console.log(data.result);                           	               
                              console.log(data.result.result.amount);                                                    	               
                              console.log(data.result.result.refNum);
                              console.log(data.result.result.status);
                              amount = data.result.result.amount/100;
                               
                               $('#info-block').html('успешное списание '+amount+' сум.'); 
                                                                          	               
                                         
                            }else{
                                alert('no data');
                            }
                        },
                        error: function(data){
                           alert('error');
                        }
                      }); 
                        } else {
                            return false;
                        }   
                        
	 })
	 
	
	 
});";

$this->registerJs($script, yii\web\View::POS_END);
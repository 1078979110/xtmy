<?php
namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
class ExcelExport extends AbstractExporter
{
    public function export()
    {
        $data = $this->getData();
        $cellData = $this->dataTools($data);
        //var_dump($cellData);
        //exit;
        $finename = date('订单导出'.'Y-m-d H-i-s', time());
        Excel::create($finename, function($excel) use ($cellData){
            $excel->sheet('sheet', function($sheet) use($cellData){
                $sheet->setWidth('A',20);
                $sheet->setWidth('B',20);
                $sheet->setWidth('C',40);
                $sheet->setWidth('D',20);
                $sheet->setWidth('E',20);
                $sheet->setWidth('F',20);
                $sheet->setWidth('G',20);
                $sheet->setWidth('H',20);
                $sheet->setWidth('I',40);
                $sheet->setWidth('J',20);
                $titlecolor = '#F8CBAD';
                $sheet->mergeCells('A1:A2');

                $sheet->cell('A1', function ($cells)use($titlecolor){
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setBackground($titlecolor);
                    $cells->setBorder('thin','thin','thin','thin');
                    $cells->setValue('订单号');
                });
                $sheet->mergeCells('B1:B2');
                //$sheet->cell('B1', '订单金额');
                $sheet->cell('B1', function($cells)use($titlecolor){
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setBackground($titlecolor);
                    $cells->setBorder('thin','thin','thin','thin');
                    $cells->setValue('订单金额');
                });
                $sheet->mergeCells('C1:G1');
                //$sheet->cell('C1', '订单详情');
                $sheet->cell('C1', function($cells)use($titlecolor){
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setBackground($titlecolor);
                    $cells->setBorder('thin','thin','thin','thin');
                    $cells->setValue('订单详情');
                });
                $sheet->mergeCells('H1:H2');
                //$sheet->cell('H1', '下单人');
                $sheet->cell('H1', function($cells)use($titlecolor){
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setBackground($titlecolor);
                    $cells->setBorder('thin','thin','thin','thin');
                    $cells->setValue('下单人');
                });
                $sheet->mergeCells('I1:I2');
                //$sheet->cell('I1', '医院');
                $sheet->cell('I1', function($cells)use($titlecolor){
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setBackground($titlecolor);
                    $cells->setBorder('thin','thin','thin','thin');
                    $cells->setValue('医院');
                });
                $sheet->mergeCells('J1:J2');
                //$sheet->cell('J1', '下单时间');
                $sheet->cell('J1', function($cells)use($titlecolor){
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setBackground($titlecolor);
                    $cells->setBorder('thin','thin','thin','thin');
                    $cells->setValue('下单时间');
                });
                //$sheet->cell('C2', '药品名称');
                $sheet->cell('C2', function($cells)use($titlecolor){
                    $cells->setAlignment('center');
                    $cells->setBackground($titlecolor);
                    $cells->setBorder('thin','thin','thin','thin');
                    $cells->setValue('药品名称');
                });
                //$sheet->cell('D2', '货号');
                $sheet->cell('D2', function($cells)use($titlecolor){
                    $cells->setAlignment('center');
                    $cells->setBackground($titlecolor);
                    $cells->setBorder('thin','thin','thin','thin');
                    $cells->setValue('货号');
                });
                //$sheet->cell('E2', '单位');
                $sheet->cell('E2', function($cells)use($titlecolor){
                    $cells->setAlignment('center');
                    $cells->setBackground($titlecolor);
                    $cells->setBorder('thin','thin','thin','thin');
                    $cells->setValue('单位');
                });
                //$sheet->cell('F2', '单价');
                $sheet->cell('F2', function($cells)use($titlecolor){
                    $cells->setAlignment('center');
                    $cells->setBackground($titlecolor);
                    $cells->setBorder('thin','thin','thin','thin');
                    $cells->setValue('单价');
                });
                //$sheet->cell('G2', '数量');
                $sheet->cell('G2', function($cells)use($titlecolor){
                    $cells->setAlignment('center');
                    $cells->setBackground($titlecolor);
                    $cells->setBorder('thin','thin','thin','thin');
                    $cells->setValue('数量');
                });
                $rowNum = 3;
                foreach ($cellData as $key=>$val) {
                    $color = '';
                    if ($key % 2 == 0) {
                        $color = '#DDEBF7';
                    } else {
                        $color = '#BDD7EE';
                    }
                    $len = sizeof($val['order_info']);
                    $n = $rowNum + $len - 1;
                    $sheet->mergeCells('A' . $rowNum . ':A' . $n);
                    $sheet->cell('A' . $rowNum, function ($cells) use ($val, $color) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setBackground($color);
                        $cells->setBorder('thin','thin','thin','thin');
                        $cells->setValue('\''.$val['order_id']);
                    });
                    $sheet->mergeCells('B' . $rowNum . ':B' . $n);
                    //$sheet->cell('B'.$rowNum, $val['total']);
                    $sheet->cell('B' . $rowNum, function ($cells) use ($val, $color) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setBackground($color);
                        $cells->setBorder('thin','thin','thin','thin');
                        $cells->setValue($val['total']);
                    });
                    foreach ($val['order_info'] as $k => $v) {
                        $m = $rowNum + $k;
                        $sheet->cell('C' . $m, function ($cells) use ($v, $color) {
                            $cells->setAlignment('center');
                            $cells->setBackground($color);
                            $cells->setBorder('thin','thin','thin','thin');
                            $cells->setValue($v['medicinal']);
                        });
                        $sheet->cell('D' . $m, function ($cells) use ($v, $color) {
                            $cells->setAlignment('center');
                            $cells->setBackground($color);
                            $cells->setBorder('thin','thin','thin','thin');
                            $cells->setValue($v['medicinalnum']);
                        });
                        $sheet->cell('E' . $m, function ($cells) use ($v, $color) {
                            $cells->setAlignment('center');
                            $cells->setBackground($color);
                            $cells->setBorder('thin','thin','thin','thin');
                            $cells->setValue($v['unit']);
                        });
                        $sheet->cell('F' . $m, function ($cells) use ($v, $color) {
                            $cells->setAlignment('center');
                            $cells->setBackground($color);
                            $cells->setBorder('thin','thin','thin','thin');
                            $cells->setValue($v['price']);
                        });
                        $sheet->cell('G' . $m, function ($cells) use ($v, $color) {
                            $cells->setAlignment('center');
                            $cells->setBackground($color);
                            $cells->setBorder('thin','thin','thin','thin');
                            $cells->setValue($v['num']);
                        });
                    }

                    $sheet->mergeCells('H' . $rowNum . ':H' . $n);
                    $sheet->cell('H' . $rowNum, function ($cells) use ($val, $color) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setBackground($color);
                        $cells->setBorder('thin','thin','thin','thin');
                        $cells->setValue($val['buyer_name']);
                    });
                    $sheet->mergeCells('I' . $rowNum . ':I' . $n);
                    $sheet->cell('I' . $rowNum, function ($cells) use ($val, $color) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setBackground($color);
                        $cells->setBorder('thin','thin','thin','thin');
                        $cells->setValue($val['hospital']);
                    });
                    $sheet->mergeCells('J' . $rowNum . ':J' . $n);
                    $sheet->cell('J' . $rowNum, function ($cells) use ($val, $color) {
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setBackground($color);
                        $cells->setBorder('thin','thin','thin','thin');
                        $cells->setValue($val['created_at']);
                    });
                    $rowNum = $n + 1;
                }
            });
        })->store('xls')->export('xls');
    }

    private function dataTools($models){
        $data = [];
        foreach ($models as $model){
            $oMedicinals = DB::table('order_medicinals as a')
                ->leftjoin('medicinal as b','b.id','=','a.medicinal_id')
                ->where('a.order_id',$model['id'])
                ->get(['b.medicinal','b.medicinalnum', 'b.unit','a.price', 'a.num'])->toArray();
            $orderInfo = [];
            foreach ($oMedicinals as $key=>$medicinal){
                $orderInfo[] = [
                    'medicinal' => $medicinal->medicinal,
                    'medicinalnum' => $medicinal->medicinalnum,
                    'unit'=> $medicinal->unit,
                    'price'=>$medicinal->price,
                    'num'=>$medicinal->num
                ];
            }
            $buyerInfo = DB::table('users')->where('id', $model['buyerid'])->first();
            $hospitalInfo = [];
            if($model['hospital']){
                $hospitalInfo = DB::table('hospital')->where('id', $model['hospital'])->first();
            }
            $data[] = [
                'order_id'=>$model['orderid'],
                'total'=>$model['totalprice'],
                'order_info' =>$orderInfo,
                'buyer_name' => $buyerInfo->name,
                'hospital' => !empty($hospitalInfo)?$hospitalInfo->hospital:'',
                'created_at' => $model['created_at']
            ];
        }
        return $data;
    }
}
<?php

namespace App\Exports;

use App\Models\OrderMaster;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class OrderMasterExport implements FromCollection, WithHeadings, WithStrictNullComparison, WithEvents, WithMapping, WithStyles
// class OrderMasterExport implements FromView, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;

    public function collection()
    {
        return OrderMaster::select('order_master.id','order_master.order_trans','season.season_cat','season.season_year','buyer.buyer_name', 'brand.brand_name', 'style_name','purchase_order.po_master','order_master.qty_order','order_master.qty_ocf', DB::raw('round(sum(if(raf_production.raf_dept = "DEP000000004", raf_production.raf_qty, 0)),2) as sum_raf_qty'),'order_master.qty_sbd','followup.fu_name','order_master.sketch_file','order_master.remark', DB::raw('ROW_NUMBER() OVER (ORDER BY order_master.id) AS row_num'))
        ->leftJoin('season', 'order_master.season_no', '=', 'season.season_no')
        ->leftJoin('buyer', 'order_master.buyer_no', '=', 'buyer.buyer_no')
        ->leftJoin('brand', 'order_master.brand_no', '=', 'brand.brand_no')
        ->leftJoin('style', 'order_master.style_no', '=', 'style.style_no')
        ->leftJoin('followup', 'order_master.fu_no', '=', 'followup.fu_no')
        ->leftJoin('raf_production', 'order_master.order_trans', '=', 'raf_production.order_trans')
        ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
        ->groupBy('order_master.order_trans')
        ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Order Trans',
            'Season',
            'Buyer',
            'Brand',
            'Style',
            'MR',
            'Quantity Order',
            'Quantity Garment',
            'Quantity SBD',
            'Wash Type',
            'Sketch',
            'Remark'
        ];
    }
    public function map($orderMaster): array
    {
        return [
           $orderMaster->row_num,
           $orderMaster->order_trans,
           $orderMaster->season_cat,
           $orderMaster->buyer_name,
           $orderMaster->brand_name,
           $orderMaster->style_name,
           $orderMaster->fu_name,
           $orderMaster->qty_order,
           $orderMaster->qty_gmt,
           $orderMaster->qty_sbd,
           $orderMaster->wash_type,
           '',
           $orderMaster->remark,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:M1')->applyFromArray(
            [
                'font' => [
                    'name' => 'Arial',
                    'bold' => true,
                    'italic' => false,
                    'underline' => false,
                    'strikethrough' => false,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => [
                            'rgb' => '000000'
                        ]
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false,
                ],
                'quotePrefix'    => true
            ]
        );
        
    }

    public function setImage($workSheet) {
        $this->collection()->each(function($orderMaster,$index) use($workSheet) {
            //dd($orderMaster);
            $drawing = new Drawing();
            $drawing->setName('Image');
            $drawing->setDescription($orderMaster->sketch_file);
            $drawing->setPath(public_path('sketch/'.$orderMaster->sketch_file));
            $drawing->setHeight(100);
            $index+=2;
            $drawing->setCoordinates("L$index");
            $drawing->setWorksheet($workSheet);
            $drawing->setOffsetX(10);
            $drawing->setOffsetY(10);
        });
    }

    public function registerEvents():array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // $event->sheet->getDefaultRowDimension()->setRowHeight(100);
                // $event->sheet->getRowDimension(1)->setRowHeight(10);
                // $event->sheet->getColumnDimension('L')->setWidth(100);
                $workSheet = $event->sheet->getDelegate();
                $this->setImage($workSheet);
                
                $this->collection()->each(function($orderMaster,$index) use($workSheet) {
                    $index+=2;
                    $workSheet->getColumnDimension('A')->setWidth(8);
                    $workSheet->getColumnDimension('B')->setWidth(18);
                    $workSheet->getColumnDimension('C')->setWidth(15);
                    $workSheet->getColumnDimension('D')->setWidth(15);
                    $workSheet->getColumnDimension('E')->setWidth(15);
                    $workSheet->getColumnDimension('F')->setWidth(20);
                    $workSheet->getColumnDimension('G')->setWidth(15);
                    $workSheet->getColumnDimension('H')->setWidth(20);
                    $workSheet->getColumnDimension('I')->setWidth(20);
                    $workSheet->getColumnDimension('J')->setWidth(20);
                    $workSheet->getColumnDimension('K')->setWidth(20);
                    $workSheet->getColumnDimension('L')->setWidth(30);
                    $workSheet->getColumnDimension('M')->setWidth(30);
                    $workSheet->getRowDimension($index)->setRowHeight(100);

                    $workSheet->getStyle("A$index:M$index")->applyFromArray(
                        [
                            'font' => [
                                'name' => 'Arial',
                                'bold' => false,
                                'italic' => false,
                                'underline' => false,
                                'strikethrough' => false,
                                'color' => [
                                    'rgb' => '000000'
                                ]
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => [
                                        'rgb' => '000000'
                                    ]
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                                'wrapText' => false,
                            ],
                            'quotePrefix'    => true
                        ]
                    );
                });
            },
        ];
    }
}

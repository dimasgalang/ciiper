<?php

namespace App\Http\Controllers;

use App\Imports\ProductionPlanningsImport;
use App\Models\Accesories;
use App\Models\BordirType;
use App\Models\Factory;
use App\Models\LogCiiper;
use App\Models\OrderList;
use App\Models\OrderMaster;
use App\Models\ProductionPlanning;
use App\Models\ProPlanAcc;
use App\Models\SetupIncrement;
use App\Models\WashType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class ProductionPlanningController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $productionplannings = DB::select('select distinct production_planning.*,purchase_order.po_master,order_list.pobuyer_no,order_list.dcpo_qty,(select ifnull(round(avg(percentage),0),0) from proplan_detail t2 where t2.order_list = production_planning.order_list and category_no = "CAT000000001") as average_acc,(select ifnull(round(avg(percentage),0),0) from proplan_detail t2 where t2.order_list = production_planning.order_list and category_no = "CAT000000002") as average_sample,(select ifnull(round(avg(percentage),0),0) from proplan_detail t2 where t2.order_list = production_planning.order_list and category_no = "CAT000000003") as average_fabric,(select ifnull(round(avg(percentage),0),0) from proplan_detail t2 where t2.order_list = production_planning.order_list and category_no = "CAT000000004") as average_mi,(select ifnull(round(sum(raf_qty),0),0) from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000001") as sum_raf_cut,(select ifnull(round(sum(raf_qty),0),0)-order_list.dcpo_qty from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000001") as balance_cut, (select ifnull(round(sum(raf_qty),0),0) from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000002") as sum_raf_sew,(select ifnull(round(sum(raf_qty),0),0)-order_list.dcpo_qty from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000002") as balance_sew,(select ifnull(round(sum(raf_qty),0),0) from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000003") as sum_raf_iron,(select ifnull(round(sum(raf_qty),0),0)-order_list.dcpo_qty from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000003") as balance_iron,(select ifnull(round(sum(raf_qty),0),0) from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000004") as sum_raf_pack,(select ifnull(round(sum(raf_qty),0),0)-order_list.dcpo_qty from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000004") as balance_pack from production_planning left join proplan_detail on production_planning.order_list = proplan_detail.order_list left join order_master on production_planning.order_trans = order_master.order_trans left join order_list on production_planning.order_list = order_list.order_list left join purchase_order on order_master.po_no = purchase_order.po_no left join raf_production on raf_production.order_list = production_planning.order_list where production_planning.void = "' . $request->void . '"');
        } else {
            $productionplannings = DB::select('select distinct production_planning.*,purchase_order.po_master,order_list.pobuyer_no,order_list.dcpo_qty,(select ifnull(round(avg(percentage),0),0) from proplan_detail t2 where t2.order_list = production_planning.order_list and category_no = "CAT000000001") as average_acc,(select ifnull(round(avg(percentage),0),0) from proplan_detail t2 where t2.order_list = production_planning.order_list and category_no = "CAT000000002") as average_sample,(select ifnull(round(avg(percentage),0),0) from proplan_detail t2 where t2.order_list = production_planning.order_list and category_no = "CAT000000003") as average_fabric,(select ifnull(round(avg(percentage),0),0) from proplan_detail t2 where t2.order_list = production_planning.order_list and category_no = "CAT000000004") as average_mi,(select ifnull(round(sum(raf_qty),0),0) from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000001") as sum_raf_cut,(select ifnull(round(sum(raf_qty),0),0)-order_list.dcpo_qty from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000001") as balance_cut, (select ifnull(round(sum(raf_qty),0),0) from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000002") as sum_raf_sew,(select ifnull(round(sum(raf_qty),0),0)-order_list.dcpo_qty from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000002") as balance_sew,(select ifnull(round(sum(raf_qty),0),0) from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000003") as sum_raf_iron,(select ifnull(round(sum(raf_qty),0),0)-order_list.dcpo_qty from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000003") as balance_iron,(select ifnull(round(sum(raf_qty),0),0) from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000004") as sum_raf_pack,(select ifnull(round(sum(raf_qty),0),0)-order_list.dcpo_qty from raf_production t2 where t2.order_list = production_planning.order_list and raf_dept = "DEP000000004") as balance_pack from production_planning left join proplan_detail on production_planning.order_list = proplan_detail.order_list left join order_master on production_planning.order_trans = order_master.order_trans left join order_list on production_planning.order_list = order_list.order_list left join purchase_order on order_master.po_no = purchase_order.po_no left join raf_production on raf_production.order_list = production_planning.order_list where production_planning.void = "false"');
        }
        return view('productionplanning.index', compact('productionplannings'));
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/', $nama_file);
        $import = Excel::import(new ProductionPlanningsImport(), storage_path('app/public/excel/' . $nama_file));
        Storage::delete($path);

        if ($import) {
            Alert::success('Import Successfully!', 'Production Planning data successfully imported!');
            return redirect()->intended('productionplanning/index');
        } else {
            return redirect()->intended('productionplanning/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    public function create()
    {
        $ordermasters = OrderMaster::select('order_master.*', 'purchase_order.po_master')
            ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
            ->get();
        $orderlists = OrderList::all();
        $washtypes = WashType::all();
        $bordirtypes = BordirType::all();
        $factorys = Factory::all();
        $setupincements = SetupIncrement::all()->where('models', '=', 'ProductionPlanning')->last();
        $lastorderlist = SetupIncrement::all()->where('models', '=', 'OrderList')->last();
        $accesoriessewings = Accesories::all()->where('category_no', '=', 'CAT000000006');
        $accesoriespackings = Accesories::all()->where('category_no', '=', 'CAT000000005');
        return view('productionplanning.create', compact('ordermasters', 'orderlists', 'setupincements', 'washtypes', 'bordirtypes', 'factorys', 'lastorderlist', 'accesoriessewings', 'accesoriespackings'));
    }

    public function fetchorderlist($order_trans)
    {
        $orderlists   = OrderList::select('*', 'order_master.*')
            ->leftJoin('order_master', 'order_master.order_trans', '=', 'order_list.order_trans')
            ->where('order_list.order_trans', '=', $order_trans)
            ->get();
        return response()->json($orderlists);
    }

    public function masterqty($order_trans)
    {
        $masterqty = OrderMaster::select('*')->where('order_trans', '=', $order_trans)->get();
        return response()->json($masterqty);
    }

    public function store(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Created Production Planning ' . $request->plan_no;
        $messageOrder = 'Created Order List ' . $request->order_list;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        LogCiiper::create([
            'username' => $username,
            'activity' => $messageOrder,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'plus',
            'color' => 'bg-primary',
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'ProductionPlanning'
        ], [
            'models' => 'ProductionPlanning',
            'last_number' => $request->plan_no,
        ]);
        SetupIncrement::updateOrCreate([
            'models' => 'OrderList'
        ], [
            'models' => 'OrderList',
            'last_number' => $request->order_list,
        ]);
        ProductionPlanning::create([
            'plan_no' => $request->plan_no,
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'has_sample' => $request->has_sample,
            'has_mi' => $request->has_mi,
            'has_fab_cart' => $request->has_fab_cart,
            'has_acc_cart' => $request->has_acc_cart,
            'fab_date' => $request->fab_date,
            'acc_date' => $request->acc_date,
            'bordir_approve' => $request->bordir_approve,
            'pattern_date' => $request->pattern_date,
            'sampletest_date' => $request->sampletest_date,
            'reqmarker_date' => $request->reqmarker_date,
            'marker_date' => $request->marker_date,
            'pilotrun_date' => $request->pilotrun_date,
            'ppm_date' => $request->ppm_date,
            'startcut_date' => $request->startcut_date,
            'finishcut_date' => $request->finishcut_date,
            'startsew_date' => $request->startsew_date,
            'finishsew_date' => $request->finishsew_date,
            'finishpack_date' => $request->finishpack_date,
            'remark' => $request->remark,
            'void' => 'false'
        ]);
        OrderList::create([
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'factory_no' => $request->factory_no,
            'lot_no' => $request->lot_no,
            'pobuyer_no' => $request->pobuyer_no,
            'dcpo_qty' => $request->dcpo_qty,
            'carton_qty' => $request->carton_qty,
            'ex_factory_date' => $request->ex_factory_date,
            'vsl_date' => $request->vsl_date,
            'wash_no' => $request->wash_no,
            'bordir_no' => $request->bordir_no,
            'line' => $request->line,
            'target_qty' => $request->target_qty,
            'production_day' => $request->production_day,
            'smv' => str_replace(",", ".", $request->smv),
            'void' => 'false'
        ]);

        for ($i = 0; $i < count($request->accesories_sew); $i++) {
            $getLastPPAcc = SetupIncrement::all()->where('models', '=', 'ProPlanAcc')->last();
            if (count($getLastPPAcc[0]) == 0) {
                $new_number = 'PPA' . str_pad(intval(substr($getLastPPAcc[0]->last_number, 3, 9)) + 1, 9, '0', STR_PAD_LEFT);
            } else {
                $new_number = 'PPA' . str_pad(1, 9, '0', STR_PAD_LEFT);
            }
            $item = new ProPlanAcc();
            $item->proplan_acc_no = $new_number;
            $item->order_trans = $request->order_trans;
            $item->order_list = $request->order_list;
            $item->category_no = 'CAT000000006';
            $item->accesories_no = $request->accesories_sew[$i];
            $item->item_date = $request->item_date_sew[$i];
            $item->void = 'false';
            $item->save();

            SetupIncrement::updateOrCreate([
                'models' => 'ProPlanAcc'
            ], [
                'models' => 'ProPlanAcc',
                'last_number' => $new_number,
            ]);
        }

        for ($i = 0; $i < count($request->accesories_pack); $i++) {
            $getLastPPAcc = SetupIncrement::all()->where('models', '=', 'ProPlanAcc')->last();
            if (count($getLastPPAcc[0]) == 0) {
                $new_number = 'PPA' . str_pad(intval(substr($getLastPPAcc[0]->last_number, 3, 9)) + 1, 9, '0', STR_PAD_LEFT);
            } else {
                $new_number = 'PPA' . str_pad(1, 9, '0', STR_PAD_LEFT);
            }
            $item = new ProPlanAcc();
            $item->proplan_acc_no = $new_number;
            $item->order_trans = $request->order_trans;
            $item->order_list = $request->order_list;
            $item->category_no = 'CAT000000005';
            $item->accesories_no = $request->accesories_pack[$i];
            $item->item_date = $request->item_date_pack[$i];
            $item->void = 'false';
            $item->save();

            SetupIncrement::updateOrCreate([
                'models' => 'ProPlanAcc'
            ], [
                'models' => 'ProPlanAcc',
                'last_number' => $new_number,
            ]);
        }

        Alert::success('Create Successfully!', 'Production Planning ' . $request->plan_no . ' successfully created!');
        return redirect()
            ->route('productionplanning.create');
    }

    public function delete($id)
    {
        $productionplannings = ProductionPlanning::find($id);
        $productionplannings->delete();

        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Deleted Production Planning ' . $productionplannings->plan_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'trash',
            'color' => 'bg-danger',
        ]);
        Alert::success('Delete Successfully!', 'Production Planning ' . $productionplannings->plan_no . ' successfully deleted!');
        return redirect('productionplanning/index');
    }

    public function find($id)
    {
        $productionplannings = ProductionPlanning::select('production_planning.*', 'order_list.id as idorder', 'order_list.order_list', 'order_list.pobuyer_no', 'order_list.dcpo_qty', 'order_list.target_qty', 'order_list.carton_qty', 'order_list.lot_no', 'order_list.production_day', 'order_list.line', 'order_list.smv', 'order_list.ex_factory_date', 'order_list.vsl_date', 'order_list.factory_no')
            ->leftJoin('order_list', 'order_list.order_list', '=', 'production_planning.order_list')
            ->where('production_planning.id', '=', $id)->get();
        // dd($productionplannings);
        $orderlists = OrderList::select('order_list.order_list', 'order_list.pobuyer_no', 'production_planning.*')
            ->leftJoin('production_planning', 'production_planning.order_list', '=', 'order_list.order_list')
            ->where('production_planning.id', '=', $id)
            ->get();
        $ordermasters = OrderMaster::select('order_master.order_trans', 'purchase_order.po_master', 'production_planning.*')
            ->leftJoin('purchase_order', 'order_master.po_no', '=', 'purchase_order.po_no')
            ->leftJoin('production_planning', 'order_master.order_trans', '=', 'production_planning.order_trans')
            ->where('production_planning.id', '=', $id)
            ->get();
        $washtypes = WashType::all();
        $bordirtypes = BordirType::all();
        $factorys = Factory::all();
        return view('productionplanning.update', compact('productionplannings', 'orderlists', 'ordermasters', 'washtypes', 'bordirtypes', 'factorys'));
    }

    public function update(Request $request)
    {
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Updated Production Planning ' . $request->plan_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $productionplannings = ProductionPlanning::findOrFail($request->id);
        $orderlists = OrderList::findOrFail($request->idorder);
        // dd($request->idorder);

        $validator = Validator::make($request->all(), [
            'plan_no' => 'required|max:255|',
            'order_trans' => 'required|max:255|',
            'order_list' => 'required|max:255',
            'has_sample' => 'required|max:255',
            'has_mi' => 'required|max:255',
            'has_fab_cart' => 'required|max:255',
            'has_acc_cart' => 'required|max:255',
            'fab_date' => 'required|max:255',
            'acc_date' => 'required|max:255',
            'bordir_approve' => 'required|max:255',
            'pattern_date' => 'required|max:255',
            'sampletest_date' => 'required|max:255',
            'reqmarker_date' => 'required|max:255',
            'marker_date' => 'required|max:255',
            'pilotrun_date' => 'required|max:255',
            'ppm_date' => 'required|max:255',
            'startcut_date' => 'required|max:255',
            'finishcut_date' => 'required|max:255',
            'startsew_date' => 'required|max:255',
            'finishsew_date' => 'required|max:255',
            'finishpack_date' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $productionplannings->fill([
            'plan_no' => $request->plan_no,
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'has_sample' => $request->has_sample,
            'has_mi' => $request->has_mi,
            'has_fab_cart' => $request->has_fab_cart,
            'has_acc_cart' => $request->has_acc_cart,
            'fab_date' => $request->fab_date,
            'acc_date' => $request->acc_date,
            'bordir_approve' => $request->bordir_approve,
            'pattern_date' => $request->pattern_date,
            'sampletest_date' => $request->sampletest_date,
            'reqmarker_date' => $request->reqmarker_date,
            'marker_date' => $request->marker_date,
            'pilotrun_date' => $request->pilotrun_date,
            'ppm_date' => $request->ppm_date,
            'startcut_date' => $request->startcut_date,
            'finishcut_date' => $request->finishcut_date,
            'startsew_date' => $request->startsew_date,
            'finishsew_date' => $request->finishsew_date,
            'finishpack_date' => $request->finishpack_date,
            'remark' => $request->remark,
        ]);

        $orderlists->fill([
            'order_trans' => $request->order_trans,
            'order_list' => $request->order_list,
            'factory_no' => $request->factory_no,
            'lot_no' => $request->lot_no,
            'pobuyer_no' => $request->pobuyer_no,
            'dcpo_qty' => $request->dcpo_qty,
            'carton_qty' => $request->carton_qty,
            'ex_factory_date' => $request->ex_factory_date,
            'vsl_date' => $request->vsl_date,
            'wash_no' => $request->wash_no,
            'bordir_no' => $request->bordir_no,
            'line' => $request->line,
            'target_qty' => $request->target_qty,
            'production_day' => $request->production_day,
            'smv' => str_replace(",", ".", $request->smv),
        ]);

        $productionplannings->save();
        $orderlists->save();

        Alert::success('Update Successfully!', 'Production Planning ' . $request->plan_no . ' successfully updated!');
        return redirect('productionplanning/index');
    }


    public function void(Request $request)
    {
        $productionplannings = ProductionPlanning::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Void Production Planning ' . $productionplannings->plan_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $productionplannings->fill([
            'void' => 'true',
        ]);

        $productionplannings->save();

        Alert::success('Void Successfully!', 'Production Planning ' . $productionplannings->plan_no . ' successfully voided!');
        return redirect('productionplanning/index');
    }

    public function restore(Request $request)
    {
        $productionplannings = ProductionPlanning::findOrFail($request->id);
        $username = Auth::user()->name;
        $storeTime = Carbon::now();
        $message = 'Restore Production Planning ' . $productionplannings->plan_no;
        LogCiiper::create([
            'username' => $username,
            'activity' => $message,
            'time' => $storeTime->toDateTimeString(),
            'icon' => 'edit',
            'color' => 'bg-warning',
        ]);

        $productionplannings->fill([
            'void' => 'false',
        ]);

        $productionplannings->save();

        Alert::success('Restore Successfully!', 'Production Planning ' . $productionplannings->plan_no . ' successfully restored!');
        return redirect('productionplanning/index');
    }
}

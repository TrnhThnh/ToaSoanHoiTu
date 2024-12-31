<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BantinXuatBan;

class NhuanButController extends Controller
{
    public function index()
    {
        $nhuanButList = DB::table('bantinxuatban')
            ->leftJoin('nhuanbut', 'bantinxuatban.MaBT_XB', '=', 'nhuanbut.MaBT_XB')
            ->join('bantinhientruong', 'bantinxuatban.MaBTHT_XB', '=', 'bantinhientruong.MaBT_HT')
            ->join('bantinbientap', 'bantinxuatban.MaBTBT_XB', '=', 'bantinbientap.MaBT_BT')
            ->select(
                'bantinxuatban.MaBT_XB',
                'bantinxuatban.TieuDeBT_XB',
                'bantinhientruong.TenPhongVien',
                'bantinhientruong.MaNV_PV as MaPhongVien',
                'bantinbientap.TenBienTapVien',
                'bantinbientap.MaNV_BTV as MaBienTapVien',
                'nhuanbut.NgayThanhToan',
                'nhuanbut.TinhTrangThanhToan',
                'bantinxuatban.NoiDungBT_XB'
            )
            ->paginate(10);
        foreach ($nhuanButList as $baiTin) {
            $wordCount = str_word_count(strip_tags($baiTin->NoiDungBT_XB));
            if ($wordCount < 500) {
                $baiTin->NhuanBut = 100000;
            } elseif ($wordCount <= 1000) {
                $baiTin->NhuanBut = 200000;
            } else {
                $baiTin->NhuanBut = 300000;
            }
            $baiTin->NhuanButPhongVien = $baiTin->NhuanBut / 2;
            $baiTin->NhuanButBienTapVien = $baiTin->NhuanBut / 2;
        }
        return view('quanly.nhuanbut.index', compact('nhuanButList'));
    }    
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'MaPV' => 'required|integer',
            'TenPV' => 'required|string',
            'NhuanButPV' => 'required|integer',
            'MaBT' => 'required|integer',
            'TenBT' => 'required|string',
            'NhuanButBT' => 'required|integer',
        ]);

        DB::table('nhuanbut')->updateOrInsert(
            ['MaBT_XB' => $id],
            [
                'NgayThanhToan' => now(),
                'TinhTrangThanhToan' => 'Đã Thanh Toán',
                'Ma_PV' => $request->MaPV,
                'Ten_PV' => $request->TenPV,
                'Ma_BT' => $request->MaBT,
                'Ten_BT' => $request->TenBT,
                'NhuanButPV' => $request->NhuanButPV,
                'NhuanButBTV' => $request->NhuanButBT
            ]
        );
        return redirect()->back()->with('success', 'Cập nhật thành công!');
    }
}

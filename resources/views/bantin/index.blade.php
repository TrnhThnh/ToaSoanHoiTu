@extends('layouts.app')

@section('content')
    <div class="flex justify-between p-6">
        <div class="flex-1 mx-10">
            <div class="container mx-auto mt-6">
                <h1 class="text-3xl font-bold mb-4">Danh Sách Bản Tin - {{ $currentLoaiTin->TenLT }}</h1>
                @if($bantins->isEmpty())
                    <p class="text-red-500">Không có bản tin nào trong loại này.</p>
                @else
                    <ul class="list-none pl-0">
                        @foreach($bantins as $key => $bantin)
                        <a href="{{ route('bantin.show', $bantin->MaBT_XB) }}" class="block text-black-600">
                            <li class="flex mb-4 bg-white rounded-lg shadow-md p-4">
                                @if($bantin->AnhDaiDien_XB)
                                    <img src="{{ asset($bantin->AnhDaiDien_XB) }}" class="w-32 h-32 object-cover rounded-lg mr-4">
                                @endif
                                <div class="flex-1">
                                    <h2 class="text-xl font-semibold">
                                        {{ \Illuminate\Support\Str::limit($bantin->TieuDeBT_XB, 70, '...') }}
                                    </h2>
                                    <p class="text-gray-600">{{ \Illuminate\Support\Str::limit(strip_tags($bantin->NoiDungBT_XB), 150) }}</p>
                                    <p class="text-gray-500 text-sm">Tác giả: {{ $bantin->TenPhongVien_XB ?? 'Chưa có thông tin' }}</p>
                                    <p class="text-sm text-gray-500 mb-2">
                                        <span class="font-bold">
                                            {{ $bantin->loaiTin ? $bantin->loaiTin->chuyenMuc->TenCM : 'Chưa có loại tin' }} - 
                                            {{ $bantin->loaiTin ? $bantin->loaiTin->TenLT : 'Chưa có loại tin' }}
                                        </span> - 
                                        <span>{{ \Carbon\Carbon::parse($bantin->NgayXuatBan)->format('d/m/Y H:i') }}</span>
                                    </p>
                                </div>
                            </li>
                        </a>
                        @if(($key + 1) % 3 == 0)
                        <div class="col-span-3 my-6">
                                    @foreach($quangcaos->where('ViTri', 'Giữa')->where('TrangThai', 'Đang Quảng Cáo')->random(1) as $quangcao)
                                        <a href="{{ $quangcao->URL }}" target="_blank">
                                            <img src="{{ asset($quangcao->AnhQC) }}" 
                                                 alt="{{ $quangcao->TieuDeQC }}" 
                                                 class="w-full h-48 object-cover rounded-lg">
                                        </a>
                                    @endforeach
                                </div>
                        @endif
                        @endforeach
                    </ul>

                    <div class="mt-6">
                        {{ $bantins->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dropdownButton = document.getElementById('dropdownButton');
            var dropdownMenu = document.getElementById('dropdownMenu');
            
            dropdownButton.addEventListener('click', function () {
                dropdownMenu.classList.toggle('hidden');
            });
            window.addEventListener('click', function (e) {
                if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        });
    </script>
@endsection

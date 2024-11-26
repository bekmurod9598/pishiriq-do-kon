<x-layouts.main>

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">MAHSULOT BRENDLARI OYNASI
                    </h5>
                </li>
            </ol>
        </div>

        <div class="row p-2">
             {{-- alert chiqarish uchu --}}
             <x-alert />
            <div class="col-12">
                <div class="card h-auto">
                    <div class="page-titles">
                        <ol class="breadcrumb">
                            <li>
                                <h5 class="bc-title text-primary">
                                    Mavjud brendlar ro'yxati
                                </h5>
                            </li>
                        </ol>
                        <li class="nav-item" role="presentation">
                            <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#basicModal">
                                <i class="fa fa-plus-square"></i>  Brend qo'shish
                            </button>
                        </li>
                    </div>
                    <div class="card-body">
                        <div class="people-list dz-scroll">
                            <div class="table-responsive" id="tabpros">
                                <table class="table table-bordered table-responsive-sm text-center" style="font-size: 14px;">
                                    <thead>
                                        <tr >
                                            <th style="background-color: #87CEEB; color: white;">#</th>
                                            <th style="background-color: #87CEEB; color: white;">Brend nomi</th>
                                            <th style="background-color: #87CEEB; color: white;">Vaqt</th>
                                            <th style="background-color: #87CEEB; color: white;">O'chirish</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($brands as $brand)
                                        <tr>
                                            <td>{{ $brand->id }}</td>
                                            <td>{{ $brand->brand }}</td>
                                            <td>{{ $brand->created_at }}</td>
                                            <td>
                                                <!-- O'chirish tugmasi -->
                                                @if (date("Y-m-d")==date("Y-m-d", strtotime($brand->created_at)))
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $brand->id }}">
                                                        O'chirish
                                                    </button>
                                                     <!-- Modalni komponent sifatida qo'shish -->
                                                    <x-modal modalId="deleteModal{{ $brand->id }}" title="Modelni o'chirish" action="{{ route('brands.update', $brand->id ?? 0) }}" />
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">Hozircha hech qanday brend mavjud emas.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="basicModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yangi brend qo'shish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('brands.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-sm-12">
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id ?? 1}}">
                                <input type="text" class="form-control mb-xl-0 mb-3" name="brand" value="{{ old('brand') }}" placeholder="Mdf">
                            </div>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary light m-r-5" title="Yangi brendni saqlash" type="submit"><i class="fa-check fa-solid fa-check me-1"></i>Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.main>

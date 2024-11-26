<x-layouts.main>

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">YASIN
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
                                    Mahsulotlar ro'yxati
                                </h5>
                            </li>
                        </ol>
                        <li class="nav-item" role="presentation">
                            <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#basicModal">
                                <i class="fa fa-plus-square"></i>  Mahsulot qo'shish
                            </button>
                        </li>
                    </div>
                    <div class="card-body">
                        <div class="people-list dz-scroll">
                            <div class="table-responsive" id="tabpros">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modalni komponent sifatida qo'shish -->
    <x-modal modalId="deleteModal1" title="Turni o'chirish" action="{{ route('types.update', $type->id ?? 0) }}" />

    <div class="modal fade" id="basicModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                    <button type="button" class="btn btn-primary light m-r-5" title="Yangi turni saqlash" type="submit"><i class="fa-check fa-solid fa-check me-1"></i>Saqlash</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.main>

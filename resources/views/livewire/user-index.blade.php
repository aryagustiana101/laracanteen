<div class="card shadow-sm">
    <div class="card-header pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Tabel Akun Pengguna</span>
        </h3>
    </div>
    <div class="card-body py-3">
        <div class="row mt-5 justify-content-between">
            <div class="col-md-3 mb-3">
                <select wire:model="limit" class="form-select form-select-solid w-auto d-inline me-5 mb-3">
                    @for ($i = 1; $i <= 10; $i++) @if ($i==1) <option value="{{ $i * 10 }}" selected>
                        {{ $i * 10 }}
                        </option>
                        @else
                        <option value="{{ $i * 10 }}">
                            {{ $i * 10 }}
                        </option>
                        @endif
                        @endfor
                </select>
                <select wire:model="sequence" class="form-select form-select-solid w-auto d-inline mb-3">
                    <option value="desc" selected>Tebaru</option>
                    <option value="asc">Terlama</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <input wire:model="search" type="text" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <div class="row">
            <div class="table-responsive mt-5">
                <table class="table align-middle gs-0 gy-4 table-row-bordered table-row-gray-100 border ounded">
                    <caption>{{ $users->links() }}</caption>
                    <thead>
                        <tr class="fw-bolder text-muted bg-light border-bottom">
                            <th class="ps-4 min-w-50px border-end">No.</th>
                            <th class="min-w-275px">Pengguna</th>
                            <th class="min-w-150px">Nomor Telepon</th>
                            <th class="min-w-125px">Jenis Kelamin</th>
                            <th class="min-w-125px">Role</th>
                            <th class="min-w-35px">Status</th>
                            <th class="min-w-175px text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        @php
                        $id = '';
                        $name = '';
                        $gender = '';
                        $role = '';
                        if ($user->student ?? false){
                        $id = $user->student->id;
                        $name = $user->student->name;
                        $gender = $user->student->gender;
                        $role = 'Peserta Didik';
                        }
                        if ($user->teacher ?? false){
                        $id = $user->teacher->id;
                        $name = $user->teacher->name;
                        $gender = $user->teacher->gender;
                        $role = 'Guru';
                        }
                        if ($user->seller ?? false){
                        $id = $user->seller->id;
                        $name = $user->seller->name;
                        $gender = $user->seller->gender;
                        $role = 'Penjual';
                        }
                        if ($user->teller ?? false){
                        $id = $user->teller->id;
                        $name = $user->teller->name;
                        $gender = $user->teller->gender;
                        $role = 'Teller';
                        }
                        if ($user->administrator ?? false){
                        $id = $user->administrator->id;
                        $name = $user->administrator->name;
                        $gender = $user->administrator->gender;
                        $role = 'Administrator';
                        }
                        @endphp
                        <tr>
                            <td class="border-end">
                                <p class="text-muted fw-bolder fs-6 text-center">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </p>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-5">
                                        @if ($user->image ?? false)
                                        {{-- <span class="symbol-label bg-light">
                                            <img src="../assets/media/svg/avatars/001-boy.svg"
                                                class="h-75 align-self-end" />
                                        </span> --}}
                                        @else
                                        <div class="symbol-label fs-2 fw-bold text-primary bg-light">{{ $name[0] }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-start flex-column">
                                        <a href="#" class="text-dark fw-bolder text-hover-primary mb-1 fs-6">
                                            {{ $name }}
                                        </a>
                                        <span class="text-muted fw-bold text-muted d-block fs-7">{{ $user->email
                                            }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                    {{ $user->phone_number }}
                                </span>
                            </td>
                            <td>
                                <span class="text-dark fw-bolder d-block mb-1 fs-6">
                                    {{ $gender }}
                                </span>
                            </td>
                            <td>
                                <a href="#" class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">
                                    {{ $role }}
                                </a>
                            </td>
                            <td>
                                @if ($user->active_status)
                                <span class="badge badge-light-primary">Aktif</span>
                                @else
                                <span class="badge badge-light-danger">Non Aktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="#" class="mx-3 badge badge-light-success">
                                    <i class="fa-solid fa-magnifying-glass-plus text-success"></i>
                                </a>
                                <a href="#" class="mx-3 badge badge-light-primary">
                                    <i class="fa-solid fa-user-pen text-primary"></i>
                                </a>
                                <a href="#" class="mx-3 badge badge-light-danger">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<section>
    <header>
        <p class="mt-1 text-sm text-muted">
            {{ __("Update your account's profile information, email address, and profile image.") }}
            <span class="ms-1" data-feather="alert-octagon"></span>
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-4">
        @csrf
        @method('patch')

        <div class="row">
            <!-- Card for Profile Information -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>{{ __('Profile Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Profile Image Column -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <img src="{{ $user->image ? url('storage/' . $user->image) : url('storage/images/default.png') }}"
                                        alt="" class="img-fluid">
                                    <input type="file" name="image" class="form-control mt-2">
                                    @if($errors->has('image'))
                                        <div class="text-danger mt-1">{{ $errors->first('image') }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Profile Fields -->
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" required autofocus>
                                    @if($errors->has('name'))
                                        <div class="text-danger mt-1">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">{{ __('Email') }}</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" required>
                                    @if($errors->has('email'))
                                        <div class="text-danger mt-1">{{ $errors->first('email') }}</div>
                                    @endif

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                        <div class="mt-2">
                                            <p class="text-muted">
                                                {{ __('Your email address is unverified.') }}
                                                <button form="send-verification"
                                                    class="btn btn-link p-0">{{ __('Click here to re-send the verification email.') }}</button>
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group mb-3">
                                    <label for="nip" class="form-label">{{ __('NIP') }}</label>
                                    <input type="text" class="form-control" id="nip" name="nip"
                                        value="{{ old('nip', $user->nip) }}">
                                    @if($errors->has('nip'))
                                        <div class="text-danger mt-1">{{ $errors->first('nip') }}</div>
                                    @endif
                                </div>

                                <div class="form-group mb-3">
                                    <label for="pangkat" class="form-label">{{ __('Pangkat') }}</label>
                                    <select class="form-select" id="pangkat" name="pangkat_id">
                                        <option value="" disabled selected>{{ __('Select Pangkat') }}</option>
                                        @foreach ($pangkats as $pangkat)
                                            <option value="{{ $pangkat->id }}"
                                                {{ $user->pangkat_id == $pangkat->id ? 'selected' : '' }}>
                                                {{ $pangkat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('pangkat_id'))
                                        <div class="text-danger mt-1">{{ $errors->first('pangkat_id') }}</div>
                                    @endif
                                </div>

                                @can('jabatan-atasan')
                                    <div class="form-group mb-3">
                                        <label for="jabatan" class="form-label">{{ __('Jabatan') }}</label>
                                        <input type="text" class="form-control" id="jabatan" name="jabatan"
                                            value="{{ old('jabatan', $user->jabatan) }}">
                                        @if($errors->has('jabatan'))
                                            <div class="text-danger mt-1">{{ $errors->first('unit_kerja') }}</div>
                                        @endif
                                    </div>
                                @endcan

                                <div class="form-group mb-3">
                                    <label for="unit_kerja" class="form-label">{{ __('Unit Kerja') }}</label>
                                    <input type="text" class="form-control" id="unit_kerja" name="unit_kerja"
                                        value="{{ old('unit_kerja', $user->unit_kerja) }}">
                                    @if($errors->has('unit_kerja'))
                                        <div class="text-danger mt-1">{{ $errors->first('unit_kerja') }}</div>
                                    @endif
                                </div>

                                <div class="form-group mb-3">
                                    <label for="tmt_jabatan" class="form-label">{{ __('TMT Jabatan') }}</label>
                                    <input type="date" class="form-control" id="tmt_jabatan" name="tmt_jabatan"
                                        value="{{ old('tmt_jabatan', $user->tmt_jabatan) }}">
                                    @if($errors->has('tmt_jabatan'))
                                        <div class="text-danger mt-1">{{ $errors->first('tmt_jabatan') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card for Atasan Information -->
        @can('select-atasan')
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>{{ __('Atasan Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <button type="button" class="btn btn-outline-secondary me-1 mb-1" id="selectAtasanBtn">
                                    {{ __('Select Atasan') }}
                                </button>
                            </div>

                            <div class="form-group mb-3">
                                <input type="hidden" name="atasan_id" id="atasan_id_input"
                                    value="{{ old('atasan_id', $user->atasan_id) }}">

                                <label for="atasan_name" class="form-label">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="atasan_name"
                                    value="{{ old('atasan_name', optional($user->atasan)->name) }}" disabled>
                            </div>

                            <div class="form-group mb-3">
                                <label for="atasan_pangkat" class="form-label">{{ __('Pangkat') }}</label>
                                <input type="text" class="form-control" id="atasan_pangkat"
                                    value="{{ old('atasan_pangkat', optional($user->atasan)->pangkat ? $user->atasan->pangkat->name : '') }}"
                                    disabled>
                            </div>

                            <div class="form-group mb-3">
                                <label for="atasan_unit_kerja" class="form-label">{{ __('Unit Kerja') }}</label>
                                <input type="text" class="form-control" id="atasan_unit_kerja"
                                    value="{{ old('atasan_unit_kerja', optional($user->atasan)->unit_kerja) }}" disabled>
                            </div>

                            <div class="form-group mb-3">
                                <label for="atasan_jabatan" class="form-label">{{ __('Jabatan') }}</label>
                                <input type="text" class="form-control" id="atasan_jabatan"
                                    value="{{ old('atasan_jabatan', optional($user->atasan)->jabatan) }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        <!-- Save Button -->
        <div class="d-flex align-items-center gap-2">
            <button type="submit" class="btn btn-outline-secondary me-1 mb-1">{{ __('Save') }}</button>
        </div>
    </form>
</section>

{{-- ALERT ERROR GLOBAL (opsional) --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- NAMA --}}
<div class="mb-3">
    <label>Nama</label>
    <input type="text"
           name="name"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $user->name ?? '') }}">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- EMAIL --}}
<div class="mb-3">
    <label>Email</label>
    <input type="email"
           name="email"
           class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $user->email ?? '') }}">
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- PASSWORD --}}
<div class="mb-3">
    <label>Password</label>
    <input type="password"
           name="password"
           class="form-control @error('password') is-invalid @enderror">
    @isset($user)
        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
    @endisset
    @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- NIM --}}
<div class="mb-3">
    <label>NIM</label>
    <input type="text"
           name="nim"
           class="form-control @error('nim') is-invalid @enderror"
           value="{{ old('nim', $user->nim ?? '') }}">
    @error('nim')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- SEMESTER --}}
<div class="mb-3">
    <label>Semester</label>
    <input type="text"
           name="semester"
           class="form-control @error('semester') is-invalid @enderror"
           value="{{ old('semester', $user->semester ?? '') }}">
    @error('semester')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- ROLE --}}
<div class="mb-3">
    <label>Role</label>
    <select name="role"
            class="form-control @error('role') is-invalid @enderror">
        <option value="">-- Pilih Role --</option>
        @foreach (['admin_ruangan', 'admin_barang', 'user', 'dosen'] as $role)
            <option value="{{ $role }}"
                {{ old('role', $user->role ?? '') == $role ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $role)) }}
            </option>
        @endforeach
    </select>
    @error('role')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- FAKULTAS --}}
<div class="mb-3">
    <label>Fakultas</label>
    <select name="faculty_id"
            id="faculty_id"
            class="form-control @error('faculty_id') is-invalid @enderror">
        <option value="">-- Pilih Fakultas --</option>
        @foreach ($faculties as $faculty)
            <option value="{{ $faculty->id }}"
                {{ old('faculty_id', $user->faculty_id ?? '') == $faculty->id ? 'selected' : '' }}>
                {{ $faculty->name }}
            </option>
        @endforeach
    </select>
    @error('faculty_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- PROGRAM STUDI --}}
<div class="mb-3">
    <label>Program Studi</label>
    <select name="study_program_id"
            id="study_program_id"
            class="form-control @error('study_program_id') is-invalid @enderror">
        <option value="">-- Pilih Program Studi --</option>
        {{-- opsi akan diisi via JavaScript --}}
    </select>
    @error('study_program_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- SCRIPT LANGSUNG DI SINI (tanpa @push) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // semua prodi dari PHP -> JS
    const allStudyPrograms = @json($studyPrograms);
    const facultySelect = document.getElementById('faculty_id');
    const prodiSelect   = document.getElementById('study_program_id');

    // untuk edit / saat validasi gagal, supaya prodi lama tetap terpilih
    let selectedProdiId = '{{ old('study_program_id', $user->study_program_id ?? '') }}';

    function fillProdiOptions() {
        const facultyId = facultySelect.value;

        // reset options
        prodiSelect.innerHTML = '<option value="">-- Pilih Program Studi --</option>';

        if (!facultyId) {
            return; // kalau belum pilih fakultas, jangan tampilkan apa-apa
        }

        // filter prodi berdasarkan faculty_id
        let filtered = allStudyPrograms.filter(sp => String(sp.faculty_id) === String(facultyId));

        // kalau tidak ada yang cocok, tampilkan semua (biar tidak kosong sama sekali)
        if (filtered.length === 0) {
            filtered = allStudyPrograms;
        }

        filtered.forEach(function (sp) {
            const opt = document.createElement('option');
            opt.value = sp.id;
            opt.textContent = sp.name;

            if (String(sp.id) === String(selectedProdiId)) {
                opt.selected = true;
            }

            prodiSelect.appendChild(opt);
        });
    }

    // isi saat halaman pertama kali dibuka
    fillProdiOptions();

    // isi ulang setiap kali fakultas berubah
    facultySelect.addEventListener('change', function () {
        selectedProdiId = ''; // reset pilihan prodi
        fillProdiOptions();
    });
});
</script>

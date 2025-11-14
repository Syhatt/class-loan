<div class="mb-3">
    <label>Nama</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}">
</div>

<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}">
</div>

<div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control">
    @if (isset($user))
        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
    @endif
</div>

<div class="mb-3">
    <label>NIM</label>
    <input type="text" name="nim" class="form-control" value="{{ old('nim', $user->nim ?? '') }}">
</div>

<div class="mb-3">
    <label>Semester</label>
    <input type="text" name="semester" class="form-control" value="{{ old('semester', $user->semester ?? '') }}">
</div>

<div class="mb-3">
    <label>Role</label>
    <select name="role" class="form-control">
        @foreach (['superadmin', 'admin_fakultas', 'user', 'dosen'] as $role)
            <option value="{{ $role }}" {{ old('role', $user->role ?? '') == $role ? 'selected' : '' }}>
                {{ ucfirst($role) }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Fakultas</label>
    <select name="faculty_id" class="form-control">
        @foreach ($faculties as $faculty)
            <option value="{{ $faculty->id }}"
                {{ old('faculty_id', $user->faculty_id ?? '') == $faculty->id ? 'selected' : '' }}>
                {{ $faculty->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Program Studi</label>
    <select name="study_program_id" class="form-control">
        @foreach ($studyPrograms as $sp)
            <option value="{{ $sp->id }}"
                {{ old('study_program_id', $user->study_program_id ?? '') == $sp->id ? 'selected' : '' }}>
                {{ $sp->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="mb-3 flex justify-end"><button type="button" wire:click="toggleMutations" role="switch" aria-checked="{{ $mutationsEnabled ? 'true' : 'false' }}" class="rounded border px-3 py-2 text-xs font-semibold {{ $mutationsEnabled ? 'border-emerald-300 bg-emerald-50 text-emerald-700' : 'border-slate-300 text-slate-600' }}">{{ $mutationsEnabled ? 'Editing enabled' : 'Enable editing' }}</button></div>
        <h5>Verify student profile</h5>
        <p>Enter your date of birth, review the matching records, and select your profile.</p>
        <input type="date" wire:model.live="dob" class="form-control mb-3">
        @error('dob') <div class="text-danger small">{{ $message }}</div> @enderror
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Name</th>
                        <th>Father</th>
                        <th>Gender</th>
                        <th>Mobile</th>
                        <th>Date of birth</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($matches as $student)
                    <tr>
                        <td><input type="radio" wire:model="selectedStudentId" value="{{ $student->id }}"></td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->fname ?: '-' }}</td>
                        <td>{{ $student->gender ?: '-' }}</td>
                        <td>{{ $student->mobile_1 ?: '-' }}</td>
                        <td>{{ $student->dob?->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-muted">Enter your date of birth to find matching StudentDB records.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @error('selectedStudentId') <div class="text-danger small">{{ $message }}</div> @enderror
        <button wire:click="verify" class="btn btn-primary">Verify student profile</button>
    </div>
</div>
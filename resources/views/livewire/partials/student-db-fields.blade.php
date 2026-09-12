@php($input = 'w-full rounded border-slate-300 px-2.5 py-1.5 text-xs focus:border-cyan-500 focus:ring-cyan-500')
<div class="grid gap-4 lg:grid-cols-3">
    <section class="rounded border border-slate-200 bg-white p-4 shadow-sm lg:col-span-2">
        <h2 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-700">Identity</h2>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4"><label class="lg:col-span-2"><span
                    class="field-label">Full name *</span><input wire:model="name"
                    class="{{ $input }}">@error('name')<span class="field-error">{{ $message
                    }}</span>@enderror</label><label><span class="field-label">Gender</span><select wire:model="gender"
                    class="{{ $input }}">
                    <option value="">Select</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                </select></label><label><span class="field-label">Date of birth</span><input wire:model="dob"
                    type="date" class="{{ $input }}">@error('dob')<span class="field-error">{{ $message
                    }}</span>@enderror</label><label><span class="field-label">Father / guardian</span><input
                    wire:model="fname" class="{{ $input }}"></label><label><span class="field-label">Mother</span><input
                    wire:model="mname" class="{{ $input }}"></label><label><span class="field-label">Aadhaar
                    ID</span><input wire:model="aadhaar_id" class="{{ $input }}"></label><label><span
                    class="field-label">PEN ID</span><input wire:model="pen_id"
                    class="{{ $input }}"></label><label><span class="field-label">APAAR ID</span><input
                    wire:model="apper_id" class="{{ $input }}"></label></div>
    </section>
    <section class="rounded border border-slate-200 bg-white p-4 shadow-sm">
        <h2 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-700">Images</h2>
        <div class="space-y-3">@foreach([['dpImage','dp_img_ref','Profile
            photo'],['dobCertificate','dob_cert_img_ref','DOB certificate'],['aadhaarImage','aadhaar_img_ref','Aadhaar
            card']] as [$upload, $ref, $label])<label class="block"><span class="field-label">{{ $label
                    }}</span>@if($this->{$ref})<a href="{{ Storage::disk('public')->url($this->{$ref}) }}"
                    target="_blank" class="mb-1 block text-[10px] text-cyan-700">View current image</a>@endif<input
                    wire:model="{{ $upload }}" type="file" accept="image/*" class="{{ $input }}">@error($upload)<span
                    class="field-error">{{ $message }}</span>@enderror</label>@endforeach</div>
    </section>
    <section class="rounded border border-slate-200 bg-white p-4 shadow-sm lg:col-span-2">
        <h2 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-700">Address & contact</h2>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4"><label><span class="field-label">Village</span><input
                    wire:model="village" class="{{ $input }}"></label><label><span class="field-label">Post
                    office</span><input wire:model="post_office" class="{{ $input }}"></label><label><span
                    class="field-label">Police station</span><input wire:model="police_station"
                    class="{{ $input }}"></label><label><span class="field-label">District</span><input
                    wire:model="district" class="{{ $input }}"></label><label><span
                    class="field-label">Block</span><input wire:model="block" class="{{ $input }}"></label><label><span
                    class="field-label">Pincode</span><input wire:model="pincode"
                    class="{{ $input }}"></label><label><span class="field-label">State</span><input wire:model="state"
                    class="{{ $input }}"></label><label><span class="field-label">Nationality</span><input
                    wire:model="nationality" class="{{ $input }}"></label><label><span class="field-label">Mobile
                    1</span><input wire:model="mobile_1" class="{{ $input }}"></label><label><span
                    class="field-label">Mobile 2</span><input wire:model="mobile_2" class="{{ $input }}"></label><label
                class="sm:col-span-2"><span class="field-label">Email</span><input wire:model="email" type="email"
                    class="{{ $input }}">@error('email')<span class="field-error">{{ $message }}</span>@enderror</label>
        </div>
    </section>
    <section class="rounded border border-slate-200 bg-white p-4 shadow-sm">
        <h2 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-700">Admission metadata</h2>
        <div class="grid gap-3 sm:grid-cols-2"><label><span class="field-label">Shreny ID</span><input
                    wire:model="adm_shreny_id" type="number" class="{{ $input }}"></label><label><span
                    class="field-label">Section ID</span><input wire:model="adm_section_id" type="number"
                    class="{{ $input }}"></label><label><span class="field-label">Order</span><input
                    wire:model="order_id" type="number" class="{{ $input }}"></label><label><span
                    class="field-label">School ID</span><input wire:model="school_id" type="number"
                    class="{{ $input }}"></label><label><span class="field-label">Session ID</span><input
                    wire:model="session_id" type="number" class="{{ $input }}"></label><label
                class="flex items-center gap-2 pt-5 text-xs"><input wire:model="is_active" type="checkbox"
                    class="rounded border-slate-300 text-cyan-600"> Active</label></div>
    </section>
    <section class="rounded border border-slate-200 bg-white p-4 shadow-sm lg:col-span-3"><label><span
                class="field-label">Remarks</span><textarea wire:model="remarks" rows="2"
                class="{{ $input }}"></textarea></label></section>
</div>
<style>
    .field-label {
        display: block;
        margin-bottom: .25rem;
        font-size: .7rem;
        font-weight: 600;
        color: #475569
    }

    .field-error {
        display: block;
        margin-top: .25rem;
        font-size: .65rem;
        color: #e11d48
    }
</style>
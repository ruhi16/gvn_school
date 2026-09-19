<?php

namespace App\Livewire;

use App\Livewire\Concerns\UsesActiveSchoolSession;
use App\Models\StudentDb;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class StudentDbForm extends Component
{
    use WithFileUploads;
    use UsesActiveSchoolSession;

    public ?int $studentId = null;
    public string $name = '', $gender = '', $fname = '', $mname = '', $dob = '';
    public string $aadhaar_id = '', $pen_id = '', $apper_id = '';
    public string $village = '', $post_office = '', $police_station = '', $district = '', $block = '', $pincode = '';
    public string $state = 'West Bengal', $nationality = 'Indian';
    public string $mobile_1 = '', $mobile_2 = '', $email = '';
    public ?int $adm_shreny_id = null, $adm_section_id = null, $order_id = null, $school_id = null, $session_id = null;
    public string $remarks = '';
    public bool $is_active = true;
    public $dpImage, $dobCertificate, $aadhaarImage;
    public ?string $dp_img_ref = null, $dob_cert_img_ref = null, $aadhaar_img_ref = null;

    public function mount(?int $studentId = null): void
    {
        if ($studentId === null) {
            return;
        }

        $student = StudentDb::findOrFail($studentId);
        $this->studentId = $student->id;

        foreach ([
            'name',
            'gender',
            'fname',
            'mname',
            'aadhaar_id',
            'pen_id',
            'apper_id',
            'village',
            'post_office',
            'police_station',
            'district',
            'block',
            'pincode',
            'state',
            'nationality',
            'mobile_1',
            'mobile_2',
            'email',
            'remarks',
            'is_active',
            'adm_shreny_id',
            'adm_section_id',
            'order_id',
            'school_id',
            'session_id',
            'dp_img_ref',
            'dob_cert_img_ref',
            'aadhaar_img_ref',
        ] as $field) {
            $this->{$field} = $student->{$field} ?? (in_array($field, ['is_active']) ? true : (in_array($field, ['adm_shreny_id', 'adm_section_id', 'order_id', 'school_id', 'session_id']) ? null : ''));
        }

        $this->dob = $student->getRawOriginal('dob') ?? '';
    }

    public function save(): void
    {
        if (!$this->canMutate()) return;
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'in:Male,Female,Other'],
            'fname' => ['nullable', 'string', 'max:255'],
            'mname' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'aadhaar_id' => ['nullable', 'string', 'max:255'],
            'pen_id' => ['nullable', 'string', 'max:255'],
            'apper_id' => ['nullable', 'string', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'post_office' => ['nullable', 'string', 'max:255'],
            'police_station' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'block' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'mobile_1' => ['nullable', 'string', 'max:255'],
            'mobile_2' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'adm_shreny_id' => ['nullable', 'integer'],
            'adm_section_id' => ['nullable', 'integer'],
            'order_id' => ['nullable', 'integer'],
            'school_id' => ['nullable', 'integer'],
            'session_id' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
            'remarks' => ['nullable', 'string', 'max:255'],
            'dpImage' => ['nullable', 'image', 'max:5120'],
            'dobCertificate' => ['nullable', 'image', 'max:5120'],
            'aadhaarImage' => ['nullable', 'image', 'max:5120'],
        ]);

        $student = $this->studentId
            ? tap(StudentDb::findOrFail($this->studentId))->update($data)
            : StudentDb::create($data);
        $folder = 'student-dbs/' . $student->id;
        $this->storeImage($student, 'dpImage', 'dp_img_ref', $folder . '/dp');
        $this->storeImage($student, 'dobCertificate', 'dob_cert_img_ref', $folder . '/dob');
        $this->storeImage($student, 'aadhaarImage', 'aadhaar_img_ref', $folder . '/aadhaar');

        session()->flash('success', $this->studentId ? 'Student updated.' : 'Student admitted.');
        $this->redirectRoute('admin.students');
    }

    private function storeImage(StudentDb $student, string $upload, string $column, string $folder): void
    {
        if (!$this->{$upload}) {
            return;
        }

        if ($student->{$column}) {
            Storage::disk('public')->delete($student->{$column});
        }

        $student->{$column} = $this->{$upload}->store($folder, 'public');
        $student->save();
    }

    public function render()
    {
        return view('livewire.student-db-form');
    }
}
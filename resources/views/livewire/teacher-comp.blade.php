@include('livewire.partials.catalog-crud', [
    'title' => 'Teachers',
    'singular' => 'teacher',
    'type' => 'teacher',
    'fields' => [
        'name' => 'Name',
        'email' => 'Email',
        'mobile' => 'Mobile',
        'high_qual' => 'Highest qualification',
        'high_qual_subject' => 'Qualification subject',
        'prof_qual' => 'Professional qualification',
        'prof_qual_subject' =>
            'Professional subject',
        'desc' => 'Description',
        'vill' => 'Village / town',
        'post_office' => 'Post office',
        'police_station' => 'Police station',
        'district' => 'District',
        'block' => 'Block',
        'pincode' => 'Pincode',
        'order_id'
        => 'Order',
        'school_id' => 'School ID',
        'session_id' => 'Session ID',
        'is_active' => 'Status',
        'remarks' => 'Remarks'
    ],
    'required' => ['name']
])
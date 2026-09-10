@include('livewire.partials.catalog-crud', ['title' => 'Sessions', 'singular' => 'session', 'type' => 'session',
'fields' => ['name' => 'Name', 'start_date' => 'Start date', 'end_date' => 'End date', 'status' => 'Status', 'order_id'
=> 'Order', 'school_id' => 'School ID', 'session_id' => 'Parent session ID', 'is_active' => 'Status', 'remarks' =>
'Remarks'], 'required' => ['name']])
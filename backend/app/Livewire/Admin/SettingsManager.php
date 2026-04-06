<?php

namespace App\Livewire\Admin;

use App\Models\OfficeSetting;
use Livewire\Component;
use Livewire\WithFileUploads;

class SettingsManager extends Component
{
    use WithFileUploads;

    public $showSuccess = false;
    public $phone, $email;
    public $address, $address_am, $address_or;
    public $working_hours, $working_hours_am, $working_hours_or;
    public $map_url, $facebook_url, $twitter_url, $linkedin_url, $youtube_url;
    public $header_logo, $footer_logo;
    public $current_header_logo, $current_footer_logo;

    protected $rules = [
        'phone' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string',
        'address_am' => 'nullable|string',
        'address_or' => 'nullable|string',
        'working_hours' => 'nullable|string',
        'working_hours_am' => 'nullable|string',
        'working_hours_or' => 'nullable|string',
        'map_url' => 'nullable|string',
        'facebook_url' => 'nullable|url|max:1024',
        'twitter_url' => 'nullable|url|max:1024',
        'linkedin_url' => 'nullable|url|max:1024',
        'youtube_url' => 'nullable|url|max:1024',
        'header_logo' => 'nullable|image|max:2048',
        'footer_logo' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $s = OfficeSetting::first();
        if ($s) {
            $fields = [
                'phone',
                'email',
                'address',
                'address_am',
                'address_or',
                'working_hours',
                'working_hours_am',
                'working_hours_or',
                'map_url',
                'facebook_url',
                'twitter_url',
                'linkedin_url',
                'youtube_url',
                'header_logo' => 'current_header_logo',
                'footer_logo' => 'current_footer_logo'
            ];
            foreach ($fields as $key => $val) {
                $f = is_numeric($key) ? $val : $key;
                $target = is_numeric($key) ? $val : $val;
                if (isset($s->$f)) {
                    $this->$target = $s->$f;
                }
            }
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'address_am' => $this->address_am,
            'address_or' => $this->address_or,
            'working_hours' => $this->working_hours,
            'working_hours_am' => $this->working_hours_am,
            'working_hours_or' => $this->working_hours_or,
            'map_url' => $this->map_url,
            'facebook_url' => $this->facebook_url,
            'twitter_url' => $this->twitter_url,
            'linkedin_url' => $this->linkedin_url,
            'youtube_url' => $this->youtube_url,
        ];

        if ($this->header_logo && !is_string($this->header_logo)) {
            $data['header_logo'] = $this->header_logo->store('logos', 'public');
        }

        if ($this->footer_logo && !is_string($this->footer_logo)) {
            $data['footer_logo'] = $this->footer_logo->store('logos', 'public');
        }

        OfficeSetting::updateOrCreate(['id' => 1], $data);
        $this->dispatch('notify', message: 'Settings updated successfully!', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.settings-manager');
    }
}

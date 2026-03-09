<?php

namespace App\Livewire\Admin;

use App\Models\OfficeSetting;
use Livewire\Component;

class SettingsManager extends Component
{
    public $showSuccess = false;
    public $phone, $email;
    public $address, $address_am, $address_or;
    public $working_hours, $working_hours_am, $working_hours_or;
    public $map_url, $facebook_url, $twitter_url, $linkedin_url, $youtube_url;

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
                'youtube_url'
            ];
            foreach ($fields as $f) {
                if (isset($s->$f)) {
                    $this->$f = $s->$f;
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

        OfficeSetting::updateOrCreate(['id' => 1], $data);
        $this->showSuccess = true;
    }

    public function render()
    {
        return view('livewire.admin.settings-manager');
    }
}

<?php

if (!function_exists('translate')) {
    function translate($key) {
        return $key;
    }
}

if (!function_exists('single_price')) {
    function single_price($price) {
        return "৳" . number_format((float)$price, 2);
    }
}

if (!function_exists('addon_is_activated')) {
    function addon_is_activated($addon) {
        if ($addon === 'affiliate_system') {
            if (class_exists('App\Models\FeatureActivation')) {
                try {
                    $feature = \App\Models\FeatureActivation::where('name', 'affiliate')->first();
                    return $feature && $feature->status == '1';
                } catch (\Exception $e) {}
            }
            return true;
        }
        return false;
    }
}

if (!function_exists('flash')) {
    function flash($message) {
        session()->flash('success', $message);
        return new class {
            public function success() { return $this; }
            public function error() { 
                session()->flash('error', session()->get('success'));
                return $this; 
            }
            public function warning() { 
                session()->flash('warning', session()->get('success'));
                return $this; 
            }
        };
    }
}

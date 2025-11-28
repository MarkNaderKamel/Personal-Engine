<?php

namespace App\Controllers;

use App\Core\Security;

class WeatherController
{
    public function index()
    {
        if (!Security::isAuthenticated()) {
            header('Location: /login');
            exit;
        }
        
        $data = [
            'weather' => $this->getWeatherData(),
            'forecast' => $this->getForecastData()
        ];
        
        include __DIR__ . '/../Views/modules/weather/index.php';
    }
    
    private function getWeatherData()
    {
        return [
            'location' => 'Your Location',
            'temp' => 72,
            'temp_min' => 65,
            'temp_max' => 78,
            'feels_like' => 74,
            'humidity' => 45,
            'wind_speed' => 8,
            'condition' => 'Partly Cloudy',
            'icon' => 'cloud-sun',
            'description' => 'Partly cloudy skies with a gentle breeze',
            'uv_index' => 5,
            'visibility' => 10,
            'pressure' => 1015
        ];
    }
    
    private function getForecastData()
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $icons = ['sun', 'cloud-sun', 'cloud', 'cloud-rain', 'sun', 'cloud-sun', 'sun'];
        $forecast = [];
        
        $today = date('w');
        for ($i = 0; $i < 7; $i++) {
            $dayIndex = ($today + $i) % 7;
            $forecast[] = [
                'day' => $days[$dayIndex],
                'date' => date('M d', strtotime("+$i days")),
                'icon' => $icons[$i],
                'high' => rand(70, 85),
                'low' => rand(55, 68),
                'condition' => $this->getConditionFromIcon($icons[$i])
            ];
        }
        
        return $forecast;
    }
    
    private function getConditionFromIcon($icon)
    {
        $conditions = [
            'sun' => 'Sunny',
            'cloud-sun' => 'Partly Cloudy',
            'cloud' => 'Cloudy',
            'cloud-rain' => 'Rainy',
            'cloud-lightning' => 'Thunderstorm',
            'snow' => 'Snow'
        ];
        
        return $conditions[$icon] ?? 'Clear';
    }
}

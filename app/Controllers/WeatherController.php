<?php

namespace App\Controllers;

use App\Core\Security;

class WeatherController
{
    private $cacheFile = '/tmp/weather_cache.json';
    private $cacheExpiry = 1800;
    
    public function index()
    {
        if (!Security::isAuthenticated()) {
            header('Location: /login');
            exit;
        }
        
        $weatherData = $this->fetchWeatherData();
        
        $data = [
            'weather' => $weatherData['current'],
            'forecast' => $weatherData['forecast'],
            'isLive' => $weatherData['isLive'],
            'lastUpdated' => $weatherData['lastUpdated']
        ];
        
        include __DIR__ . '/../Views/modules/weather/index.php';
    }
    
    private function fetchWeatherData()
    {
        if (file_exists($this->cacheFile)) {
            $cacheContent = @file_get_contents($this->cacheFile);
            if ($cacheContent !== false) {
                $cache = json_decode($cacheContent, true);
                if ($cache && isset($cache['timestamp']) && isset($cache['data']) && 
                    (time() - $cache['timestamp']) < $this->cacheExpiry) {
                    return $cache['data'];
                }
            }
        }
        
        $weatherData = $this->fetchFromWttrIn();
        
        if ($weatherData['isLive']) {
            $cacheData = [
                'timestamp' => time(),
                'data' => $weatherData
            ];
            @file_put_contents($this->cacheFile, json_encode($cacheData));
        }
        
        return $weatherData;
    }
    
    private function fetchFromWttrIn()
    {
        $url = 'https://wttr.in/?format=j1';
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'header' => 'User-Agent: Life Atlas/1.0',
                'ignore_errors' => true
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            return $this->getFallbackData();
        }
        
        $data = @json_decode($response, true);
        
        if (!is_array($data) || 
            !isset($data['current_condition']) || 
            !is_array($data['current_condition']) || 
            empty($data['current_condition'][0])) {
            return $this->getFallbackData();
        }
        
        $current = $data['current_condition'][0];
        
        if (!isset($current['temp_C']) || !is_numeric($current['temp_C'])) {
            return $this->getFallbackData();
        }
        
        $location = isset($data['nearest_area'][0]) ? $data['nearest_area'][0] : [];
        
        $locationName = '';
        if (isset($location['areaName'][0]['value']) && is_string($location['areaName'][0]['value'])) {
            $locationName = $location['areaName'][0]['value'];
        }
        if (isset($location['region'][0]['value']) && is_string($location['region'][0]['value'])) {
            $locationName .= ($locationName ? ', ' : '') . $location['region'][0]['value'];
        }
        if (empty($locationName)) {
            $locationName = 'Your Location';
        }
        
        $iconMap = [
            'Sunny' => 'sun',
            'Clear' => 'sun',
            'Partly cloudy' => 'cloud-sun',
            'Partly Cloudy' => 'cloud-sun',
            'Cloudy' => 'cloud',
            'Overcast' => 'clouds',
            'Mist' => 'cloud-haze',
            'Fog' => 'cloud-fog',
            'Light rain' => 'cloud-drizzle',
            'Patchy rain' => 'cloud-drizzle',
            'Moderate rain' => 'cloud-rain',
            'Rain' => 'cloud-rain',
            'Heavy rain' => 'cloud-rain-heavy',
            'Thunderstorm' => 'cloud-lightning-rain',
            'Snow' => 'snow',
            'Light snow' => 'snow',
            'Sleet' => 'cloud-sleet'
        ];
        
        $condition = isset($current['weatherDesc'][0]['value']) ? $current['weatherDesc'][0]['value'] : 'Clear';
        $icon = $this->getIconForCondition($condition, $iconMap);
        
        $forecast = $this->parseForecast($data, $iconMap);
        
        $tempMin = $this->celsiusToFahrenheit($current['temp_C']) - 5;
        $tempMax = $this->celsiusToFahrenheit($current['temp_C']) + 5;
        
        if (isset($data['weather'][0]['mintempC']) && is_numeric($data['weather'][0]['mintempC'])) {
            $tempMin = $this->celsiusToFahrenheit($data['weather'][0]['mintempC']);
        }
        if (isset($data['weather'][0]['maxtempC']) && is_numeric($data['weather'][0]['maxtempC'])) {
            $tempMax = $this->celsiusToFahrenheit($data['weather'][0]['maxtempC']);
        }
        
        $humidity = isset($current['humidity']) && is_numeric($current['humidity']) ? $current['humidity'] : 50;
        $windSpeed = isset($current['windspeedKmph']) && is_numeric($current['windspeedKmph']) ? 
                     round($current['windspeedKmph'] * 0.621371) : 5;
        $feelsLike = isset($current['FeelsLikeC']) && is_numeric($current['FeelsLikeC']) ? 
                     $this->celsiusToFahrenheit($current['FeelsLikeC']) : 
                     $this->celsiusToFahrenheit($current['temp_C']);
        $uvIndex = isset($current['uvIndex']) && is_numeric($current['uvIndex']) ? $current['uvIndex'] : 5;
        $visibility = isset($current['visibility']) && is_numeric($current['visibility']) ? 
                      round($current['visibility'] * 0.621371) : 10;
        $pressure = isset($current['pressure']) && is_numeric($current['pressure']) ? $current['pressure'] : 1015;
        
        return [
            'current' => [
                'location' => $locationName,
                'temp' => $this->celsiusToFahrenheit($current['temp_C']),
                'temp_min' => $tempMin,
                'temp_max' => $tempMax,
                'feels_like' => $feelsLike,
                'humidity' => $humidity,
                'wind_speed' => $windSpeed,
                'condition' => $condition,
                'icon' => $icon,
                'description' => $condition . ' with ' . $humidity . '% humidity',
                'uv_index' => $uvIndex,
                'visibility' => $visibility,
                'pressure' => $pressure
            ],
            'forecast' => $forecast,
            'isLive' => true,
            'lastUpdated' => date('g:i A')
        ];
    }
    
    private function getIconForCondition($condition, $iconMap)
    {
        foreach ($iconMap as $key => $value) {
            if (stripos($condition, $key) !== false) {
                return $value;
            }
        }
        return 'cloud-sun';
    }
    
    private function parseForecast($data, $iconMap)
    {
        $forecast = [];
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        
        if (!isset($data['weather']) || !is_array($data['weather'])) {
            return $this->getDefaultForecast();
        }
        
        foreach ($data['weather'] as $day) {
            if (!isset($day['date']) || !isset($day['maxtempC']) || !isset($day['mintempC'])) {
                continue;
            }
            
            $date = @strtotime($day['date']);
            if ($date === false) {
                continue;
            }
            
            if (!is_numeric($day['maxtempC']) || !is_numeric($day['mintempC'])) {
                continue;
            }
            
            $dayCondition = 'Clear';
            if (isset($day['hourly']) && is_array($day['hourly'])) {
                $midIndex = min(4, count($day['hourly']) - 1);
                if ($midIndex >= 0 && isset($day['hourly'][$midIndex]['weatherDesc'][0]['value'])) {
                    $dayCondition = $day['hourly'][$midIndex]['weatherDesc'][0]['value'];
                }
            }
            
            $dayIcon = $this->getIconForCondition($dayCondition, $iconMap);
            
            $forecast[] = [
                'day' => $days[(int)date('w', $date)],
                'date' => date('M d', $date),
                'icon' => $dayIcon,
                'high' => $this->celsiusToFahrenheit($day['maxtempC']),
                'low' => $this->celsiusToFahrenheit($day['mintempC']),
                'condition' => $dayCondition
            ];
        }
        
        if (empty($forecast)) {
            return $this->getDefaultForecast();
        }
        
        return $forecast;
    }
    
    private function getDefaultForecast()
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $icons = ['sun', 'cloud-sun', 'cloud', 'cloud-rain', 'sun', 'cloud-sun', 'sun'];
        $forecast = [];
        
        $today = (int)date('w');
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
    
    private function celsiusToFahrenheit($celsius)
    {
        if (!is_numeric($celsius)) {
            return 72;
        }
        return (int)round(($celsius * 9/5) + 32);
    }
    
    private function getFallbackData()
    {
        return [
            'current' => [
                'location' => 'Your Location',
                'temp' => 72,
                'temp_min' => 65,
                'temp_max' => 78,
                'feels_like' => 74,
                'humidity' => 45,
                'wind_speed' => 8,
                'condition' => 'Partly Cloudy',
                'icon' => 'cloud-sun',
                'description' => 'Unable to fetch live weather data',
                'uv_index' => 5,
                'visibility' => 10,
                'pressure' => 1015
            ],
            'forecast' => $this->getDefaultForecast(),
            'isLive' => false,
            'lastUpdated' => 'N/A'
        ];
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

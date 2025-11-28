<?php

namespace App\Controllers;

use App\Core\Security;

class NewsController
{
    private $cacheDir = '/tmp/news_cache';
    private $cacheExpiry = 3600;
    
    public function index()
    {
        if (!Security::isAuthenticated()) {
            header('Location: /login');
            exit;
        }
        
        $category = isset($_GET['category']) ? $_GET['category'] : 'general';
        $category = preg_replace('/[^a-z]/', '', strtolower($category));
        
        $validCategories = ['general', 'technology', 'business', 'health', 'science', 'sports', 'entertainment'];
        if (!in_array($category, $validCategories)) {
            $category = 'general';
        }
        
        $newsData = $this->fetchNews($category);
        
        $data = [
            'categories' => $validCategories,
            'currentCategory' => $category,
            'articles' => $newsData['articles'],
            'isLive' => $newsData['isLive'],
            'lastUpdated' => $newsData['lastUpdated']
        ];
        
        include __DIR__ . '/../Views/modules/news/index.php';
    }
    
    private function fetchNews($category)
    {
        if (!is_dir($this->cacheDir)) {
            @mkdir($this->cacheDir, 0755, true);
        }
        
        $cacheFile = $this->cacheDir . '/' . $category . '.json';
        
        if (file_exists($cacheFile)) {
            $cacheContent = @file_get_contents($cacheFile);
            if ($cacheContent !== false) {
                $cache = @json_decode($cacheContent, true);
                if (is_array($cache) && 
                    isset($cache['timestamp']) && 
                    isset($cache['data']) && 
                    isset($cache['data']['articles']) &&
                    !empty($cache['data']['articles']) &&
                    (time() - $cache['timestamp']) < $this->cacheExpiry) {
                    return $cache['data'];
                }
            }
        }
        
        $newsData = $this->fetchFromHackerNews($category);
        
        if ($newsData['isLive'] && !empty($newsData['articles'])) {
            $cacheData = [
                'timestamp' => time(),
                'data' => $newsData
            ];
            @file_put_contents($cacheFile, json_encode($cacheData));
        }
        
        return $newsData;
    }
    
    private function fetchFromHackerNews($category)
    {
        $endpoint = $this->getHNEndpoint($category);
        $url = "https://hacker-news.firebaseio.com/v0/{$endpoint}.json";
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'header' => 'User-Agent: Life Atlas/1.0',
                'ignore_errors' => true
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            return $this->getFallbackNews($category);
        }
        
        $storyIds = @json_decode($response, true);
        
        if (!is_array($storyIds) || empty($storyIds)) {
            return $this->getFallbackNews($category);
        }
        
        $storyIds = array_slice($storyIds, 0, 15);
        $articles = [];
        $successCount = 0;
        $failCount = 0;
        
        foreach ($storyIds as $id) {
            if (!is_numeric($id)) {
                continue;
            }
            
            $storyUrl = "https://hacker-news.firebaseio.com/v0/item/{$id}.json";
            $storyResponse = @file_get_contents($storyUrl, false, $context);
            
            if ($storyResponse === false) {
                $failCount++;
                if ($failCount > 5) {
                    break;
                }
                continue;
            }
            
            $story = @json_decode($storyResponse, true);
            
            if (!is_array($story) || !isset($story['title']) || empty($story['title'])) {
                continue;
            }
            
            if (isset($story['type']) && $story['type'] !== 'story') {
                continue;
            }
            
            $storyTime = isset($story['time']) && is_numeric($story['time']) ? $story['time'] : time();
            $storyUrl = isset($story['url']) && is_string($story['url']) ? $story['url'] : "https://news.ycombinator.com/item?id={$id}";
            $storyScore = isset($story['score']) && is_numeric($story['score']) ? (int)$story['score'] : 0;
            $storyComments = isset($story['descendants']) && is_numeric($story['descendants']) ? (int)$story['descendants'] : 0;
            
            $articles[] = [
                'title' => htmlspecialchars($story['title'], ENT_QUOTES, 'UTF-8'),
                'description' => $this->generateDescription($story, $storyScore, $storyComments),
                'source' => $this->getSource($storyUrl),
                'date' => date('M d, Y', $storyTime),
                'url' => $storyUrl,
                'score' => $storyScore,
                'comments' => $storyComments
            ];
            
            $successCount++;
            
            if ($successCount >= 8) {
                break;
            }
        }
        
        if (empty($articles)) {
            return $this->getFallbackNews($category);
        }
        
        return [
            'articles' => $articles,
            'isLive' => true,
            'lastUpdated' => date('g:i A')
        ];
    }
    
    private function getHNEndpoint($category)
    {
        $endpoints = [
            'general' => 'topstories',
            'technology' => 'topstories',
            'business' => 'topstories',
            'science' => 'topstories',
            'sports' => 'newstories',
            'health' => 'newstories',
            'entertainment' => 'newstories'
        ];
        
        return isset($endpoints[$category]) ? $endpoints[$category] : 'topstories';
    }
    
    private function generateDescription($story, $score, $comments)
    {
        if (isset($story['text']) && is_string($story['text']) && !empty($story['text'])) {
            $text = strip_tags($story['text']);
            $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
            $text = trim($text);
            if (strlen($text) > 200) {
                $text = substr($text, 0, 200) . '...';
            }
            return $text;
        }
        
        $description = "This story has {$score} points";
        if ($comments > 0) {
            $description .= " and {$comments} comments";
        }
        $description .= " on Hacker News.";
        
        if (isset($story['url']) && is_string($story['url'])) {
            $domain = @parse_url($story['url'], PHP_URL_HOST);
            if ($domain) {
                $domain = str_replace('www.', '', $domain);
                $description .= " Source: {$domain}";
            }
        }
        
        return $description;
    }
    
    private function getSource($url)
    {
        if (empty($url) || !is_string($url)) {
            return 'Hacker News';
        }
        
        $domain = @parse_url($url, PHP_URL_HOST);
        if (!$domain) {
            return 'Hacker News';
        }
        
        $domain = str_replace('www.', '', $domain);
        
        $knownSources = [
            'github.com' => 'GitHub',
            'nytimes.com' => 'NY Times',
            'bbc.com' => 'BBC',
            'bbc.co.uk' => 'BBC',
            'techcrunch.com' => 'TechCrunch',
            'wired.com' => 'Wired',
            'arstechnica.com' => 'Ars Technica',
            'theverge.com' => 'The Verge',
            'reuters.com' => 'Reuters',
            'bloomberg.com' => 'Bloomberg',
            'medium.com' => 'Medium',
            'dev.to' => 'DEV Community',
            'news.ycombinator.com' => 'Hacker News'
        ];
        
        if (isset($knownSources[$domain])) {
            return $knownSources[$domain];
        }
        
        $parts = explode('.', $domain);
        if (count($parts) > 0) {
            return ucfirst($parts[0]);
        }
        
        return 'Web';
    }
    
    private function getFallbackNews($category)
    {
        $articles = [
            'general' => [
                [
                    'title' => 'Global Markets Show Strong Recovery Amid Economic Optimism',
                    'description' => 'Stock markets around the world are showing signs of recovery as economic indicators improve and investor confidence grows.',
                    'source' => 'Financial Times',
                    'date' => date('M d, Y', strtotime('-1 hour')),
                    'url' => '#',
                    'score' => 0,
                    'comments' => 0
                ],
                [
                    'title' => 'New Study Reveals Benefits of Remote Work for Productivity',
                    'description' => 'Research shows that remote workers report higher job satisfaction and productivity levels compared to traditional office settings.',
                    'source' => 'Business Insider',
                    'date' => date('M d, Y', strtotime('-3 hours')),
                    'url' => '#',
                    'score' => 0,
                    'comments' => 0
                ],
                [
                    'title' => 'Climate Summit Reaches Historic Agreement on Emissions',
                    'description' => 'World leaders agree on new measures to combat climate change at international summit, setting ambitious targets for 2030.',
                    'source' => 'Reuters',
                    'date' => date('M d, Y', strtotime('-5 hours')),
                    'url' => '#',
                    'score' => 0,
                    'comments' => 0
                ]
            ],
            'technology' => [
                [
                    'title' => 'AI Revolution: New Breakthroughs in Machine Learning',
                    'description' => 'Latest developments in artificial intelligence are transforming industries worldwide with unprecedented capabilities.',
                    'source' => 'TechCrunch',
                    'date' => date('M d, Y', strtotime('-2 hours')),
                    'url' => '#',
                    'score' => 0,
                    'comments' => 0
                ],
                [
                    'title' => 'Quantum Computing Milestone Achieved by Research Team',
                    'description' => 'Scientists demonstrate quantum supremacy with new processor, opening doors for future computing applications.',
                    'source' => 'Wired',
                    'date' => date('M d, Y', strtotime('-4 hours')),
                    'url' => '#',
                    'score' => 0,
                    'comments' => 0
                ]
            ],
            'business' => [
                [
                    'title' => 'Startup Funding Reaches New Heights in 2024',
                    'description' => 'Venture capital investments hit record levels as investors bet on innovation and technology disruption.',
                    'source' => 'Forbes',
                    'date' => date('M d, Y', strtotime('-1 hour')),
                    'url' => '#',
                    'score' => 0,
                    'comments' => 0
                ],
                [
                    'title' => 'E-commerce Growth Continues to Accelerate Globally',
                    'description' => 'Online shopping trends show no signs of slowing down as digital transformation continues across retail.',
                    'source' => 'Bloomberg',
                    'date' => date('M d, Y', strtotime('-6 hours')),
                    'url' => '#',
                    'score' => 0,
                    'comments' => 0
                ]
            ],
            'health' => [
                [
                    'title' => 'New Treatment Shows Promise for Chronic Conditions',
                    'description' => 'Clinical trials reveal breakthrough treatment with minimal side effects and high efficacy rates.',
                    'source' => 'Medical News Today',
                    'date' => date('M d, Y', strtotime('-3 hours')),
                    'url' => '#',
                    'score' => 0,
                    'comments' => 0
                ]
            ],
            'science' => [
                [
                    'title' => 'Space Exploration: Mars Mission Update Released',
                    'description' => 'Latest findings from Mars rover reveal new insights about the red planet potential for past life.',
                    'source' => 'NASA',
                    'date' => date('M d, Y', strtotime('-2 hours')),
                    'url' => '#',
                    'score' => 0,
                    'comments' => 0
                ]
            ],
            'sports' => [
                [
                    'title' => 'Championship Finals Set for This Weekend',
                    'description' => 'Top teams prepare for the ultimate showdown in this season exciting finale match.',
                    'source' => 'ESPN',
                    'date' => date('M d, Y', strtotime('-1 hour')),
                    'url' => '#',
                    'score' => 0,
                    'comments' => 0
                ]
            ],
            'entertainment' => [
                [
                    'title' => 'Award Season: Nominees Announced for Major Awards',
                    'description' => 'This year nominees showcase diverse talent from around the world in multiple categories.',
                    'source' => 'Variety',
                    'date' => date('M d, Y', strtotime('-4 hours')),
                    'url' => '#',
                    'score' => 0,
                    'comments' => 0
                ]
            ]
        ];
        
        $fallbackArticles = isset($articles[$category]) ? $articles[$category] : $articles['general'];
        
        return [
            'articles' => $fallbackArticles,
            'isLive' => false,
            'lastUpdated' => 'Offline'
        ];
    }
}

<?php

namespace App\Controllers;

use App\Core\Security;

class NewsController
{
    public function index()
    {
        if (!Security::isAuthenticated()) {
            header('Location: /login');
            exit;
        }
        
        $category = $_GET['category'] ?? 'general';
        
        $data = [
            'categories' => ['general', 'technology', 'business', 'health', 'science', 'sports', 'entertainment'],
            'currentCategory' => $category,
            'articles' => $this->getNewsArticles($category)
        ];
        
        include __DIR__ . '/../Views/modules/news/index.php';
    }
    
    private function getNewsArticles($category)
    {
        $articles = [
            'general' => [
                [
                    'title' => 'Global Markets Show Strong Recovery',
                    'description' => 'Stock markets around the world are showing signs of recovery as economic indicators improve.',
                    'source' => 'Financial Times',
                    'date' => date('M d, Y', strtotime('-1 hour')),
                    'image' => null,
                    'url' => '#'
                ],
                [
                    'title' => 'New Study Reveals Benefits of Remote Work',
                    'description' => 'Research shows that remote workers report higher job satisfaction and productivity levels.',
                    'source' => 'Business Insider',
                    'date' => date('M d, Y', strtotime('-3 hours')),
                    'image' => null,
                    'url' => '#'
                ],
                [
                    'title' => 'Climate Summit Reaches Historic Agreement',
                    'description' => 'World leaders agree on new measures to combat climate change at international summit.',
                    'source' => 'Reuters',
                    'date' => date('M d, Y', strtotime('-5 hours')),
                    'image' => null,
                    'url' => '#'
                ]
            ],
            'technology' => [
                [
                    'title' => 'AI Revolution: New Breakthroughs in Machine Learning',
                    'description' => 'Latest developments in artificial intelligence are transforming industries worldwide.',
                    'source' => 'TechCrunch',
                    'date' => date('M d, Y', strtotime('-2 hours')),
                    'image' => null,
                    'url' => '#'
                ],
                [
                    'title' => 'Quantum Computing Milestone Achieved',
                    'description' => 'Scientists demonstrate quantum supremacy with new 1000-qubit processor.',
                    'source' => 'Wired',
                    'date' => date('M d, Y', strtotime('-4 hours')),
                    'image' => null,
                    'url' => '#'
                ]
            ],
            'business' => [
                [
                    'title' => 'Startup Funding Reaches New Heights',
                    'description' => 'Venture capital investments hit record levels as investors bet on innovation.',
                    'source' => 'Forbes',
                    'date' => date('M d, Y', strtotime('-1 hour')),
                    'image' => null,
                    'url' => '#'
                ],
                [
                    'title' => 'E-commerce Growth Continues to Accelerate',
                    'description' => 'Online shopping trends show no signs of slowing down as digital transformation continues.',
                    'source' => 'Bloomberg',
                    'date' => date('M d, Y', strtotime('-6 hours')),
                    'image' => null,
                    'url' => '#'
                ]
            ],
            'health' => [
                [
                    'title' => 'New Treatment Shows Promise for Common Disease',
                    'description' => 'Clinical trials reveal breakthrough treatment with minimal side effects.',
                    'source' => 'Medical News Today',
                    'date' => date('M d, Y', strtotime('-3 hours')),
                    'image' => null,
                    'url' => '#'
                ]
            ],
            'science' => [
                [
                    'title' => 'Space Exploration: Mars Mission Update',
                    'description' => 'Latest findings from Mars rover reveal new insights about the red planet.',
                    'source' => 'NASA',
                    'date' => date('M d, Y', strtotime('-2 hours')),
                    'image' => null,
                    'url' => '#'
                ]
            ],
            'sports' => [
                [
                    'title' => 'Championship Finals Set for This Weekend',
                    'description' => 'Top teams prepare for the ultimate showdown in this season finale.',
                    'source' => 'ESPN',
                    'date' => date('M d, Y', strtotime('-1 hour')),
                    'image' => null,
                    'url' => '#'
                ]
            ],
            'entertainment' => [
                [
                    'title' => 'Award Season: Nominees Announced',
                    'description' => 'This year nominees showcase diverse talent from around the world.',
                    'source' => 'Variety',
                    'date' => date('M d, Y', strtotime('-4 hours')),
                    'image' => null,
                    'url' => '#'
                ]
            ]
        ];
        
        return $articles[$category] ?? $articles['general'];
    }
}

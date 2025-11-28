<?php

namespace App\Models;

use App\Core\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    public function getUpcomingBirthdays($userId, $days = 30)
    {
        $sql = "SELECT *, 
                EXTRACT(DOY FROM birthday) as birthday_doy,
                EXTRACT(DOY FROM CURRENT_DATE) as today_doy
                FROM contacts 
                WHERE user_id = :user_id 
                AND birthday IS NOT NULL
                ORDER BY birthday_doy";
        $contacts = $this->db->fetchAll($sql, ['user_id' => $userId]);
        
        $upcoming = [];
        $currentDoy = date('z') + 1;
        
        foreach ($contacts as $contact) {
            $birthdayDoy = $contact['birthday_doy'];
            $daysUntil = $birthdayDoy - $currentDoy;
            
            if ($daysUntil < 0) {
                $daysUntil += 365;
            }
            
            if ($daysUntil <= $days) {
                $contact['days_until'] = $daysUntil;
                $upcoming[] = $contact;
            }
        }
        
        return $upcoming;
    }

    public function searchContacts($userId, $query)
    {
        $sql = "SELECT * FROM contacts 
                WHERE user_id = :user_id 
                AND (full_name LIKE :query OR email LIKE :query OR phone LIKE :query)
                ORDER BY full_name ASC";
        return $this->db->fetchAll($sql, [
            'user_id' => $userId,
            'query' => "%{$query}%"
        ]);
    }
}

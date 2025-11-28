<?php

namespace App\Models;

use App\Core\Model;

class Birthday extends Model
{
    protected $table = 'birthdays';

    public function findByUserId($userId, $orderBy = null, $limit = null)
    {
        $sql = "SELECT *, 
             EXTRACT(MONTH FROM birth_date) as birth_month,
             EXTRACT(DAY FROM birth_date) as birth_day,
             CASE 
                WHEN EXTRACT(MONTH FROM birth_date) = EXTRACT(MONTH FROM CURRENT_DATE) 
                     AND EXTRACT(DAY FROM birth_date) >= EXTRACT(DAY FROM CURRENT_DATE)
                THEN EXTRACT(DAY FROM birth_date) - EXTRACT(DAY FROM CURRENT_DATE)
                WHEN EXTRACT(MONTH FROM birth_date) = EXTRACT(MONTH FROM CURRENT_DATE) + 1
                THEN (30 - EXTRACT(DAY FROM CURRENT_DATE)) + EXTRACT(DAY FROM birth_date)
                ELSE 365
             END as days_until
             FROM {$this->table} WHERE user_id = :user_id ORDER BY birth_month, birth_day";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function findById($id)
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (user_id, person_name, birth_date, relationship, reminder_days, gift_ideas, notes)
                VALUES (:user_id, :person_name, :birth_date, :relationship, :reminder_days, :gift_ideas, :notes)";
        return $this->db->query($sql, $data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET person_name = :person_name, birth_date = :birth_date,
                relationship = :relationship, reminder_days = :reminder_days, 
                gift_ideas = :gift_ideas, notes = :notes WHERE id = :id";
        return $this->db->query($sql, $data);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function getUpcoming($userId, $days = 30)
    {
        return $this->db->fetchAll(
            "SELECT *,
             DATE_PART('year', AGE(CURRENT_DATE, birth_date)) + 1 as upcoming_age,
             CASE 
                WHEN TO_CHAR(birth_date, 'MM-DD') >= TO_CHAR(CURRENT_DATE, 'MM-DD')
                THEN TO_DATE(EXTRACT(YEAR FROM CURRENT_DATE) || '-' || TO_CHAR(birth_date, 'MM-DD'), 'YYYY-MM-DD')
                ELSE TO_DATE((EXTRACT(YEAR FROM CURRENT_DATE) + 1) || '-' || TO_CHAR(birth_date, 'MM-DD'), 'YYYY-MM-DD')
             END as next_birthday
             FROM {$this->table} 
             WHERE user_id = :user_id
             ORDER BY 
                CASE 
                    WHEN TO_CHAR(birth_date, 'MM-DD') >= TO_CHAR(CURRENT_DATE, 'MM-DD')
                    THEN TO_DATE(EXTRACT(YEAR FROM CURRENT_DATE) || '-' || TO_CHAR(birth_date, 'MM-DD'), 'YYYY-MM-DD')
                    ELSE TO_DATE((EXTRACT(YEAR FROM CURRENT_DATE) + 1) || '-' || TO_CHAR(birth_date, 'MM-DD'), 'YYYY-MM-DD')
                END ASC
             LIMIT :days",
            ['user_id' => $userId, 'days' => $days]
        );
    }

    public function getTodayBirthdays($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} 
             WHERE user_id = :user_id 
             AND EXTRACT(MONTH FROM birth_date) = EXTRACT(MONTH FROM CURRENT_DATE)
             AND EXTRACT(DAY FROM birth_date) = EXTRACT(DAY FROM CURRENT_DATE)",
            ['user_id' => $userId]
        );
    }

    public function getThisMonth($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} 
             WHERE user_id = :user_id 
             AND EXTRACT(MONTH FROM birth_date) = EXTRACT(MONTH FROM CURRENT_DATE)
             ORDER BY EXTRACT(DAY FROM birth_date)",
            ['user_id' => $userId]
        );
    }
}
